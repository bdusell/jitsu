<?php

/* Debug switch. Set to true in development mode, false in production mode. */
$DEBUG = true;

/* A prefix for all relative URLs used by the site. For example, if set to
'dev1', the index of this site is accessed through the URL
<server-address>/dev1/index.php. */
$BASE_DIR = '';

/* Access logging switch. If true, all HTTP requests to PHP scripts including
'init.php' will be logged. */
$ACCESS_LOGGING = true;
$ACCESS_LOG_DATABASE = 'access_logs';
$ACCESS_LOG_USER     = 'access_log_user';
$ACCESS_LOG_PASSWORD = 'access_log_password';
$ACCESS_LOG_TABLE    = 'access_logs';

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

?>
