# Set default language and encoding
DefaultLanguage en-US
AddDefaultCharset utf-8

<?php if(!$config->get('show_server', false)): ?>
# Hide the server signature on default error pages
ServerSignature Off

<?php endif; ?>
# Do not correct missing slashes for directories
DirectorySlash Off

# Do not serve indexes
Options -Indexes
IndexIgnore *

# Routing
RewriteEngine On
<?php if($config->has('document_root')): ?>
RewriteCond %{REQUEST_FILENAME} !-f [OR]
RewriteCond %{REQUEST_FILENAME} !^<?= $config->document_root ?>/(?:favicon\.ico|robots\.txt|.*\.(?:css|js)|assets/.*)$
<?php endif; ?>
RewriteRule ^ index.php [L]
