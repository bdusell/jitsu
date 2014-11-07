<?php

/* Debug switch. Set to true in development mode, false in production mode. */
$DEBUG = false;

/* A prefix for all relative URLs used by the site. For example, if set to
'foobar', the index of this site is accessed through the URL
<server>/foobar/index.php. */
$BASE_DIR = '';

$SOURCE_DIR = '../../back/php/';

/* The name of your website, used in page titles by default. */
$SITE_NAME = 'Example Website Name';

/* The protocol used by your site (http or https). */
$PROTOCOL = 'http';

/* Your site's subdomain. */
$SUBDOMAIN = 'www';

/* Your site's domain name. */
$DOMAIN = 'example.com';

/* Your site's contact e-mail. */
$EMAIL = "admin@$DOMAIN";

/* Names of active modules (see lib/ directory). */
$MODULES = array('core', 'sql');

?>
