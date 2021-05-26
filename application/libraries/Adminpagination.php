<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adminpagination
{

	function config($total_rows = 0, $base_url = '', $per_page = 1, $get_value = 0, $first_url = '', $uri_segment = 0)
	{
		$config = [];
		$config['total_rows'] = $total_rows; // All rows
		$config['base_url'] = $base_url; // Link show pagination
		$config['per_page'] = ($per_page == 0 ? 1 : $per_page); // rows on one page
		$config['use_page_numbers'] = TRUE; // Show number pagination
		if (count($get_value) > 0) { // If has get value search 
			$config['reuse_query_string'] = TRUE;
			$config['suffix'] = '';
			$config['first_url'] = $first_url . full_get();
		} else {
			$config['first_url'] = $first_url; // First URL
		}
		$config['uri_segment'] = $uri_segment; // Link show number pagination
		$config['next_link'] = '&rsaquo;';
		$config['prev_link'] = '&lsaquo;';
		$config['first_link'] = '«';
		$config['last_link'] = '»';
		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		return $config;
	}
}
