<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Upload_library
{
	// khoi tao sieu doi tuong
	var $CI = '';
	function __construct()
	{
		$this->CI = &get_instance();
	}
	// upload 1 file
	function upload($upload_path = '', $file_name = '')
	{
		$config = $this->config($upload_path);
		$this->CI->load->library('upload', $config);
		// thuc hien up load
		if ($this->CI->upload->do_upload($file_name)) {
			$data = $this->CI->upload->data();
		} else {
			$data = $this->CI->upload->display_errors();
		}
		return $data;
	}
	// up load nhieu file 
	function upload_file($upload_path = '', $file_name = '')
	{
		$config = $this->config($upload_path);
		//lưu biến môi trường khi thực hiện upload
		$file  = $_FILES[$file_name];
		$count = count($file['name']); //lấy tổng số file được upload
		$image_list = []; // luu ten cac anh upload thanh cong
		for ($i = 0; $i <= $count - 1; $i++) {
			$_FILES['userfile']['name']     = $file['name'][$i];  //khai báo tên của file thứ i
			$_FILES['userfile']['type']     = $file['type'][$i]; //khai báo kiểu của file thứ i
			$_FILES['userfile']['tmp_name'] = $file['tmp_name'][$i]; //khai báo đường dẫn tạm của file thứ i
			$_FILES['userfile']['error']    = $file['error'][$i]; //khai báo lỗi của file thứ i
			$_FILES['userfile']['size']     = $file['size'][$i]; //khai báo kích cỡ của file thứ i
			//load thư viện upload và cấu hình
			$this->CI->load->library('upload', $config);
			//thực hiện upload từng file
			if ($this->CI->upload->do_upload()) {
				//nếu upload thành công thì lưu toàn bộ dữ liệu
				$data = $this->CI->upload->data();
				$image_list[] = $data['file_name'];
			} else {
				$data = $this->CI->upload->display_errors();
			}
		}
		return $image_list;
	}
	function config($upload_path = '')
	{
		//Khai bao bien cau hinh
		$config = [];
		//thuc mục chứa file
		$config['upload_path']   = $upload_path;
		//Định dạng file được phép tải
		$config['allowed_types'] = 'jpg|png|gif';
		//Dung lượng tối đa
		$config['max_size']      = '4000';
		//Chiều rộng tối đa
		$config['max_width']     = '10000';
		//Chiều cao tối đa
		$config['max_height']    = '10000';
		// noi them neu trung ten
		$config['overwrite'] = FALSE;
		return $config;
	}
}
