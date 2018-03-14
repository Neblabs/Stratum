<?php

use Stratum\Original\Establish\DatabaseEstablisher;

/*

    By default, the values defined in wp-config.php will be used.

    Values defined here will override those of wp-config.php

*/  

return new DatabaseEstablisher([
    'name' => '', #'wordpress',
    'host' =>  '127.0.0.1', #'127.0.0.1',
    'username' => '', #'root',
    'password' => null #''
]);