<?php

namespace Stratum\Prebuilt\Component;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;

Class WpFooter extends Component
{
    protected $isDynamicComponent = true;
    
    public function load(PartialView $view)
    {
        return $view->from('Original/WpFooter.html')->with([
            'content' => $this->echoedContentFromWordpressWpHeadHook()
        ]);
    }

    protected function echoedContentFromWordpressWpHeadHook()
    {
        ob_start();

        wp_footer();
        (string) $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }
}