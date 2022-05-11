<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referrals extends CI_Controller
{
    private $data = array();
    private $controlNo = null;
    private $placeMerits = array('LOCAL', 'GOVERNMENT', 'OVERSEAS');
    private $where = null;
    private $table = null;
    private $select_column = array();
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
    private $content_copy = array("APPLICANT'S COPY", "PESO COPY", "ADMIN'S COPY");
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
        if (!isset($this->data['referral'])) {
            redirect('Error_page');
        }
    }

    public function index()
    {
        $this->data['title'] = 'Referrals';
        $this->data['ApplicantsModule'] = ($this->data['referral']) ? true : false;
        $this->data['referralClicked'] = true;
        $this->data['placemerits'] = $this->placeMerits;
        $this->data['ApplicantsClicked'] = true;
        $this->data['remarks'] = $this->remarks;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('ReferralLayout');
        $this->load->view('modals/ReferApplicantModal');
        $this->load->view('modals/ReferralLetterModal');
        $this->load->view('components/Footer');
    }

    public function report()
    {
        $this->data['title'] = 'Referrals';
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('ReferralReportLayout');
        $this->load->view('components/Footer');
    }

    public function referral_list()
    {

        $this->table = 'tbl_referral';
        $this->select_column = array('tbl_referral.control_no', 'tbl_referral.posID', 'tbl_applicant.name AS applicant', 'tbl_applicant.gender', 'CONCAT(tbl_applicant.address,", ", refbrgy.brgyDesc," ", refcitymun.citymunDesc) AS address', 'tbl_applicant.contactNo', 'tbl_company.name AS company', 'tbl_company.referred_placemerit', 'tbl_position.position', 'DATE_FORMAT(tbl_referral.dateReferred,"%m-%d-%Y") AS dateReferred', 'training');
        $this->order_column = array(null, 'tbl_referral.control_no', 'tbl_applicant.name', 'tbl_applicant.gender',  'address', 'tbl_applicant.contactNo', 'tbl_company.referred_placemerit', 'tbl_referral.training', 'tbl_referral.dateReferred', null);
        $fetched_data = $this->Referrals_model->generate_datatables($this->table, $this->select_column, $this->order_column);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-pos_id ="' . $row->posID . '" data-id="' . $row->control_no . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->control_no);
            $sub_array[] = strtoupper($row->applicant);
            $sub_array[] = strtoupper($row->gender);
            $sub_array[] = strtoupper($row->address);
            $sub_array[] = strtoupper($row->contactNo);
            $sub_array[] = strtoupper($row->company) . "/" . strtoupper($row->position) . '<br>' . $row->referred_placemerit;
            $sub_array[] = strtoupper($row->training);
            $sub_array[] = strtoupper($row->dateReferred);
            $sub_array[] = $this->access_rights($row->control_no, $row->posID);
            $data[] = $sub_array;
        }



        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Referrals_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->Referrals_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
        // $this->clear_var();
    }

    private function access_rights($id, $posID)
    {
        $btns = '<button class="btn btn-info btn-xs btnPrintReferral" data-toogle="tooltip" title="Print Referral"   data-control_no="' . $id . '"><i class="fa fa-print"></i></button> ';
        foreach ($this->session->access_rights as $key => $row) {
            if ($row->modules == 'referral') {
                $btns .= ($row->_edit) ? '<button class="btn btn-warning btn-xs btnEditReferredApplicant" data-toogle="tooltip" title="EDIT"   data-control_no="' . $id . '"><i class="fa fa-edit"></i></button>' : '';
                $btns .= ($row->_delete) ? '</button> <button data-toogle="tooltip" title="DELETE" class="btn btn-danger btn-xs btnDeleteReferredApplicant"  data-control_no="' . $id . '" data-pos_id ="' . $posID . '"><i class="fa fa-trash"></i></button> ' : '';
            }
        }
        return $btns;
    }
    //ADD NEW REFERRALS
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
        $this->table = 'tbl_referral';
        $rules = $this->Referrals_model->referral_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            if ($this->Referrals_model->addNew_referral($this->data, $this->table, $jv_prevUpdate)) {
                $status['status'] = true;
                $status['msg'] = 'New applicant is added to the list.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }


        $this->clear_var();
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    //UPDATE REFERRED APPLICANT INFORMATION
    public function update_referral($control_no)
    {
        $this->data = array(
            'applicantID' => $this->input->post('applicantID'),
            'posID' => $this->input->post('position'),
            'dateReferred' => $this->input->post('dateReferred'),
            'remarks' => $this->input->post('remarks'),
            'training' => $this->input->post('training')
        );
        $this->table = 'tbl_referral';
        $rules = $this->Referrals_model->referral_rules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->where = array('control_no' => $control_no);
            $jv_prevUpdate =  'UPDATE tbl_jobsolicited SET referred = referred - 1 WHERE posID = ' . $this->input->post('prevPosition') . '';
            $jv_newUpdate = 'UPDATE tbl_jobsolicited SET referred = referred + 1 WHERE posID = ' . $this->input->post('position') . '';

            if ($this->Referrals_model->update_referral($this->data, $this->table, $this->where, $jv_prevUpdate, $jv_newUpdate)) {
                $status['status'] = true;
                $status['msg'] = 'Referred applicant information is now updated.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function company_list()
    {
        $where = array('referred_placemerit' => $this->input->post('placemerit'), 'deleted' => false);
    }

    public function searched_applicant()
    {
        $applicant = $this->input->post('applicantName');
        $this->db->select('id,name');
        $this->db->like('name', $applicant);
        $this->db->order_by('name ASC');
        $result = $this->db->get('tbl_applicant');

        $list = array();

        if ($result->num_rows() > 0) {

            foreach ($result->result() as $key => $row) {
                $list[] = '<li class="list-group-item applicantSelected" data-id="' . $row->id . '">' . strtoupper($row->name) . '</li>';
            }
        } else {
            $list[] = '<li class="list-group-item stud" data-id="null">> NO RECORDS FOUND!' . '</li>';
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    public function get_company_list()
    {
        $this->select_column = array('tbl_company.name', 'tbl_company.id');
        $this->table = 'tbl_jobsolicited';
        $this->where = 'tbl_jobsolicited.deleted = false AND tbl_jobsolicited.vacancy != tbl_jobsolicited.hired AND tbl_company.referred_placemerit = "' . $this->input->post('placemerit') . '"';
        $this->db->distinct();
        $company_list = $this->JobSolicitation_model->getJobVacancy($this->table, $this->select_column, $this->where);
        $list = array();
        if ($company_list != null) {
            $list[] = '<option  disabled>SELECT COMPANY</option> ';
            foreach ($company_list as $key => $row) {
                $list[] = '<option value="' . $row->id . '">' . strtoupper($row->name) . ' </option>';
            }
        } else {
            $list[] = '<option selected disabled>NO JOB VACANCY AVAILABLE</option> ';
        }
        $this->clear_var();
        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    public function get_position_list()
    {
        $data = array();
        $this->table = 'tbl_jobsolicited';
        $this->where = 'tbl_jobsolicited.deleted = false AND  tbl_jobsolicited.referred != tbl_jobsolicited.vacancy AND tbl_company.id = ' . $this->input->post('companyID');
        $this->select_column = array('tbl_position.position', 'tbl_jobsolicited.posID');
        $fetched_data = $this->JobSolicitation_model->getJobVacancy($this->table, $this->select_column, $this->where);
        if ($fetched_data != null) {
            $data[] = '<option  disabled>SELECT JOB POSITION</option> ';
            foreach ($fetched_data as $key => $row) {
                $data[] = '<option value="' . $row->posID . '">' . strtoupper($row->position) . '</option>';
            }
        } else {
            $data[] = '<option>NO JOB VACANCY AVAILABLE</option>';
        }
        $this->clear_var();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    public function delete_referral()
    {
        $this->table = 'tbl_referral';
        if ($this->input->post('referral_list')) {
            $referral_list = $this->input->post('referral_list');
            foreach ($referral_list as $key => $row) {
                $this->data = array('deleted' => true);
                $jv_newUpdate = 'UPDATE tbl_jobsolicited SET referred = referred - 1 WHERE posID = ' . $row['posID'] . '';
                $this->where = array('control_no' => $row['control_no']);
                $this->Referrals_model->update_referral($this->data, $this->table, $this->where, $jv_newUpdate);
            }
            $status['status'] = true;
            $status['msg'] = 'Selected referred applicant are now deleted.';
        } else {
            $this->data = array('deleted' => true);
            $this->where = array('control_no' => $this->input->post('control_no'));
            $jv_newUpdate = 'UPDATE tbl_jobsolicited SET referred = referred - 1 WHERE posID = ' . $this->input->post('posID') . '';
            if ($this->Referrals_model->update_referral($this->data, $this->table, $this->where, $jv_newUpdate)) {
                $status['status'] = true;
                $status['msg'] = 'Referred applicant is now deleted.';
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function get_referred_applicant_info()
    {
        $this->table = 'tbl_referral';
        $this->select_column = array('tbl_applicant.address', 'tbl_referral.applicantID', 'tbl_company.id AS companyID', 'tbl_company.referred_placemerit', 'tbl_referral.training', 'tbl_referral.dateReferred', 'tbl_referral.remarks', 'tbl_position.posID');
        $this->control_no = $this->input->post('control_no');
        $this->where = array('tbl_referral.control_no' => $this->control_no);
        $applicant = $this->Referrals_model->get_referral($this->table, $this->select_column, $this->where);
        $applicant_list = array(
            "applicantID" => $applicant->applicantID,
            "address" => strtoupper($applicant->address),
            "placemerit" => $applicant->referred_placemerit,
            "companyID" => $applicant->companyID,
            "posID" => $applicant->posID,
            "dateReferred" => $applicant->dateReferred,
            "training" => $applicant->training,
            "remarks" => $applicant->remarks
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($applicant_list));
    }

    public function generate_letter()
    {
        $this->table = 'tbl_referral';
        $this->select_column = array('tbl_applicant.name AS applicant', 'CONCAT(tbl_applicant.address,", ", refBrgy.brgyDesc," ", refcitymun.citymunDesc) as address', 'tbl_company.name AS company', 'tbl_company.manager', 'tbl_company.address AS company_address', 'tbl_position.position');
        $referralList = $this->input->post('referralList');
        $content = array();
        $find = ["[header_bg]", "[current_date]", "[manager]", "[company]", "[address]", "[applicant-name]", "[position]", "[control-no]",  "[applicant-address]", "[content-copy]"];

        //if multiple referral list is selected for printing
        if (is_array($referralList)) {
            foreach ($referralList as $key => $list) {
                $this->control_no = $list['controlNo'];
                $this->where = array('tbl_referral.control_no' => $this->control_no);
                $applicant = $this->Referrals_model->get_referral($this->table, $this->select_column, $this->where);
                $result = $this->Referrals_model->get_letter();;

                foreach ($result as $key => $letter) {
                    for ($x = 0; $x < 3; $x++) {
                        $replace = [base_url('assets/images/referral_bg.jpg'), date("MM d, Y"), ucwords($applicant->manager), strtoupper($applicant->company), ucwords($applicant->company_address), ucwords($applicant->applicant), strtoupper($applicant->position), $this->control_no, ucwords($applicant->address), $this->content_copy[$x]];
                        $content[] = str_replace($find, $replace, $letter->content);
                    }
                }
            }
            //09776857068
        } else {
            $this->control_no = $this->input->post('control_no');
            $this->where = array('tbl_referral.control_no' => $this->control_no);
            $applicant = $this->Referrals_model->get_referral($this->table, $this->select_column, $this->where);
            $result = $this->Referrals_model->get_letter();
            $name = explode('|', $applicant->applicant);

            foreach ($result as $key => $letter) {
                for ($x = 0; $x < 3; $x++) {
                    $replace = [base_url('assets/images/referral_bg.jpg'), date("MM d, Y"), ucwords($applicant->manager), strtoupper($applicant->company), strtoupper($applicant->company_address), ucwords($applicant->applicant), strtoupper($applicant->position), $this->control_no, ucwords($applicant->address), $this->content_copy[$x]];
                    $content[] = str_replace($find, $replace, $letter->content);
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
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


    private function clear_var()
    {
        $this->where = array();
        $this->select_column = null;
        $this->table = null;
        $this->data = array();
    }
}