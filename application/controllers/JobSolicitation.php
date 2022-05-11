<?php
defined('BASEPATH') or exit('No direct acccess is allowed');

class JobSolicitation extends CI_Controller
{
    private $table = null;
    private $where = array();
    private $data = array();
    private $select_column = array();
    private $order_column = array();
    private $jsID = null;


    function __construct()
    {
        parent::__construct();
        if (!get_cookie('isLogin')) {
            redirect('login');
        } else {
            set_cookie('isLogin', true, 3600);
        }
        foreach ($this->session->userdata('access_rights') as $key => $row) {
            $this->data[$row->modules] = true;
        }
        if (!isset($this->data['job_solicitation'])) {
            redirect('Error_page');
        }
    }

    public function index()
    {
        $this->data['title'] = 'PESO | JOB SOLICITATION';
        $this->data['job_solicitationClicked'] = true;
        $this->data['ApplicantsModule'] = ($this->data['job_solicitation']) ? true : false;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('JOLayout');
        $this->load->view('modals/JS_modal');
        $this->load->view('components/Footer');
    }



    public function get_job_vacancy()
    {
        $data = array();
        $this->table = 'tbl_jobsolicited';
        $this->where = array('tbl_jobsolicited.deleted' => false, 'vacancy !=' => 'hired');
        $this->select_column = array('tbl_company.name', 'tbl_position.position', 'tbl_jobsolicited.posID');
        $fetched_data = $this->JobSolicitation_model->getJobVacancy($this->table, $this->select_column, $this->where);
        if ($fetched_data != null) {
            foreach ($fetched_data as $key => $row) {
                $data[] = '<option value="' . $row->posID . '">' . strtoupper($row->name . ' / ' . $row->position) . '</option>';
            }
        } else {
            $data[] = '<option>NO JOB VACANCY AVAILABLE</option>';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    public function job_vacancy_list()
    {
        $this->table = 'tbl_jobsolicited';
        $data = array();

        $this->select_column = array('tbl_jobsolicited.id', 'tbl_company.name', 'tbl_jobsolicited.hired', 'tbl_jobsolicited.no_of_referral', 'tbl_jobsolicited.referred', 'tbl_position.position', 'tbl_jobsolicited.job_description', 'tbl_jobsolicited.requirements', 'tbl_jobsolicited.vacancy', 'tbl_jobsolicited.referred', 'tbl_jobsolicited.dateSolicited');
        $this->order_column = array(null, 'tbl_company.name', 'tbl_position.position', null, null, 'tbl_jobsolicited.vacancy', 'tbl_jobsolicited.dateSolicited', null);

        $fetched_data = $this->JobSolicitation_model->generate_datatables($this->table, $this->select_column, $this->order_column);
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->id . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->name);
            $sub_array[] = strtoupper($row->position);
            $sub_array[] = word_limiter($row->job_description, 10, '...');
            $sub_array[] = word_limiter($row->requirements, 10, '...');
            $sub_array[] = strtoupper($row->dateSolicited);
            $sub_array[] = $row->vacancy . ' / ' . $row->hired;
            $sub_array[] = $row->no_of_referral . ' / ' . $row->referred;
            $sub_array[] = $this->access_rights($row->id);
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->JobSolicitation_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->JobSolicitation_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    private function access_rights($id)
    {
        $btns = '<button class="btn btn-info btn-xs btnViewJS" data-toogle="tooltip" title="VIEW DETAILS"   data-id="' . $id . '"><i class="fa fa-eye"></i></button> ';
        foreach ($this->session->access_rights as $key => $row) {
            if ($row->modules == 'job_solicitation') {
                $btns .= ($row->_edit) ? '<button class="btn btn-warning btn-xs btnEditJobSolicited" data-toogle="tooltip" title="EDIT"   data-id="' . $id . '"><i class="fa fa-edit"></i></button>' : '';
                $btns .= ($row->_delete) ? '</button> <button data-toogle="tooltip" title="DELETE" class="btn btn-danger btn-xs btnDeleteJobSolicited"  data-id="' . $id . '"><i class="fa fa-trash"></i></button> ' : '';
            }
        }
        return $btns;
    }

    public function get_jobsolicited_data()
    {
        $this->where = array('tbl_jobsolicited.id' => $this->input->post('id'));
        $this->table = 'tbl_jobsolicited';
        $this->select_column = array('tbl_jobsolicited.posID', 'tbl_position.position', 'tbl_company.name as company', 'tbl_jobsolicited.job_description', 'tbl_jobsolicited.requirements', 'tbl_jobsolicited.vacancy', 'tbl_jobsolicited.no_of_referral', 'tbl_jobsolicited.dateSolicited', 'tbl_company.id AS companyID');
        $status = $this->JobSolicitation_model->get_JobSolicitedData($this->table, $this->select_column, $this->where);
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function add_newJS()
    {
        $this->table = 'tbl_jobsolicited';
        $rules = $this->JobSolicitation_model->js_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->data = array(
                'posID' => $this->input->post('js_position'),
                'requirements' => $this->input->post('job_req'),
                'job_description' => $this->input->post('job_desc'),
                'dateSolicited' => $this->input->post('dateSolicited'),
                'vacancy' => $this->input->post('vacancy'),
                'no_of_referral' => $this->input->post('referral'),
            );
            if ($this->JobSolicitation_model->saveJobSolicited($this->table, $this->data)) {
                $status['status'] = true;
                $status['msg'] = 'New job vacancy is now registered.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function delete_js()
    {
        $this->table = 'tbl_jobsolicited';
        $jv_list = $this->input->post('job_vacancy_list');
        $this->data = array('deleted' => true);
        if (is_array($jv_list)) {
            foreach ($jv_list as $row) {
                $this->where = array('id' => $row['id']);
                $this->JobSolicitation_model->updateJobSolicited($this->table, $this->data, $this->where);
            }
            $status['status'] = true;
            $status['msg'] = 'Selected data are now deleted.';
        } else {
            $this->where = array('id' => $this->input->post('id'));
            if ($this->JobSolicitation_model->updateJobSolicited($this->table, $this->data, $this->where)) {
                $status['status'] = true;
                $status['msg'] = 'Selected data is now deleted.';
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function update_js()
    {
        $this->table = 'tbl_jobsolicited';
        $rules = $this->JobSolicitation_model->js_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->data = array(
                'posID' => $this->input->post('js_position'),
                'requirements' => $this->input->post('job_req'),
                'job_description' => $this->input->post('job_desc'),
                'dateSolicited' => $this->input->post('dateSolicited'),
                'vacancy' => $this->input->post('vacancy'),
                'no_of_referral' => $this->input->post('referral'),
            );
            $this->where = array('id' => $this->input->post('jsID'));
            if ($this->JobSolicitation_model->updateJobSolicited($this->table, $this->data, $this->where)) {
                $status['status'] = true;
                $status['msg'] = 'Job vacancy is now updated.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function load_position()
    {
        $this->table = 'tbl_position';
        $this->where = array('isdelete' => false, 'companyID' => $this->input->post('companyID'));
        $this->column = array('position', 'posID');
        $position_list = $this->Settings_model->display_list($this->table, $this->column, $this->where);
        $list = array();
        if ($position_list != null) {
            $list[] = '<option >SELECT POSITION</option>';
            foreach ($position_list as $key => $row) {
                $list[] = '<option value="' . $row->posID . '">' . strtoupper($row->position) . '</option>';
            }
        } else {
            $list[] = '<option>NO RECORDS FOUND</option>';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    public function load_company()
    {
        $this->table = 'tbl_company';
        $this->where = array('deleted' => false);
        $this->column = array('name', 'id');
        $company_list = $this->Settings_model->display_list($this->table, $this->column, $this->where);
        $list = array();
        if ($company_list != null) {
            $list[] = '<option>SELECT COMPANY</option>';
            foreach ($company_list as $key => $row) {
                $list[] = '<option value="' . $row->id . '">' . strtoupper($row->name) . '</option>';
            }
        } else {
            $list[] = '<option>NO RECORDS FOUND</option>';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    public function get_company_list()
    {
        $this->table = 'tbl_company';
        $this->where = ($this->input->post('placemerit') != null) ? array('referred_placemerit' => $this->input->post('placemerit'), 'deleted' => false) : array('deleted' => false);
        $this->column = array('name', 'id', 'manager', 'address', 'referred_placemerit');
        $company_list = $this->Settings_model->display_list($this->table, $this->column, $this->where);
        $list = array();
        if ($company_list != null) {
            foreach ($company_list as $key => $row) {
                $list[] = '<li value="' . $row->id . '" data-referred_placemerit ="' . $row->referred_placemerit . '" data-address = "' . $row->address . '" data-manager ="' . $row->manager . '" >' . strtoupper($row->name) . ' </li>';
            }
        } else {
            $list[] = '<li selected disabled>NO RECORDS FOUND</li> ';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    public function addCompany_position()
    {
        $rules = $this->JobSolicitation_model->company_position_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $companyData = array(
                'name' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'manager' => $this->input->post('manager'),
                'referred_placemerit' => $this->input->post('placemerit'),
            );

            $this->jsID = $this->input->post('companyID');
            $positionData = $this->input->post('position');
            if ($this->JobSolicitation_model->saveCompanyPosition($companyData, $positionData, $this->jsID)) {
                $status['status'] = true;
                $status['msg'] = 'Company/Establishment and Position data are now registered.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }



    /*
    * Validate company to avoid duplicate entry
    */

    public function _validateCompany($str)
    {
        if ($this->input->post('companyID') == null) {
            $where = array('name' => $this->input->post('company'), 'deleted' => false);
        } else {
            $where = array('name' => $this->input->post('company'), 'id !=' => $this->input->post('companyID'), 'deleted' => false);
        }
        $this->db->where($where);
        $result = $this->db->get('tbl_company');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateCompany', $this->input->post('company') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }
    /*
    * validation for job position duplication
    */

    public function _validatePosition($str)
    {
        $position = array();
        foreach ($this->input->post('position') as $row) {
            $where = array('position' => $row, 'isdelete' => false, 'companyID' => $this->input->post('companyID'));
            $this->db->where($where);
            $result = $this->db->get('tbl_position');
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('_validatePosition', $row . ' is already registered.');
                return FALSE;
            }
        }

        return TRUE;
    }

    /*
    *   validation for job posistion duplication
    */

    public function _validateVacancyPosition($str)
    {
        if ($this->input->post('jsID') == null) {
            $this->where = array('posID' => $this->input->post('js_position'), 'deleted' => false);
        } else {
            $this->where = array('posID ' => $this->input->post('js_position'), 'id !=' => $this->input->post('jsID'), 'deleted' => false);
        }
        $this->db->where($this->where);
        $result = $this->db->get('tbl_jobsolicited');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateVacancyPosition', 'The %s selected is already registered.');
            return FALSE;
        }
        return TRUE;
    }
}