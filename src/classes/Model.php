<?php

namespace phrame;

class Model {

	const id = 'id';

	private $_attrs;

	public function __construct($attrs) {
		$this->_attrs = $attrs;
	}

	public function to_object() {
		return self::_to_object($this->_attrs);
	}

	public function to_array() {
		return self::_to_array($this->_attrs);
	}

	public function get($name) {
		return self::_get_attr($this->_attrs, $name);
	}

	public function set($name, $value) {
		self::_set_attr($this->_attrs, $value);
	}

	public function id() {
		return $this->get($this->_id_col());
	}

	public static function fetch($id) {
		$table = self::_table_name();
		$col = self::_id_col();
		$row = \Database::row_with(
<<<SQL
select * from "$table" where "$col" = ? limit 1
SQL
		, array($id));
		$class = get_called_class();
		return $row === null ? null : new $class($row);
	}

	public function save() {
		$table = self::_table_name();
		$idcol = self::_id_col();
		$attrs = $this->to_array();
		$is_new = !array_key_exists($idcol, $attrs);
		unset($attrs[$idcol]);
		if($is_new) {
			$col_parts = array();
			$qmark_parts = array();
			$values = array();
			foreach($attrs as $k => $v) {
				$col_parts[] = "\"$k\"";
				$qmark_parts[] = '?';
				$values[] = $v;
			}
			$cols = join(', ', $col_parts);
			$qmarks = join(', ', $qmark_parts);
			\Database::execute_with(
<<<SQL
insert into "$table"($cols) values ($qmarks)
SQL
			, $values);
			$this->set($idcol, \Database::last_insert_id());
		} else {
			$set_parts = array();
			$values = array();
			foreach($attrs as $k => $v) {
				$set_parts = "set \"$k\" = ?";
				$values[] = $v;
			}
			$values[] = $this->id();
			$sets = join(', ', $set_parts);
			\Database::execute_with(
<<<SQL
update "$table" $sets where "$idcol" = ? limit 1
SQL
			, $values);
		}
	}

	private static function _table_name() {
		return self::_get_const('table');
	}

	private static function _id_col() {
		return self::_get_const('id');
	}

	private static function _get_const($name) {
		return constant(get_called_class() . '::' . $name);
	}

	private static function _get_attr($obj, $name) {
		return is_array($obj) ? $obj[$name] : $obj->$name;
	}

	private static function _set_attr($obj, $name, $value) {
		is_array($obj) ? $obj[$name] = $value : $obj->$name = $value;
	}

	private static function _to_object($obj) {
		return is_array($obj) ? (object) $obj : $obj;
	}

	private static function _to_array($obj) {
		return is_array($obj) ? $obj : (array) $obj;
	}
}

?>
