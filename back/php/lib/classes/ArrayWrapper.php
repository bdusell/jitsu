<?php

class ArrayWrapper implements Countable, Iterator, ArrayAccess {

	const NO = 0;
	const YES = 1;
	const INDEXED = 2;

	private $array;
	private $uses_keys;
	private $uses_values;
	private $uses_order;
}

?>
