<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{

    public $referralRules = array(
        'date_from' => array(
            'field' => 'dateFrom',
            'label' => 'Date From',
            'rules' => 'required|xss_clean'
        ),
        'date_to' => array(
            'field' => 'dateTo',
            'label' => 'Date To',
            'rules' => 'required|xss_clean'
        ),
        'frequency' => array(
            'field' => 'frequency',
            'label' => 'Frequency',
            'rules' => 'required|xss_clean'
        ),
        'office' => array(
            'field' => 'office',
            'label' => 'Office',
            'rules' => 'required|xss_clean'
        ),
    );

    public function getReferralReport($column, $table, $where)
    {
        $this->db->select($column);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_referral.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_referral.posid', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->join('refbrgy', 'refbrgy.brgyCode = tbl_applicant.brgyCode', 'inner');
        $this->db->join('refcitymun', 'refcitymun.citymunCode = tbl_applicant.citymunCode', 'inner');
        $this->db->join('refprovince', 'refprovince.provCode = tbl_applicant.provCode', 'inner');
        $this->db->where($where);
        $result = $this->db->get($table);
        return ($result->num_rows() > 0) ? $result->result() : null;
    }

    public function getPlacementReport($column, $table, $where)
    {
        $this->db->select($column);
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_placement.applicantID', 'inner');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_placement.posid', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->where($where);
        $result = $this->db->get($table);
        return ($result->num_rows() > 0) ? $result->result() : null;
    }

    public function getJobSolicitedReport($column, $table, $where)
    {
        $this->db->select($column);
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->where($where);
        $result = $this->db->get($table);
        return ($result->num_rows() > 0) ? $result->result() : null;
    }

    public function getCompany()
    {

        $result = $this->db->query('SELECT DISTINCT tbl_company.name,tbl_company.`id`  FROM tbl_jobsolicited INNER JOIN tbl_position ON tbl_position.`posID` = tbl_jobsolicited.`posID` INNER JOIN tbl_company ON tbl_company.id = tbl_position.`companyID` WHERE tbl_jobsolicited.deleted = FALSE AND tbl_jobsolicited.`vacancy` != tbl_jobsolicited.`referred`');
        return ($result->num_rows() > 0) ? $result->result() : null;
    }


    public function getTotalReferrals()
    {
        $this->db->select('count(*) as total');
        $this->db->where('deleted = false');
        $this->db->group_by('YEAR(dateReferred)');
        $result = $this->db->get('tbl_referral');
        return ($result->num_rows() > 0) ? $result->row()->total : 0;
    }

    public function getTotalPlacements()
    {
        $this->db->select('count(*) as total');
        $this->db->where('deleted = false');
        $this->db->group_by('YEAR(dateHired)');
        $result = $this->db->get('tbl_placement');
        return ($result->num_rows() > 0) ? $result->row()->total : 0;
    }

    public function getTotalJobSolicited()
    {
        $this->db->select('count(*) as total');
        $this->db->where('deleted = false');
        $this->db->group_by('YEAR(dateSolicited)');
        $result = $this->db->get('tbl_jobsolicited');
        return ($result->num_rows() > 0) ? $result->row()->total : 0;
    }

    public function getTotalApplicantsReg()
    {
        $this->db->select('count(*) as total');
        $this->db->where('deleted = false');
        $this->db->group_by('YEAR(dateRegistered)');
        $result = $this->db->get('tbl_applicant');
        return ($result->num_rows() > 0) ? $result->row()->total : 0;
    }

    public function getStatsReferrals($group_by)
    {
        $this->db->select(' COUNT(*) AS data,tbl_applicant.gender,CONCAT(DATE_FORMAT(DATE_ADD(tbl_referral.dateReferred, INTERVAL(-WEEKDAY(tbl_referral.dateReferred)) DAY),"%m-%d"),' - ', DATE_FORMAT(DATE_ADD(tbl_referral.dateReferred, INTERVAL(-WEEKDAY(dateReferred)+4) DAY),"%m-%d"))  AS label');
        $this->db->where('DATE_FORMAT(tbl_referral.dateReferred,"%Y")  = YEAR(CURDATE()) ');
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_referral.applicantID', 'inner');
        $this->db->group_by($group_by);
        // $this->db->group_by('WEEK(tbl_referral.dateReferred),tbl_applicant.gender');
        $this->db->order_by('label ASC');
        $result = $this->db->get('tbl_referral');
        return $result->result();
    }

    public function getStatsPlacement($group_by, $select)
    {
        $this->db->select($select);
        $this->db->where('DATE_FORMAT(tbl_placement.dateHired,"%Y")  = YEAR(CURDATE()) ');
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_placement.applicantID', 'inner');
        $this->db->group_by($group_by);
        $this->db->order_by('label ASC');
        // $this->db->group_by('WEEK(tbl_placement.dateHired),tbl_applicant.gender');
        $this->db->order_by('label ASC');
        $result = $this->db->get('tbl_placement');
        return $result->result();
    }

    public function getStatsReferral($group_by, $select)
    {
        $this->db->select($select);
        $this->db->where('DATE_FORMAT(tbl_referral.dateReferred,"%Y")  = YEAR(CURDATE()) ');
        $this->db->join('tbl_applicant', 'tbl_applicant.id = tbl_referral.applicantID', 'inner');
        $this->db->group_by($group_by);
        $this->db->order_by('label ASC');
        // $this->db->group_by('WEEK(tbl_placement.dateHired),tbl_applicant.gender');
        $this->db->order_by('label ASC');
        $result = $this->db->get('tbl_referral');
        return $result->result();
    }

    public function jobsolitation_status()
    {
        $this->db->select('tbl_jobsolicited.vacancy, tbl_jobsolicited.hired, tbl_jobsolicited.referred,tbl_company.name AS company, tbl_position.position');
        $this->db->where('tbl_jobsolicited.deleted = false');
        $this->db->join('tbl_position', 'tbl_position.posID = tbl_jobsolicited.posID', 'inner');
        $this->db->join('tbl_company', 'tbl_company.id = tbl_position.companyID', 'inner');
        $this->db->order_by('company ASC');
        $result = $this->db->get('tbl_jobsolicited');
        return ($result->num_rows() > 0) ? $result->result() : null;
    }
}
