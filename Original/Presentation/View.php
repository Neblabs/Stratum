<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Establish\Environment;
use Stratum\Original\HTTP\Cleaner\BufferCleaner;
use Stratum\Original\Presentation\Balance\Cache\ViewCache;
use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;
use Stratum\Original\Presentation\Balance\Flusher;
use Stratum\Original\Presentation\EOMWriter;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class View extends PartialView
{
    protected $masterPage =  'Master.html';
    protected $canBeCached = true;

    public function __construct()
    {
        $this->elementManagersQueue = new ElementManagersQueue;
        $this->viewCache = new ViewCache($this->variables);
    }

    public function setHeadView(PartialView $headView)
    {
        $this->headView = $headView;
    }

    public function cannotBeCached()
    {
        $this->canBeCached = false;
    }

    public function load()
    {
        if (Environment::is()->production() and $this->viewCache->viewIsCached() and $this->canBeCached) {
            $this->cleanBuffers();
            
            return $this->viewCache->loadCachedView();
        }

        $this->writeFirstDocumentChunckForPerformance();

        $this->variables['stratumPartialView'] = $this->requestedPartialView();
        $this->variables['canBeCached'] = $this->canBeCached;

        $this->mainViewElements();

        $this->writeLastLineOfDocument();
    }

    public function setElementManagersQueue(ElementManagersQueue $elementManagersQueue)
    {
        $this->elementManagersQueue = $elementManagersQueue;
    }

    public function setMasterPagePath($masterPagePath)
    {
        $this->masterPage = $masterPagePath;
    }

    protected function mainViewElements()
    {
        (object) $mainView = (new PartialView)->from($this->masterPage);

        $mainView->variables = $this->variables;

        (object) $mainViewElements = $mainView->elements();

        $this->elementManagersQueue->executeManagerTasks();

        return $mainViewElements;
    }

    protected function requestedPartialView()
    {
        (object) $requestedPartialView = (new PartialView)->from($this->htmlPath);

        $requestedPartialView->variables = $this->variables;
    
        return $requestedPartialView;
    }

    protected function writeFirstDocumentChunckForPerformance()
    {
        
        $this->cleanBuffers();
        $this->printDocType();
        $this->printOpeningHTMLTag();

        Flusher::flush();

        (object) $headElement = $this->headView->elements()->first();
        
        (object) $EOMWriter = new EOMWriter($headElement);

        (object) $ElementManagersQueue = new ElementManagersQueue;

        $ElementManagersQueue->executeManagerTasks();
        $ElementManagersQueue->clearQueue();

        $EOMWriter->render();
        
        Flusher::flush();

        ViewCacheWriter::addTopLevelNodesToQueue($headElement);
        

    }

    protected function cleanBuffers()
    {
        $bufferCleaner = new BufferCleaner;

        $bufferCleaner->cleanBuffersIfFull();
    }

    protected function printDocType()
    {
        print '<!DOCTYPE html>' . PHP_EOL;
    }

    protected function printOpeningHTMLTag()
    {
        (string) $language = function_exists('get_language_attributes')? get_language_attributes() : '';
        print "<html {$language}>";
    }

    protected function writeLastLineOfDocument()
    {
        print '</html>';
    }

}