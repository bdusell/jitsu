<?php $config = \DemoApp\Application::config(); ?>
DefaultLanguage en-US
AddDefaultCharset utf-8
<?php if(!$config->show_server): ?>
ServerSignature Off
<?php endif; ?>
RewriteEngine On
DirectorySlash Off
Options -Indexes
IndexIgnore *
RewriteCond %{REQUEST_FILENAME} !-f [OR]
RewriteCond %{REQUEST_FILENAME} !^<?= $config->document_root ?>/(robots\.txt|css/.*\.css|js/.*\.js|assets/.*)$
RewriteRule ^ index.php [L]
