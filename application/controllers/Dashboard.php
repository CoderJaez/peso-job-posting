<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $data = array();
    private $select_column = null;
    private $access_rights = array();
    private $group_by = null;
    private $femaleTotal = array();
    private $maleTotal = array();
    private $label = array();
    private $salesChartOptions = array(
        "legends" => array('display' => true, 'position' => 'top', 'fontColor' => '#151515'),
        // Boolean - If we should show the scale at all
        "showScale" => true,
        // Boolean - Whether grid lines are shown across the chart
        "scaleShowGridLines" => false,
        // String - Colour of the grid lines
        "scaleGridLineColor" => 'rgba(0,0,0,.05)',
        // Number - Width of the grid lines
        "scaleGridLineWidth" => 1,
        // Boolean - Whether to show horizontal lines (except X axis)
        "scaleShowHorizontalLines" => true,
        // Boolean - Whether to show vertical lines (except Y axis)
        "scaleShowVerticalLines" => true,
        // Boolean - Whether the line is curved between points
        "bezierCurve" => true,
        // Number - Tension of the bezier curve between points
        "bezierCurveTension" => 0.3,
        // Boolean - Whether to show a dot for each point
        "pointDot" => true,
        // Number - Radius of each point dot in pixels
        "pointDotRadius" => 4,
        // Number - Pixel width of point dot stroke
        "pointDotStrokeWidth" => 1,
        // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        "pointHitDetectionRadius" => 20,
        // Boolean - Whether to show a stroke for datasets
        "datasetStroke" => true,
        // Number - Pixel width of dataset stroke
        "datasetStrokeWidth" => 2,
        // Boolean - Whether to fill the dataset with a color
        "datasetFill" => true,
        // String - A legend template
        "legendTemplate" => '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        "maintainAspectRatio" => true,
        // Boolean - whether to make the chart responsive to window resizing
        "responsive" => true
    );


    function __construct()
    {
        parent::__construct();
        if (!get_cookie('isLogin')) {
            redirect('login');
        } else {
            set_cookie('isLogin', true, 3600);
        }
        if ($this->session->userdata('position') == 'applicant') {
            redirect('Error_page');
        }

        foreach ($this->session->userdata('access_rights') as $key => $row) {
            $this->data[$row->modules] = true;
        }
    }
    public function index()
    {
        $this->data['ApplicantsModule'] = (isset($this->data['applicant']) || isset($this->data['placement']) || isset($this->data['referral'])) ? true : false;
        $this->data['title'] = "PESO | DASHBOARD";
        $this->data['dashboard'] = true;
        $this->data['referrals'] =  $this->Report_model->getTotalReferrals();;
        $this->data['placements'] = $this->Report_model->getTotalPlacements();
        $this->data['applicants'] = $this->Report_model->getTotalApplicantsReg();
        $this->data['js'] = $this->Report_model->getTotalJobSolicited();
        $this->data['cookies'] = get_cookie('isLogin');
        $this->data['user_data'] = $this->session->all_userdata();
        $this->data['dashboardClicked'] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('AdminLayout');
        $this->load->view('components/Footer');
    }

    public function statsWeeklyReferralReport()
    {
        $this->select_column = 'SUM(CASE WHEN tbl_applicant.gender = "Female" THEN 1 ELSE 0 END) AS female,SUM(CASE WHEN tbl_applicant.gender = "Male" THEN 1 ELSE 0 END) AS male, CONCAT(DATE_FORMAT(DATE_ADD(tbl_referral.dateReferred, INTERVAL(-WEEKDAY(tbl_referral.dateReferred)) DAY),"%m-%d")," - ", DATE_FORMAT(DATE_ADD(tbl_referral.dateReferred, INTERVAL(-WEEKDAY(tbl_referral.dateReferred)+4) DAY),"%m-%d"))  AS label';
        $this->group_by = 'WEEK(tbl_referral.`dateReferred`)';

        $result = $this->Report_model->getStatsReferral($this->group_by, $this->select_column);
        foreach ($result as $key => $row) {
            $this->maleTotal[] = intval($row->male);
            $this->femaleTotal[] = intval($row->female);
            $this->label[] = $row->label;
        }

        $output['datasets'] = array(
            array(
                "label" => 'Female',
                "backgroundColor" => '#FFFFFF',
                "borderColor" => '#FFFFFF',
                "fill" => false,
                "data" => $this->maleTotal
            ),
            array(
                "label" => 'Male',
                "backgroundColor" => 'rgba(243, 156, 18,0.9)',
                "borderColor" => 'rgba(243, 156, 18,0.9)',
                "fill" => false,
                "data" => $this->femaleTotal
            )
        );
        $output['options'] =  array('legend' => array('display' => true, 'position' => 'bottom'));

        $output['labels'] = $this->label;
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function statsWeeklyPlacementReport()
    {

        $this->select_column = 'SUM(CASE WHEN tbl_applicant.gender = "Female" THEN 1 ELSE 0 END) AS female,SUM(CASE WHEN tbl_applicant.gender = "Male" THEN 1 ELSE 0 END) AS male, CONCAT(DATE_FORMAT(DATE_ADD(tbl_placement.dateHired, INTERVAL(-WEEKDAY(tbl_placement.dateHired)) DAY),"%m-%d")," - ", DATE_FORMAT(DATE_ADD(tbl_placement.dateHired, INTERVAL(-WEEKDAY(tbl_placement.dateHired)+4) DAY),"%m-%d"))  AS label';
        $this->group_by = 'WEEK(tbl_placement.`dateHired`)';
        $result = $this->Report_model->getStatsPlacement($this->group_by, $this->select_column);
        foreach ($result as $key => $row) {
            $this->maleTotal[] = intval($row->male);
            $this->femaleTotal[] = intval($row->female);
            $this->label[] = $row->label;
        }

        $output['datasets'] = array(
            array(
                "label" => 'Female',
                "fontColor" => '#0050640',
                "backgroundColor" => 'rgb(51, 205, 243)',
                "borderColor" => 'rgba(0, 192, 240,0.8)',
                "data" => $this->maleTotal
            ),
            array(
                "label" => 'Male',
                "backgroundColor" => 'rgb(224, 161, 40)',
                "borderColor" => 'rgba(243, 156, 18,0.9)',
                "data" => $this->femaleTotal
            )
        );

        $output['js'] = $this->Report_model->jobsolitation_status();
        $output['labels'] = $this->label;
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}