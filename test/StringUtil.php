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
		$this->eq(s::equal('aaa', 'aaa'), true);
		$this->eq(s::equal('aaa', 'bbb'), false);
		$this->eq(s::equal('', ''), true);
		$this->eq(s::equal('aaa', 'AAA'), false);
	}

	public function test_iequal() {
		$this->eq(s::iequal('aaa', 'AAA'), true);
		$this->eq(s::iequal('aaa', 'bbb'), false);
		$this->eq(s::iequal('', ''), true);
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

	public function test_replace_multiple() {
		$this->eq(
			s::replace_multiple(':one :two', array(
				':one' => 'a',
				':two' => 'b'
			)),
			'a b'
		);
		$this->eq(
			s::replace_multiple(':one :two', array(
				':one' => ':one :two',
				':two' => 'b'
			)),
			':one :two b'
		);
		$this->eq(
			s::replace_multiple('', array(
				'a' => 'b',
				'b' => 'c'
			)),
			''
		);
		$this->eq(
			s::replace_multiple('abcdef', array()),
			'abcdef'
		);
	}

	public function test_translate() {
		$this->eq(s::translate('abcba', 'abc', '123'), '12321');
		$this->eq(s::translate('', 'abc', '123'), '');
		$this->eq(s::translate('abc', '', ''), 'abc');
	}

	public function test_substring() {
		$this->eq(s::substring('abcdef', 2, 3), 'cde');
		$this->eq(s::substring('abcdef', 2), 'cdef');
		$this->eq(s::substring('abcdef', 2, 1000), 'cdef');
		$this->eq(s::substring('abcdef', 1000), '');
		$this->eq(s::substring('abcdef', 1000, 3), '');
		$this->eq(s::substring('abcdef', 2, 0), '');
		$this->eq(s::substring('abcdef', -2), 'ef');
		$this->eq(s::substring('abcdef', -4, 2), 'cd');
		$this->eq(s::substring('abcdef', -1000), 'abcdef');
		$this->eq(s::substring('abcdef', -7, 3), 'ab');
		$this->eq(s::substring('', 2, 4), '');
	}

	public function test_replace_substring() {
		$this->eq(s::replace_substring('abcdef', 'x', 2, 3), 'abxf');
		$this->eq(s::replace_substring('abcdef', 'x', 2), 'abx');
		$this->eq(s::replace_substring('abcdef', 'x', 2, 1000), 'abx');
		$this->eq(s::replace_substring('abcdef', 'x', 1000), 'abcdefx');
		$this->eq(s::replace_substring('abcdef', 'x', 1000, 3), 'abcdefx');
		$this->eq(s::replace_substring('abcdef', 'x', 2, 0), 'abxcdef');
		$this->eq(s::replace_substring('abcdef', 'x', -2), 'abcdx');
		$this->eq(s::replace_substring('abcdef', 'x', -4, 2), 'abxef');
		$this->eq(s::replace_substring('abcdef', 'x', -1000), 'x');
		$this->eq(s::replace_substring('abcdef', 'x', -1000, -100), 'xabcdef');
		$this->eq(s::replace_substring('abcdef', 'x', -7, 3), 'xcdef');
		$this->eq(s::replace_substring('', 'x', 2, 4), 'x');
	}

	public function test_slice() {
		$this->eq(s::slice('abcdef', 1, 3), 'bc');
		$this->eq(s::slice('abcdef', 3, 4), 'd');
		$this->eq(s::slice('abcdef', 3, 3), '');
		$this->eq(s::slice('abcdef', 3, 2), '');
		$this->eq(s::slice('abcdef', 2), 'cdef');
		$this->eq(s::slice('abcdef', 2, 1000), 'cdef');
		$this->eq(s::slice('abcdef', 1000), '');
		$this->eq(s::slice('abcdef', 1000, 4), '');
		$this->eq(s::slice('abcdef', 1000, 2000), '');
		$this->eq(s::slice('abcdef', 0, -2), 'abcd');
		$this->eq(s::slice('abcdef', 1, -1), 'bcde');
		$this->eq(s::slice('abcdef', 3, -3), '');
		$this->eq(s::slice('abcdef', 4, -5), '');
		$this->eq(s::slice('abcdef', -4, -2), 'cd');
		$this->eq(s::slice('abcdef', -4, 4), 'cd');
		$this->eq(s::slice('abcdef', -1000), 'abcdef');
		$this->eq(s::slice('abcdef', -1000, -2), 'abcd');
		$this->eq(s::slice('abcdef', -1000, -100), '');
		$this->eq(s::slice('', 3, 5), '');
	}

	public function test_replace_slice() {
		$this->eq(s::replace_slice('abcdef', 'x', 2, 4), 'abxef');
		$this->eq(s::replace_slice('abcdef', 'x', 2), 'abx');
		$this->eq(s::replace_slice('abcdef', 'x', 2, 1000), 'abx');
		$this->eq(s::replace_slice('abcdef', 'x', 1000), 'abcdefx');
		$this->eq(s::replace_slice('abcdef', 'x', 1000, 1003), 'abcdefx');
		$this->eq(s::replace_slice('abcdef', 'x', 1000, 3), 'abcdefx');
		$this->eq(s::replace_slice('abcdef', 'x', 2, 2), 'abxcdef');
		$this->eq(s::replace_slice('abcdef', 'x', -2), 'abcdx');
		$this->eq(s::replace_slice('abcdef', 'x', -4, 4), 'abxef');
		$this->eq(s::replace_slice('abcdef', 'x', -1000), 'x');
		$this->eq(s::replace_slice('abcdef', 'x', -1000, -100), 'xabcdef');
		$this->eq(s::replace_slice('abcdef', 'x', -7, -4), 'xcdef');
		$this->eq(s::replace_slice('', 'x', 2, 4), 'x');
	}

	public function test_insert() {
		$this->eq(s::insert('abcdef', 'x', 2), 'abxcdef');
		$this->eq(s::insert('abcdef', 'x', -2), 'abcdxef');
		$this->eq(s::insert('abcdef', 'x', 100), 'abcdefx');
		$this->eq(s::insert('abcdef', 'x', -100), 'xabcdef');
		$this->eq(s::insert('', 'x', 5), 'x');
	}

	public function test_pad() {
		$this->eq(s::pad('x', 5), '  x  ');
		$this->eq(s::pad('x', 9, 'yy'), 'yyyyxyyyy');
	}

	public function test_lpad() {
		$this->eq(s::lpad('x', 5), '    x');
		$this->eq(s::lpad('x', 6, 'yz'), 'yzyzyx');
	}

	public function test_rpad() {
		$this->eq(s::rpad('x', 5), 'x    ');
		$this->eq(s::rpad('x', 6, 'abc'), 'xabcab');
	}

	public function test_wrap() {
		$this->eq(s::wrap('abcdef', 2), "ab\ncd\nef");
		$this->eq(s::wrap('abcdef', 10), 'abcdef');
		$this->eq(s::wrap('abcdefg', 3, 'x'), 'abcxdefxg');
	}

	public function test_repeat() {
		$this->eq(s::repeat('x', 5), 'xxxxx');
		$this->eq(s::repeat('x', 0), '');
		$this->eq(s::repeat('abc', 2), 'abcabc');
	}

	public function test_reverse() {
		$this->eq(s::reverse('abcdef'), 'fedcba');
		$this->eq(s::reverse(''), '');
	}

	public function test_starting_with() {
		$this->eq(s::starting_with('abcdef', 'cd'), 'cdef');
		$this->eq(s::starting_with('abcdef', ''), 'abcdef');
		$this->eq(s::starting_with('abcdef', 'xyz'), null);
		$this->eq(s::starting_with('aaxaaxaa', 'x'), 'xaaxaa');
	}

	public function test_istarting_with() {
		$this->eq(s::istarting_with('abCdef', 'cd'), 'Cdef');
	}

	public function test_rstarting_with() {
		$this->eq(s::rstarting_with('aaxaa', 'x'), 'xaa');
		$this->eq(s::rstarting_with('aaxaaxaa', 'x'), 'xaa');
		$this->eq(s::rstarting_with('abcdef', 'x'), null);
	}

	public function test_starting_with_chars() {
		$this->eq(s::starting_with_chars('aaxxaa', 'x'), 'xxaa');
		$this->eq(s::starting_with_chars('aayxaa', 'xy'), 'yxaa');
	}

	public function test_before() {
		$this->eq(s::before('abcdef', 'c'), 'ab');
		$this->eq(s::before('abcdef', 'x'), null);
		$this->eq(s::before('abcdef', 'de'), 'abc');
		$this->eq(s::before('abcdef', ''), '');
	}

	public function test_ibefore() {
		$this->eq(s::ibefore('aBcDef', 'cde'), 'aB');
		$this->eq(s::ibefore('abCdEf', 'x'), null);
	}

	public function test_words() {
		$this->eq(s::words('these are words'),
			array('these', 'are', 'words'));
		$this->eq(s::words("  these   are\n\twords  \n"),
			array('these', 'are', 'words'));
		$this->eq(s::words(''), array());
		$this->eq(s::words("  \tabc  \t\n  ", "\n\t"),
			array("\tabc", "\t\n"));
	}
}

?>
