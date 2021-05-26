<?php
defined('BASEPATH') or exit('No direct script access allowed');

function fuhouse_url($url = '')
{
	return 'https://fuhouse.vn';
}

function public_url($url = '')
{
	return base_url('public/' . $url);
}

function full_get()
{
	$params = $_SERVER['QUERY_STRING'];
	return '?' . $params;
}

function pre($list, $exit = true)
{
	echo "<pre>";
	print_r($list);
	if ($exit) {
		die();
	}
}

function isJson($string)
{
	return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? json_decode($string) : false;
}

function convert_vi_to_en($str)
{
	$characters = [
		'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/' => 'a',
		'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
		'/ì|í|ị|ỉ|ĩ/' => 'i',
		'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
		'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
		'/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
		'/đ/' => 'd',
		'/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
		'/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
		'/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
		'/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
		'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
		'/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
		'/Đ/' => 'D',
	];
	return preg_replace(array_keys($characters), array_values($characters), $str);
}

function convertUrl($list)
{
	return strtolower(preg_replace(['/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'], ['', '-', ''], convert_vi_to_en($list)));
}

//Cấu hình gui mail
function config_send_mail()
{
	$config = [];
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = 'ssl://smtp.googlemail.com';
	$config['smtp_port'] = '465';
	$config['smtp_timeout'] = '30';
	$config['smtp_user'] = _emailroot;
	$config['smtp_pass'] = _passemailroot;
	$config['charset'] = 'utf-8';
	$config['newline'] = "\r\n";
	$config['wordwrap'] = TRUE;
	$config['mailtype'] = 'html';
	return $config;
}

// Check image
function check_image($thumb, $image, $vuong = FALSE)
{
	if ($thumb) {
		return base_url($thumb);
	}
	if ($image) {
		return base_url($image);
	}
	if ($vuong == TRUE) {
		return public_url('dist/images/no-image-300x300.png');
	}
	return public_url('dist/images/no-image-300x170.png');
}

