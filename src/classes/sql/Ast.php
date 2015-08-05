<?php

namespace jitsu\sql;

class Ast {

	public static function select(/* $column, ... */) {
		return new ast\SimpleSelectStatementCore(array(
			'distinct' => false,
			'columns' => func_get_args()
		));
	}

	public static function select_distinct(/* $column, ... */) {
		return new ast\SimpleSelectStatementCore(array(
			'distinct' => true,
			'columns' => func_get_args()
		));
	}

	private static function insert_mode($table, $mode) {
		return new ast\InsertStatement(array(
			'type' => $mode,
			'table' => $table
		));
	}

	public static function insert($table) {
		return self::insert_mode($table, ast\InsertStatement::INSERT);
	}

	public static function insert_or_replace($table) {
		return self::insert_mode($table, ast\InsertStatement::INSERT_OR_REPLACE);
	}

	public static function insert_or_ignore($table) {
		return self::insert_mode($table, ast\InsertStatement::INSERT_OR_IGNORE);
	}

	public static function update($table, $assignments) {
		return new ast\UpdateStatement(array(
			'table' => $table,
			'assignments' => $assignments
		));
	}

	public static function set($name, $expr) {
		return new ast\Assignment(array(
			'column' => self::name($name),
			'expr' => $expr
		));
	}

	public static function delete($table) {
		return new ast\DeleteStatement(array(
			'table' => $table
		));
	}

	public static function values(/* $value_array, ... */) {
		return new ast\ValuesStatementCore(func_get_args());
	}

	public static function row(/* $expr, ... */) {
		return new ast\ValuesStatementCore(array(func_get_args()));
	}

	public static function star() {
		return new ast\WildcardColumnExpression(array());
	}

	public static function table($name) {
		return new ast\TableReference(array(
			'table' => self::name($name)
		));
	}

	public static function col($name) {
		return new ast\ColumnReference(array(
			'column' => self::name($name)
		));
	}

	public static function name($name) {
		return new ast\Identifier(array(
			'value' => $name
		));
	}

	public static function value($value = null) {
		if(func_num_args() === 0) {
			return new ast\AnonymousPlaceholder(array());
		} elseif(is_string($value)) {
			return new ast\StringLiteral(array('value' => $value));
		} elseif(is_int($value)) {
			return new ast\IntegerLiteral(array('value' => $value));
		} elseif(is_real($value)) {
			return new ast\RealLiteral(array('value' => $value));
		} elseif($value === null) {
			return new ast\NullLiteral(array());
		}
	}

	public static function create_table(
		$name,
		$column_defs,
		$constraints = array(),
		$modifiers = array()
	) {
		return new ast\CreateTableStatement(array(
			'temporary' => false,
			'if_not_exists' => false,
			'name' => $name,
			'columns' => $column_defs,
			'constraints' => $constraints,
			'modifiers' => $modifiers
		));
	}

	public static function col_def($type, $name, $clauses = array()) {
		$attrs = array(
			'name' => self::name($name),
			'type' => $type
		);
		foreach($clauses as $c) {
			$attrs[self::get_col_def_key($c)] = $c;
		}
		return new ast\ColumnDefinition($attrs);
	}

	private static function get_col_def_key($c) {
		if($c instanceof ast\NotNullClause) {
			return 'not_null';
		} elseif($c instanceof ast\DefaultValueClause) {
			return 'default';
		} elseif($c instanceof ast\AutoincrementClause) {
			return 'autoincrement';
		} elseif($c instanceof ast\KeyClause) {
			return 'key';
		} elseif($c instanceof ast\ForeignKeyClause) {
			return 'foreign_key';
		} else {
			throw new \InvalidArgumentException(
				'invalid column definition clause');
		}
	}

	public static function primary_key() {
		$args = func_get_args();
		if($args) {
			return new ast\PrimaryKeyConstraint(array(
				'columns' => self::names($args)
			));
		} else {
			return new ast\PrimaryKeyClause(array());
		}
	}

