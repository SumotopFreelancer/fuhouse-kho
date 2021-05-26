<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Myerror extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}
	function index()
	{
		redirect(base_url('dang-nhap'));
	}
}
