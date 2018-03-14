<?php
return 
"<?php

namespace Stratum\Custom\Component;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;

Class {$this->typeName} extends Component
{
    public function load(PartialView \$view)
    {
        return \$view->from('{$this->typeName}.html');
    }
}";