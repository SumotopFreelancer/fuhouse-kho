<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Warehousemap extends MY_Controller
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
				$input['where']['warehouse_map.product_id'] = $this->input->get('product_id');
				$product_id = $this->input->get('product_id');
				$product = $this->product_model->get_info($product_id, 'id, color, id_dongsanpham');
				if (!empty($product)) {
					$inputsize = [];
					$inputsize['select'] = 'id, name';
					$inputsize['where'] = ['id_dongsanpham' => $product->id_dongsanpham];
					$inputsize['order'] = ['sort_order', 'asc'];
					$this->data['sizes'] = $this->size_model->get_list($inputsize);
					$this->data['colors'] = isJson($product->color);
				}
			}
			if ($this->input->get('color') != 'none' && $this->input->get('color') != '') {
				$input['where']['warehouse_map.color'] = $this->input->get('color');
			}
			if ($this->input->get('size_id') != 'none' && $this->input->get('size_id') != '') {
				$input['where']['warehouse_map.size_id'] = $this->input->get('size_id');
			}
			if ($this->input->get('kho_id') != 'none' && $this->input->get('kho_id') != '') {
				$input['where']['warehouse_map.kho_id'] = $this->input->get('kho_id');
			}
		}
		$input['select'] = 'warehouse_map.id, product.name as product_name, warehouse_map.color, warehouse_map.size, size.name as size_name, warehouse_map.kho_id, kho.name as kho_name, warehouse_map.total';
		$input['join'] = [
			'product' => ['product.id = warehouse_map.product_id', 'left'],
			'kho' => ['kho.id = warehouse_map.kho_id', 'left'],
			'size' => ['size.id = warehouse_map.size_id', 'left']
		];
		// Pagination
		$config = $this->adminpagination->config($this->warehousemap_model->get_total($input), base_url('warehousemap'), 30, $_GET, base_url('warehousemap'), 2);
		$this->pagination->initialize($config);
		$segment = intval($this->uri->segment(2)) == 0 ? 0 : ($this->uri->segment(2) * $config['per_page']) - $config['per_page'];
		$this->data['phantrang'] = $this->pagination->create_links();
		$input['limit'] = [$config['per_page'], $segment];
		// Data
		$this->data['list'] = $this->warehousemap_model->get_list($input);
		$this->data['totalRows'] = $config['total_rows'];
		// View
		$this->data['temp'] = 'warehousemap/index';
		$this->load->view('main', $this->data);
	}
	// Ajax lấy danh sách size và màu theo sản phẩm
	public function get_size_color()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$id = $this->input->post('product_id');
			$product = $this->product_model->get_info($id, 'id, color, id_dongsanpham');
			if (!empty($product)) {
				$input = [];
				$input['select'] = 'id, name';
				$input['where'] = ['id_dongsanpham' => $product->id_dongsanpham];
				$input['order'] = ['sort_order', 'asc'];
				$sizes = $this->size_model->get_list($input);
				$colors = isJson($product->color);

				$sizeHtml = '<option value="">-- Chọn size --</option>';
				$colorHtml = '<option value="">-- Chọn màu --</option>';
				if (!empty($sizes) && !empty($colors)) {
					foreach ($sizes as $row) {
						$sizeHtml .= '<option value="' . $row->id . '">' . $row->name . '</option>';
					}
					$sizeHtml .= '<option value="required">Theo yêu cầu</option>';
					foreach ($colors as $row) {
						$colorHtml .= '<option value="' . $row . '">' . $row . '</option>';
					}
					$colorHtml .= '<option value="required">Theo yêu cầu</option>';
					$result = [
						'status' => 1,
						'sizeHtml' => $sizeHtml,
						'colorHtml' => $colorHtml,
						'messenger' => 'Tìm thấy thuộc tính sản phẩm'
					];
				} else {
					$result = [
						'status' => 0,
						'sizeHtml' => $sizeHtml,
						'colorHtml' => $colorHtml,
						'messenger' => 'Sản phẩm chưa được cài đặt thuộc tính'
					];
				}
			}
		}
		echo json_encode($result);
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
			$this->form_validation->set_rules('size_id', 'Size', 'required');
			$this->form_validation->set_rules('color', 'Màu', 'required');
			$this->form_validation->set_rules('qty', 'Số lượng', 'required');
			if ($this->input->post('size_id') == 'required') {
				$this->form_validation->set_rules('size_required_val', 'Size theo yêu cầu', 'required');
			}
			if ($this->input->post('color') == 'required') {
				$this->form_validation->set_rules('color_required_val', 'Màu theo yêu cầu', 'required');
			}
			if ($this->form_validation->run() == FALSE) {
				$errors = $this->form_validation->error_array();
				$result = [
					'status' => 0,
					'messenger' => 'Lỗi dữ liệu',
					'errors' => $errors
				];
			} else {
				// Kiểm tra trước khi nhập kho
				$type = 0;
				// Nếu loại sản phẩm có màu và size
				$where = [
					'product_id' => $this->input->post('product_id'),
					'color' => $this->input->post('color'),
					'size_id' => $this->input->post('size_id'),
					'kho_id' => $this->input->post('kho_id'),
					'type' => 0
				];
				// Nếu sản phẩm theo yêu cầu
				if ($this->input->post('color') == 'required' || $this->input->post('size_id') == 'required') {
					$type = 1;
					if ($this->input->post('color') == 'required') {
						$where['color'] = $this->input->post('color_required_val');
					}
					if ($this->input->post('size_id') == 'required') {
						$where['size'] = $this->input->post('size_required_val');
						$where['size_id'] = 0;
					}
					$where['type'] = 1;
				}
				$warehouseInfo = $this->warehousemap_model->get_info_rule($where);
				// Nếu đã có trong database
				if ($warehouseInfo) {
					// Cập nhật bảng warehouse_map
					$data = ['total' => $warehouseInfo->total + $this->input->post('qty')];
					$this->warehousemap_model->update($warehouseInfo->id, $data);
					// Thêm vào bảng warehouse_map_import
					$data = [
						'warehouse_map_id' => $warehouseInfo->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $this->input->post('qty'),
						'created' => now()
					];
					$this->warehousemapimport_model->create($data);
				} else {
					// Nếu sản phẩm có màu và size
					if ($type == 0) {
						// Thêm vào bảng warehouse_map
						$data = [
							'product_id' => $this->input->post('product_id'),
							'color' => $this->input->post('color'),
							'size_id' => $this->input->post('size_id'),
							'kho_id' => $this->input->post('kho_id'),
							'total' => $this->input->post('qty'),
							'type' => $type
						];
						$this->warehousemap_model->create($data);
						// Thêm vào bảng warehouse_map_import
						$warehouse_map_id = $this->db->insert_id();
						$data = [
							'warehouse_map_id' => $warehouse_map_id,
							'admin_name' => $this->admin->name,
							'admin_id' => $this->admin->id,
							'qty' => $this->input->post('qty'),
							'created' => now()
						];
						$this->warehousemapimport_model->create($data);
					}
					// Nếu sản phẩm theo yêu cầu
					if ($type == 1) {
						// Thêm vào bảng warehouse_map
						$data = [
							'product_id' => $this->input->post('product_id'),
							'color' => $this->input->post('color'),
							'size_id' => $this->input->post('size_id'),
							'kho_id' => $this->input->post('kho_id'),
							'total' => $this->input->post('qty'),
							'type' => $type
						];
						if ($this->input->post('color') == 'required') {
							$data['color'] = $this->input->post('color_required_val');
						}
						if ($this->input->post('size_id') == 'required') {
							$data['size'] = $this->input->post('size_required_val');
							$data['size_id'] = 0;
						}
						$this->warehousemap_model->create($data);
						// Thêm vào bảng warehouse_map_import
						$warehouse_map_id = $this->db->insert_id();
						$data = [
							'warehouse_map_id' => $warehouse_map_id,
							'admin_name' => $this->admin->name,
							'admin_id' => $this->admin->id,
							'qty' => $this->input->post('qty'),
							'created' => now()
						];
						$this->warehousemapimport_model->create($data);
					}
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
		$warehouse_map_id = intval($this->input->post('warehouse_map_id'));
		$qty = intval($this->input->post('qty'));
		$warehouseInfo = $this->warehousemap_model->get_info($warehouse_map_id);
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
				$warehouse_map_id = $this->input->post('warehouse_map_id');
				$qty = $this->input->post('qty');
				$warehouseInfo = $this->warehousemap_model->get_info($warehouse_map_id);
				$warehouse_to = $this->input->post('warehouse_to');
				// Kiểm tra sản phẩm đã tồn tại hay chưa
				$where = [
					'product_id' => $warehouseInfo->product_id,
					'color' => $warehouseInfo->color,
					'size' => $warehouseInfo->size,
					'size_id' => $warehouseInfo->size_id,
					'kho_id' => $warehouse_to,
					'type' => $warehouseInfo->type
				];
				$warehouseInfoHas = $this->warehousemap_model->get_info_rule($where);
				// Nếu sản phẩm này đã có trong kho
				if ($warehouseInfoHas) {
					// Cập nhật số lượng bảng tồn kho
					$data = ['total' => $warehouseInfoHas->total + $qty];
					$this->warehousemap_model->update($warehouseInfoHas->id, $data);
					// Thêm bảng nhập kho
					$data = [
						'warehouse_map_id' => $warehouseInfoHas->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'created' => now()
					];
					$this->warehousemapimport_model->create($data);
					// Thêm bảng chuyển kho
					$data = [
						'warehouse_map_from_id' => $warehouseInfo->id,
						'warehouse_map_to_id' => $warehouseInfoHas->id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'warehouse_from' => $warehouseInfo->kho_id,
						'warehouse_to' => $warehouseInfoHas->kho_id,
						'created' => now()
					];
					$this->warehousemaptransfer_model->create($data);
				} else {
					// Thêm vào bảng tồn kho
					$data = [
						'product_id' => $warehouseInfo->product_id,
						'color' => $warehouseInfo->color,
						'size' => $warehouseInfo->size,
						'size_id' => $warehouseInfo->size_id,
						'kho_id' => $warehouse_to,
						'total' => $qty,
						'type' => $warehouseInfo->type
					];
					$this->warehousemap_model->create($data);
					// Thêm bảng nhập kho
					$warehouse_map_id = $this->db->insert_id();
					$data = [
						'warehouse_map_id' => $warehouse_map_id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'created' => now()
					];
					$this->warehousemapimport_model->create($data);
					// Thêm bảng chuyển kho
					$data = [
						'warehouse_map_from_id' => $warehouseInfo->id,
						'warehouse_map_to_id' => $warehouse_map_id,
						'admin_name' => $this->admin->name,
						'admin_id' => $this->admin->id,
						'qty' => $qty,
						'warehouse_from' => $warehouseInfo->kho_id,
						'warehouse_to' => $warehouse_to,
						'created' => now()
					];
					$this->warehousemaptransfer_model->create($data);
				}
				// Trừ tổng tồn kho sản phẩm đã chuyển
				$data = ['total' => $warehouseInfo->total - $qty];
				$this->warehousemap_model->update($warehouseInfo->id, $data);
				// Result
				$result = [
					'status' => 1,
					'messenger' => '<div class="alert alert-success">Chuyển kho thành công!</div>'
				];
			}
		}
		echo json_encode($result);
	}
	// Xuất kho cho đơn hàng
	function get_order_detail()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$transaction_id = intval($this->input->post('transaction_id'));
			$transaction = $this->transactionadmin_model->get_info($transaction_id);
			if (!empty($transaction)) {
				// Kho
				$input = [];
				$input['select'] = 'id, name';
				$warehouseLocations = $this->kho_model->get_list($input);
				// Detail Order
				$input = [];
				$input['where'] = ['transaction_id' => $transaction_id];
				$order_detail = $this->orderadmin_model->get_list($input);
				if (!empty($order_detail)) {
					// Thông tin khách hàng
					$html = '<div class="box box-info"><div class="box-header with-border"><h3 class="box-title">Thông tin khách hàng</h3></div><div class="box-body">';
					$html .= '<p><b>Họ và tên: </b>' . $transaction->customer_name . '</p>';
					$html .= '<p><b>Số điện thoại: </b>' . $transaction->customer_phone . '</p>';
					$html .= '<div><b>Địa chỉ: </b>' . $transaction->customer_address . '</div>';
					$html .= '</div>';
					$html .= '</div>';
					// Thông tin đơn hàng
					$html .= '<div class="box box-info"><div class="box-header with-border"><h3 class="box-title">Thông tin đơn hàng</h3></div><div class="box-body table-responsive no-padding mailbox-messages"><table class="table table-hover cus_text_mid"><tr class="btn-default"><th style="width: 200px;">Tên sản phẩm</th><th>Màu</th><th>Size</th><th>SL</th><th style="width: 150px;">Kho</th></tr>';
					foreach ($order_detail as $row) {
						$html .= '<tr>';
						$html .= '<td><input type="hidden" value="' . $row->id . '" name="order_id">' . $row->product_name . '</td>';
						$html .= '<td>' . ($row->color ? $row->color : '---') . '</td>';
						$html .= '<td>' . ($row->size ? $row->size : '---') . '</td>';
						$html .= '<td>' . $row->qty . '</td>';
						$html .= '<td>';
						$html .= '<select class="form-control" name="kho_id">';
						$html .= '<option value="">-- Chọn kho --</option>';
						if (!empty($warehouseLocations)) {
							foreach ($warehouseLocations as $row) {
								$html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
							}
						}
						$html .= '</select>';
						$html .= '</td>';
						$html .= '</tr>';
					}
					$html .= '</table>';
					$html .= '</div>';
					$html .= '</div>';
					$result = [
						'status' => 1,
						'html' => $html,
						'messenger' => 'Thông tin đơn hàng'
					];
				}
			}
		}
		echo json_encode($result);
	}
	function _check_error_export()
	{
		// Kiểm tra đơn hàng này đã được xuất kho chưa
		$transaction_id = intval($this->input->post('transaction_id'));
		$hasMapExport = $this->warehousemapexport_model->get_info_rule(['transaction_id' => $transaction_id]);
		$hasExport = $this->warehouseexport_model->get_info_rule(['transaction_id' => $transaction_id]);
		if (!empty($hasMapExport) || !empty($hasExport)) {
			$this->form_validation->set_message(__FUNCTION__, '<div class="alert alert-danger">Sản phẩm của đơn hàng này đã được xuất kho</div>');
			return false;
		}
		// Kiểm tra kho có sản phẩm này không
		$order_ids = $this->input->post('order_ids');
		$kho_ids = $this->input->post('kho_ids');
		$errorProduct = '';
		if ($order_ids) {
			foreach ($order_ids as $key => $order_id) {
				$orderInfo = $this->orderadmin_model->get_info($order_id);
				if ($orderInfo->type == 1) {
					$where = [
						'product_id' => $orderInfo->product_id,
						'color' => $orderInfo->color,
						'size' => $orderInfo->size,
						'size_id' => $orderInfo->size_id,
						'kho_id' => $kho_ids[$key],
						'type' => $orderInfo->type
					];
					if (!$this->warehousemap_model->get_info_rule($where)) {
						$errorProduct .= '<div class="alert alert-danger">' . $orderInfo->product_name . ' không có trong kho</div>';
					} else {
						$warehouseInfo = $this->warehousemap_model->get_info_rule($where);
						if ($warehouseInfo->total < $orderInfo->qty) {
							$errorProduct .= '<div class="alert alert-danger">Kho không đủ số lượng cho sản phẩm ' . $orderInfo->product_id . '</div>';
						}
					}
				}
				if ($orderInfo->type == 0) {
					$where = [
						'product_id' => $orderInfo->product_id,
						'color' => $orderInfo->color,
						'size_id' => $orderInfo->size_id,
						'kho_id' => $kho_ids[$key],
						'type' => $orderInfo->type
					];
					if (!$this->warehousemap_model->get_info_rule($where)) {
						$errorProduct .= '<div class="alert alert-danger">' . $orderInfo->product_name . ' không có trong kho</div>';
					} else {
						$warehouseInfo = $this->warehousemap_model->get_info_rule($where);
						if ($warehouseInfo->total < $orderInfo->qty) {
							$errorProduct .= '<div class="alert alert-danger">Kho không đủ số lượng cho sản phẩm ' . $orderInfo->product_name . '</div>';
						}
					}
				}
				if ($orderInfo->type == -1) {
					$where = [
						'product_id' => $orderInfo->product_id,
						'kho_id' => $kho_ids[$key]
					];
					if (!$this->warehouse_model->get_info_rule($where)) {
						$errorProduct .= '<div class="alert alert-danger">' . $orderInfo->product_name . ' không có trong kho</div>';
					} else {
						$warehouseInfo = $this->warehouse_model->get_info_rule($where);
						if ($warehouseInfo->total < $orderInfo->qty) {
							$errorProduct .= '<div class="alert alert-danger">Kho không đủ số lượng cho sản phẩm ' . $orderInfo->product_name . '</div>';
						}
					}
				}
			}
		}
		if ($errorProduct != '') {
			$this->form_validation->set_message(__FUNCTION__, $errorProduct);
			return false;
		}
		return true;
	}
	public function export_for_order()
	{
		$result = [
			'status' => -1,
			'messenger' => 'Truy cập không cho phép'
		];
		if ($this->input->post()) {
			$this->form_validation->set_rules('transaction_id', 'Đơn hàng', 'required');
			$this->form_validation->set_rules('kho_ids[]', 'Kho', 'required');
			$this->form_validation->set_rules('error_export', '', 'callback__check_error_export');
			if ($this->form_validation->run() == FALSE) {
				$errors = $this->form_validation->error_array();
				$result = [
					'status' => 0,
					'messenger' => 'Lỗi dữ liệu',
					'errors' => $errors
				];
			} else {
				$transaction_id = $this->input->post('transaction_id');
				$order_ids = $this->input->post('order_ids');
				$kho_ids = $this->input->post('kho_ids');

				if ($order_ids) {
					foreach ($order_ids as $key => $order_id) {
						$orderInfo = $this->orderadmin_model->get_info($order_id);
						if ($orderInfo->type == 1) {
							$where = [
								'product_id' => $orderInfo->product_id,
								'color' => $orderInfo->color,
								'kho_id' => $kho_ids[$key],
								'type' => $orderInfo->type
							];
							if ($orderInfo->size_id > 0) {
								$where['size_id'] = $orderInfo->size_id;
							} else {
								$where['size'] = $orderInfo->size;
							}
							$warehouseInfo = $this->warehousemap_model->get_info_rule($where);
							if ($warehouseInfo) {
								// Trừ trong tồn kho
								$data = ['total' => $warehouseInfo->total - $orderInfo->qty];
								$this->warehousemap_model->update($warehouseInfo->id, $data);
								// Thêm vào bảng xuất kho
								$data = [
									'warehouse_map_id' => $warehouseInfo->id,
									'admin_name' => $this->admin->name,
									'admin_id' => $this->admin->id,
									'qty' => $orderInfo->qty,
									'transaction_id' => $transaction_id,
									'created' => now()
								];
								$this->warehousemapexport_model->create($data);
							}
						}
						if ($orderInfo->type == 0) {
							$where = [
								'product_id' => $orderInfo->product_id,
								'color' => $orderInfo->color,
								'size_id' => $orderInfo->size_id,
								'kho_id' => $kho_ids[$key],
								'type' => $orderInfo->type
							];
							$warehouseInfo = $this->warehousemap_model->get_info_rule($where);
							if ($warehouseInfo) {
								// Trừ trong tồn kho
								$data = ['total' => $warehouseInfo->total - $orderInfo->qty];
								$this->warehousemap_model->update($warehouseInfo->id, $data);
								// Thêm vào bảng xuất kho
								$data = [
									'warehouse_map_id' => $warehouseInfo->id,
									'admin_name' => $this->admin->name,
									'admin_id' => $this->admin->id,
									'qty' => $orderInfo->qty,
									'transaction_id' => $transaction_id,
									'created' => now()
								];
								$this->warehousemapexport_model->create($data);
							}
						}
						if ($orderInfo->type == -1) {
							$where = [
								'product_id' => $orderInfo->product_id,
								'kho_id' => $kho_ids[$key]
							];
							$warehouseInfo = $this->warehouse_model->get_info_rule($where);
							if ($warehouseInfo) {
								// Trừ trong tồn kho
								$data = ['total' => $warehouseInfo->total - $orderInfo->qty];
								$this->warehouse_model->update($warehouseInfo->id, $data);
								// Thêm vào bảng xuất kho
								$data = [
									'warehouse_id' => $warehouseInfo->id,
									'admin_name' => $this->admin->name,
									'admin_id' => $this->admin->id,
									'qty' => $orderInfo->qty,
									'transaction_id' => $transaction_id,
									'created' => now()
								];
								$this->warehouseexport_model->create($data);
							}
						}
					}
				}
				$result = [
					'status' => 1,
					'messenger' => '<div class="alert alert-success">Xuất kho thành công!</div>'
				];
			}
		}
		echo json_encode($result);
	}
}
