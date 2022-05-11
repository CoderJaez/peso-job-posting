<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_account extends CI_Controller
{
    private $data = array();
    private $table = null;
    private $where = array();
    private $select_column = array();
    private $userID = null;
    private $order_column = array();

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
        if (!isset($this->data['settings'])) {
            redirect('Error_page');
        }
    }

    public function index()
    {
        $this->data['ApplicantsModule'] = (isset($this->data['applicant']) || isset($this->data['placement']) || isset($this->data['referralClicked'])) ? true : false;
        $this->data['ApplicantsModule'] = ($this->data['applicant'] || $this->data['placement'] || $this->data['referral']) ? true : false;
        $this->data['title'] = 'USER ACCOUNTS';
        $this->data['SettingsClicked'] = true;
        $this->data['user_account'] = true;
        $this->data['modules'] = $this->UserAccount_model->module_list();
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('UserAccountLayout');
        $this->load->view('modals/UserAccForm_modal');
        $this->load->view('components/Footer');
    }




    public function user_account_list()
    {
        $this->table = 'tbl_user';
        $this->select_column = array('userID', 'fullname', 'username', 'dateRegistered', 'position', 'active');
        $this->order_column = array(null, 'fullname', 'position', 'username', 'dateRegistered', null, null);
        $fetched_data = $this->UserAccount_model->generate_datatables($this->table, $this->select_column, $this->order_column);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $active_status = ($row->active) ? '<button class="btn btn-xs btn-success btnSetActiveInactive" data-status="1" data-id="' . $row->userID . '">Active</button>' : '<button class="btn btn-xs btn-danger btnSetActiveInactive" data-status="0" data-id="' . $row->userID . '">Inactive</button>';
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="inputSelectUserAccount" data-id="' . $row->userID . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->fullname);
            $sub_array[] = strtoupper($row->position);
            $sub_array[] = strtoupper($row->username);
            $sub_array[] = strtoupper($row->dateRegistered);
            $sub_array[] = '<button class="btn btn-warning btn-xs btnEditUser" data-toogle="tooltip" title="Edit"   data-id="' . $row->userID . '"><i class="fa fa-edit"></i></button> <button data-toogle="tooltip" title="Delete" class="btn btn-danger btn-xs btnDeleteUser"  data-id="' . $row->userID . '"><i class="fa fa-trash"></i></button> ' . $active_status;
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->UserAccount_model->get_all_data(array('deleted' => false)),
            "recordsFiltered" => $this->UserAccount_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function delete_account()
    {
        $this->table = 'tbl_user';
        $this->where = array('userID' => $this->input->post('userID'));
        $this->data = array('deleted' => true);
        $this->db->set($this->data);
        $this->db->where($this->where);
        if ($this->db->update($this->table)) {
            $status['status'] = true;
            $status['msg'] = 'User account successfully deleted.';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function setActiveInactive()
    {
        $active_status = ($this->input->post('status') == "true") ? false : true;
        $msg = ($this->input->post('status') == "true") ? 'inactive' : 'active';
        $this->where = array('userID' => $this->input->post('userID'));
        $this->data = array('active' => $active_status);
        $this->table = 'tbl_user';
        if ($this->UserAccount_model->setActiveDeactiveUser($this->table, $this->data, $this->where)) {
            $status['status'] = true;
            $status['msg'] = 'User account successfully ' . $msg;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }


    public function addnew_account()
    {
        $rules = $this->UserAccount_model->user_rules;
        $this->form_validation->set_rules($rules);
        $userID = userID();
        $this->table = 'tbl_user';
        if ($this->form_validation->run() === TRUE) {
            $user_data = array(
                'userID' => $userID,
                'fullname' => strtoupper($this->input->post('fullname')),
                'username' => $this->input->post('uname'),
                'position' => $this->input->post('position'),
                'pass' => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
                'active' => true
            );
            $access_rights = array();
            foreach ($this->input->post('access_rights') as $key => $value) {
                $sub_array['userID'] = $userID;
                foreach ($value as $k => $row) {
                    $sub_array[$k] = ($row == "true") ? true : (($row == "false") ? false : $row);
                }
                $access_rights[] = $sub_array;
            }
            if ($this->UserAccount_model->add_new_acc($this->table, $user_data, $access_rights)) {
                $status['status'] = true;
                $status['msg'] = 'Success';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function getUserAccount()
    {
        $this->userID = $this->input->post('userID');
        $this->where = array('userID' => $this->userID);
        $this->select_column = array('userID', 'fullname', 'username', 'position');
        $this->table = 'tbl_user';
        $status['user_data'] = $this->UserAccount_model->getUserAccInfo($this->table, $this->select_column, $this->where);
        $this->table = 'tbl_access_rights';
        $status['access_rights'] = $this->UserAccount_model->getUserAccessRights($this->table, $this->where, 'tbl_access_rights.*');
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function update_account($id)
    {
        $this->userID = $id;
        $rules = $this->UserAccount_model->user_rules;
        $this->form_validation->set_rules($rules);
        $userID = userID();
        $this->table = 'tbl_user';
        $this->where = array('userID' => $this->userID);
        if ($this->form_validation->run() === TRUE) {
            $user_data = array(
                'fullname' => strtoupper($this->input->post('fullname')),
                'username' => $this->input->post('uname'),
                'position' => $this->input->post('position'),
                'pass' => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
                'active' => true
            );
            $access_rights = array();
            foreach ($this->input->post('access_rights') as $key => $value) {
                $sub_array['userID'] = $this->userID;
                foreach ($value as $k => $row) {
                    $sub_array[$k] = ($row == "true") ? true : (($row == "false") ? false : $row);
                }
                $access_rights[] = $sub_array;
            }
            if ($this->UserAccount_model->update_acc($this->table, $user_data, $access_rights, $this->where)) {
                $status['status'] = true;
                $status['msg'] = 'Success';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }




    /*
    * validation for username duplication
    */

    public function _validateUsername($str)
    {
        if ($this->userID == null) {
            $where = array('username' => $this->input->post('uname'), 'deleted' => false);
        } else {
            $where = array('username' => $this->input->post('uname'), 'deleted' => false, 'userID' => $this->input->post('userID'));
        }
        $this->db->where($where);
        $result = $this->db->get('tbl_user');
        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('_validateUsername', $this->input->post('uname') . ' is already registered.');
            return FALSE;
        }
        return TRUE;
    }
}