<?php $e = config::show_errors() ? '1' : '0'; ?>
short_open_tag = 1
asp_tags = 0
zend.script_encoding = "UTF-8"
arg_separator.output = "&"
arg_separator.input = "&;"
html_errors = 0
register_argc_argv = 0
auto_globals_jit = 1
enable_post_data_reading = 1
default_mimetype = "text/html"
default_charset = "UTF-8"
expose_php = <?= $e ?>

display_startup_errors = <?= $e ?>

report_memleaks = <?= $e ?>

track_errors = <?= $e ?>
