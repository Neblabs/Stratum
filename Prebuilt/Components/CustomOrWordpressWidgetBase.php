<?php

namespace Stratum\Prebuilt\Component;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;
use Stratum\Original\Presentation\Widgets\Widget;

Abstract Class CustomOrWordpressWidgetBase extends Component
{
    protected $customWidgetsPrefix = '';

    protected abstract function widgetTemplate();
#   protected abstract function wordpressWidgetData(); 
    protected abstract function customWidgetData();

    public function load(PartialView $view)
    {
        return $view->from($this->widgetTemplate())->with($this->chooseWidgetData());
    }

    protected function chooseWidgetData()
    {
        if ($this->isWordpressWidget()) {
            return $this->wordpressWidgetData();
        }

        return $this->customWidgetData();
    }

    protected function isWordpressWidget()
    {
        $this->widget = $this->bindedData;

        return strpos($this->widget->wordpressWidget->id_base, $this->customWidgetsPrefix) !== 0;
    }

    protected function wordpressWidgetData()
    {
        return [
            'widget' => $this->createWordpressWidgetData(),
        ];
    }

    protected function createWordpressWidgetData()
    {
        $this->widget->wordpressWidget->stratumData = $this->widget->widgetData;
        (string) $title = $this->widget->title;
        (object) $widget = $this->createWidgetObject();
        $widget->title = $title;
        return $widget;
    }

    protected function createWidgetObject()
    {
        return new Widget($this->widget->wordpressWidget);
    }
}







