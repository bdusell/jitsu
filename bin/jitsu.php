#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/vendor/jitsu/error/error.php';
\Jitsu\bootstrap(true);
require dirname(__DIR__) . '/vendor/autoload.php';

\Jitsu\Project::cli($argv);
