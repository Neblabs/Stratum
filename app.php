<?php

use Stratum\CoreBox\CssManagement\PropertyValueCompiler;
use Stratum\Original\Autoloader\Autoloader;

define('STRATUM_ROOT_DIRECTORY', __DIR__);
define('WORDPRESS_ROOT_DIRECTORY', realpath('../../../'));

require 'vendor/autoload.php';

require_once STRATUM_ROOT_DIRECTORY . '/Original/Autoloader/autoloader.php';

$autoloader = new Autoloader;

Autoloader::register();









