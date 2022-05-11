<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referrals_model extends CI_Model
{
    private $table = null;
    private $data = array();
    private $column = array();
    private $where = array();

    public $referral_rules = array(
        'applicant' => array(
            'field' => 'applicantID',
            'label' => 'Applicant Name',
            'rules' => 'required|xss_clean'
        ),
        'position' => array(
            'field' => 'position',
            'label' => 'Desired position',
            'rules' => 'required|xss_clean|callback__validatePosition'
        ),
        'dateReferred' => array(
            'field' => 'dateReferred',
            'label' => 'Date referred',
            'rules' => 'required|xss_clean'
        ),
        'remarks' => array(
            'field' => 'remarks',
            'label' => 'Remarks',
            'rules' => 'required|xss_clean'
        )
    );

    public function addNew_referral($data, $table, $q1, $q2 = null)
    {
        $this->db->trans_start();
        $this->data = $data;
        $this->table = $table;
        $this->db->insert($this->table, $this->data);
        $this->db->query($q1);
        if ($q2 != null) $this->db->query($q2);
        $this->db->trans_complete();
        return ($this->db->trans_status()) ? true : false;
    }
    public function list_of_company($where)
    {
        $this->db->select('name');
        $this->db->where($where);
        $result = $this->db->get('tbl_company');
        return ($result->num_row > 0) ? $result->result() : null;
    }


    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_referral.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_referral.posid', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        $where = '(tbl_applicant.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false ) OR (tbl_applicant.address LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false ) OR (tbl_applicant.gender LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false ) OR (tbl_company.referred_placemerit LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false ) OR (tbl_company.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false ) OR(tbl_position.position LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_referral.deleted = false )';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tbl_referral.dateReferred DESC, tbl_applicant.name ASC');
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


    public function get_letter()
    {
        $result = $this->db->get('tbl_letter');
        return $result->result();
    }

    public function update_referral($data, $table, $where, $q1, $q2 = null)
    {
        $this->table = $table;
        $this->data = $data;
        $this->where = $where;
        $this->db->trans_start();
        $this->db->set($this->data);
        $this->db->where($this->where);
        $this->db->update($this->table);
        $this->db->query($q1);
        if ($q2 != null) {
            $this->db->query($q2);
        }
        $this->db->trans_complete();
        return ($this->db->trans_status())  ? true : false;
    }

    public function get_referral($table, $select_column, $where)
    {
        $this->select_column = $select_column;
        $this->table = $table;
        $this->where = $where;
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where($this->where);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_referral.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_referral.posid', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $result = $this->db->get();
        return ($result->num_rows() > 0) ? $result->row() : null;
    }
}
