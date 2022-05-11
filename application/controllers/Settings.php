<?php
defined('BASEPATH') or exit('No direct script access allowed');

class  Settings extends CI_Controller
{
    private $data = array();
    private $companyId = null;
    private $positionID = null;
    private $table = array();
    private $selected_column = array();
    private $order_column = array();
    private $placemerit = array('LOCAL', 'GOVERNMENT', 'OVERSEAS');

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
        if (!isset($this->data['settings'])) {
            redirect('Error_page');
        }
    }
    public function page($name)
    {

        $this->data['ApplicantsModule'] = (isset($this->data['applicant']) || isset($this->data['placement']) || isset($this->data['referral'])) ? true : false;
        $this->data['ApplicantsModule'] = ($this->data['applicant'] || $this->data['placement'] || $this->data['referral']) ? true : false;
        $this->data['title'] = $name;
        $this->data['SettingsClicked'] = true;
        $this->data[$name] = true;
        $this->data['placemerit'] = ($name == 'company') ? $this->placemerit : null;
        $this->data['province'] = ($name == 'company') ? $this->loadProvince() : null;
        $this->data[$name] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('SettingsLayout');
        $this->load->view('components/Footer');
    }


    public function loadProvince()
    {
        $this->selected_column = array('provDesc', 'provCode');
        $this->table = 'refprovince';
        $order_by = 'provDesc ASC';
        return $this->Applicant_model->getData($this->selected_column, $this->table, $order_by);
    }

    public function add_letter()
    {
        $this->data['add_newLetter'] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('SettingsLayout');
        $this->load->view('components/Footer');
    }

    public function display_company_list()
    {
        $this->table = 'tbl_company';
        $this->selected_column = array('tbl_company.id', 'referred_placemerit', 'name', 'tbl_company.address', 'refbrgy.brgyDesc', 'refcitymun.citymunDesc', 'refprovince.provDesc', 'tbl_company.brgyCode', 'tbl_company.citymunCode', 'tbl_company.provCode', 'manager', 'dateRegistered');
        $this->order_column = array(null, 'referred_placemerit', 'name', 'address', 'manager', 'dateRegistered', null);
        $fetched_data = $this->Settings_model->generate_datatables($this->table, $this->selected_column, $this->order_column);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->id . '" data-address="' . $row->address . '" data-brgycode = "' . $row->brgyCode . '" data-citymuncode="' . $row->citymunCode . '" data-provcode="' . $row->provCode . '"  class="inputSelectCompany"/>';
            $sub_array[] = strtoupper($row->referred_placemerit);
            $sub_array[] = strtoupper($row->name);
            $sub_array[] = strtoupper("$row->address, $row->brgyDesc, $row->citymunDesc, $row->provDesc");
            $sub_array[] = strtoupper($row->manager);
            $sub_array[] = strtoupper($row->dateRegistered);
            $sub_array[] = '<button class="btn btn-warning btn-xs btnEditCompany"  data-id="' . $row->id . '"><i class="fa fa-edit"></i></button> <button class="btn btn-danger btn-xs btnDeleteCompany"  data-id="' . $row->id . '"><i class="fa fa-trash"></i></button> ';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Settings_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->Settings_model->get_filtered_data(),
            "data" => $data
        );

        echo json_encode($output);
    }

    public function add_company($_companyId = null)
    {
        $this->companyId = $_companyId;
        $rules = $this->Settings_model->companyRules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $data = array('name' => $this->input->post('company_name'), 'address' => $this->input->post('company_address'), 'manager' => $this->input->post('manager_name'), 'referred_placemerit' => $this->input->post('placemerit'), 'provCode' => $this->input->post('provCode'), 'citymunCode' => $this->input->post('citymunCode'), 'brgyCode' => $this->input->post('brgyCode'));
            if ($this->companyId != '') {
                if ($this->Settings_model->update_company($data, $this->companyId)) {
                    $status = array('success' => true, 'msg' => 'Update success');
                } else {
                    $status = array('success' => false, 'msg' => 'Registration failed');
                }
            } else {
                if ($this->Settings_model->add_company($data)) {
                    $status = array('success' => true, 'msg' =>  'Success');
                } else {
                    $status = array('success' => false, 'msg' => 'Registration failed');
                }
            }
        } else {
            $status['msg'] = validation_errors();
        }
        $status['token'] = $this->security->get_csrf_hash();
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
    /* 
    * moving to trash selected data 
    */
    public function delete_company()
    {
        if (is_array($this->input->post('companyList'))) {
            $company_list = $this->input->post('companyList');
            $data = array('deleted' => true);
            foreach ($company_list as $key => $row) {
                $this->Settings_model->update_company($data, $row['companyID']);
            }
            $status = array('success' => true, 'msg' => 'Deleted data success');
        } else {
            $data = array('deleted' => true);
            $this->companyId = $this->input->post('company_id');
            if ($this->Settings_model->update_company($data, $this->companyId)) {
                $status = array('success' => true, 'msg' => 'Delete data success');
            } else {
                $status = array('success' => false, 'msg' => 'Registration failed');
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    /*
    * Validate company to avoid duplicate entry
    */

    public function _validateCompany($str)
    {
        if ($this->companyId == null) {
            $where = array('name' => $this->input->post('company_name'), 'deleted' => false);
        } else {
            $where = array('name' => $this->input->post('company_name'), 'id !=' => $this->companyId, 'deleted' => false);
        }
        $this->db->where($where);
        $result = $this->db->get('tbl_company');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateCompany', $this->input->post('company_name') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }
    /*
    * validation for job position duplication
    */

    public function _validatePosition($str)
    {
        if ($this->positionID == null) {
            $where = array('position' => $this->input->post('position'), 'isdelete' => false, 'companyID' => $this->input->post('companyID'));
        } else {
            $where = array('position' => $this->input->post('position'), 'posID !=' => $this->positionID, 'isdelete' => false, 'companyID' => $this->input->post('companyID'));
        }
        $this->db->where($where);
        $result = $this->db->get('tbl_position');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validatePosition', $this->input->post('position') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }

    /*
    * Saving data for position
    */

    public function save_position($_positionID = null)
    {
        $this->positionID = $_positionID;
        $rules = $this->Settings_model->positionRules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            if ($this->positionID == null) {
                $this->data = array('companyID' => $this->input->post('companyID'), 'position' => strtoupper($this->input->post('position')));
                $this->table = 'tbl_position';
                if ($this->Settings_model->add_position($this->data, $this->table)) {
                    $status['status'] = true;
                    $status['msg'] = strtoupper($this->input->post('position')) . " is now registered";
                }
            } else {
                $this->data = array('companyID' => $this->input->post('companyID'), 'position' => strtoupper($this->input->post('position')));
                $where = array('posID' => $this->positionID);
                $this->table = 'tbl_position';
                if ($this->Settings_model->update_position($this->data, $this->table, $where)) {
                    $status['status'] = true;
                    $status['msg'] = strtoupper($this->input->post('position')) . " is now Updated";
                }
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function position_list()
    {
        $this->table = 'tbl_position';
        $this->selected_column = array('posID', 'tbl_position.companyID', 'tbl_company.name AS company_name', 'position', 'tbl_position.dateRegistered');
        $this->order_column = array(null, 'company_name', 'position', 'dateRegistered', null);
        $fetched_data = $this->Settings_model->generate_datatables($this->table, $this->selected_column, $this->order_column);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->posID . '" class="inputSelectCompany"/>';
            $sub_array[] = strtoupper($row->company_name);
            $sub_array[] = strtoupper($row->position);
            $sub_array[] = strtoupper($row->dateRegistered);
            $sub_array[] = '<button  data-toggle="tooltip" title ="EDIT" class="btn btn-warning btn-xs btnEditPosition" data-companyid = "' . $row->companyID . '"  data-id="' . $row->posID . '"><i class="fa fa-edit"></i></button> <button data-toggle="tooltip" title ="DELETE" class="btn btn-danger btn-xs btnDeletePosition"  data-id="' . $row->posID . '"><i class="fa fa-trash"></i></button> ';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Settings_model->get_all_data(array('tbl_position.isdelete' => false)),
            "recordsFiltered" => $this->Settings_model->get_filtered_data(),
            "data" => $data
        );

        echo json_encode($output);
    }

    public function delete_position()
    {
        $this->table = 'tbl_position';
        $data = array('isdelete' => true);
        if (is_array($this->input->post('positionList'))) {
            $positionList = $this->input->post('positionList');
            foreach ($positionList as $key => $row) {
                $where = array('posID' => $row['positionID']);
                $this->Settings_model->update_position($data, $this->table, $where);
            }
            $status = array('success' => true, 'msg' => 'Deleted data success');
        } else {
            $where = array('posID' => $this->input->post('posID'));
            if ($this->Settings_model->update_position($data, $this->table, $where)) {
                $status = array('success' => true, 'msg' => 'Delete data success');
            } else {
                $status = array('success' => false, 'msg' => 'Registration failed');
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
}