<?php

namespace Stratum\Extend\Saver\Cache\Helper;

use Stratum\Custom\Finder\Cache\MenuItems;
use Stratum\Custom\Model\Cache\Category;
use Stratum\Extend\Saver\Cache\SubCategories;

Class SubCategoriesInMenuItems extends SubCategory
{

    public function save()
    {
        (array) $menus = [
            'main-navigation-menu',
            'secondary-navigation-menu',
            'quick-menu'
        ];

        foreach($menus as $menu) {
            (array) $menuItems = MenuItems::inMenu($menu)->find();
        
            foreach($menuItems as $menuItem) {

                if ($menuItem->isCategory()) {
                    $this->saveSubCategoryWithParentId($menuItem->typeId);
                }
            }
        }

    }

}







