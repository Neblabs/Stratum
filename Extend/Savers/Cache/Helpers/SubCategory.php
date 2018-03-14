<?php

namespace Stratum\Extend\Saver\Cache\Helper;

use Stratum\Custom\Model\Cache\Category;
use Stratum\Extend\Saver\Cache\Cache;
use Stratum\Extend\Saver\Cache\SubCategories;

Abstract Class SubCategory extends Cache
{
    protected function saveSubCategoryWithParentId($parentCategoryId)
    {
        (object) $subCategoriesSaver = new SubCategories($parentCategoryId);
        $subCategoriesSaver->save();
    }   

    public function __construct()
    {

    }
}