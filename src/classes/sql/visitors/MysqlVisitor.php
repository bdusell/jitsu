<?php

namespace jitsu\sql\visitors;

class MysqlVisitor extends CodeGenerationVisitor {

	public function __construct($database) {
		parent::__construct($database, new MysqlPrecedenceVisitor());
	}

	public function visitIdentifier($n) {
		return '`' . str_replace('`', '``', $n->value) . '`';
	}

	public function visitConcatenationExpression($n) {
		return (
			'CONCAT(' . $n->left->accept($this) . ', ' .
			$n->right->accept($this) . ')'
		);
	}

	public function insertCommand($type) {
		switch($type) {
		case \jitsu\sql\ast\InsertStatement::INSERT_OR_REPLACE:
			return 'REPLACE';
		case \jitsu\sql\ast\InsertStatement::INSERT_OR_IGNORE:
			return 'INSERT IGNORE';
		default:
			return $type;
		}
	}

	public function visitAutoincrementClause($n) {
		return 'AUTO_INCREMENT';
	}

	public function visitBitfieldType($n) {
		return 'BIT(' . $n->width . ')';
	}

	public function visitBooleanType($n) {
		return 'BOOL';
	}

	public function visitIntegerType($n) {
		$r = self::integerName($n->bytes);
		if(!$n->signed) $r .= ' UNSIGNED';
		return $r;
	}

	private static function integerName($size) {
		if($size <= 1) {
			return 'TINYINT';
		} elseif($size <= 2) {
			return 'SMALLINT';
		} elseif($size <= 3) {
			return 'MEDIUMINT';
		} elseif($size <= 4) {
			return 'INT';
		} else {
			return 'BIGINT';
		}
	}

	public function visitDecimalType($n) {
		return 'DECIMAL(' . $n->digits . ', ' . $n->decimals . ')';
	}

	public function visitRealType($n) {
		return $n->bytes <= 4 ? 'FLOAT' : 'DOUBLE';
	}

	public function visitDateType($n) {
		return 'DATE';
	}

	public function visitTimeType($n) {
		return 'TIME';
	}

	public function visitDatetimeType($n) {
		return 'DATETIME';
	}

	public function visitTimestampType($n) {
		return 'TIMESTAMP';
	}

	public function visitYearType($n) {
		return 'YEAR';
	}

	private static function charset_name($charset) {
		switch($charset) {
		case \jitsu\sql\ast\CharacterStringType::ASCII:
			return 'ascii';
		case \jitsu\sql\ast\CharacterStringType::UNICODE:
			return 'utf8mb4';
		}
		return null;
	}

	private static function collation_name($collation) {
		switch($collation) {
		case \jitsu\sql\ast\CharacterStringType::CASE_SENSITIVE:
			return 'bin';
		case \jitsu\sql\ast\CharacterStringType::CASE_INSENSITIVE:
			return 'general_ci';
		}
		return null;
	}

	private static function str_mods($n) {
		$r = '';
		$name = self::charset_name($n->character_set);
		if($name !== null) {
			$r .= ' CHARACTER SET ' . $name;
			$collation = self::collation_name($n->collation);
			if($collation !== null) {
				$r .= ' COLLATE ' . $name . '_' . $collation;
			}
		}
		return $r;
	}

	public function visitFixedStringType($n) {
		return 'CHAR(' . $n->length . ')' . self::str_mods($n);
	}

	public function visitStringType($n) {
		return 'VARCHAR(' . $n->maximum_length . ')' . self::str_mods($n);
	}

	public function visitByteStringType($n) {
		return 'VARBINARY(' . $n->maximum_length . ')';
	}

	public function visitTextType($n) {
		return self::prefixSizeName($n->prefix_size) . 'TEXT' . self::str_mods($n);
	}

	public function visitBlobType($n) {
		return self::prefixSizeName($n->prefix_size) . 'BLOB';
	}

	private static function prefixSizeName($size) {
		if($size <= 1) {
			return 'TINY';
		} elseif($size <= 2) {
			return '';
		} elseif($size <= 3) {
			return 'MEDIUM';
		} else {
			return 'LONG';
		}
	}
}

?>
