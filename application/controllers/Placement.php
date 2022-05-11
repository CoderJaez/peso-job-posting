<?php
defined('BASEPATH') or exit('No direct acccess is allowed');

class Placement extends CI_Controller
{
    private $data = array();
    private $table = null;
    private $where = array();
    private $select_column = array();
    private $order_column  = array();
    private $placementID = null;
    private $placeMerits = array('LOCAL', 'GOVERNMENT', 'OVERSEAS');

    private $remarks = array(
        "1. 4p's" => 1,
        "2. IP's" => 2,
        "3. GIP's" => 3,
        "4. PWD's" => 4,
        "5. JOBSTARTER" => 5,
        "6. SPES" => 6,
        "7. RETRENCHED" => 7,
        "8. RETURNING OFW" => 8,
        "9. MIGRATORY WORKERS" => 9,
        "10. RURAL WORKERS" => 10,
        "11.JOB ORDER" => 11,
        "12. REGULAR" => 12,
        "13. PLANTILLA" => 13
    );

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
        if (!isset($this->data['placement'])) {
            redirect('Error_page');
        }
    }


    public function index()
    {
        $this->data['title'] = 'PESO | HIRED APPLICANTS';
        $this->data['ApplicantsClicked'] = true;
        $this->data['placementClicked'] = true;
        $this->data['remarks'] = $this->remarks;
        $this->data['placemerits'] = $this->placeMerits;
        $this->data['ApplicantsModule'] = ($this->data['placement']) ? true : false;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('Hired_applicantsLayout.php');
        $this->load->view('modals/HiredApplicants_modal');
        $this->load->view('components/Footer');
    }

    public function hired_applicant_list()
    {
        $data = array();
        $this->table = 'tbl_placement';
        $this->select_column = array('tbl_applicant.name', 'tbl_placement.placementID', 'tbl_company.name AS company', 'tbl_company.referred_placemerit', 'tbl_position.posID', 'tbl_position.position', 'tbl_placement.dateHired', 'tbl_placement.isReferred', 'tbl_placement.remarks');
        $this->order_column = array(null, 'tbl_applicant.name', 'tbl_company.referred_placemet', 'tbl_placement.dateHired', 'tbl_placement.remarks', null, null);
        $fetched_data = $this->HireApplicant_model->generate_datatables($this->table, $this->select_column, $this->order_column);
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" value="' . $row->placementID . '" data-pos_id="' . $row->posID . '">';
            $sub_array[] = strtoupper($row->name);
            $sub_array[] = strtoupper($row->company . '/' . $row->referred_placemerit);
            $sub_array[] = strtoupper($row->position);
            $sub_array[] = $row->dateHired;
            $sub_array[] = $row->remarks;
            $sub_array[] = ($row->isReferred) ? '<i class="fa fa-check"></i>' : '';
            $sub_array[] = $this->access_rights($row->placementID, $row->posID);
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->HireApplicant_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->HireApplicant_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    private function access_rights($placementID, $posID)
    {
        $btns = '';
        foreach ($this->session->access_rights as $key => $row) {
            if ($row->modules == 'applicant') {
                $btns .= ($row->_edit) ? '<button class="btn btn-xs btn-warning btnEditHiredApplicant" data-toogle="tooltip" title="EDIT" value="' . $placementID . '"><i class="fa fa-edit"></i></button> ' : '';
                $btns .= ($row->_delete) ? '<button class="btn btn-xs btn-danger btnDeleteHiredApplicant" data-toogle="tooltip" title="DELETE" value="' . $placementID . '" data-pos_id ="' . $posID . '"><i class="fa fa-trash"></i></button> ' : '';
            }
        }
        return $btns;
    }

    public function getHiredApplicantData()
    {
        $this->select_column = array('tbl_applicant.name', 'tbl_applicant.id AS applicantID', 'tbl_placement.placementID', 'tbl_company.id AS companyID', 'tbl_company.referred_placemerit', 'tbl_position.position', 'tbl_position.posID', 'tbl_placement.dateHired', 'tbl_placement.isReferred', 'tbl_placement.remarks');
        $this->table = 'tbl_placement';
        $this->where = array('tbl_placement.placementID' => $this->input->post('placementID'));
        $row = $this->HireApplicant_model->getApplicantData($this->table, $this->select_column, $this->where);
        $this->data = array(
            'placementID' => $row->placementID,
            'applicantName' => $row->name,
            'applicantID' => $row->applicantID,
            'posID' => $row->posID,
            'companyID' => $row->companyID,
            'dateHired' => $row->dateHired,
            'remarks' => $row->remarks,
            'placemerit' => $row->referred_placemerit,
            'hiring_status' => ($row->isReferred) ? 'referred' : 'walk-in',
            'posID' => $row->posID
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($this->data));
    }

    public function hire_newApplicant()
    {

        $rules = $this->HireApplicant_model->applicantRules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->data = array(
                'applicantID' => $this->input->post('applicantID'),
                'isReferred' => ($this->input->post('hiring_status') == 'referred') ? true : false,
                'dateHired' => $this->input->post('dateHired'),
                'posID' => $this->input->post('position'),
                'remarks' => $this->input->post('remarks'),
            );
            $job_vacancy_data = 'UPDATE tbl_jobsolicited SET hired = hired + 1 WHERE posID = ' . $this->input->post('position') . '';
            $this->table = 'tbl_placement';

            if ($this->HireApplicant_model->addHired_applicant($this->table, $this->data, $job_vacancy_data)) {
                $status['status'] = true;
                $status['msg'] = 'Applicant is now registered';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function update_HiredApplicant($placementID)
    {
        $this->placementID = $this->input->post('placementID');
        $rules = $this->HireApplicant_model->applicantRules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->data = array(
                'applicantID' => $this->input->post('applicantID'),
                'isReferred' => ($this->input->post('hiring_status') == 'referred') ? true : false,
                'dateHired' => $this->input->post('dateHired'),
                'posID' => $this->input->post('position'),
                'remarks' => $this->input->post('remarks'),
            );
            $this->where = array('placementID' => $this->input->post('placementID'));
            $this->table = 'tbl_placement';
            $jv_status = true;
            $jv_prevUpdate =  'UPDATE tbl_jobsolicited SET hired = hired - 1 WHERE posID = ' . $this->input->post('prevPosition') . '';
            $jv_newUpdate = 'UPDATE tbl_jobsolicited SET hired = hired + 1 WHERE posID = ' . $this->input->post('position') . '';

            if ($this->HireApplicant_model->updateHired_applicant($this->table, $this->data, $this->where, $jv_newUpdate, $jv_prevUpdate)) {
                $status['status'] = true;
                $status['msg'] = 'Applicant information is now updated';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function delete_applicant()
    {
        $applicant_list = $this->input->post('applicant_list');
        $this->table = 'tbl_placement';
        if (is_array($applicant_list)) {
            foreach ($applicant_list as $key => $row) {
                $this->data = array(
                    'deleted' => true
                );
                $this->where = array('placementID' => $row['placementID']);
                $jv_query =  'UPDATE tbl_jobsolicited SET hired = hired - 1 WHERE posID = ' . $row['posID'] . '';
                $this->HireApplicant_model->deleteHired_applicant($this->table, $this->data, $this->where, $jv_query);
            }
            $status['status'] = true;
            $status['msg'] = 'Applicant selected are now deleted.';
        } else {
            $this->data = array('deleted' => true);
            $this->where = array('placementID' => $this->input->post('placementID'));
            $jv_query =  'UPDATE tbl_jobsolicited SET hired = hired - 1 WHERE posID = ' . $this->input->post('posID') . '';
            if ($this->HireApplicant_model->deleteHired_applicant($this->table, $this->data, $this->where, $jv_query)) {

                $status['status'] = true;
                $status['msg'] = 'Applicant selected is now deleted.';
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    /*
    * validation for hired applicants duplication
    */

    public function _validateHireApplicant($str)
    {
        if ($this->placementID == null) {
            $where = array('applicantID' => $this->input->post('applicantID'), 'deleted' => false);
        } else {
            $where = array('applicantID !=' => $this->input->post('applicantID'), 'placementID ' => $this->placementID, 'deleted' => false);
        }
        $this->db->where($where);
        $result = $this->db->get('tbl_placement');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateHireApplicant', 'The applicant is already hired.');
            return FALSE;
        }
        return TRUE;
    }

    public function _validateReferredApplicant($str)
    {
        $this->table = 'tbl_referral';
        if ($str == 'referred') {
            $this->where = array('applicantID' => $this->input->post('applicantID'), 'deleted' => false);
            if (!$this->HireApplicant_model->applicantReferred($this->table, $this->where)) {
                $this->form_validation->set_message('_validateReferredApplicant', 'The applicant you selected is not in the referred list. PLease change HIRING STATUS.');
                return FALSE;
            }
        } else {
            $this->where = array('applicantID' => $this->input->post('applicantID'), 'deleted' => false);
            if ($this->HireApplicant_model->applicantReferred($this->table, $this->where)) {
                $this->form_validation->set_message('_validateReferredApplicant', 'The applicant you selected is in the referred list. PLease change HIRING STATUS.');
                return FALSE;
            }
        }
        return TRUE;
    }


    public function _validatePosition($str)
    {
        $this->where = array('applicantID' => $this->input->post('applicantID'), 'posID' => $this->input->post('position'), 'deleted' => false);
        $this->table = 'tbl_referral';
        if ($this->input->post('hiring_status') == 'referred') {
            if (!$this->HireApplicant_model->applicantReferred($this->table, $this->where)) {
                $this->form_validation->set_message('_validatePosition', 'The position selected of the applicant  is not matched in the referred list. PLease check in the referral list.');
                return FALSE;
            }
        }
        return TRUE;
    }
}