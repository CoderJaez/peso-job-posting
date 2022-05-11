<?php
defined('BASEPATH') or exit('No direct acccess is allowed');

class JobSolicitation_model extends CI_Model
{
    private $table = null;
    private $select_column = array();
    private $order_column = array();
    private $data = array();
    private $where = null;

    public $company_position_rules = array(
        'placemerit' => array(
            'field' => 'placemerit',
            'label' => 'Referred placemerit',
            'rules' => 'required'
        ),
        'company' => array(
            'field' => 'company',
            'label' => 'Company / Establishment',
            'rules' => 'required|xss_clean|callback__validateCompany'
        ),
        'position' => array(
            'field' => 'position[]',
            'label' => 'Position',
            'rules' => 'required|xss_clean|callback__validatePosition'
        ),
        'address' => array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'required|xss_clean'
        ),
        'manager' => array(
            'field' => 'manager',
            'label' => 'Manager',
            'rules' => 'required|xss_clean'
        ),

    );


    public $js_rules = array(
        'company' => array(
            'field' => 'js_company',
            'label' => 'Company / Establishment',
            'rules' => 'required'
        ),
        'position' => array(
            'field' => 'js_position',
            'label' => 'Position',
            'rules' => 'required|callback__validateVacancyPosition'
        ),
        'vacancy' => array(
            'field' => 'vacancy',
            'label' => 'Vacancy',
            'rules' => 'required'
        ),
        'referral' => array(
            'field' => 'referral',
            'label' => 'Referral',
            'rules' => 'required'
        ),
        'dateSolicit' => array(
            'field' => 'dateSolicited',
            'label' => 'Date Solicited',
            'rules' => 'required'
        ),
        'job_descp' => array(
            'field' => 'job_desc',
            'label' => 'Job description',
            'rules' => 'required'
        ),
        'job_req' => array(
            'field' => 'job_req',
            'label' => 'Job requirements',
            'rules' => 'required'
        ),
    );

    public function get_JobSolicitedData($table, $column, $where)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->where = $where;
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_company.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_company.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_company.provCode');
        $this->db->where($this->where);
        return $this->db->get()->row();
    }

    public function getJobApplicationList($where)
    {
        $this->table = 'tbl_applicants_applied';
        $this->select_column = 'tbl_position.position, tbl_company.name AS company, CONCAT(tbl_company.address, ", ", refbrgy.brgyDesc, ", ", refcitymun.citymunDesc) AS company_address, tbl_applicants_applied.dateApplied, tbl_applicants_applied.referred';
        $this->where = $where;
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_applicants_applied.appID', 'inner');
        $this->db->join('tbl_user', 'tbl_user.userID = tbl_applicant.userID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_applicants_applied.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_company.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_company.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_company.provCode');
        $this->db->where($this->where);
        return $this->db->get()->result();
    }



    public function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $where = ' (tbl_company.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_jobsolicited.deleted = false ) OR(tbl_position.position LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_jobsolicited.deleted = false )';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tbl_jobsolicited.dateSolicited DESC, tbl_position.position ASC');
        }
    }

    public function get_jobvacancy($table, $column, $where, $limit, $start)
    {
        $this->db->select($column);
        $this->db->from($table);
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_company.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_company.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_company.provCode');
        $this->db->where($where);
        $this->db->order_by('tbl_jobsolicited.dateSolicited DESC, tbl_position.position ASC');
        $this->db->limit($limit);
        $this->db->offset($start);
        $result = $this->db->get();
        return $result->result();
    }

    public function count_all_jv($table, $where, $column = null, $group_by = null)
    {
        if ($column != null) $this->db->select($column);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_company.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_company.citymunCode', 'inner');
        $this->db->order_by('refcitymun.citymunDesc ASC, refbrgy.brgyDesc');
        if ($group_by != null) {
            $this->db->group_by($group_by);
            $result = $this->db->get()->result();
        } else {
            $result = $this->db->count_all_results();
        }
        return $result;
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


    public function saveJobSolicited($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
        return ($this->db->insert($this->table, $this->data)) ? true : false;
    }

    public function updateJobSolicited($table, $data, $where)
    {
        $this->table = $table;
        $this->data = $data;
        $this->where = $where;
        return ($this->db->update($this->table, $this->data, $this->where)) ? true : false;
    }

    public function saveCompanyPosition($companyData, $positionData, $id)
    {
        $data = array();
        $this->db->trans_start();
        if ($id == null) {
            $companyID = ($this->db->insert('tbl_company', $companyData)) ? $this->db->insert_id() : null;
            foreach ($positionData as $position) {
                array_push($data, array('position' => $position, 'companyID' => $companyID));
            }
        } else {
            foreach ($positionData as $position) {
                array_push($data, array('position' => $position, 'companyID' => $id));
            }
        }
        $this->db->insert_batch('tbl_position', $data);
        $this->db->trans_complete();
        return ($this->db->trans_status()) ? true : false;
    }

    public function getJobVacancy($table, $column, $where)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->where = $where;
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->select($this->select_column);
        $this->db->where($this->where);
        $result = $this->db->get($this->table);
        return ($result->num_rows() > 0) ? $result->result() : null;
    }
}