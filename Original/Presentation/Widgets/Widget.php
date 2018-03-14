<?php

namespace Stratum\Original\Presentation\Widgets;

use WP_Widget;

Class Widget
{
    public $title;
    public $subtitle;
    public $widgetData;

    public $wordpressWidget;

    public function __construct(WP_Widget $wordpressWidget)
    {
        $this->wordpressWidget = $wordpressWidget;
        $this->widgetData = $wordpressWidget->stratumData;
        $this->title = isset($wordpressWidget->stratumData['title'])? $wordpressWidget->stratumData['title'] : '';
        $this->subtitle = isset($wordpressWidget->stratumData['subTitle'])? $wordpressWidget->stratumData['subTitle'] : $wordpressWidget->stratumData['subtitle'];
        
        //$this->removeTitleSoWordpressDoesNotOutPutIt();


    }

    public function hasTitle()
    {
        return !empty($this->title);
    }

    public function hasSubtitle()
    {
        return !empty($this->subtitle);
    }

    public function content()
    {   
        ob_start();

        $this->wordpressWidget->widget([
            'before_widget' => '',
            'before_title' => '',
            'after_title' => '',
            'after_widget' => ''
        ],
        $this->widgetData
        );

        (string) $widgetContent = ob_get_contents();

        ob_end_clean();

        return $widgetContent;
    }

    protected function removeTitleSoWordpressDoesNotOutPutIt()
    {
        $this->widgetData['title'] = '';
    }
}





