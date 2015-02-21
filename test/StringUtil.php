<?php

use phrame\test\UnitTest;
use phrame\StringUtil as s;

class StringUtilTest extends UnitTest {

	public function test_length() {
		$this->eq(s::length('aaa'), 3);
		$this->eq(s::length(''), 0);
		$this->eq(s::size(''), 0);
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
		$this->eq(s::chars('abc'), array('a', 'b', 'c'));
		$this->eq(s::chars(''), array());
	}

	public function test_chunks() {
		$this->eq(
			s::chunks('abcde', 2),
			array('ab', 'cd', 'e')
		);
		$this->eq(
			s::chunks('abc', 4),
			array('abc')
		);
		$this->eq(
			s::chunks('', 3),
			array()
		);
	}

	public function test_split() {
		$this->eq(s::split('a b'), array('a', 'b'));
		$this->eq(s::split(''), array());
		$this->eq(s::split('  a  b  '), array('a', 'b'));
		$this->eq(s::split(" \tabc\n  "), array('abc'));
		$this->eq(s::split('aba', 'b'), array('a', 'a'));
		$this->eq(s::split('a', 'a'), array('', ''));
		$this->eq(s::split('abbaba', 'bb'), array('a', 'aba'));
		$this->eq(s::split('ababa', 'b', 1), array('a', 'aba'));
		$this->eq(s::split('ababa', 'b', -1), array('a', 'a'));
	}

	public function test_tokenize() {
		$this->eq(s::tokenize('xabcxyabc', 'abc'), array('x', 'xy'));
		$this->eq(s::tokenize('abcabc', 'abc'), array());
		$this->eq(s::tokenize('', 'abc'), array());
		$this->eq(s::tokenize('', ''), array());
	}

	public function test_join() {
		$this->eq(s::join(array('a', 'b', 'c')), 'abc');
		$this->eq(s::join(' ', array('a', 'b', 'c')), 'a b c');
	}

	public function test_trim() {
		$this->eq(s::trim('   a   '), 'a');
		$this->eq(s::trim("\n\ta "), 'a');
		$this->eq(s::trim('   a'), 'a');
		$this->eq(s::trim('a   '), 'a');
		$this->eq(s::trim('a'), 'a');
		$this->eq(s::trim('  a   a  '), 'a   a');
		$this->eq(s::trim(''), '');
		$this->eq(s::trim('abcxyzcba', 'abc'), 'xyz');
	}

	public function test_rtrim() {
		$this->eq(s::rtrim('  a  '), '  a');
		$this->eq(s::rtrim('abcxyzcba', 'abc'), 'abcxyz');
		$this->eq(s::rtrim(''), '');
	}

	public function test_ltrim() {
		$this->eq(s::ltrim('  a  '), 'a  ');
		$this->eq(s::ltrim('abcxyzcba', 'abc'), 'xyzcba');
		$this->eq(s::ltrim(''), '');
	}

	public function test_lower() {
		$this->eq(s::lower('ABC'), 'abc');
		$this->eq(s::lower('abc'), 'abc');
		$this->eq(s::lower(':;"'), ':;"');
		$this->eq(s::lower('AbC'), 'abc');
		$this->eq(s::lower(''), '');
	}

	public function test_upper() {
		$this->eq(s::upper('abc'), 'ABC');
		$this->eq(s::upper('ABC'), 'ABC');
		$this->eq(s::upper(''), '');
	}

	public function test_lcfirst() {
		$this->eq(s::lcfirst('ABC'), 'aBC');
		$this->eq(s::lcfirst('FooBar'), 'fooBar');
		$this->eq(s::lcfirst(''), '');
		$this->eq(s::lower_first('ABC'), 'aBC');
	}

	public function test_ucfirst() {
		$this->eq(s::ucfirst('abc'), 'Abc');
		$this->eq(s::ucfirst(''), '');
		$this->eq(s::upper_first('abc'), 'Abc');
	}

	public function test_ucwords() {
		$this->eq(s::ucwords('alpha beta gamma'),
			'Alpha Beta Gamma');
		$this->eq(s::ucwords(''), '');
		$this->eq(s::upper_words('alpha beta gamma'),
			'Alpha Beta Gamma');
	}

	public function test_replace() {
		$this->eq(s::replace('abcxabc', 'x', 'y'), 'abcyabc');
		$this->eq(s::replace('afoobara', 'foobar', 'b'), 'aba');
		$this->eq(s::replace('aaxyaaaxya', 'xy', 'b'), 'aabaaaba');
		$this->eq(s::replace('', 'abc', ''), '');
		$this->eq(s::replace('abc', '', 'x'), 'xaxbxcx');
		$this->eq(s::replace('', '', 'x'), 'x');
	}

	public function test_count_replace() {
		$this->eq(s::count_replace('abcxabc', 'x', 'y'),
			array('abcyabc', 1));
		$this->eq(s::count_replace('afoobara', 'foobar', 'b'),
			array('aba', 1));
		$this->eq(s::count_replace('aaxyaaaxya', 'xy', 'b'),
			array('aabaaaba', 2));
		$this->eq(s::count_replace('', 'abc', ''),
			array('', 0));
		$this->eq(s::count_replace('abc', '', 'x'),
			array('xaxbxcx', 4));
		$this->eq(s::count_replace('', '', 'x'),
			array('x', 1));
	}

	public function test_ireplace() {
		$this->eq(s::ireplace('abcXabc', 'x', 'y'), 'abcyabc');
		$this->eq(s::ireplace('afoOBara', 'foobar', 'b'), 'aba');
		$this->eq(s::ireplace('aaxYaaaXya', 'xy', 'b'), 'aabaaaba');
		$this->eq(s::ireplace('', 'abc', ''), '');
		$this->eq(s::ireplace('abc', '', 'x'), 'xaxbxcx');
		$this->eq(s::ireplace('', '', 'x'), 'x');
	}

	public function test_count_ireplace() {
		$this->eq(s::count_ireplace('abcXabc', 'x', 'y'),
			array('abcyabc', 1));
		$this->eq(s::count_ireplace('afoOBara', 'foobar', 'b'),
			array('aba', 1));
		$this->eq(s::count_ireplace('aaxYaaaXya', 'xy', 'b'),
			array('aabaaaba', 2));
		$this->eq(s::count_ireplace('', 'abc', ''),
			array('', 0));
		$this->eq(s::count_ireplace('abc', '', 'x'),
			array('xaxbxcx', 4));
		$this->eq(s::count_ireplace('', '', 'x'),
			array('x', 1));
	}
}

?>
