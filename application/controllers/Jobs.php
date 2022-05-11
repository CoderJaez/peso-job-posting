<?php
defined('BASEPATH') or die("No direct access script is allowed.");
// include_once(dirname(__FILE__) . "/Applicant.php");

class Jobs extends CI_Controller
{
    private $data = array();
    private $table = null;
    private $select_column = null;
    private $where = null;
    private $placemerit = null;
    private $location = null;
    private $brgyCode = null;
    private $limit = 10;
    private $offset = 0;

    function __construct()
    {
        parent::__construct();
    }

    public function index($placemerit = null, $location = null, $brgyCode = null, $page = null)
    {
        $this->data['title'] = "Job hiring in Pagadian";
        if (count($this->uri->segment_array()) == 1) {
            $this->placemerit = null;
            $this->location = null;
            $this->brgyCode = null;
        } else  if (count($this->uri->segment_array()) == 2) {
            if (!is_numeric($this->uri->segment(2))) {
                $this->placemerit = $placemerit;
                $this->location = null;
                $this->brgyCode = null;
            } else {
                $this->placemerit = null;
                $this->location = null;
                $this->brgyCode = null;
            }
        } else if (count($this->uri->segment_array()) == 3) {
            $this->placemerit = $placemerit;
            $this->location = null;
            $this->brgyCode = null;
        } else if (count($this->uri->segment_array()) >= 4) {
            $this->placemerit = $placemerit;
            $this->location = $location;
            $this->brgyCode = $brgyCode;
        }


        $total = $this->countJobVacancies(null);
        $this->data['pagination_links'] = $this->_pagination();
        $this->data['job_vacancy'] = $this->job_vacancy_list();
        $this->data['total_jobs'] = $this->countJobVacancies('COUNT(*) AS total,tbl_company.referred_placemerit', 'tbl_company.referred_placemerit');
        $this->data['total_jobs_location'] = $this->countJobVacanciesLocation('refbrgy.brgyDesc');
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/NavBarMenu');
        $this->load->view('components/SideBarJobCategory');
        $this->load->view('JobsLayout');
        $this->load->view('components/Footer');
    }

    public function job_application()
    {
        $this->data['title'] = "Job hiring in Pagadian";
        $this->data['job_application'] = true;
        $this->data['application_list'] = $this->job_application_list();
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/SideBar');
        $this->load->view('JobApplicationLayout');
        $this->load->view('components/Footer');
    }



