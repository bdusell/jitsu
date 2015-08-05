<?php

namespace jitsu\orm;

use jitsu\sql\Ast as sql;

class Model {

	public static function define($name, $defs) {
		return new ModelDefinition($name, $defs);
	}

	public static function primary_key($defs = null) {
		if($defs === null) {
			return new definitions\DefaultPrimaryKey;
		} else {
			return new definitions\PrimaryKey($defs);
		}
	}

	public static function attr($type, $name) {
		return new definitions\Column(sql::col_def($type, $name));
	}

	public static function unique($col_defs) {
		return new definitions\Unique($col_defs);
	}

	public static function has_a($model_def) {
		$pk_defs = $model_def->primary_key_column_definitions();
		if(!$pk_defs) {
			throw new \InvalidArgumentException(
				'referenced model definition has no primary key');
		}
		return new definitions\ForeignKey(
			$model_def->name(),
			$pk_defs
		);
	}

	public static function bool_t() {
		return sql::bool_type();
	}

	public static function int_t($bytes = 4, $signed = false) {
		return sql::int_type($bytes, $signed);
	}

	public static function decimal_t($digits, $decimals) {
		return sql::decimal_type($digits, $decimals);
	}

	public static function real_t($bytes = 8) {
		return sql::real_type($bytes);
	}

	public static function date_t() {
		return sql::date_type();
	}

	public static function time_t() {
		return sql::time_type();
	}

	public static function datetime_t() {
		return sql::datetime_type();
	}

	public static function timestamp_t() {
		return sql::timestamp_type();
	}

	public static function year_t() {
		return sql::year_type();
	}

	public static function fixed_string_t(
		$length,
		$charset = null,
		$collation = null
	) {
		return sql::fixed_string_type($length, $charset, $collation);
	}

	public static function string_t(
		$max_length = null,
		$charset = null,
		$collation = null
	) {
		return sql::string_type($max_length, $charset, $collation);
	}

	public static function byte_string_t($max_length = null) {
		return sql::byte_string_type($max_length);
	}

	public static function text_t(
		$prefix_size = 2,
		$charset = null,
		$collation = null
	) {
		return sql::text_type($prefix_size, $charset, $collation);
	}

	public static function ascii() {
		return sql::ascii();
	}

	public static function unicode() {
		return sql::unicode();
	}

	public static function case_sensitive() {
		return sql::case_sensitive();
	}

	public static function case_insensitive() {
		return sql::case_insensitive();
	}
}

?>