//Return number
function check_phone($string)
{
	return preg_replace('/[^0-9]/', '', $string);
}
// Check sort
function check_sort($string = 'default', $table = '')
{
	if ($table) {
		$sort = [$table . '.id', 'desc'];
		if ($string == 'default') {
			$sort = [$table . '.id', 'desc'];
		}
		if ($string == 'numAsc') {
			$sort = [$table . '.sort_order', 'asc'];
		}
		if ($string == 'numDesc') {
			$sort = [$table . '.sort_order', 'desc'];
		}
		if ($string == 'timerAsc') {
			$sort = [$table . '.timer', 'asc'];
		}
		if ($string == 'timerDesc') {
			$sort = [$table . '.timer', 'desc'];
		}
	} else {
		$sort = ['id', 'desc'];
		if ($string == 'default') {
			$sort = ['id', 'desc'];
		}
		if ($string == 'numAsc') {
			$sort = ['sort_order', 'asc'];
		}
		if ($string == 'numDesc') {
			$sort = ['sort_order', 'desc'];
		}
		if ($string == 'timerAsc') {
			$sort = ['timer', 'asc'];
		}
		if ($string == 'timerDesc') {
			$sort = ['timer', 'desc'];
		}
	}
	return $sort;
}
function type_menu($type = 'Không xác định', $id_type = 0)
{
	$CI = get_instance();
	$CI->load->model('pages_model');
	$CI->load->model('catalognew_model');
	$CI->load->model('news_model');
	$CI->load->model('catalog_model');
	$CI->load->model('products_model');
	$CI->load->model('catalogservice_model');
	$CI->load->model('services_model');

	$result = ['texttype' => $type, 'error' => 'Không tồn tại'];

	if ($type == 'outlink') {
		$result['texttype'] = 'Liên kết';
		$result['error'] = 'ok';
	}
	if ($type == 'pages') {
		$result['texttype'] = 'Trang';
		if ($CI->pages_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'news') {
		$result['texttype'] = 'Bài viết';
		if ($CI->news_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'catalognew') {
		$result['texttype'] = 'Danh mục bài viết';
		if ($CI->catalognew_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'products') {
		$result['texttype'] = 'Sản phẩm';
		if ($CI->products_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'catalog') {
		$result['texttype'] = 'Danh mục sản phẩm';
		if ($CI->catalog_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'services') {
		$result['texttype'] = 'Dịch vụ';
		if ($CI->services_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	if ($type == 'catalogservice') {
		$result['texttype'] = 'Danh mục dịch vụ';
		if ($CI->catalogservice_model->get_info($id_type)) {
			$result['error'] = 'ok';
		}
	}
	return $result;
}
function convert_permissions($controller)
{
	$characters = [
		'nvkd' => 'Nhân viên kinh doanh',
		'nvsx' => 'Nhân viên sản xuất',
		'nvk' => 'Nhân viên kho'
	];
	foreach ($characters as $key => $row) {
		if ($controller == $key) {
			return str_replace($key, $row, $controller);
		}
	}
}
function convert_permissions_item($controller, $item)
{
	if ($controller == 'nvkd') {
		$characters = [
			'addajax' => 'Thêm sản phẩm',
			'deleteajax' => 'Xóa sản phẩm',
			'updateajax' => 'Sửa sản phẩm',
			'uploadimg' => 'Tải hình',
			'delimg' => 'Xóa hình',
			'orderreturn' => 'Trả lại cho NVSX',
			'sendwarehouse' => 'Chuyển cho kho',

			'index' => 'Tạo đơn hàng',
			'ordernew' => 'ĐH mới tạo',
			'orderdoing' => 'ĐH đang sản xuất',
			'orderalready' => 'ĐH đã sản xuất',
			'orderwarehouse' => 'ĐH đã chuyển cho kho',
		];
	} else if ($controller == 'nvsx') {
		$characters = [
			'ordertake' => 'Nhận sản xuất ĐH',
			'uploadimg' => 'Tải hình',

			'index' => 'ĐH mới',
			'orderdoing' => 'ĐH đang sản xuất',
			'orderedit' => 'Chỉnh sửa ĐH',
			'orderalready' => 'ĐH đã sản xuất',
			'orderreturn' => 'ĐH bị trả lại',
		];
	} else {
		$characters = [
			'tocustomer' => 'Đã giao sản phẩm cho khách',

			'index' => 'ĐH chưa giao cho khách',
			'success' => 'ĐH đã giao cho khách'
		];
	}
	foreach ($characters as $key => $row) {
		if ($item == $key) {
			return str_replace($key, $row, $item);
		}
	}
}
function get_url_add($url = '', $name, $model)
{
	$CI = &get_instance();
	$CI->load->model($model);

	if ($url == '') {
		$url = convertUrl($name);
	}
	$where = ['url' => $url];
	if ($CI->{$model}->get_info_rule($where)) {
		$url = $url . '-' . now();
	}
	return $url;
}
function get_url_edit($url = '', $name, $model, $id)
{
	$CI = &get_instance();
	$CI->load->model($model);

	if ($url == '') {
		$url = convertUrl($name);
	}
	$where = ['url' => $url];
	if ($CI->{$model}->get_info_rule($where)) {
		$info_slug = $CI->{$model}->get_info_rule($where);
		if ($info_slug->id != $id) {
			$url = $url . '-' . now();
		}
	}
	return $url;
}
// Check image
function check_image_admin($image = '')
{
	if ($image) {
		return base_url($image);
	}
	return public_url('admin/img/no-image-80x80.png');
}
// Switch sort
function switch_sort($string = '')
{
	if ($string == 'numAsc' || $string == 'numDesc') {
		return 'numShow';
	} else {
		return 'hidden';
	}
}
// Check và lưu danh mục chính
function main_catalog($catalog_id, $catalog_ids)
{
	if (in_array(intval($catalog_id), $catalog_ids)) {
		return intval($catalog_id);
	} else {
		return intval($catalog_ids[0]);
	}
}
// Resize Image
function resize_image($filename = '', $width = 300, $height = 170)
{
	if ($filename) {
		list($img_width) = getimagesize(base_url($filename));
		if ($img_width > $width) {
			$config = [
				'image_library' => 'gd2',
				'source_image' => $_SERVER['DOCUMENT_ROOT'] . $filename,
				'new_image' => $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/thumbnail/',
				'quality' => '80%',
				'maintain_ratio' => TRUE,
				'create_thumb' => TRUE,
				'thumb_marker' => '-' . $width . '-' . $height,
				'width' => $width,
				'height' => $height
			];
			$CI = get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($config);
			if (!$CI->image_lib->resize()) {
				return $CI->image_lib->display_errors();
			} else {
				$path_parts = pathinfo($filename);
				$name = $path_parts['filename'];
				$pre = $path_parts['extension'];
				return '/uploads/images/thumbnail/' . $name . '-' . $width . '-' . $height . '.' . $pre;
			}
			$CI->image_lib->clear();
		}
	}
	return $filename;
}
// Check image_thumb
function check_thumb($postImg, $infoImg, $infoThumb, $width = 300, $height = 170)
{
	$result = '';
	if ($postImg !== $infoImg) {
		$result = resize_image($postImg, $width, $height);
	} else {
		if (!$infoThumb) {
			$result = resize_image($infoImg, $width, $height);
		}
	}
	return $result;
}
// Merge
function merge($img = [], $alt = [])
{
	$merge = '';
	if ($img) {
		$merge = json_encode(array_combine($img, $alt));
	}
	return $merge;
}
function get_date_admin($time)
{
	$format = '%d/%m/%Y %H:%i';
	$date = mdate($format, $time);
	return $date;
}

function convert_time_admin($time)
{
	$time = str_replace("/", "-", $time);
	return strtotime($time);
}

function get_date($time, $full_time = TRUE)
{
	$format = '%d/%m/%Y';
	if ($full_time) {
		$format = $format . ' %h:%i:%s';
	}
	$date = mdate($format, $time);
	return $date;
}
