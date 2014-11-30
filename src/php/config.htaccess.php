DefaultLanguage en-US
AddDefaultCharset utf-8
ServerSignature Off
RewriteEngine On
DirectorySlash Off
Options -Indexes
IndexIgnore *
RewriteBase <?= dirname(dirname(__DIR__)) . '/build/' . (config::is_production() ? 'prod' : 'dev') . '/' ?>

RewriteCond %{REQUEST_FILENAME} !-f [OR]
RewriteCond %{REQUEST_FILENAME} !^.*?/(css|js|assets)/[^/]+$
RewriteRule .* index.php [L]
