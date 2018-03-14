<?php

namespace Stratum\Extend\Saver\Cache;


Class SubCategories extends Cache
{
    protected $parentCategoryId;
    protected $subCategoriesOf = [];
    protected $cachedArrayFileLocation = 'Categories/Subcategories';


    public function __construct($parentCategoryId)
    {
        $this->parentCategoryId = $parentCategoryId;
        $this->subCategoriesOf[$parentCategoryId] = [];
    }

    public function save()
    {
        (array) $WpTermCategoryObjects = get_categories([
            'hide_empty' => false,
            'child_of' => $this->parentCategoryId,
            'number' => 8
        ]);

        foreach($WpTermCategoryObjects as $WpTermCategoryObject) {

            (object) $category = \Stratum\Custom\Model\Cache\Category::createFromWpTermObject($WpTermCategoryObject);

            $this->addCategoryToArray($category);

        }

        $this->serializeMergeAndStoreArrayInCache();
    }

    protected function addCategoryToArray(Category $category)
    {
        $this->subCategoriesOf[$this->parentCategoryId][] = $category;
    }

    protected function serializeMergeAndStoreArrayInCache()
    {
        (object) $cachedArray = $this->cachedArray();

        $cachedArray[$this->parentCategoryId] = $this->subCategoriesOf[$this->parentCategoryId];

        $this->saveCachedArray($cachedArray);
    }












}