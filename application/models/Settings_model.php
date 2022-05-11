<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    private $table = '';
    private $select_column = array();
    private $order_column = array();
    private $where = array();
    public $companyRules = array(
        'company' => array(
            'field' => 'company_name',
            'label' => 'Company name',
            'rules' => 'required|xss_clean|callback__validateCompany',
        ),
        'address' => array(
            'field' => 'company_address',
            'label' => 'Company address',
            'rules' => 'required|xss_clean',
        ),
        'manager' => array(
            'field' => 'manager_name',
            'label' => 'Manager name',
            'rules' => 'required|xss_clean',
        ),
        'province' => array(
            'field' => 'provCode',
            'label' => 'Province',
            'rules' => 'required|xss_clean'
        ),
        'barangay' => array(
            'field' => 'brgyCode',
            'label' => 'Barangay',
            'rules' => 'required|xss_clean'
        ),
        'city_municipality' => array(
            'field' => 'citymunCode',
            'label' => 'City/Municipality',
            'rules' => 'required|xss_clean'
        )

    );

    public  $positionRules = array(
        'company' => array(
            'field' => 'companyID',
            'label' => 'Company name',
            'rules' => 'required|xss_clean'
        ),
        'position' => array(
            'field' => 'position',
            'label' => 'Position',
            'rules' => 'required|xss_clean|callback__validatePosition',
        ),
    );

    public function company_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_company.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_company.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_company.provCode');
        $where = '(name LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false) OR (address LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false) OR (manager LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false) OR (referred_placemerit LIKE "%' . $_POST["search"]["value"] . '%" AND deleted = false)';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dateRegistered ASC, name ASC');
        }
    }

    public function position_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('tbl_company', 'tbl_position.companyID = tbl_company.id', 'inner');

        $where = '(tbl_position.position LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_position.isdelete = false) OR (tbl_company.name LIKE "%' . $_POST["search"]["value"] . '%" AND tbl_position.isdelete = false)';

        if (isset($_POST["search"]["value"])) {
            $this->db->where($where);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tbl_position.dateRegistered ASC, name ASC');
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
        switch ($this->table) {
            case 'tbl_company':
                $this->company_query();
                break;
            case 'tbl_position':
                $this->position_query();
                break;
            default:
                break;
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function generate_datatables($table, $column, $order)
    {
        $this->table = $table;
        $this->select_column = $column;
        $this->order_column = $order;
        switch ($this->table) {
            case 'tbl_company':
                $this->company_query();
                break;
            case 'tbl_position':
                $this->position_query();
                break;
            default:
                break;
        }
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function add_company($data)
    {
        if ($this->db->insert('tbl_company', $data))
            return true;
        return false;
    }

    public function update_company($data, $id)
    {
        $this->db->set($data);
        $this->db->where(array('id' => $id));
        return ($this->db->update('tbl_company')) ? true : false;
    }
    public function add_position($data, $table)
    {
        if ($this->db->insert($table, $data))
            return true;
        return false;
    }

    public function update_position($data, $table, $where)
    {
        $this->db->set($data);
        $this->db->where($where);
        return ($this->db->update($table)) ? true : false;
    }

    public function getLastId()
    {
        $this->db->select('id');
        $this->db->order_by('dateRegistered, DESC');
        $result = $this->db->get('tbl_company', 1);
        if ($result->num_rows() > 0)
            return $result->row();
        return null;
    }

    public function display_list($table, $column, $where)
    {
        $this->select_column = $column;
        $this->table = $table;
        $this->where = $where;

        $this->db->select($this->select_column);
        $this->db->where($this->where);
        $result = $this->db->get($this->table);
        return ($result->num_rows() > 0) ? $result->result() : null;
    }
}