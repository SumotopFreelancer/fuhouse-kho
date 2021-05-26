<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Visit
{
	var $CI = '';

	function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('visit_model');
		$this->CI->load->model('useronl_model');

		$this->muoiphut = 600; // 10 phut
		$this->now = time();
		$this->locktime = $this->now - $this->muoiphut;

		// Kiểm tra xem IP đã có trong CSDL chưa. Tùy thuộc vào giá trị tính bằng phút trong biến locktime.
		$this->day = date('d');
		$this->weekday = date('w');
		$this->month = date('m');
		$this->year = date('Y');
		$this->daystart = mktime(0, 0, 0, $this->month, $this->day, $this->year);
		$this->monthstart = mktime(0, 0, 0, $this->month, 1, $this->year);

		// weekstart
		$this->weekday--;
		if ($this->weekday < 0) {
			$this->weekday = 7;
		}
		$this->weekday = $this->weekday * 24 * 60 * 60;
		$this->weekstart = $this->daystart - $this->weekday;
		$this->yesterdaystart = $this->daystart - (24 * 60 * 60);

		$this->ip = get_user_ip();
		$this->session_id = $this->CI->session->session_id;
	}
	// Thêm SESSION vào database khi thời gian vào hệ thống + 15 phut > thời gian hiện tại
	function add()
	{
		if (!$this->CI->session->userdata($this->session_id)) {
			$useradd = ['ip_address' => $this->ip, 'last_activity' => $this->now, 'sessions' => $this->session_id];
			$this->CI->db->insert('ci_sessions', $useradd);
			$this->CI->db->trans_complete();
			$id_session = $this->CI->db->insert_id();
			$this->CI->session->set_userdata($this->session_id, $id_session);
		}
	}
	// Tổng truy cập
	function total()
	{
		return $this->CI->visit_model->get_total();
	}
	// Truy cập hôm nay
	function today()
	{
		$input = [];
		$input['where'] = ['last_activity >' => $this->daystart];
		$today = $this->CI->visit_model->get_total($input);
		return $today;
	}
	// Truy cập hôm qua
	function yesterday()
	{
		$input = [];
		$input['where'] = ['last_activity >' => $this->yesterdaystart, 'last_activity <' => $this->daystart];
		$yesterday = $this->CI->visit_model->get_total($input);
		return $yesterday;
	}
	// Truy cập tuần
	function week()
	{
		$input = [];
		$input['where'] = ['last_activity >=' => $this->weekstart];
		$week = $this->CI->visit_model->get_total($input);
		return $week;
	}
	// Truy cập hàng tháng
	function month()
	{
		$input = [];
		$input['where'] = ['last_activity >=' => $this->monthstart];
		$month = $this->CI->visit_model->get_total($input);
		return $month;
	}
	// Xóa truy câp khi vượt quá số lượng cho phép
	function update_delete()
	{
		if ($this->CI->visit_model->get_total() >= 1000) {
			$this->CI->db->empty_table('ci_sessions');
			// $updatetotal = $thongke->totalkh + $this->records;
			// $datatotal = ['totalkh' => $updatetotal];
			// $this->CI->thongketruycap_model->update(1, $datatotal);
		}
	}
	// ------------------------ ĐANG ONLINE ---------------------------
	function online()
	{
		// Nếu quá 10 phút mà ko thấy session này làm việc thì tiến hành xóa
		$where = ['time <' => $this->locktime];
		$this->CI->useronl_model->del_rule($where);
		// Đang online
		$input = [];
		$input['where'] = ['session' => $this->session_id];
		$input['order'] = ['time', 'desc'];
		$count = $this->CI->useronl_model->get_total($input);
		if ($count == 0) {
			$data = ['session' => $this->session_id, 'time' => $this->now, 'ip' => $this->ip];
			$this->CI->useronl_model->create($data);
		} else {
			$data = ['time' => $this->now];
			$whereup = ['session', $this->session_id];
			$this->CI->useronl_model->update_rule($whereup, $data);
		}
		$count_user_online = $this->CI->useronl_model->get_total_all();
		return $count_user_online;
	}
}
