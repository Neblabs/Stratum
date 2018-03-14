<?php

namespace Stratum\Prebuilt\Component;

use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;
use Stratum\Original\Presentation\Widgets\Widget;

Class Widgets extends Component
{
    public function load(PartialView $view)
    {
        return $view->from('Original/Widgets.html')->with([
            'widgets' => $this->widgets()
        ]);
    }

    protected function widgets()
    {
        global $wp_registered_widgets;

        (string) $sidebarName = $this->bindedData;
        (array) $sidebars = get_option('sidebars_widgets');

        if (empty($wp_registered_widgets) || ($sidebars == '')) {
            return [];
        }

        

        $sidebars = is_integer(array_keys($sidebars)[0])? $sidebars[0] : $sidebars;

        (array) $requestedSideBarWidgets = $sidebars[$sidebarName];

        (array) $requestedWidgetObjects = [];

        foreach ((array) $requestedSideBarWidgets as $widgetId) {
            (object) $widget = $wp_registered_widgets[$widgetId]['callback'];

            if (is_array($widget)) {
                $widget = $widget[0];
            }

            (array) $dataForAllWidgetsOfTheSameType = get_option("widget_{$widget->id_base}");
            $WidgetNumber = preg_replace('/((-*)([0-9]*)([A-Za-z_]+)([0-9]*)(-*))/', '', $widgetId);
            

            (integer) $widget->number = (integer) trim($WidgetNumber, '-');

            $widget->stratumData = $dataForAllWidgetsOfTheSameType[$widget->number];            

            $requestedWidgetObjects[] = new Widget($widget);

        }

        return $requestedWidgetObjects;
    }
}