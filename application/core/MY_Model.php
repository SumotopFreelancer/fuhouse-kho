<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	// Ten table
	var $table = '';

	// Key chinh cua table
	var $key = 'id';

	// Order mac dinh (VD: $order = ['id', 'desc])
	var $order = '';

	// Cac field select mac dinh khi get_list (VD: $select = 'id, name')
	var $select = '';

	/**
	 * Them row moi
	 * $data : du lieu ma ta can them
	 */
	function create($data = [])
	{
		if ($this->db->insert($this->table, $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Cap nhat row tu id
	 * $id : khoa chinh cua bang can sua
	 * $data : mang du lieu can sua
	 */
	function update($id, $data)
	{
		if (!$id) {
			return FALSE;
		}
		$where = [];
		$where[$this->key] = $id;
		$this->update_rule($where, $data);

		return TRUE;
	}

	/**
	 * Cap nhat row tu dieu kien
	 * $where : dieu kien
	 * $data : mang du lieu can cap nhat
	 */
	function update_rule($where, $data)
	{
		if (!$where) {
			return FALSE;
		}
		$this->db->where($where);
		$this->db->update($this->table, $data);

		return TRUE;
	}

	/**
	 * Xoa row tu id
	 * $id : gia tri cua khoa chinh
	 */
	function delete($id)
	{
		if (!$id) {
			return FALSE;
		}
		if (is_numeric($id)) {
			$where = [$this->key => $id];
		} else {
			//$id = 1,2,3...
			$where = $this->key . " IN (" . $id . ") ";
		}
		$this->del_rule($where);

		return TRUE;
	}

	/**
	 * Xoa row tu dieu kien
	 * $where : mang dieu kien de xoa
	 */
	function del_rule($where)
	{
		if (!$where) {
			return FALSE;
		}
		$this->db->where($where);
		$this->db->delete($this->table);

		return TRUE;
	}

	/**
	 * Thực hiện câu lệnh query
	 * $sql : cau lenh sql
	 */
	function query($sql)
	{
		$rows = $this->db->query($sql);
		return $rows->result;
	}

	/**
	 * Lay thong tin cua row tu id
	 * $id : id can lay thong tin
	 * $field : cot du lieu ma can lay
	 */
	function get_info($id, $field = '')
	{
		if (!$id) {
			return FALSE;
		}

		$where = [];
		$where[$this->key] = $id;

		return $this->get_info_rule($where, $field);
	}

	/**
	 * Lay thong tin cua row tu dieu kien
	 * $where: Mảng điều kiện
	 * $field: Cột muốn lấy dữ liệu
	 */
	function get_info_rule($where = [], $field = '')
	{
		if ($field) {
			$this->db->select($field);
		}
		$this->db->where($where);
		$query = $this->db->get($this->table);
		if ($query->num_rows()) {
			return $query->row();
		}

		return FALSE;
	}

	/**
	 * Lay tong so
	 */
	function get_total($input = [])
	{
		$this->get_list_set_input($input);
		$query = $this->db->get($this->table);

		return $query->num_rows();
	}

	function get_total_all()
	{
		return $this->db->count_all($this->table);
	}
	/**
	 * Lay tong so
	 * $field: cot muon tinh tong
	 */
	function get_sum($field, $where = [])
	{
		$this->db->select_sum($field); //tinh rong
		$this->db->where($where); //dieu kien
		$this->db->from($this->table);

		$row = $this->db->get()->row();
		foreach ($row as $f => $v) {
			$sum = $v;
		}
		return $sum;
	}

	/**
	 * Lay 1 row
	 */
	function get_row($input = [])
	{
		$this->get_list_set_input($input);

		$query = $this->db->get($this->table);

		return $query->row();
	}

	/**
	 * Lay danh sach
	 * $input : mang cac du lieu dau vao
	 */
	function get_list($input = [], $array = FALSE)
	{
		//xu ly ca du lieu dau vao
		$this->get_list_set_input($input);

		//thuc hien truy van du lieu
		$query = $this->db->get($this->table);
		if ($array == FALSE) {
			return $query->result();
		} else {
			return $query->result_array();
		}
	}

	/**
	 * Gan cac thuoc tinh trong input khi lay danh sach
	 * $input : mang du lieu dau vao
	 */

	protected function get_list_set_input($input = [])
	{
		if ((isset($input['select'])) && $input['select']) {
			$this->db->select($input['select']);
		}
		if (isset($input['join']) && $input['join']) {
			foreach ($input['join'] as $table => $condition) {
				$this->db->join($table, $condition[0], !empty($condition[1]) ? $condition[1] : 'inner');
				// inner, left, right
			}
		}
		if ((isset($input['where'])) && $input['where']) {
			$this->db->where($input['where']);
		}
		if ((isset($input['where_in'])) && $input['where_in']) {
			$this->db->where_in($input['where_in'][0], $input['where_in'][1]);
		}
		if ((isset($input['like'])) && $input['like']) {
			$this->db->like($input['like']);
		}
		if ((isset($input['or_like'])) && $input['or_like']) {
			$this->db->or_like($input['or_like'][0], $input['or_like'][1]);
		}
		if ((isset($input['group_by'])) && $input['group_by']) {
			$this->db->group_by($input['group_by']);
		}
		if (isset($input['order']) && $input['order']) {
			foreach ($input['order'] as $col => $type) {
				$this->db->order_by($col, $type);
			}
		}
		if (isset($input['limit'][0]) && isset($input['limit'][1])) {
			$this->db->limit($input['limit'][0], $input['limit'][1]);
		}
	}
	/**
	 * kiểm tra sự tồn tại của dữ liệu theo 1 điều kiện nào đó
	 * $where : mang du lieu dieu kien
	 */
	function check_exists($where = [])
	{
		$this->db->where($where);
		//thuc hien cau truy van lay du lieu
		$query = $this->db->get($this->table);

		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// backend
	function menucon_admin($id, $sort = [])
	{
		$this->db->where('parent_id', $id);
		if (isset($sort[0]) && isset($sort[1])) {
			$this->db->order_by($sort[0], $sort[1]);
		} else {
			$this->db->order_by('sort_order', 'asc');
		}
		return $this->db->get($this->table)->result();
	}
	function get_sub_full_admin($danhmuc, $sort = [])
	{
		if ($danhmuc->parent_id == 0) {
			$catalog_subs_id[] = $danhmuc->id;
			if (count($this->menucon_admin($danhmuc->id)) > 0) {
				$c = $this->menucon_admin($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
					if (count($this->menucon_admin($subs->id)) > 0) {
						$ttcon = $this->menucon_admin($subs->id, $sort);
						foreach ($ttcon as $subs1) {
							$catalog_subs_id[] = $subs1->id;
							if (count($this->menucon_admin($subs1->id)) > 0) {
								$ttcon1 = $this->menucon_admin($subs1->id, $sort);
								foreach ($ttcon1 as $subs2) {
									$catalog_subs_id[] = $subs2->id;
								}
							}
						}
					}
				}
			}
		} else {
			$catalog_subs_id[] = $danhmuc->id;
			if (count($this->menucon_admin($danhmuc->id)) > 0) {
				$c = $this->menucon_admin($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
					if (count($this->menucon_admin($subs->id)) > 0) {
						$ttcon = $this->menucon_admin($subs->id, $sort);
						foreach ($ttcon as $subs1) {
							$catalog_subs_id[] = $subs1->id;
						}
					}
				}
			}
		}
		return $catalog_subs_id;
	}
	function get_sub_one_admin($danhmuc, $sort = [])
	{
		if ($danhmuc->parent_id == 0) {
			$catalog_subs_id = [];
			if (count($this->menucon_admin($danhmuc->id)) > 0) {
				$c = $this->menucon_admin($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
				}
			}
		} else {
			$catalog_subs_id = [];
			if (count($this->menucon_admin($danhmuc->id)) > 0) {
				$c = $this->menucon_admin($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
				}
			}
		}
		return $catalog_subs_id;
	}

	// frontend
	function menucon($id, $sort = [])
	{
		$this->db->where('parent_id', $id);
		$this->db->where('status', 1);
		if (isset($sort[0]) && isset($sort[1])) {
			$this->db->order_by($sort[0], $sort[1]);
		} else {
			$this->db->order_by('sort_order', 'asc');
		}
		return $this->db->get($this->table)->result();
	}
	function get_sub_full($danhmuc, $sort = [])
	{
		if ($danhmuc->parent_id == 0) {
			$catalog_subs_id[] = $danhmuc->id;
			if (count($this->menucon($danhmuc->id)) > 0) {
				$c = $this->menucon($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
					if (count($this->menucon($subs->id)) > 0) {
						$ttcon = $this->menucon($subs->id, $sort);
						foreach ($ttcon as $subs1) {
							$catalog_subs_id[] = $subs1->id;
							if (count($this->menucon($subs1->id)) > 0) {
								$ttcon1 = $this->menucon($subs1->id, $sort);
								foreach ($ttcon1 as $subs2) {
									$catalog_subs_id[] = $subs2->id;
								}
							}
						}
					}
				}
			}
		} else {
			$catalog_subs_id[] = $danhmuc->id;
			if (count($this->menucon($danhmuc->id)) > 0) {
				$c = $this->menucon($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
					if (count($this->menucon($subs->id)) > 0) {
						$ttcon = $this->menucon($subs->id, $sort);
						foreach ($ttcon as $subs1) {
							$catalog_subs_id[] = $subs1->id;
						}
					}
				}
			}
		}
		return $catalog_subs_id;
	}
	function get_sub_one($danhmuc, $sort = [])
	{
		if ($danhmuc->parent_id == 0) {
			$catalog_subs_id = [];
			if (count($this->menucon($danhmuc->id)) > 0) {
				$c = $this->menucon($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
				}
			}
		} else {
			$catalog_subs_id = [];
			if (count($this->menucon($danhmuc->id)) > 0) {
				$c = $this->menucon($danhmuc->id, $sort);
				foreach ($c as $subs) {
					$catalog_subs_id[] = $subs->id;
				}
			}
		}
		return $catalog_subs_id;
	}
}
