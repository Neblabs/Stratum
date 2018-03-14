<?php

use Stratum\Original\HTTP\Registrator\GET;
use Stratum\Original\HTTP\Registrator\POST;

GET::request()->to('/')
              ->use('HomeController.show');
               



