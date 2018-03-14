<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\Balance\Cache\ComponentCache;
use Stratum\Original\Presentation\Balance\Cache\ComponentCacheMap;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\ElementManagersQueue;

Class ComponentWriter
{
    protected $component;
    protected $componentElements;
    protected $writtenElements;

    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    public function setComponentElements(GroupOfNodes $componentElements)
    {
        $this->componentElements = $componentElements;
    }

    public function component()
    {
        return $this->component;
    }

    public function write()
    {
        (string) $componentContent = $this->get();

        print $componentContent;

        $this->saveCacheIfAvailable($componentContent);
    }

    public function get()
    {
        if (empty($this->writtenElements)) {
            (object) $componentElements = empty($this->componentElements)? $this->component->elements() : $this->componentElements;

            $this->executeManagers();
    
            (string) $Text = Text::className();
    
            if ($componentElements instanceof $Text) { $componentElements = [$componentElements]; }
    
            (string) $writtenElements = '';
    
            foreach ($componentElements as $node) {
                (object) $writer = EOMNodeWriter::createFrom($node);
    
                $writtenElements.= $writer->get();
            }

            $this->writtenElements = $writtenElements;
        }

        return $this->writtenElements;
    }

    public function writeToCache()
    {
        $this->saveCacheIfAvailable($this->get());
    }

    protected function saveCacheIfAvailable($componentContent)
    {

        if ($this->component->canBeCached()) {

            (object) $componentCache = new ComponentCache(
                    $this->component->name(),
                    $this->component->bindedData()
            );

            if (!$componentCache->componentIsCached()) {

                $componentCache->setComponentContent($componentContent);
    
                $componentCache->saveToCache();   
            }
        }
    }

    protected function executeManagers()
    {
        (object) $managersQueue = new ElementManagersQueue;

        $managersQueue->executeManagerTasks();

        $managersQueue->clearQueue();
    }
}