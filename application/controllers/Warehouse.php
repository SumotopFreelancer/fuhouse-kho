<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Warehouse extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	// Thống kê
	function index()
	{
		$input = [];
		if ($this->input->get()) {
			if ($this->input->get('product_id') != 'none' && $this->input->get('product_id') != '') {
				$input['where']['warehouse.product_id'] = $this->input->get('product_id');
			}
			if ($this->input->get('kho_id') != 'none' && $this->input->get('kho_id') != '') {
				$input['where']['warehouse.kho_id'] = $this->input->get('kho_id');
			}
		}
		$input['select'] = 'warehouse.id, product.name as product_name, warehouse.kho_id, kho.name as kho_name, warehouse.total';
		$input['join'] = [
			'product' => ['product.id = warehouse.product_id', 'left'],
			'kho' => ['kho.id = warehouse.kho_id', 'left']
		];
		// Pagination
		$config = $this->adminpagination->config($this->warehouse_model->get_total($input), base_url('warehouse'), 30, $_GET, base_url('warehouse'), 2);
		$this->pagination->initialize($config);
		$segment = intval($this->uri->segment(2)) == 0 ? 0 : ($this->uri->segment(2) * $config['per_page']) - $config['per_page'];
		$this->data['phantrang'] = $this->pagination->create_links();
		$input['limit'] = [$config['per_page'], $segment];
		// Data
		$this->data['list'] = $this->warehouse_model->get_list($input);
		$this->data['totalRows'] = $config['total_rows'];
		// View
		$this->data['temp'] = 'warehouse/index';
		$this->load->view('main', $this->data);
	}

	// Nhập kho
	public function action_import()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$this->form_validation->set_rules('kho_id', 'Kho', 'required');
			$this->form_validation->set_rules('product_id', 'Sản phẩm', 'required');
			$this->form_validation->set_rules('qty', 'Số lượng', 'required');
			if ($this->form_validation->run() == FALSE) {
				$errors = $this->form_validation->error_array();
				$result = [
					'status' => 0,
					'messenger' => 'Lỗi dữ liệu',
					'errors' => $errors
				];
			} else {
				// Kiểm tra trước khi nhập kho
				$where = [
					'product_id' => $this->input->post('product_id'),
					'kho_id' => $this->input->post('kho_id')
				];
				$warehouseInfo = $this->warehouse_model->get_info_rule($where);
				// Nếu đã có trong database
				if ($warehouseInfo) {
					// Cập nhật bảng warehouse
					$data = ['total' => $warehouseInfo->total + $this->input->post('qty')];
					$this->warehouse_model->update($warehouseInfo->id, $data);
					// Thêm vào bảng warehouse_import
					$data = [
						'warehouse_id' => $warehouseInfo->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $this->input->post('qty'),
						'created' => now()
					];
					$this->warehouseimport_model->create($data);
				} else {
					// Thêm vào bảng warehouse
					$data = [
						'product_id' => $this->input->post('product_id'),
						'kho_id' => $this->input->post('kho_id'),
						'total' => $this->input->post('qty')
					];
					$this->warehouse_model->create($data);
					// Thêm vào bảng warehouse_import
					$warehouse_id = $this->db->insert_id();
					$data = [
						'warehouse_id' => $warehouse_id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $this->input->post('qty'),
						'created' => now()
					];
					$this->warehouseimport_model->create($data);
				}
				$result = [
					'status' => 1,
					'messenger' => '<div class="alert alert-success">Nhập kho thành công!</div>'
				];
			}
		}
		echo json_encode($result);
	}
	// Chuyển kho
	public function warehouse_to()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$warehouseFromId = $this->input->post('warehouseFromId');
			$input = [];
			$input['select'] = 'id, name';
			$input['where'] = ['id !=' => $warehouseFromId];
			$khos = $this->kho_model->get_list($input);
			if (!empty($khos)) {
				$html = '<option value="">-- Chọn kho --</option>';
				foreach ($khos as $row) {
					$html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
				}
				$result = [
					'status' => 1,
					'html' => $html,
					'messenger' => 'Tìm thấy danh sách kho'
				];
			}
		}
		echo json_encode($result);
	}
	function _check_qty_from()
	{
		$warehouse_id = intval($this->input->post('warehouse_id'));
		$qty = intval($this->input->post('qty'));
		$warehouseInfo = $this->warehouse_model->get_info($warehouse_id);
		if ($warehouseInfo) {
			if ($warehouseInfo->total >= $qty) {
				return true;
			} else {
				$this->form_validation->set_message(__FUNCTION__, 'Chỉ còn ' . $warehouseInfo->total . ' sản phẩm! Không thể chuyển quá số lượng trong kho');
				return false;
			}
		} else {
			$this->form_validation->set_message(__FUNCTION__, 'Không tồn tại!');
			return false;
		}
	}
	function action_transfer()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$this->form_validation->set_rules('warehouse_to', 'Kho', 'required');
			$this->form_validation->set_rules('qty', 'Số lượng', 'required|callback__check_qty_from');
			if ($this->form_validation->run() == FALSE) {
				$errors = $this->form_validation->error_array();
				$result = [
					'status' => 0,
					'messenger' => 'Lỗi dữ liệu',
					'errors' => $errors
				];
			} else {
				$warehouse_id = $this->input->post('warehouse_id');
				$qty = $this->input->post('qty');
				$warehouseInfo = $this->warehouse_model->get_info($warehouse_id);
				$warehouse_to = $this->input->post('warehouse_to');
				// Kiểm tra sản phẩm đã tồn tại hay chưa
				$where = [
					'product_id' => $warehouseInfo->product_id,
					'kho_id' => $warehouse_to
				];
				$warehouseInfoHas = $this->warehouse_model->get_info_rule($where);
				// Nếu sản phẩm này đã có trong kho
				if ($warehouseInfoHas) {
					// Cập nhật số lượng bảng tồn kho
					$data = ['total' => $warehouseInfoHas->total + $qty];
					$this->warehouse_model->update($warehouseInfoHas->id, $data);
					// Thêm bảng nhập kho
					$data = [
						'warehouse_id' => $warehouseInfoHas->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'created' => now()
					];
					$this->warehouseimport_model->create($data);
					// Thêm bảng chuyển kho
					$data = [
						'warehouse_from_id' => $warehouseInfo->id,
						'warehouse_to_id' => $warehouseInfoHas->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'warehouse_from' => $warehouseInfo->kho_id,
						'warehouse_to' => $warehouseInfoHas->kho_id,
						'created' => now()
					];
					$this->warehousetransfer_model->create($data);
				} else {
					// Thêm vào bảng tồn kho
					$data = [
						'product_id' => $warehouseInfo->product_id,
						'kho_id' => $warehouse_to,
						'total' => $qty
					];
					$this->warehouse_model->create($data);
					// Thêm bảng nhập kho
					$warehouse_id = $this->db->insert_id();
					$data = [
						'warehouse_id' => $warehouse_id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'created' => now()
					];
					$this->warehouseimport_model->create($data);
					// Thêm bảng chuyển kho
					$data = [
						'warehouse_from_id' => $warehouseInfo->id,
						'warehouse_to_id' => $warehouse_id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'warehouse_from' => $warehouseInfo->kho_id,
						'warehouse_to' => $warehouse_to,
						'created' => now()
					];
					$this->warehousetransfer_model->create($data);
				}
				// Trừ tổng tồn kho sản phẩm đã chuyển
				$data = ['total' => $warehouseInfo->total - $qty];
				$this->warehouse_model->update($warehouseInfo->id, $data);
				// Result
				$result = [
					'status' => 1,
					'messenger' => '<div class="alert alert-success">Chuyển kho thành công!</div>'
				];
			}
		}
		echo json_encode($result);
	}
}
