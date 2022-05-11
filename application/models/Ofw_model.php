<?php
defined('BASEPATH') or exit('No direct script access allowed. ');


class Ofw_model extends CI_Model
{
    private $table = null;
    private $where = null;
    private $select_column = null;

    public $ofw_rules = array(
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
        'brgyCode' => array(
            'field' => 'brgyCode',
            'label' => 'Barangay',
            'rules' => 'required|xss_clean'
        ),
        'country' => array(
            'field' => 'country',
            'label' => 'Country deployment',
            'rules' => 'required|xss_clean'
        ),
        'yearFromTo' => array(
            'field' => 'yearFromTo',
            'label' => 'Years From - To',
            'rules' => 'required|xss_clean|min_length[8]'
        ),
        'fbAcc' => array(
            'field' => 'fbAcc',
            'label' => 'Facebook account',
            'rules' => 'required|xss_clean'
        ),
        'email' => array(
            'field' => 'email',
            'label' => 'Email address',
            'rules' => 'required|xss_clean|valid_email'
        ),
        'dependentName' => array(
            'field' => 'dependentName',
            'label' => 'Dependent Name',
            'rules' => 'required|xss_clean'
        ),
        'contactNo' => array(
            'field' => 'contactNo',
            'label' => 'Contact number',
            'rules' => 'required|xss_clean|min_length[11]'
        ),
        'dependentFb' => array(
            'field' => 'dependentfbAcc',
            'label' => 'Dependent Facebook account',
            'rules' => 'required|xss_clean'
        )
    );

    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_ofw.brgyCode', 'inner');
        $where = '(tbl_ofw.lname LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_ofw.deleted = false) OR (refbrgy.brgyDesc LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_ofw.deleted = false) OR (tbl_ofw.country LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false) OR (tbl_ofw.deleted = false AND tbl_ofw.dependentName LIKE "%' . $_POST["search"]["value"] . '%")';
        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tbl_ofw.dateRegistered ASC, tbl_ofw.lname ASC');
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


    public function add_ofw($table, $data)
    {
        return ($this->db->insert($table, $data)) ? true : false;
    }

    public function update_ofw($table, $data, $where)
    {
        return ($this->db->update($table, $data, $where)) ? true : false;
    }
}