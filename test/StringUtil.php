<?php

use phrame\test\UnitTest;
use phrame\StringUtil as s;

class StringUtilTest extends UnitTest {

	public function test_length() {
		$this->ok(s::length('aaa') === 3);
		$this->ok(s::length('') === 0);
		$this->ok(s::size('') === 0);
	}

	public function test_equal() {
		$this->ok(s::equal('aaa', 'aaa'));
		$this->not(s::equal('aaa', 'bbb'));
		$this->ok(s::equal('', ''));
		$this->not(s::equal('aaa', 'AAA'));
	}

	public function test_iequal() {
		$this->ok(s::iequal('aaa', 'AAA'));
		$this->not(s::iequal('aaa', 'bbb'));
		$this->ok(s::iequal('', ''));
	}

	public function test_chars() {
		$this->ok(s::chars('abc') === array('a', 'b', 'c'));
		$this->eq(s::chars(''), array());
	}

	public function test_chunks() {
		$this->ok(
			s::chunks('abcde', 2) ===
			array('ab', 'cd', 'e')
		);
		$this->ok(
			s::chunks('abc', 4) ===
			array('abc')
		);
	}
}

?>
