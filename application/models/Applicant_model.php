<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applicant_model extends CI_Model
{
    private $table = '';
    private $select_column = array();
    private $order_column = array();
    private $requestType = null;
    private $data = array();

    public $applicant_rules = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Applicant name',
            'rules' => 'required|xss_clean'
        ),
        'address' => array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'required|xss_clean'
        ),
        'bryCode' => array(
            'field' => 'brgyCode',
            'label' => 'Barangay',
            'rules' => 'required|xss_clean'
        ),
        'cityCode' => array(
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
        'contact' => array(
            'field' => 'contactNo',
            'label' => 'Contact no.',
            'rules' => 'required|xss_clean|min_length[11]|max_length[14]'
        ),
        'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|xss_clean|valid_emails'
        )

    );


    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        if ($this->requestType == 'applied') {
            $this->db->join('tbl_applicants_applied', 'tbl_applicants_applied.appID = tbl_applicant.id', 'inner');
            $this->db->join('tbl_position', 'tbl_position.posID = tbl_applicants_applied.posID', 'inner');
            $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
            $where = '(tbl_applicant.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false  AND tbl_applicants_applied.referred = false) OR (tbl_applicant.address LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false   AND tbl_applicants_applied.referred = false) OR (gender LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false  AND tbl_applicants_applied.referred = false)';
        } else {
            $where = '(tbl_applicant.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false) OR (tbl_applicant.address LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false) OR (gender LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_applicant.deleted = false)';
        }
        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dateRegistered ASC, name ASC');
        }
    }

    public function get_all_data($where)
    {
        $this->db->from($this->table);
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    public function get_allApplied_data($where)
    {
        $this->db->from('tbl_applicants_applied');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function generate_datatables($table, $column, $order, $requestType)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->order_column = $order;
        $this->requestType = $requestType;
        $this->make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function addNew_applicant($data, $table)
    {
        $this->table = $table;
        return ($this->db->insert($this->table, $data)) ? true : false;
    }

    public function addApplied_applicant($applicant_data, $applied_data)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_applicant', $applicant_data);
        $this->db->insert('tbl_applicants_applied', $applied_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_applicant($data, $table, $where)
    {
        $this->table = $table;
        $this->db->set($data);
        $this->db->where($where);
        return ($this->db->update($this->table)) ? true : false;
    }

    public function search_applicant($where, $table)
    {
        $this->table = $table;

        $select_column = array('tbl_applicant.id', 'tbl_applicant.name', 'CONCAT(tbl_applicant.address, ", ", refbrgy.brgyDesc,", ",refcitymun.citymunDesc) AS address');
        $this->db->select($select_column);
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        $this->db->where($where);
        $result = $this->db->get($this->table);
        return $result->row();
    }


    public function getApplicantAppliedData($where)
    {
        $this->table = 'tbl_applicant';
        $select_column = array('tbl_applicant.id', 'tbl_applicant.name', 'CONCAT(tbl_applicant.address, ", ", refbrgy.brgyDesc,", ",refcitymun.citymunDesc) AS address', 'tbl_company.referred_placemerit', 'tbl_applicants_applied.posID', 'tbl_position.position', 'tbl_company.name AS company');
        $this->db->select($select_column);
        $this->db->join('tbl_applicants_applied', 'tbl_applicants_applied.appID = tbl_applicant.id', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_applicants_applied.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->where($where);
        $result = $this->db->get($this->table);
        return $result->row();
    }

    public function ApplicantData($where)
    {
        $this->table = 'tbl_applicant';
        $this->db->select('tbl_applicant.*,refbrgy.brgyDesc,refcitymun.citymunDesc, refprovince.provDesc');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        $this->db->where($where);
        $result = $this->db->get($this->table);
        return $result->row();
    }


    public function applyJob($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
        return $this->db->insert($this->table, $this->data);
    }
    public function getData($column, $table, $order_by, $where = null)
    {
        $this->db->select($column);
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->order_by($order_by);
        $result = $this->db->get($table);
        return $result->result();
    }
}