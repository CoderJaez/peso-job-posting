<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ofw extends CI_Controller
{
    private $data = array();
    private $select_column = array();
    private $table = null;
    private $where = null;
    private $order_column = null;
    function __construct()
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
        if (!isset($this->data['applicant'])) {
            redirect('error_page');
        }
    }

    public function index()
    {
        $this->data['ApplicantsModule'] = (isset($this->data['applicant']) || isset($this->data['placement']) || isset($this->data['referral'])) ? true : false;
        $this->data['title'] = "PESO | OFW";
        $this->data['brgy'] = $this->loadBrgy();
        $this->data['ofwClicked'] = true;
        $this->load->view('components/Header', $this->data);
        $this->load->view('components/Sidebar');
        $this->load->view('OfwLayout');
        $this->load->view('modals/Ofw_modal');
        $this->load->view('components/Footer');
    }


    private function access_rights($id)
    {
        $btns = '';
        foreach ($this->session->access_rights as $key => $row) {
            if ($row->modules == 'ofw') {
                $btns .= ($row->_edit) ? '<button class="btn btn-warning btn-xs btnEditOfw" data-toogle="tooltip" title="Edit"   data-id="' . $id . '"><i class="fa fa-edit"></i></button>' : '';
                $btns .= ($row->_delete) ? '</button> <button data-toogle="tooltip" title="Delete" class="btn btn-danger btn-xs btnDeleteOfw"  data-id="' . $id . '"><i class="fa fa-trash"></i></button> ' : '';
            }
        }
        return $btns;
    }

    public function ofw_list()
    {
        $this->select_column = array('tbl_ofw.ofwID', 'tbl_ofw.ofw_fbAcc', 'tbl_ofw.dependent_fbAcc', 'tbl_ofw.fname', 'tbl_ofw.mi', 'tbl_ofw.lname', 'tbl_ofw.brgyCode', 'tbl_ofw.contactNo', 'tbl_ofw.country', 'tbl_ofw.yearFromTo', 'tbl_ofw.email', 'tbl_ofw.dependentName', 'refbrgy.brgyDesc');
        $this->table = 'tbl_ofw';
        $this->order_column = array(null, 'tbl_ofw.lname', 'tbl_ofw.country', 'tbl_ofw.yearFromTo', null, null, null, null, null, null);
        $fetched_data = $this->Ofw_model->generate_datatables($this->table, $this->select_column, $this->order_column);
        $data = array();
        foreach ($fetched_data as $key => $row) {
            $mi = ($row->mi != '') ? $row->mi[0] . '.' : '';
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="selectRow" data-id="' . $row->ofwID . '" class="inputSelectApplicant"/>';
            $sub_array[] = strtoupper($row->lname . ', ' . $row->fname . ' ' . $mi);
            $sub_array[] = strtoupper($row->brgyDesc);
            $sub_array[] = strtoupper($row->country);
            $sub_array[] = strtoupper($row->yearFromTo);
            $sub_array[] = strtoupper($row->email);
            $sub_array[] = strtoupper($row->dependentName);
            $sub_array[] = strtoupper($row->contactNo);
            $sub_array[] = strtoupper($row->ofw_fbAcc);
            $sub_array[] = strtoupper($row->dependent_fbAcc);
            $sub_array[] = $this->access_rights($row->ofwID);
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->Ofw_model->get_all_data(array('tbl_ofw.deleted' => false)),
            "recordsFiltered" => $this->Ofw_model->get_filtered_data(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function add_ofw()
    {
        $rules = $this->Ofw_model->ofw_rules;
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == TRUE) {
            $this->data = array(
                'ofwID' => $this->ofwID(),
                'fname' => strtoupper($this->input->post('fname')),
                'lname' => strtoupper($this->input->post('lname')),
                'mi' => strtoupper($this->input->post('mi')),
                'brgyCode' => $this->input->post('brgyCode'),
                'country' => strtoupper($this->input->post('country')),
                'yearFromTo' => $this->input->post('yearFromTo'),
                'email' => $this->input->post('email'),
                'ofw_fbAcc' => $this->input->post('fbAcc'),
                'dependentName' => strtoupper($this->input->post('dependentName')),
                'contactNo' => $this->input->post('contactNo'),
                'dependent_fbAcc' => $this->input->post('dependentfbAcc'),

            );
            $this->table = 'tbl_ofw';
            if ($this->Ofw_model->add_ofw($this->table, $this->data)) {
                $status['status'] = true;
                $status['msg'] = 'New Ofw data added.';
            }
        } else {
            $status['status'] = false;
            $status['msg'] = validation_errors();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    private function ofwID()
    {
        $lastID = array();
        $id = null;
        $this->db->select('ofwID');
        $this->db->where(array('deleted' => false));
        $this->db->order_by('id DESC');
        $this->db->limit(1);
        $result = $this->db->get('tbl_ofw');
        if ($result->num_rows() > 0) {
            $lastID = explode('-', $result->row()->ofwID);
            $id = implode('-', array(date('my'), sprintf('%03d', $lastID[1] + 1)));
        } else {
            $id = implode('-', [date('my'), sprintf('%03d', 1)]);
        }
        return $id;
    }

    private function loadBrgy()
    {
        $this->select_column = array('brgyCode', 'brgyDesc');
        $this->table = 'refbrgy';
        $this->where = array('citymunCode' => '097322');
        $order_by = 'brgyDesc ASC';
        return $this->Applicant_model->getData($this->select_column, $this->table, $order_by, $this->where);
    }
}