<?php

namespace Stratum\Original\Presentation\Balance\Cache;

use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;

Class ViewCache
{
    protected $viewCacheMap;
    protected $variables;

    public function __construct(array &$variables = null)
    {
        $this->viewCacheMap = new ViewCacheMap;
        $this->variables = $variables;
    }

    public function viewIsCached()
    {
        return $this->viewCacheMap->aViewForTheCurrentRequestExists();
    }

    public function loadCachedView()
    {
        require_once $this->viewCacheMap->cachedFileNameForCurrentRequest();
    }

    public function clearCache()
    {
        (new ViewCacheWriter)->clearAll();
    }

    public function clearCacheMap()
    {
        $this->viewCacheMap->clearMap();
    }

}