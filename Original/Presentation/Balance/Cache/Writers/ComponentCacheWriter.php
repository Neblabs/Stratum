<?php

namespace Stratum\Original\Presentation\Balance\Cache\Writer;

use Stratum\Original\Presentation\Balance\Cache\ComponentCacheMap;
use Stratum\Original\Presentation\Writer\ComponentWriter;

Class ComponentCacheWriter
{
    protected $componentName;
    protected $componentParameter;
    protected $componentContent;

    protected static $cachableComponentsQueue = [];

    public static function addComponentToQueue(ComponentWriter $componentWriter)
    {
        static::$cachableComponentsQueue[] = $componentWriter;
    }

    public static function saveComponentsInQueue()
    {
        foreach (static::$cachableComponentsQueue as $key => $componentWriter) {
            (object) $ComponentCacheWriter = new Static([
                'componentName' => $componentWriter->component()->name(),
                'componentParameter' => $componentWriter->component()->bindedData(),
                'componentContent' => $componentWriter->get()
            ]);

            $ComponentCacheWriter->write();

            unset(static::$cachableComponentsQueue[$key]);
        }
    }

    public function __construct(array $componentData)
    {
        $this->componentName = $componentData['componentName'];
        $this->componentParameter = $componentData['componentParameter'];
        $this->componentContent = $componentData['componentContent'];

        $this->componentCacheMap = new ComponentCacheMap($componentName = $this->componentName, $componentParameter = $this->componentParameter);
    }

    public function write()
    {
        (string) $componentFileName = $this->componentCacheMap->generatedComponentFileName();

        $this->componentCacheMap->updateCacheMap();

        file_put_contents(
            STRATUM_ROOT_DIRECTORY . "/Storage/Balance/Cache/Components/{$componentFileName}", 
            $this->componentContent
        );
    }
}