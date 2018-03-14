<?php

use Stratum\Original\HTTP\Registrator\Wordpress;

Wordpress::request()->to('home')
                    ->use('WordPressController.home');
