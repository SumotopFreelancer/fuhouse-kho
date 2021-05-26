<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	public $data = []; // Biến $data gửi sang view
	function __construct()
	{
		parent::__construct();

		$this->load->library('adminpagination');
		$this->load->model('setting_model');
		$this->form_validation->set_error_delimiters('', '');
		$this->data['message'] = $this->session->flashdata('message');

		$this->_check_login();

		$setadmin = $this->setting_model->get_info(1);
		if (!empty($setadmin)) {
			$this->setadmin = $setadmin;
		}

		if ($this->session->has_userdata('admin') && $this->session->userdata('admin')) {
			$this->adminRoot = $this->config->item('adminRoot');
			$this->admin = $this->session->userdata('admin');
			$this->data['permissions'] = isJson($this->admin->permissions);
			$this->load->model('admin_model');
		}
		// MODEL
		$this->load->model('warehousemap_model');
		$this->load->model('warehousemapimport_model');
		$this->load->model('warehousemapexport_model');
		$this->load->model('warehousemaptransfer_model');
		$this->load->model('warehouse_model');
		$this->load->model('warehouseimport_model');
		$this->load->model('warehouseexport_model');
		$this->load->model('warehousetransfer_model');
		$this->load->model('transactionadmin_model');
		$this->load->model('orderadmin_model');
		$this->load->model('kho_model');
		$this->load->model('size_model');
		$this->load->model('product_model');

		// Kho
		$input = [];
		$input['select'] = 'id, name';
		$this->data['warehouseLocations'] = $this->kho_model->get_list($input);
		// Đơn hàng
		$input = [];
		$input['select'] = 'id';
		$this->data['transactions'] = $this->transactionadmin_model->get_list($input);
		// Sản phẩm map
		$input = [];
		$input['select'] = 'id, name';
		$input['where'] = ['id_dongsanpham >' => 0];
		$this->data['productsMap'] = $this->product_model->get_list($input);
		// Sản phẩm thường
		$input = [];
		$input['select'] = 'id, name';
		$input['where'] = ['id_dongsanpham' => 0];
		$this->data['productsDefault'] = $this->product_model->get_list($input);
	}

	/* Kiểm tra trạng thái đăng nhập của admin */
	private function _check_login()
	{
		// Lấy controller và action trên url và cho về chữ không in hoa
		$controller = strtolower($this->uri->rsegment(1));
		$action = strtolower($this->uri->rsegment(2));
		// Lấy thông tin admin
		$admin = $this->session->userdata('admin');
		$adminRoot = $this->config->item('adminRoot');
		// Nếu chưa có session admin và truy vào url cấp 1 khác login
		if (!$admin && $controller != 'login') {
			redirect(base_url('dang-nhap'));
		}
		// Nếu có session admin và truy vào url cấp 1 là login
		if ($admin && $controller == 'login') {
			redirect(base_url('home'));
		} elseif (!in_array($controller, ['login', 'home']) && $admin->type != $adminRoot->type) {
			// Kiểm tra quyền
			$permissions = json_decode($admin->permissions);
			$check = true;
			if (!isset($permissions->{$controller})) {
				$check = false;
			}
			$permissionsAction = $permissions->{$controller};
			if (!in_array($action, $permissionsAction)) {
				$check = false;
			}
			if ($check == false) {
				$this->session->set_flashdata('message', '<div class="callout callout-danger">Bạn không đủ quyền hạn cho phép!</div>');
				redirect(base_url('home'));
			}
		}
	}
}
