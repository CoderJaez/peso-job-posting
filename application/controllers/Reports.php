<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{
    private $data = array();
    private $table = null;
    private $where = null;
    private $select_column = array();
    private $placeMerits = array('LOCAL', 'GOVERNMENT', 'OVERSEAS');

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
        if (!isset($this->data['report'])) {
            redirect('Error_page');
        }
    }

    public function page($page)
    {
        $this->data['title'] = strtoupper('reports | ' . $page);
        $this->data['ApplicantsModule'] = (isset($this->data['applicant']) || isset($this->data['placement']) || isset($this->data['referral'])) ? true : false;
        $this->data['ApplicantsModule'] = ($this->data['applicant'] || $this->data['placement'] || $this->data['referral']) ? true : false;
        $this->data['ReportsClicked'] = true;
        $this->data[$page . '_report'] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('reports/' . ucwords($page));
        $this->load->view('components/Footer');
    }

    public function generate_referral_reports()
    {
        $this->table = 'tbl_referral';
        $this->selected_column = array('tbl_referral.control_no', 'tbl_applicant.name AS applicant', 'tbl_applicant.gender', 'CONCAT(tbl_applicant.address,", ", refbrgy.brgyDesc,", ", refcitymun.citymunDesc) AS address', 'tbl_applicant.contactNo', 'tbl_company.name AS company', 'tbl_company.referred_placemerit', 'tbl_position.position', 'tbl_referral.remarks', 'tbl_referral.training');
        $where = 'tbl_referral.dateReferred BETWEEN "' . date('Y-m-d', strtotime($this->input->post('dateFrom') . "-1 days")) . '" AND  "' . date('Y-m-d', strtotime($this->input->post('dateTo') . "+1 days")) . '" AND tbl_referral.deleted = false';
        $no = 0;
        $totalMale = 0;
        $totalFemale = 0;
        $content = array();
        $rule = $this->Report_model->referralRules;
        $this->form_validation->set_rules($rule);
        if ($this->form_validation->run() === TRUE) {
            $fetched_data = $this->Report_model->getReferralReport($this->selected_column, $this->table, $where);
            if ($fetched_data != null) {
                foreach ($fetched_data as $key => $row) {
                    $no++;
                    $place_merit = '';
                    foreach ($this->placeMerits as $key => $p) {
                        $place_merit .= ($p == $row->referred_placemerit) ? '<td><i class="fa fa-check"></td>' : '<td></td>';
                    }
                    $totalMale += ($row->gender == 'Male') ? 1 : 0;
                    $totalFemale += ($row->gender != 'Male') ? 1 : 0;
                    $gender = ($row->gender == 'Male') ? '<td><span class="fa fa-check"> </span></td><td></td>' : '<td></td><td><span class="fa fa-check"> </span></td>';
                    $training = ($row->training == "NONE") ? '<td></td><td></td>' : (($row->training == "TESDA") ? '<td><span class="fa fa-check"></span></td><td></td>' : '<td></td><td><span class="fa fa-check"></span></td>');
                    $content[] = '<tr> <td>' . $no . '</td><td>' . strtoupper($row->applicant) . '</td>' . $gender . '<td>' . strtoupper($row->address) . '</td><td>' . $row->contactNo . '</td><td>' . strtoupper($row->company) . '</td><td>' . strtoupper($row->position) . '</td>' . $place_merit . $training . '<td>' . $row->remarks . '</td></tr>';
                }
                $content[] = '<tr><td colspan="2" style="text-align:right;"> TOTAL</td><td>' . $totalMale . '</td><td>' . $totalFemale . '</td><td colspan="10"></td></tr>';
                $content[] = ($this->input->post('office') == 'dole') ? '<tr style="border-style:none !important"><td style="border-style:none !important;font-size:12pt!important;text-align: left;" colspan="12">Note: the PESO shall accomplish the form on a monthly basis and sumbit its accomlishments to the Regional Office every 25th to the succeding month.</td></tr>' : null;
                $content[] = ($this->input->post('office') == 'dole') ? '<tr style="border-style:none !important"><td style="border-style:none !important;font-size:12pt!important;text-align: left;font-weight:bold" colspan="12">REMARKS: <br> 1.4p\'s &nbsp;&nbsp;2.IP\'s &nbsp;&nbsp; 3.GIP\'s&nbsp;&nbsp; 4.PWD\'s &nbsp;&nbsp; 5.JOBSTARTER &nbsp;&nbsp; 6.SPES &nbsp;&nbsp; 7.RETRENCHED &nbsp;&nbsp; 8.RETURNING OFW\'s &nbsp;&nbsp;  9.MIGRATORY WORKERS &nbsp;&nbsp; 10.RURAL WORKERS &nbsp;&nbsp; <br> 11.JOB ORDER &nbsp;&nbsp; 12.REGULAR &nbsp;&nbsp; 13.PLANTILLA</td></tr>' : '<tr style="border-style:none !important"><td colspan="4" style="border-style:none;text-align:right;" >PREPARED BY:</td><td  style="text-align:center;border-style:none;border-bottom:1px solid black;!important"><br><br><br><br>' . $this->session->name . ' </td><td colspan="5" style="border-style:none;text-align:right"> NOTED BY:</td><td style="text-align:center;border-style:none;border-bottom:1px solid black;!important"><br><br><br><br>ENGR. JOSEPH ISAIAS M. QUIPOT VI, CE</td> </tr><tr><td colspan="3" style="border-style:none;" ></td></td><td colspan="2" style="border-style:none;text-align:center">' . $this->session->position . '</td><td colspan="5" style="border-style:none"></td><td style="border-style:none;text-align:center">CITY ADMINISTRATOR / PESO MANAGER</td></tr>';
            } else {
                $content[] = '<tr><td colspan="14">No records found.</td></tr>';
            }
            $status['referral'] = ($this->input->post('frequency') == 'week') ? 'WEEKLY REFERRAL REPORT' : 'MONTHLY REFERRAL REPORT';
            $status['DateFromTo'] =  date('F d, Y', strtotime($this->input->post('dateFrom'))) . ' to ' .  date('F d, Y', strtotime($this->input->post('dateTo')));
            $status['frequency'] = 'For the ' . $this->input->post('frequency') . ' of: ' . date('F d, Y', strtotime($this->input->post('dateFrom'))) . ' to ' .  date('F d, Y', strtotime($this->input->post('dateTo'))) . '';
            $status['header'] = ($this->input->post('office') == 'peso') ? base_url('assets/images/peso_header.jpg') : base_url('assets/images/dole_header_.jpg');
            $status['status'] = true;
            $status['content'] = $content;
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function generate_placement_report()
    {
        $this->table = 'tbl_placement';
        $this->selected_column = array('tbl_applicant.name AS applicant', '(SELECT tbl_referral.`dateReferred` FROM tbl_referral WHERE tbl_referral.`applicantID` = tbl_placement.`applicantID` AND tbl_referral.`posid` = tbl_placement.`posID` AND tbl_referral.deleted = FALSE) AS dateReferred', 'tbl_applicant.gender', 'tbl_company.name AS company', 'tbl_company.referred_placemerit', 'tbl_placement.dateHired', 'tbl_position.position', 'tbl_placement.remarks');
        $where = 'tbl_placement.dateHired BETWEEN "' . date('Y-m-d', strtotime($this->input->post('dateFrom') . "-1 days")) . '" AND  "' . date('Y-m-d', strtotime($this->input->post('dateTo') . "+1 days")) . '" AND tbl_placement.deleted = false';
        $no = 0;
        $totalMale = 0;
        $totalFemale = 0;
        $content = array();
        $rule = $this->Report_model->referralRules;
        $this->form_validation->set_rules($rule);

        if ($this->form_validation->run() === TRUE) {
            $fetched_data = $this->Report_model->getPlacementReport($this->selected_column, $this->table, $where);
            if ($fetched_data != null) {
                foreach ($fetched_data as $key => $row) {
                    $no++;
                    $place_merit = '';
                    foreach ($this->placeMerits as $key => $p) {
                        $place_merit .= ($p == $row->referred_placemerit) ? '<td><i class="fa fa-check"></i> ' : '<td></td>';
                    }
                    $totalMale += ($row->gender == 'Male') ? 1 : 0;
                    $totalFemale += ($row->gender != 'Male') ? 1 : 0;
                    $gender = ($row->gender == 'Male') ? '<td><span class="fa fa-check"> </span></td><td></td>' : '<td></td><td><span class="fa fa-check"> </span></td>';
                    //Data every row//
                    $content[] = '<tr> <td>' . $no . '</td><td>' . $row->dateReferred . '</td><td>' . strtoupper($row->applicant) . '</td><td>' . strtoupper($row->company) . '</td><td>' . strtoupper($row->position) . '</td>' . $place_merit . $gender . '<td>' . $row->dateHired . '</td><td>' . $row->remarks . '</td></tr>';
                }
                $content[] = '<tr><td colspan="7" style="text-align:right;"> TOTAL</td><td>' . $totalMale . '</td><td>' . $totalFemale . '</td><td colspan="2"></td></tr>';
                $content[] = ($this->input->post('office') == 'dole') ? '<tr style="border-style:none !important"><td style="border-style:none !important;font-size:12pt!important;text-align: left;" colspan="12">Note: the PESO shall accomplish the form on a monthly basis and sumbit its accomlishments to the Regional Office every 25th to the succeding month.</td></tr>' : null;
                $content[] = ($this->input->post('office') == 'dole') ? '<tr style="border-style:none !important"><td style="border-style:none !important;font-size:12pt!important;text-align: left;font-weight:bold" colspan="12">REMARKS: <br> 1.4p\'s &nbsp;&nbsp;2.IP\'s &nbsp;&nbsp; 3.GIP\'s&nbsp;&nbsp; 4.PWD\'s &nbsp;&nbsp; 5.JOBSTARTER &nbsp;&nbsp; 6.SPES &nbsp;&nbsp; 7.RETRENCHED &nbsp;&nbsp; 8.RETURNING OFW\'s &nbsp;&nbsp;  9.MIGRATORY WORKERS &nbsp;&nbsp; 10.RURAL WORKERS &nbsp;&nbsp; <br> 11.JOB ORDER &nbsp;&nbsp; 12.REGULAR &nbsp;&nbsp; 13.PLANTILLA</td></tr>' : '<tr style="border-style:none !important"><td colspan="2"  style="border-style:none;text-align:right" >PREPARED BY:</td><td  style="text-align:center;border-style:none;border-bottom:1px solid black;!important"><br><br><br><br>' . $this->session->name . ' </td><td colspan="3" style="border-style:none;text-align:right"> NOTED BY:</td><td colspan="4" style="text-align:center;border-style:none;border-bottom:1px solid black;!important"><br><br><br><br>ENGR. JOSEPH ISAIAS M. QUIPOT VI, CE</td> </tr> <tr><td colspan="2" style="border-style:none;" ></td></td><td style="border-style:none;text-align:center">' . $this->session->position . '</td><td colspan="3" style="border-style:none"></td><td colspan="4" style="border-style:none;text-align:center">CITY ADMINISTRATOR / PESO MANAGER</td></tr>';
            } else {
                $content[] = '<tr><td colspan="11">No records found.</td></tr>';
            }
            $status['placement'] = ($this->input->post('frequency') == 'week') ? 'WEEKLY PLACEMENT REPORT' : 'MONTHLY PLACEMENT REPORT';
            $status['DateFromTo'] =  date('F d, Y', strtotime($this->input->post('dateFrom'))) . ' to ' .  date('F d, Y', strtotime($this->input->post('dateTo')));
            $status['frequency'] = 'For the ' . $this->input->post('frequency') . ' of: ' . date('F d, Y', strtotime($this->input->post('dateFrom'))) . ' to ' .  date('F d, Y', strtotime($this->input->post('dateTo'))) . '';
            $status['header'] = ($this->input->post('office') == 'peso') ? base_url('assets/images/peso_header.jpg') : base_url('assets/images/dole_header_.jpg');
            $status['status'] = true;
            $status['content'] = $content;
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function generate_jobsolicitation_reports()
    {
        $this->table = 'tbl_jobsolicited';
        $this->select_column = array('tbl_position.position', 'tbl_jobsolicited.job_description', 'tbl_jobsolicited.requirements', '(tbl_jobsolicited.vacancy - tbl_jobsolicited.hired) as vacancy', 'tbl_jobsolicited.hired');


        $content = array();
        $rule = $this->Report_model->referralRules;
        $this->form_validation->set_rules($rule);
        $no = 0;
        if ($this->form_validation->run() === TRUE) {
            $company_list = $this->Report_model->getCompany();

            if ($company_list != null) {
                foreach ($company_list as $key => $row) {
                    $no++;
                    $list = '';

                    $this->where = 'tbl_jobsolicited.`vacancy` != tbl_jobsolicited.`hired` AND tbl_jobsolicited.deleted = false AND tbl_company.id = ' . $row->id;
                    $fetched_data = $this->Report_model->getJobSolicitedReport($this->select_column, $this->table, $this->where);
                    $list .= '<tr><td rowspan="' . count($fetched_data) . '" stlye="border-bottom:none">' . $no . '. ' . strtoupper($row->name) . '</td>';

                    foreach ($fetched_data as $k => $js) {
                        $list .= '<td>' . strtoupper($js->position) . '</td><td style="text-align:justify">' . $js->job_description . '</td><td style="text-align:justify"> <center><strong>' . $js->vacancy . '</strong></center><br>' . $js->requirements . '</td></tr>';
                    }
                    $content[] = $list;
                }
                $content[] = '<tr><td style="text-align:right;border-style:none;"><br>PREPARED BY: </td><td style="border-style:none;border-bottom:1px solid; text-align:center"><br><br><br>' . $this->session->name . '</td></tr>';
                $content[] = '<tr><td style="border-style:none;"></td><td style="border-style:none; text-align:center">' . $this->session->position . '</td></tr>';
                $content[] = '<tr><td colspan="2" style="border-style:none"></td><td style="text-align:right;border-style:none;"><br>NOTED BY: </td><td style="border-style:none;border-bottom:1px solid; text-align:center"><br><br><br>ENGR. JOSEPH ISAIAS M. QUIPOT VI, CE</td></tr>';
                $content[] = '<tr><td colspan="3" style="border-style:none;"></td><td style="border-style:none; text-align:center">CITY ADMINISTRATOR/PESO MANAGER</td></tr>';
            } else {
                $content[] = '<tr><td colspan="4">No records found.</td></tr>';
            }

            $status['frequency'] =   strtoupper($this->input->post('frequency') . ' of: ' . date('F d, Y', strtotime($this->input->post('dateFrom'))) . ' to ' .  date('F d, Y', strtotime($this->input->post('dateTo'))));
            $status['status'] = true;
            $status['content'] = $content;
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
}