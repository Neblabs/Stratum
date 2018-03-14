<?php

use Stratum\Original\Establish\Environment;

/*
    It's a shame, but wordpress needs to be loaded in the global namespace.
 */

if ($ApplicationController->HTTPRouterFoundTheCorrectRoute()) {
    return;
}

$ApplicationController->WordpressController->setWordpressRouteHandler();

ob_start();

   
    
    if (!Environment::is()->testing()) {
        require_once ABSPATH . 'OriginalIndex.php';
    }


    (string) $outputContent = ob_get_contents();

    $ApplicationController->WordpressController->setOutputContent($outputContent);

//buffers are closed just before we send the view via Stratum\Original\HTTP\Response\HTML::sendResponse() or inside a shutdown callback function.
