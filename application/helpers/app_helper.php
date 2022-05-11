<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('pagination')) {
	function pagination($total_rows, $per_page, $url, $uri_segment)
	{
		$ci = &get_instance();


		$config['base_url']    = site_url($url);
		$config['total_rows']  = $total_rows;
		$config['uri_segment'] = $uri_segment;
		$config['per_page']    = $per_page;

		$ci->load->library('pagination');
		$ci->pagination->initialize($config);
		return $ci->pagination->create_links();
	}
}


if (!function_exists('loadProvince')) {

	function loadProvince()
	{
		$ci = &get_instance();
		$selected_column = array('provDesc', 'provCode');
		$table = 'refprovince';
		$order_by = 'provDesc ASC';
		return $ci->Applicant_model->getData($selected_column, $table, $order_by);
	}
}


if (!function_exists('appID')) {
	function appID()
	{
		$ci = &get_instance();
		$lastID = array();
		$id = null;
		$ci->db->select('id');
		$ci->db->where(array('deleted' => false));
		$ci->db->order_by('id DESC');
		$ci->db->limit(1);
		$result = $ci->db->get('tbl_applicant');
		if ($result->num_rows() > 0) {
			$lastID = explode('-', $result->row()->id);
			$id = implode('-', array(date('my'), sprintf('%03d', $lastID[1] + 1)));
		} else {
			$id = implode('-', [date('my'), sprintf('%03d', 1)]);
		}
		return $id;
	}
}

if (!function_exists('control_no')) {

	function control_no()
	{
		$ci = &get_instance();
		$control_no = array();
		$id = null;
		$ci->db->select('control_no');
		$ci->db->where(array('deleted' => false));
		$ci->db->order_by('control_no DESC');
		$ci->db->limit(1);
		$result = $ci->db->get('tbl_referral');
		if ($result->num_rows() > 0) {
			$control_no = explode('-', $result->row()->control_no);
			$id = implode('-', array(date('my'), sprintf('%03d', $control_no[1] + 1)));
		} else {
			$id = implode('-', [date('my'), sprintf('%03d', 1)]);
		}
		return $id;
	}
}

if (!function_exists('userID')) {

	function userID()
	{
		$ci = &get_instance();
		$lastID = array();
		$id = null;
		$ci->db->select('userID');
		$ci->db->where(array('deleted' => false));
		$ci->db->order_by('userID DESC');
		$ci->db->limit(1);
		$result = $ci->db->get('tbl_user');
		if ($result->num_rows() > 0) {
			$lastID = explode('-', $result->row()->userID);
			$id = implode('-', array(date('mY'), sprintf('%03d', $lastID[1] + 1)));
		} else {
			$id = implode('-', [date('mY'), sprintf('%03d', 1)]);
		}
		return $id;
	}
}