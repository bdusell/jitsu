<?php

use Jitsu\StringUtil;

function html($s) {
	return StringUtil::encodeHtml($s, true);
}

function htmlattr($s) {
	return StringUtil::encodeHtml($s, false);
}

function url($s) {
	return StringUtil::encodeUrl($s);
}
