<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Error_page extends CI_Controller
{
    private $data = array();
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['title'] = 'ERROR 404';
        $this->load->view('components/Header', $this->data);
        $this->load->view('Show_404');
        $this->load->view('components/Footer');
    }
}