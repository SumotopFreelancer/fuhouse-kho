<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends My_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	function index()
	{
		$this->data['temp'] = 'home/index';
		$this->load->view('main', $this->data);
	}
	// Đăng xuất
	function logout()
	{
		if ($this->session->userdata('admin')) {
			$this->session->unset_userdata('admin');
		}
		redirect(base_url('login'));
	}
}
