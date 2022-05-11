<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resumes extends CI_Controller
{
    private $data = array();
    private $where = array();

    public function __construct()
    {
        parent::__construct();
        if (!get_cookie('isLogin')) {
            redirect('login');
        } else {
            set_cookie('isLogin', true, 3600);
        }

        if ($this->session->userdata('position') != 'applicant') {
            redirect('Error_page');
        }
    }

    public function profile()
    {
        $this->data['title'] = "Job hiring in Pagadian";
        $this->data['profile'] = true;
        $this->data['province'] = loadProvince();
        $this->data['applicant'] = $this->ApplicantData();
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/SideBar');
        $this->load->view('ResumeLayout');
        $this->load->view('components/Footer');
    }


    public function account()
    {
        $this->data['title'] = "Job hiring in Pagadian";
        $this->data['account_settings'] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/SideBar');
        $this->load->view('ApplicantUserAcc');
        $this->load->view('components/Footer');
    }

    private function ApplicantData()
    {
        $where = array('tbl_applicant.userID' => $this->session->userdata('userID'));

        $result = $this->Applicant_model->ApplicantData($where);
        $this->session->set_userdata('applicant', $result);
        return  $result;
    }

    public function getApplicantData()
    {
        $where = array('tbl_applicant.userID' => $this->session->userdata('userID'));

        $result = $this->Applicant_model->ApplicantData($where);
        $this->session->set_userdata('applicant', $result);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function updatePersonalInfo()
    {
        $rules = $this->Applicant_model->applicant_rules;
        $rules['bday'] = array('field' => 'bday', 'label' => 'Date of birth', 'rules' => 'required|xss_clean');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $this->session->set_userdata('name', $this->input->post('name'));

            $data = array(
                'name' => $this->input->post('name'),
                'bday' => $this->input->post('bday'),
                'contactNo' => str_replace('-', '', $this->input->post('contactNo')),
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'address' => $this->input->post('address'),
                'citymunCode' => $this->input->post('citymunCode'),
                'provCode' => $this->input->post('provCode'),
                'brgyCode' => $this->input->post('brgyCode')
            );

            $where = array('userID' => $this->session->userdata('userID'));
            if ($this->Applicant_model->update_applicant($data, 'tbl_applicant', $where)) {
                $status['msg'] = "Applicant's information  updated.";
                $status['success'] = true;
                $_data = array('fullname' => $this->input->post('name'));
                $where = array('userID' => $this->session->userdata('userID'));
                $this->Applicant_model->update_applicant($_data, 'tbl_user', $where);
            }
        } else {
            $status['success'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function update_account()
    {
        $rules = $this->UserAccount_model->applicantAccRules;
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'username' => $this->input->post('email'),
                'pass' => password_hash($this->input->post('confirm_password'), PASSWORD_DEFAULT)
            );
            $where = array('userID' => $this->session->userdata('userID'));
            if ($this->Applicant_model->update_applicant($data, 'tbl_user', $where)) {
                $this->session->set_userdata(array('email' => $this->input->post('email')));
                $status['msg'] = "Applicant's user account updated.";
                $status['success'] = true;
            }
        } else {
            $status['msg'] = validation_errors();
            $status['success'] = false;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function upload_photo()
    {
        if (isset($_FILES['photo']['name'])) {
            $path = './assets/uploads/images/' . $this->session->userdata('userID');
            if (!is_dir($path)) //create the folder if it's not already exists
            {
                mkdir($path, 0755, TRUE);
            }

            $config['upload_path'] = $path;
            $config['allowed_types'] = 'png|jpeg|jpg';
            $this->load->library('upload', $config);
            $files = scandir($path);
            

            if ($this->upload->do_upload('photo')) {
                $post_img = 'assets/uploads/images/' . $this->session->userdata('userID') . '/' . $this->upload->data('file_name');
                $data = array('image_dir' => $post_img);
                $where = array('userID' => $this->session->userdata('userID'));
                if ($this->Applicant_model->update_applicant($data, 'tbl_applicant', $where)) {
                    $status['success'] = true;
                    $status['msg'] = 'Upload success.';
                }
            } else {
                $status['msg'] = $this->upload->display_errors();
                $status['success'] = false;
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function _validateEmail($str)
    {
        $where = array('username' => $this->input->post('email'), 'deleted' => false, 'userID !=' => $this->session->userdata('userID'));
        $this->db->where($where);
        $result = $this->db->get('tbl_user');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateEmail', $this->input->post('email') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }
}