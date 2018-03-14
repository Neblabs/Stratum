<?php
return trim("<?php
use Stratum\Original\Installer\InstallationManager;

define('ABSPATH',  __DIR__ . '/');

require_once 'wp-content/themes/{$themeDirectoryName}/index.php';
");