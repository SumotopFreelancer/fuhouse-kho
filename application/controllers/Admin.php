<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	// Check user
	function check_admin()
	{
		$action = $this->uri->rsegment(2);
		$username = $this->input->post('username');
		$where = ['username' => $username];
		// kiem tra xem tai khoan da ton tai hay chua
		$check = true;
		if ($action == 'edit') {
			$info = $this->data['info'];
			if ($info->username == $username) {
				$check = false;
			}
		}
		if ($check && ($this->admin_model->check_exists($where) || $username == $this->adminRoot->username)) {
			$this->form_validation->set_message(__FUNCTION__, 'Tài khoản đã tồn tại');
			return false;
		}
		return true;
	}


	function edit()
	{
		// lấy id cần chỉnh sửa phân đoạn thứ 3 của uri
		$id = intval($this->uri->rsegment('3'));
		$info = $this->admin_model->get_info($id);
		if (!$info) {
			$this->session->set_flashdata('message', '<div class="callout callout-danger">Không tồn tại</div>');
			redirect(base_url('admin'));
		}
		if ($this->admin->id != $info->id && $this->admin->type != $this->adminRoot->type) {
			redirect('admin');
		}
		$this->data['info'] = $info;
		if ($this->input->post()) {
			$this->form_validation->set_rules('name', 'Tên hiển thị', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|min_length[6]|max_length[32]|callback_check_admin');
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[32]');
				$this->form_validation->set_rules('re_password', 'Nhập lại mật khẩu', 'trim|matches[password]');
			}
			if ($this->form_validation->run()) {
				$data = [
					'name' => $this->input->post('name', TRUE),
					'username' => $this->input->post('username', TRUE),
					'last_login' => now(),
				];
				if ($this->input->post('password')) {
					$data['password'] = md5($this->input->post('password'));
				}
				if ($this->admin_model->update($id, $data)) {
					$this->session->set_flashdata('message', '<div class="callout callout-success">Chỉnh sửa thành công</div>');
				} else {
					$this->session->set_flashdata('message', '<div class="callout callout-danger">Không chỉnh sửa được. Thử lại sau</div>');
				}
				if ($this->input->post('cus_btn_save') == 'Lưu lại') {
					redirect(base_url('admin/edit/' . $id));
				} else {
					redirect(base_url());
				}
			}
		}
		$this->data['temp'] = 'admin/edit';
		$this->load->view('main', $this->data);
		$this->db->cache_delete_all();
	}
}