    public function applyJob()
    {
        $this->where = array('tbl_jobsolicited.id' => $this->input->post('id'));
        $this->table = 'tbl_jobsolicited';
        $position = $this->JobSolicitation_model->get_JobSolicitedData($this->table, 'tbl_jobsolicited.posID', $this->where);

        $this->table = 'tbl_applicants_applied';
        $this->data = array('appID' => $this->session->userdata('appID'), 'posID' => $position->posID);

        if ($this->Applicant_model->applyJob($this->table, $this->data)) {
            $status['success'] = true;
            $status['msg'] = 'Successfully applied.';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function registerJobSeekerAcc()
    {
        $rules = $this->UserAccount_model->JobUserAccRules;
        $this->form_validation->set_rules($rules);
        $user_id = userID();
        if ($this->form_validation->run() == TRUE) {
            $user_acc = array(
                'userID' => $user_id,
                'position' => 'applicant',
                'fullname' => $this->input->post('fname') . ' ' . $this->input->post('lname'),
                'username' => $this->input->post('email'),
                'pass' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'active' => true
            );
            $applicant_data = array(
                'id' => appID(),
                'userID' => $user_id,
                'name' => strtoupper($this->input->post('fname') . ' ' . $this->input->post('lname')),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'contactNo' => $this->input->post('contactNo'),
                'address' => $this->input->post('address'),
                'brgyCode' => $this->input->post('brgyCode'),
                'citymunCode' => $this->input->post('citymunCode'),
                'provCode' => $this->input->post('provCode'),
            );

            if ($this->UserAccount_model->registerNewJobSeekerAcc($user_acc, $applicant_data)) {
                $status['msg'] = "Account successfully registered.";
                $status['success'] = true;
            }
        } else {
            $status['msg'] = array(
                'fname' => form_error('fname'),
                'lname' => form_error('lname'),
                'email' => form_error('email'),
                'password' => form_error('password'),
                'contactNo' => form_error('contactNo'),
                'gender' => form_error('gender'),
                'address' => form_error('address'),
                'provCode' => form_error('provCode'),
                'citymunCode' => form_error('citymunCode'),
                'brgyCode' => form_error('brgyCode'),

            );
            $status['success'] = false;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    private function _pagination()
    {
        $url = null;
        $uri_segment = null;
        $total_rows = $this->countAllRows();

        if ($this->placemerit != null && $this->location != null) {
            $url = "jobs/$this->placemerit/$this->location/$this->brgyCode";
        } else if ($this->placemerit != null) {
            $url = "jobs/$this->placemerit/";
        } else {
            $url = "jobs/";
        }
        if (count($this->uri->segment_array()) == 3) {
            $this->offset = $this->uri->segment(3);
            $uri_segment = 3;
        } else if (count($this->uri->segment_array()) == 5) {
            $this->offset = $this->uri->segment(5);
            $uri_segment = 5;
        } else {
            $this->offset = $this->uri->segment(2);
            $uri_segment = 2;
        }
        return pagination($total_rows, $this->limit, $url, $uri_segment);
    }

    public function job_vacancy_list()
    {
        $this->table = 'tbl_jobsolicited';
        $this->where = 'tbl_jobsolicited.hired != tbl_jobsolicited.vacancy';
        $this->select_column = array('tbl_jobsolicited.id', 'tbl_company.name', 'tbl_jobsolicited.hired', 'tbl_position.position', 'tbl_company.address', 'refbrgy.brgyDesc', 'refcitymun.citymunDesc', 'tbl_jobsolicited.job_description', 'tbl_jobsolicited.requirements', 'tbl_jobsolicited.vacancy', 'tbl_jobsolicited.hired', 'tbl_jobsolicited.dateSolicited');
        if ($this->placemerit != null && $this->location != null) {
            if ($this->placemerit != 'all-jobs') {
                $this->where .= ' AND tbl_company.referred_placemerit = "' . $this->placemerit . '" ';
            }
            if ($this->location == 'outside-pagadian') {
                $this->where .= ' AND tbl_company.citymunCode != "097322"';
            } else {
                $this->where .= ' AND tbl_company.brgyCode = "' . $this->brgyCode . '"';
            }
        } else if ($this->placemerit != null && $this->placemerit != 'all-jobs') {
            $this->where .= ' AND tbl_company.referred_placemerit = "' . $this->placemerit . '"';
        } else if ($this->placemerit != null && $this->placemerit == 'all-jobs') {
            $this->where .= ' AND tbl_company.citymunCode != "097322"';
        }
        $fetched_data = $this->JobSolicitation_model->get_jobvacancy($this->table, $this->select_column, $this->where, $this->limit, $this->offset);
        return $fetched_data;
    }


    public function get_jobsolicited_data($id)
    {
        $this->where = array('tbl_jobsolicited.id' => $id);
        $this->table = 'tbl_jobsolicited';
        $this->select_column = array('tbl_jobsolicited.posID', 'tbl_position.position', 'tbl_company.name as company', 'tbl_jobsolicited.job_description', 'tbl_company.address', 'refbrgy.brgyDesc', 'refcitymun.citymunDesc', 'refprovince.provDesc', 'tbl_jobsolicited.requirements', 'tbl_jobsolicited.vacancy', 'tbl_jobsolicited.hired', 'tbl_jobsolicited.dateSolicited', 'tbl_company.id AS companyID');
        $status = $this->JobSolicitation_model->get_JobSolicitedData($this->table, $this->select_column, $this->where);
        return $status;
    }


    public function view($id, $position, $company)
    {
        $this->data['total_jobs_location'] = $this->countJobVacanciesLocation('refbrgy.brgyDesc');
        $this->data['total_jobs'] = $this->countJobVacancies('COUNT(*) AS total,tbl_company.referred_placemerit', 'tbl_company.referred_placemerit');
        $this->data['province'] = loadProvince();
        $this->data['job_data'] = $this->get_jobsolicited_data($id);
        $this->data['title'] = ucwords($company);
        $this->data['has_applied'] = ($this->Applicant_model->getApplicantAppliedData(array('tbl_applicants_applied.posID' => $this->get_jobsolicited_data($id)->posID))) ? true : false;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/NavBarMenu');
        $this->load->view('components/SideBarJobCategory');
        $this->load->view('JobDataLayout');
        $this->load->view('components/Footer');
    }


    private function countAllRows()
    {
        $this->where = 'tbl_jobsolicited.hired != tbl_jobsolicited.vacancy';
        if ($this->placemerit != null && $this->location != null) {
            $this->where .= ' AND tbl_company.referred_placemerit = "' . $this->placemerit . '" ';
            if ($this->location == 'outside-pagadian') {
                $this->where .= ' AND tbl_company.citymunCode = "' . $this->brgyCode . '"';
            } else {
                $this->where .= ' AND tbl_company.brgyCode = "' . $this->brgyCode . '"';
            }
        } else if ($this->placemerit != null) {
            $this->where .= ' AND tbl_company.referred_placemerit = "' . $this->placemerit . '"';
        }
        $this->table = 'tbl_jobsolicited';
        return $this->JobSolicitation_model->count_all_jv($this->table, $this->where, null);
    }
    public function countJobVacancies($column, $group_by = null)
    {
        $this->where = 'tbl_jobsolicited.hired != tbl_jobsolicited.vacancy';

        $this->table = 'tbl_jobsolicited';
        $this->select_column = $column;
        return $this->JobSolicitation_model->count_all_jv($this->table, $this->where, $this->select_column, $group_by);
    }

    public function countJobVacanciesLocation($group_by = null)
    {
        $this->where = 'tbl_jobsolicited.hired != tbl_jobsolicited.vacancy';
        if ($this->placemerit != null && $this->placemerit != 'all-jobs') {
            $this->where .= ' AND tbl_company.referred_placemerit = "' . $this->placemerit . '"';
            $this->select_column = 'COUNT(*) AS total,refbrgy.brgyDesc,tbl_company.brgyCode,tbl_company.referred_placemerit, tbl_company.citymunCode, refcitymun.citymunDesc';
        } else {
            $this->select_column = 'COUNT(*) AS total,refbrgy.brgyDesc,tbl_company.brgyCode, tbl_company.citymunCode, refcitymun.citymunDesc';
        }
        $this->table = 'tbl_jobsolicited';
        return $this->JobSolicitation_model->count_all_jv($this->table, $this->where, $this->select_column, $group_by);
    }





    private function do_upload($appID)
    {
        if (isset($_FILES['resume']['name'])) {
            $path = './assets/uploads/' . $appID;
            if (!is_dir($path)) //create the folder if it's not already exists
            {
                mkdir($path, 0755, TRUE);
            }

            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|doc|docx';
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('resume')) {
                $post_img = 'assets/uploads/' . $appID . '/' . $this->upload->data('file_name');
            } else {
                $post_img = 'No file uploaded.';
            }
            return $post_img;
        }
    }

    public function quick_apply()
    {
        $rules = $this->Applicant_model->applicant_rules;
        $this->form_validation->set_rules($rules);
        $jobSolicitedID = $this->input->post('id');

        if ($this->form_validation->run() === TRUE) {
            $this->where = array('tbl_jobsolicited.id' => $jobSolicitedID);
            $this->table = 'tbl_jobsolicited';
            $position = $this->JobSolicitation_model->get_JobSolicitedData($this->table, 'tbl_jobsolicited.posID', $this->where);
            $id = appID();
            $data = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'contactNo' => str_replace('-', '', $this->input->post('contactNo')),
                'gender' => $this->input->post('gender'),
                'address' => $this->input->post('address'),
                'citymunCode' => $this->input->post('citymunCode'),
                'provCode' => $this->input->post('provCode'),
                'brgyCode' => $this->input->post('brgyCode'),
                'email' => $this->input->post('email'),
                'resume_dir' => $this->do_upload($id),
            );
            $applied_data = array(
                'appID' => $id,
                'posId' => $position->posID
            );

            if ($this->Applicant_model->addApplied_applicant($data, $applied_data)) {
                $status['success'] = true;
                $status['msg'] = "You've successfully applied.";
            }
        } else {
            $status['success'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    // Load list of job application for every applicants
    public function job_application_list()
    {
        $this->where = array('tbl_applicant.userID' => $this->session->userdata('userID'));
        return $this->JobSolicitation_model->getJobApplicationList($this->where);
    }


    public function _validateEmail($str)
    {
        $where = array('username' => $this->input->post('email'), 'deleted' => false);
        $this->db->where($where);
        $result = $this->db->get('tbl_user');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateEmail', $this->input->post('email') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }
}