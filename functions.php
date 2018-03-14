<?php

use Stratum\Original\HTTP\Wordpress\TemplateIncluder;
use Stratum\Original\HTTP\Wordpress\WordpressRouterHandler;
use Stratum\Original\Installer\InstallationManager;
use Stratum\Original\Installer\SetupManager;
use Stratum\Original\WordPress\StratumWordpressCompatibiltyManager;

require_once 'Bootstrap.php';

(object) $StratumWordpressCompatibiltyManager = new StratumWordpressCompatibiltyManager;

$StratumWordpressCompatibiltyManager->manageCompatibilty();