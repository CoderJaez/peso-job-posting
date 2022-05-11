<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utility extends CI_Controller
{
    public function loadCity()
    {
        $this->selected_column = array('citymunDesc', 'citymunCode');
        $this->table = 'refcitymun';
        $this->where = array('provCode' => $this->input->post('provCode'));
        $order_by = 'citymunDesc ASC';
        $status = $this->Applicant_model->getData($this->selected_column, $this->table, $order_by, $this->where);
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }

    public function loadBrgy()
    {
        $this->selected_column = array('brgyDesc', 'brgyCode');
        $this->table = 'refbrgy';
        $this->where = array('citymunCode' => $this->input->post('citymunCode'));
        $order_by = 'brgyDesc ASC';
        $status = $this->Applicant_model->getData($this->selected_column, $this->table, $order_by, $this->where);
        $this->output->set_content_type('application/json')->set_output(json_encode($status));
    }
}