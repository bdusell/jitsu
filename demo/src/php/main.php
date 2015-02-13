<?php

use phrame\RequestUtil;
use phrame\ResponseUtil;

require_once dirname(dirname(__DIR__)) . '/vendor/phrame/functions/common.php';
require __DIR__ . '/autoload.php';

App::respond(RequestUtil::instance(), ResponseUtil::instance());

?>
