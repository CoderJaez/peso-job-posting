<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserAccount_model extends CI_Model
{

    private $table = null;
    private $select_column = array();
    private $order_column = array();

    public $user_acc = array(
        'username' => array(
            'field' => 'username',
            'label' => 'User name',
            'rules' => 'required|xss_clean'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|xss_clean'
        )
    );

    public $user_rules = array(
        'fullname' => array(
            'field' => 'fullname',
            'label' => 'Fullname',
            'rules' => 'required|xss_clean'
        ),
        'position' => array(
            'field' => 'position',
            'label' => 'Position',
            'rules' => 'required|xss_clean'
        ),
        'username' => array(
            'field' => 'uname',
            'label' => 'User name',
            'rules' => 'required|xss_clean|callback__validateUsername'
        ),
        'password' => array(
            'field' => 'pass',
            'label' => 'Password',
            'rules' => 'required|xss_clean'
        ),
        'access_rights' => array(
            'field' => 'access_rights[]',
            'label' => 'Access rights',
            'rules' => 'required|xss_clean',
            array(
                'required' => 'You have not selected the %s'
            )
        )
    );

    public $JobUserAccRules = array(
        'fname' => array(
            'field' => 'fname',
            'label' => 'Firstname',
            'rules' => 'required|xss_clean'
        ),
        'lname' => array(
            'field' => 'lname',
            'label' => 'Lastname',
            'rules' => 'required|xss_clean'
        ),
        'email' => array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|xss_clean|valid_email|callback__validateEmail'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|xss_clean|min_length[6]'
        ),
        'address' => array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'required|xss_clean'
        ),
        'brgyCode' => array(
            'field' => 'brgyCode',
            'label' => 'Barangay',
            'rules' => 'required|xss_clean'
        ),
        'citymunCode' => array(
            'field' => 'citymunCode',
            'label' => 'City/Municipality',
            'rules' => 'required|xss_clean'
        ),
        'provCode' => array(
            'field' => 'provCode',
            'label' => 'Province',
            'rules' => 'required|xss_clean'
        ),
        'gender' => array(
            'field' => 'gender',
            'label' => 'Gender',
            'rules' => 'required|xss_clean'
        ),
        'contactNo' => array(
            'field' => 'contactNo',
            'label' => 'Contact no.',
            'rules' => 'required|xss_clean|min_length[11]|max_length[14]|numeric'
        ),
    );

    public $applicantAccRules = array(
        'email' => array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|xss_clean|valid_email|callback__validateEmail'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|xss_clean|min_length[6]'
        ),
        'confirm_password' => array(
            'field' => 'confirm_password',
            'label' => 'Confirm Password',
            'rules' => 'required|xss_clean|min_length[6]|matches[password]'
        )
    );

    public function add_new_acc($table, $user_data, $access_rights)
    {
        $this->table = $table;
        return ($this->db->insert($this->table, $user_data) && $this->db->insert_batch('tbl_access_rights', $access_rights)) ? true : false;
    }

    public function registerNewJobSeekerAcc($users_acc, $applicant_data)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_user', $users_acc);
        $this->db->insert('tbl_applicant', $applicant_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }



    public function module_list()
    {
        return $this->db->get('tbl_modules')->result();
    }



    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $where = '(fullname LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false) OR (username LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false)';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dateRegistered ASC, username ASC');
        }
    }

    public function get_all_data($where)
    {
        $this->db->from($this->table);
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function generate_datatables($table, $column, $order)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->order_column = $order;
        $this->make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserAccInfo($table, $column, $where)
    {
        $this->db->select($column);
        $this->db->where($where);
        $this->db->from($table);
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->row() : null;
    }

    public function getUserAccessRights($table, $where, $column)
    {
        $this->db->select($column);
        $this->db->where($where);
        $this->db->from($table);
        $this->db->join('tbl_modules', 'tbl_modules.moduleID = tbl_access_rights.moduleID', 'inner');
        return $this->db->get()->result();
    }

    public function update_acc($table, $user_data, $access_rights, $where)
    {
        $this->table = $table;
        $this->where = $where;
        if ($this->delete_access_rights()) {
            $this->db->set($user_data);
            $this->db->from($this->table);
            $this->db->where($this->where);
            return ($this->db->update() && $this->db->insert_batch('tbl_access_rights', $access_rights)) ? true : false;
        }
    }

    private function delete_access_rights()
    {
        return $this->db->delete('tbl_access_rights', $this->where);
    }


    public function setActiveDeactiveUser($table, $data, $where)
    {
        $this->db->set($data);
        $this->db->from($table);
        $this->db->where($where);
        return ($this->db->update()) ? true : false;
    }
}
