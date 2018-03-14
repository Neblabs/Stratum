<?php

namespace Stratum\Original\Presentation\Balance\Cache;

use Stratum\Original\Presentation\Balance\Cache\Writer\ComponentCacheWriter;
use Symfony\Component\Finder\Finder;

Class ComponentCache
{
    protected $componentName;
    protected $componentParameter;
    protected $componentContent;

    public function __construct($componentName = null, $componentParameter = null)
    {
        $this->componentName = $componentName;
        $this->componentParameter = $this->cleanParameter($componentParameter);

        $this->componentCacheMap = new ComponentCacheMap($componentName, $this->componentParameter);
    }

    public function setComponentContent($componentContent)
    {
        $this->componentContent = $componentContent;
    }

    public function componentIsCached()
    {
        return $this->componentCacheMap->componentExistsInCache();
    }

    public  function cachedComponentContent()
    {
        return file_get_contents($this->componentCacheMap->fileNameForCachedComponent());
    }

    public function saveToCache()
    {
        (object) $componentCacheWriter = new ComponentCacheWriter([
            'componentName' => $this->componentName,
            'componentParameter' => $this->componentParameter,
            'componentContent' => $this->componentContent
        ]); 

        $componentCacheWriter->write();
    }

    public function clearCache()
    {
        $this->componentCacheMap->resetCacheMap();

        (object) $finder = new Finder;
        (string) $componentsCacheDirectory = STRATUM_ROOT_DIRECTORY . '/Storage/Balance/Cache/Components';
        $finder->files()->in($componentsCacheDirectory)->depth('== 0');

        foreach ($finder as $file) {
            unlink($file->getRealPath());
        }

        
    }

    public function clearCacheMap()
    {
        $this->componentCacheMap->resetCacheMap();
    }


    protected function cleanParameter($parameter)
    {
        if (is_scalar($parameter)) {
            return $parameter;
        }

        return '';
    }






}