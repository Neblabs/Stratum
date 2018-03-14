<?php

namespace Stratum\Original\HTTP\Wordpress;

Class TemplateIncluder
{
    public function setIncludePath()
    {
        (integer) $lowPriority = 4;
        
        add_filter('template_include', [$this, 'setPlaceHolderFile'], $lowPriority);
    }

    public function setPlaceHolderFile()
    {
        return STRATUM_ROOT_DIRECTORY . '/Original/Stratum/Placeholder.php';
    }
}


