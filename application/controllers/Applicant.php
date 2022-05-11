<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applicant extends CI_Controller
{
    private $data = array();
    private $applicantID = '';
    private $placeMerits = array('LOCAL', 'GOVERNMENT', 'OVERSEAS');
    private $table = array();
    private $selected_column = array();
    private $order_column = array();
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

    public function __construct()
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
        if (!isset($this->data['applicant'])) {
            redirect('Error_page');
        }
    }


    public function index()
    {

        $this->data['ApplicantsModule'] = ($this->data['applicant']) ? true : false;
        $this->data['title'] = 'PESO | APPLICANTS';
        $this->data['ApplicantsClicked'] = true;
        $this->data['applicantClicked'] = true;
        $this->data['province'] = loadProvince();
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('ApplicantLayout');
        $this->load->view('modals/ApplicantFormModal');
        $this->load->view('components/Footer');
    }

    public function applied()
    {
        $this->data['ApplicantsModule'] = ($this->data['applicant']) ? true : false;
        $this->data['title'] = 'PESO | APPLICANTS';
        $this->data['ApplicantsClicked'] = true;
        $this->data['applicantAppliedClicked'] = true;
        $this->data['remarks'] = $this->remarks;
        $this->data['placemerits'] = $this->placeMerits;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('ApplicantAppliedLayout');
        $this->load->view('modals/ReferApplicantModal');
        $this->load->view('components/Footer');
    }

    private function access_rights($id, $requestType = null, $file = null)
    {
        $btns = '';
        if ($requestType != null && $requestType == 'applied') {
            foreach ($this->session->access_rights as $key => $row) {
                if ($row->modules == 'applicant') {

                    $btns .= (file_exists("./$file")) ? '<a href="' . base_url($file) . '" class="btn btn-primary btn-xs btn-dlFile" data-toogle="tooltip" title="Download resume"  data-id="' . $id . '" ><i class="fa fa-download"></i></a> ' : "";
                    $btns .= ($row->_edit) ? '<button class="btn btn-warning btn-xs btnReferApplicant" data-toogle="tooltip" title="Refer"   data-id="' . $id . '"><i class="fa fa-mail-forward"></i></button>' : '';
                    $btns .= ($row->_delete) ? '</button> <button data-toogle="tooltip" title="Delete" class="btn btn-danger btn-xs btnDeleteApplicant"  data-id="' . $id . '"><i class="fa fa-trash"></i></button> ' : '';
                }
            }
        } else {
            foreach ($this->session->access_rights as $key => $row) {
                if ($row->modules == 'applicant') {
                    $btns .= ($row->_edit) ? '<button class="btn btn-warning btn-xs btnEditApplicant" data-toogle="tooltip" title="Edit"   data-id="' . $id . '"><i class="fa fa-edit"></i></button>' : '';
                    $btns .= ($row->_delete) ? '</button> <button data-toogle="tooltip" title="Delete" class="btn btn-danger btn-xs btnDeleteApplicant"  data-id="' . $id . '"><i class="fa fa-trash"></i></button> ' : '';
                }
            }
        }
        return $btns;
    }


    public function applicant_list()
    {
        $this->table = 'tbl_applicant';
        $this->selected_column = array('tbl_applicant.id', 'tbl_applicant.name', 'tbl_applicant.email', 'tbl_applicant.gender', 'tbl_applicant.address', 'tbl_applicant.contactNo', 'tbl_applicant.dateRegistered', 'tbl_applicant.brgyCode', 'refbrgy.citymunCode', 'refbrgy.provCode', 'refbrgy.brgyDesc', 'refcitymun.citymunDesc', 'refprovince.provDesc');
        $this->order_column = array(null, 'name', 'gender', 'contactNo', 'email', 'address', 'dateRegistered', null);
        $fetched_data = $this->Applicant_model->generate_datatables($this->table, $this->selected_column, $this->order_column, null);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->id . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->name);
            $sub_array[] = strtoupper($row->gender);
            $sub_array[] = strtoupper($row->email);
            $sub_array[] = strtoupper($row->address . ', ' . $row->brgyDesc . ' ' . $row->citymunDesc);
            $sub_array[] = strtoupper($row->contactNo);
            $sub_array[] = strtoupper($row->dateRegistered);
            $sub_array[] = $this->access_rights($row->id);
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Applicant_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->Applicant_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function applicant_applied_list()
    {
        $this->table = 'tbl_applicant';
        $this->selected_column = array('tbl_applicants_applied.id', 'tbl_applicant.name', 'tbl_applicant.gender', 'tbl_applicant.address', 'tbl_applicant.contactNo', 'tbl_applicant.dateRegistered', 'tbl_applicant.brgyCode', 'refbrgy.citymunCode', 'refbrgy.provCode', 'refbrgy.brgyDesc', 'refcitymun.citymunDesc', 'refprovince.provDesc', 'resume_dir', 'tbl_applicant.email', 'tbl_position.position', 'tbl_company.name AS company',);
        $this->order_column = array(null, 'name', 'gender', 'contactNo', 'address', 'email', null, 'dateRegistered', null);
        $fetched_data = $this->Applicant_model->generate_datatables($this->table, $this->selected_column, $this->order_column, 'applied');
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->id . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->name);
            $sub_array[] = strtoupper($row->gender);
            $sub_array[] = strtoupper($row->address . ', ' . $row->brgyDesc . ' ' . $row->citymunDesc);
            $sub_array[] = $row->email;
            $sub_array[] = strtoupper($row->contactNo);
            $sub_array[] = strtoupper("$row->company/$row->position");
            $sub_array[] = strtoupper($row->dateRegistered);
            $sub_array[] = $this->access_rights($row->id, 'applied', $row->resume_dir);
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Applicant_model->get_allApplied_data(array('deleted' => false, 'referred' => false)),
            "recordsFiltered" => $this->Applicant_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function refer_applicant()
    {
        $this->data = array(
            'control_no' => control_no(),
            'applicantID' => $this->input->post('applicantID'),
            'posID' => $this->input->post('position'),
            'dateReferred' => $this->input->post('dateReferred'),
            'remarks' => $this->input->post('remarks'),
            'training' => $this->input->post('training')
        );
        $jv_prevUpdate =  'UPDATE tbl_jobsolicited SET referred = referred  +1 WHERE posID = ' . $this->input->post('position') . '';
        $prevUpdate =  'UPDATE tbl_applicants_applied SET referred = TRUE WHERE posID = ' . $this->input->post('position') . '';
        $this->table = 'tbl_referral';
        $rules = $this->Referrals_model->referral_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            if ($this->Referrals_model->addNew_referral($this->data, $this->table, $jv_prevUpdate, $prevUpdate)) {
                $status['status'] = true;
                $status['msg'] = 'New applicant is added to the list.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function getApplicantData()
    {
        $this->where = array('tbl_applicant.id' => $this->input->post('_applicantID'));
        $result = $this->Applicant_model->ApplicantData($this->where);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function getApplicantAppliedData()
    {
        $this->where = array('tbl_applicants_applied.id' => $this->input->post('id'));
        $result = $this->Applicant_model->getApplicantAppliedData($this->where);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }


    public function save_applicant($_applicantID = null)
    {
        $this->applicantID = $_applicantID;
        $this->table = 'tbl_applicant';
        $rules = $this->Applicant_model->applicant_rules;
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'id' => appID(), 'name' => $this->input->post('name'),
                'contactNo' => str_replace('-', '', $this->input->post('contactNo')),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'citymunCode' => $this->input->post('citymunCode'),
                'provCode' => $this->input->post('provCode'),
                'brgyCode' => $this->input->post('brgyCode')
            );

            if ($this->applicantID == null) {

                if ($this->Applicant_model->addNew_applicant($data, $this->table)) {
                    $status['msg'] = 'New applicant is registered.';
                    $status['success'] = true;
                }
            } else {
                $update_data = array(
                    'name' => $this->input->post('name'),
                    'contactNo' => str_replace('-', '', $this->input->post('contactNo')),
                    'email' => $this->input->post('email'),
                    'gender' => $this->input->post('gender'),
                    'address' => $this->input->post('address'),
                    'citymunCode' => $this->input->post('citymunCode'),
                    'provCode' => $this->input->post('provCode'),
                    'brgyCode' => $this->input->post('brgyCode')
                );
                $where = array('id' => $this->applicantID);
                if ($this->Applicant_model->update_applicant($update_data, $this->table, $where)) {
                    $status['msg'] = "Applicant's information  updated.";
                    $status['success'] = true;
                }
            }
        } else {
            $status['msg'] = validation_errors();
            $status['success'] = false;
        }
        // $status['token_name'] = $this->security->get_csrf_token_name();
        // $status['token'] = $this->security->get_csrf_hash();
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }



    public function delete_applicant()
    {
        $this->table = 'tbl_applicant';
        $data = array('deleted' => true);
        if (is_array($this->input->post('applicantList'))) {
            foreach ($this->input->post('applicantList') as $key => $row) {
                $where = array('id' => $row['applicantID']);
                $this->Applicant_model->update_applicant($data, $this->table, $where);
                $status['msg'] = "The selected Applicant's information are deleted.";
                $status['success'] = true;
            }
        } else {
            $this->applicantID = $this->input->post('applicantID');
            if ($this->applicantID != null) {
                $where = array('id' => $this->applicantID);
                if ($this->Applicant_model->update_applicant($data, $this->table, $where)) {
                    $status['msg'] = "Applicant's information  deleted.";
                    $status['success'] = true;
                }
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function _validatePosition($str)
    {
        $this->where = 'tbl_jobsolicited.deleted = false AND tbl_jobsolicited.referred != tbl_jobsolicited.no_of_referral AND tbl_jobsolicited.posID = ' . $this->input->post('position');
        $table = 'tbl_jobsolicited';
        $this->select_column = array('tbl_position.position', 'tbl_jobsolicited.posID');
        $fetched_data = $this->JobSolicitation_model->getJobVacancy($table, $this->select_column, $this->where);
        if ($fetched_data == null) {
            $this->form_validation->set_message('_validatePosition', 'The position selected for the applicant  is already reached the maximum referred capacity. Please choose another desired position.');
            return FALSE;
        }
        return TRUE;
    }

    public function search_applicant()
    {
        $hiring_status = $this->input->post('hiring_status');
        if ($hiring_status == null || $hiring_status == 'walk-in') {
            $where = array('tbl_applicant.id' => $this->input->post('_applicantID'), 'deleted' => false);
            $this->table = 'tbl_applicant';
            $result = $this->Applicant_model->search_applicant($where, $this->table);
            $data['applicant'] = $result->name;
            $data['id'] = $result->id;
            $data['address'] = $result->address;
        } else {
            $this->table = 'tbl_referral';
            $this->selected_column = array('tbl_referral.id AS referralID', 'tbl_applicant.address', 'tbl_referral.applicantID', 'tbl_company.name AS company', 'tbl_position.position', 'tbl_company.id AS companyID', 'tbl_company.referred_placemerit', 'tbl_referral.training', 'tbl_referral.remarks', 'tbl_position.posID');
            $this->control_no = $this->input->post('control_no');
            $this->where = array('tbl_referral.applicantID' => $this->input->post('_applicantID'));
            $applicant = $this->Referrals_model->get_referral($this->table, $this->selected_column, $this->where);
            if ($applicant != null) {
                $data = array(
                    "referralID" => $applicant->referralID,
                    "applicantID" => $applicant->applicantID,
                    "placemerit" => $applicant->referred_placemerit,
                    "company" => '<option value="' . $applicant->companyID . '">' . $applicant->company . '</option>',
                    "position" => '<option value="' . $applicant->posID . '">' . $applicant->position . '</option>',
                    "remarks" => $applicant->remarks,
                    "referred" => true,
                    "status" => true
                );
            } else {
                $data['status'] = false;
                $data['referred'] = true;
                $data['msg'] = "The selected applicant is not referred to the office";
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}