<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_auth extends CI_Controller
{
    private $data = array();

    function __construct()
    {
        parent::__construct();
        // if (get_cookie('isLogin') == false && $this->session->has_userdata('logged_id')) {
        //     redirect('Dashboard');
        // }
    }

    public function login()
    {
        $this->data['title'] = 'PESO | LOGIN';
        $this->load->view('components/Header', $this->data);
        $this->load->view('LoginLayout');
        $this->load->view('components/Footer');
    }

    public function logout()
    {
        set_cookie('isLogin', false);
        $this->session->unset_userdata(array('userID', 'name', 'position', 'logged_id'));
        $this->session->sess_destroy();
        redirect('jobs');
    }

    public function register()
    {
        $this->data['title'] = 'PESO | REGISTER JobSeeker';
        $this->data['province'] = loadProvince();
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/NavBarMenu');
        $this->load->view('RegisterJobSeekerLayout');
        $this->load->view('components/Footer');
    }

    public function check_user()
    {
        $rules = $this->UserAccount_model->user_acc;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $column = array('userID', 'fullname', 'position', 'username', 'pass');
            $where = array('username' => $this->input->post('username'));
            $table = 'tbl_user';
            $result = $this->UserAccount_model->getUserAccInfo($table, $column, $where);
            if ($result == null) {
                $status['status'] = false;
                $status['msg'] = 'Incorrect username/password';
            } else {
                if (password_verify($this->input->post('password'), $result->pass)) {

                    $_where = array('tbl_applicant.userID' => $result->userID);
                    $_result = $this->Applicant_model->ApplicantData($_where);
                    $status['status'] = true;
                    $status['position'] = $result->position;
                    $status['msg'] = 'Login successfull. redirecting.... ';
                    set_cookie('isLogin', true, 3600);
                    $user_data = array(
                        'userID' => $result->userID,
                        'name' => strtoupper($result->fullname),
                        'email' => $result->username,
                        'position' => $result->position,
                        'logged_in' => true
                    );

                    $this->session->set_userdata($user_data);
                    if ($_result) {
                        $this->session->set_userdata(array('image_dir' => $_result->image_dir, 'dateCreated' => $_result->dateRegistered, 'appID' => $_result->id));
                    }

                    $column = array('tbl_modules.modules', 'tbl_access_rights._add', 'tbl_access_rights._edit', 'tbl_access_rights._delete');
                    $where = array('userID' => $result->userID);
                    $access_rights = $this->UserAccount_model->getUserAccessRights('tbl_access_rights', $where, $column);

                    $this->session->set_userdata('access_rights', $access_rights);
                } else {
                    $status['status'] = false;
                    $status['msg'] = 'Incorrect username/password.';
                }
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
}