<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HireApplicant_model extends CI_Model
{

    private $table = null;
    private $where = array();
    private $select_column = array();
    private $order_column = array();
    private $data = array();
    public $applicantRules = array(
        'hiring_status' => array(
            'field' => 'hiring_status',
            'label' => 'Hiring status',
            'rules' => 'required|callback__validateReferredApplicant'
        ),
        'applicant' => array(
            'field' => 'applicantID',
            'label' => 'Applicant name',
            'rules' => 'required|xss_clean|callback__validateHireApplicant'
        ),
        'placemerit' => array(
            'field' => 'placemerit',
            'label' => 'Referred placemerit',
            'rules' => 'required|xss_clean'
        ),
        'company' => array(
            'field' => 'company',
            'label' => 'Company',
            'rules' => 'required|xss_clean'
        ),
        'position' => array(
            'field' => 'position',
            'label' => 'Desired position',
            'rules' => 'required|xss_clean|callback__validatePosition'
        ),
        'remarks' => array(
            'field' => 'remarks',
            'label' => 'Remarks',
            'rules' => 'required|xss_clean'
        ),
        'dateHired' => array(
            'field' => 'dateHired',
            'lable' => 'Date hired',
            'rules' => 'required|xss_clean'
        )
    );


    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_placement.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_placement.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $where = '(tbl_applicant.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false ) OR (tbl_applicant.address LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false ) OR (tbl_applicant.gender LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false ) OR (tbl_company.referred_placemerit LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false ) OR (tbl_company.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false ) OR(tbl_position.position LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_placement.deleted = false )';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tbl_placement.dateHired DESC, tbl_applicant.name ASC');
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


    public function applicantReferred($table, $where)
    {
        $this->table = $table;
        $this->where = $where;
        $this->db->where($this->where);
        $result = $this->db->get($this->table);
        return ($result->num_rows() > 0) ? true : false;
    }


    public function addHired_applicant($table, $data, $jv_data)
    {
        $this->db->trans_start();
        $this->db->insert($table, $data);
        $this->db->query($jv_data);
        $this->db->trans_complete();
        return ($this->db->trans_status()) ? true : false;
    }

    public function updateHired_applicant($table, $data, $where, $newUpdate = null, $prevUpdate = null)
    {

        $this->db->trans_start();
        $this->table = $table;
        $this->data = $data;
        $this->where = $where;
        $this->db->update($this->table, $this->data, $this->where);
        $this->db->query($prevUpdate);
        $this->db->query($newUpdate);
        $this->db->trans_complete();
        return ($this->db->trans_status()) ? true : false;
    }


    public function deleteHired_applicant($table, $data, $where, $jv_query)
    {
        $this->table = $table;
        $this->data = $data;
        $this->where = $where;
        $this->db->trans_start();
        $this->db->update($this->table, $this->data, $this->where);
        $this->db->query($jv_query);
        $this->db->trans_complete();

        return ($this->db->trans_status()) ? true : false;
    }

    public function getApplicantData($table, $column, $where)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->where = $where;
        $this->db->select($this->select_column);
        $this->db->where($this->where);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_placement.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_placement.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $result = $this->db->get($this->table);
        return ($result->num_rows() > 0) ? $result->row() : null;
    }
}