	public static function unique() {
		$args = func_get_args();
		if($args) {
			return new ast\UniqueConstraint(array(
				'columns' => self::names($args)
			));
		} else {
			return new ast\UniqueClause(array());
		}
	}

	public static function foreign_key(
		$cols,
		$table = null,
		$foreign_cols = null,
		$attrs = null
	) {
		if(!is_array($cols)) {
			list($cols, $table, $foreign_cols, $attrs) =
				array(null, $cols, $table, $foreign_cols);
		}
		if($attrs === null) $attrs = array();
		$result = new ast\ForeignKeyClause($attrs + array(
			'table' => $table,
			'columns' => self::names($foreign_cols)
		));
		if($cols !== null) {
			$result = new ast\ForeignKeyConstraint(array(
				'columns' => self::names($cols),
				'references' => $result
			));
		}
		return $result;
	}

	private static function names($names) {
		if($names === null) return null;
		$result = array();
		foreach($names as $name) {
			$result[] = self::name($name);
		}
		return $result;
	}

	public static function autoincrement() {
		return new ast\AutoincrementClause(array());
	}

	public static function not_null() {
		return new ast\NotNullClause(array());
	}

	public static function bitfield_type($width) {
		return new ast\BitfieldType(array(
			'width' => $width
		));
	}

	public static function bool_type() {
		return new ast\BooleanType(array());
	}

	public static function int_type($bytes = 4, $signed = false) {
		return new ast\IntegerType(array(
			'bytes' => $bytes,
			'signed' => $signed
		));
	}

	public static function decimal_type($digits, $decimals) {
		return new ast\DecimalType(array(
			'digits' => $digits,
			'decimals' => $decimals
		));
	}

	public static function real_type($bytes = 8) {
		return new ast\RealType(array(
			'bytes' => $bytes
		));
	}

	public static function date_type() {
		return new ast\DateType(array());
	}

	public static function time_type() {
		return new ast\TimeType(array());
	}

	public static function datetime_type() {
		return new ast\DatetimeType(array());
	}

	public static function timestamp_type() {
		return new ast\TimestampType(array());
	}

	public static function year_type() {
		return new ast\YearType(array());
	}

	private static function charset($charset) {
		return $charset === null ? self::ascii() : $charset;
	}

	private static function collation($collation) {
		return $collation === null ? self::case_sensitive() : $collation;
	}

	private static function str_attrs($charset, $collation) {
		return array(
			'character_set' => self::charset($charset),
			'collation' => self::collation($collation)
		);
	}

	public static function ascii() {
		return ast\CharacterStringType::ASCII;
	}

	public static function unicode() {
		return ast\CharacterStringType::UNICODE;
	}

	public static function case_sensitive() {
		return ast\CharacterStringType::CASE_SENSITIVE;
	}

	public static function case_insensitive() {
		return ast\CharacterStringType::CASE_INSENSITIVE;
	}

	public static function fixed_string_type(
		$length,
		$charset = null,
		$collation = null
	) {
		return new ast\FixedStringType(array(
			'length' => $length
		) + self::str_attrs($charset, $collation));
	}

	public static function string_type(
		$max_length = null,
		$charset = null,
		$collation = null
	) {
		if($max_length === null) $max_length = 255; /* 2^8 - 1 */
		return new ast\StringType(array(
			'maximum_length' => $max_length
		) + self::str_attrs($charset, $collation));
	}

	public static function byte_string_type($max_length = null) {
		if($max_length === null) $max_length = 255;
		return new ast\ByteStringType(array(
			'maximum_length' => $max_length
		));
	}

	public static function text_type(
		$prefix_size = 2,
		$charset = null,
		$collation = null
	) {
		return new ast\TextType(array(
			'prefix_size' => $prefix_size
		) + self::str_attrs($charset, $collation));
	}

	public static function blob_type($prefix_size = 2) {
		return new ast\BlobType(array(
			'prefix_size' => $prefix_size
		));
	}
}

?>
