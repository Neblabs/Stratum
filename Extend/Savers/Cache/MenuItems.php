<?php

namespace Stratum\Extend\Saver\Cache;

use Stratum\Custom\Domain\MenuItem;
use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Extend\Saver\Cache\Helper\SubCategoriesInMenuItems;

Class MenuItems extends Cache
{
    protected $nonHierarchicalMenus = [];
    protected $hierarchicalMenus = [];
    protected $menuLocation;
    protected $cachedArrayFileLocation = 'Menus/Menus';

    public static function saveAll()
    {
        (object) $mainNavigationMenuItems = new MenuItems('main-navigation-menu');
        (object) $secondaryNavigationMenuItems = new MenuItems('secondary-navigation-menu');
        (object) $quickNavigationMenuItems = new MenuItems('quick-menu');
    
        $mainNavigationMenuItems->save();
        $secondaryNavigationMenuItems->save();
        $quickNavigationMenuItems->save();
    
        (object) $SubCategoriesInMenuItemsSaver = new SubCategoriesInMenuItems;
    
        $SubCategoriesInMenuItemsSaver->save();
    }
    public function __construct($menuLocation)
    {
        $this->generateMenuItemIdFromMenuLocation($menuLocation);
        $this->menuLocation = $menuLocation;

        $this->nonHierarchicalMenus[$menuLocation] = [];

    }

    public function save()
    {
        $menuitems = wp_get_nav_menu_items($this->menuItemId);

        (array) $menuitems = $menuitems !== false ? $menuitems : [];

        foreach ($menuitems as $menuitem) {

            (object) $menuitem = MenuItem::createFromWordpressPost($menuitem);

            $this->addMenuItemToNonHierarchicalMenu($menuitem);
            
        }

        $this->addMenusToHierarchicalMenusAndMoveSubmenusInsideTheirParent();

        $this->storeMenusToArray();
        
    }  

    protected function generateMenuItemIdFromMenuLocation($menuLocation)
    {
        (object) $option = Options::withName('theme_mods_Corebox')->find()->first()->value;
        (array) $themeLocations = unserialize(($option));

        $this->menuItemId = $themeLocations['nav_menu_locations'][$menuLocation];

    }

    protected function addMenuItemToNonHierarchicalMenu(MenuItem $menuItem)
    {
        $this->nonHierarchicalMenus[$this->menuLocation][] = $menuItem;
    }

    protected function addMenusToHierarchicalMenusAndMoveSubmenusInsideTheirParent()
    {
        foreach ($this->nonHierarchicalMenus[$this->menuLocation] as $menuItem) {
            
            if ($menuItem->isTopLevelMenu()) {
                $this->hierarchicalMenus[$this->menuLocation][] = $menuItem;
            } else {
                $this->addSubmenuToItsParentObject($menuItem);
            }
        }
    }

    protected function addSubmenuToItsParentObject(MenuItem $submenuItem)
    {
        foreach ($this->nonHierarchicalMenus[$this->menuLocation] as $menuItem) {         
            
            if ($submenuItem->isSubmenuOf($menuItem)) {
                $menuItem->addSubmenu($submenuItem);
            }
        } 
    }

    protected function storeMenusToArray()
    {
        (array) $cachedArrayOfMenus = $this->cachedArray();

        $cachedArrayOfMenus[$this->menuLocation] = $this->hierarchicalMenus[$this->menuLocation];
        
        $this->saveCachedArray($cachedArrayOfMenus);

    }




}