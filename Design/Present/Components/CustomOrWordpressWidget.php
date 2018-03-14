<?php

namespace Stratum\Custom\Component;

use Stratum\CoreBox\Widgets\Widget;
use Stratum\Custom\Model\MYSQL;
use Stratum\Prebuilt\Component\CustomOrWordpressWidgetBase;

Class CustomOrWordpressWidget extends CustomOrWordpressWidgetBase
{
    protected $customWidgetsPrefix = '';

    protected function widgetTemplate()
    {
        /*
            Must return a template file path relative to the /Views Directory.
            You may use CustomOrWordpressWidgetBase::isWordpressWidget() to determine the best path for the current widget.

            Note: CustomOrWordpressWidgetBase::isWordpressWidget() uses Self::$customWidgetsPrefix property. (see line: 11)
         */
    }

    protected function customWidgetData()
    {
        return [];
    }
}