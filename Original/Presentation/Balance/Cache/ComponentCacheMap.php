<?php

namespace Stratum\Original\Presentation\Balance\Cache;

Class ComponentCacheMap
{
    protected $componentName;
    protected $componentParameter;
    protected $componentFileName;
    protected static $ComponentCacheMap = [];

    public function __construct($componentName, $componentParameter)
    {
        $this->componentName = $componentName;
        $this->componentParameter = $componentParameter;

        $this->loadArrayOncePerHTTPRequest();
    }

    public function componentExistsInCache()
    {
        return isset(static::$ComponentCacheMap[$this->componentName][$this->parameter()]);
    }

    public function updateCacheMap()
    {
        static::$ComponentCacheMap[$this->componentName]
                                  [$this->parameter()] = $this->generatedComponentFileName();

        $this->saveArray();
    }

    public function resetCacheMap()
    {
        static::$ComponentCacheMap = [];

        $this->saveArray();
    }

    protected function saveArray()
    {
        file_put_contents($this->cacheMapFile(), $this->arrayAsPhpFile());
    }

    protected function arrayAsPhpFile()
    {
        (string) $array = var_export(static::$ComponentCacheMap, $return = true);

        return "<?php \n
            return {$array};
        ";
    }

    protected function parameter()
    {
        return (!empty($this->componentParameter))? $this->componentParameter : '__NULL__';
    }

    public function generatedComponentFileName()
    {
        if (empty($this->componentFileName)) {
            $this->componentFileName = $this->generategeneratedComponentFileName();
        }

        return $this->componentFileName;
    }

    public function fileNameForCachedComponent()
    {
        (string) $componentFileName = static::$ComponentCacheMap[$this->componentName][$this->parameter()];

        return STRATUM_ROOT_DIRECTORY . "/Storage/Balance/Cache/Components/{$componentFileName}";
    }

    protected function generategeneratedComponentFileName()
    {
        return $this->componentName . random_int(1000000000, 9999999999) . '.html';
    }

    protected function loadArrayOncePerHTTPRequest()
    {
        if (empty(static::$ComponentCacheMap)) {
            static::$ComponentCacheMap = require $this->cacheMapFile();
        }
    }

    protected function cacheMapFile()
    {
        return STRATUM_ROOT_DIRECTORY . '/Storage/Balance/Cache/Components/Map/ComponentsCacheMap.php';
    }


}