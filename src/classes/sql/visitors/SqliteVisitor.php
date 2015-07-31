<?php

namespace jitsu\sql\visitors;

class SqliteVisitor extends CodeGenerationVisitor {

	public function __construct($database) {
		parent::__construct($database, new SqlitePrecedenceVisitor());
	}

	public function visitBitfieldType($n) {
		return 'INTEGER';
	}

	public function visitBooleanType($n) {
		return 'INTEGER';
	}

	public function visitIntegerType($n) {
		return 'INTEGER';
	}

	public function visitDecimalType($n) {
		return 'REAL';
	}

	public function visitRealType($n) {
		return 'REAL';
	}

	public function visitDateType($n) {
		return 'TEXT';
	}

	public function visitTimeType($n) {
		return 'TEXT';
	}

	public function visitDatetimeType($n) {
		return 'TEXT';
	}

	public function visitTimestampType($n) {
		return 'INTEGER';
	}

	public function visitYearType($n) {
		return 'INTEGER';
	}

	public function visitFixedStringType($n) {
		return 'TEXT';
	}

	public function visitStringType($n) {
		return 'TEXT';
	}

	public function visitByteStringType($n) {
		return 'BLOB';
	}

	public function visitTextType($n) {
		return 'TEXT';
	}

	public function visitBlobType($n) {
		return 'BLOB';
	}
}

?>
