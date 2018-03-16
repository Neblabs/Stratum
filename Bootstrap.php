<?php

use Stratum\Original\Autoloader\Autoloader;
use Stratum\Original\Establish\Environment;

ini_set('memory_limit', -1);

if (!defined('STRATUM_ROOT_DIRECTORY')) define('STRATUM_ROOT_DIRECTORY', __DIR__);

if (!defined('ABSPATH')) define('ABSPATH', str_replace('wp-content/themes/Corebox', '', __DIR__));

require 'vendor/autoload.php';

require_once STRATUM_ROOT_DIRECTORY . '/Original/Utilities/Compatibilty/CompatibleComponents.php';
require_once STRATUM_ROOT_DIRECTORY . '/Original/Autoloader/Autoloader.php';

Autoloader::register();

//f (Environment::is()->production()) {

//   require_once STRATUM_ROOT_DIRECTORY . '/Storage/Autoloader/CachedClasses.php';
//

register_shutdown_function(function(){
    if (!headers_sent()) {
        header_remove('Transfer-Encoding');
    }
});


#to do: move it out
set_error_handler(function($er, $e){
    (boolean) $fileCouldNotBeWritten = strpos(strtolower($e), 'permission denied') !== false;

    if ($fileCouldNotBeWritten) {
        exit('Stratum requires your WordPress root directory and the subdirectories to be writable.');
    }
}, E_WARNING);


