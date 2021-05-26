<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontpagination
{
	function config($total_rows = 0, $base_url = '', $per_page = 1, $get_value = 0, $first_url = '', $uri_segment = 0)
	{
		$config = [];
		$config['total_rows'] = $total_rows; // All rows
		$config['base_url'] = $base_url; // Link show pagination
		$config['per_page'] = ($per_page == 0 ? 1 : $per_page); // rows on one page
		$config['use_page_numbers'] = TRUE; // Show number pagination
		$config['num_links'] = 3;
		if (count($get_value) > 0) { // If has get value search 
			$config['reuse_query_string'] = TRUE;
			$config['suffix'] = '';
			$config['first_url'] = $first_url . full_get();
		} else {
			$config['first_url'] = $first_url; // First URL
		}
		$config['uri_segment'] = $uri_segment; // Link show number pagination
		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
		$config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
		$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a class="active">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		return $config;
	}
}
