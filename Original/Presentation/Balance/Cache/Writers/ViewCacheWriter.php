<?php

namespace Stratum\Original\Presentation\Balance\Cache\Writer;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\Writer\EOMNodeWriter;
use Stratum\Original\Presentation\Balance\Cache\ViewCacheMap;
use Stratum\Original\Establish\Environment;
use Symfony\Component\Finder\Finder; 

Class ViewCacheWriter
{
    protected static $nodesQueue = [];
    protected static $writenComponents = [];

    public static function setWrittenComponent(Component $component)
    {
        static::$writenComponents[] = spl_object_hash($component);
    }

    public static function componentHasAlradyBeenWritten(Component $component)
    {
        return in_array(spl_object_hash($component), static::$writenComponents);
    }

    public static function addTopLevelNodesToQueue($nodeOrComponent)
    {
        (string) $Component = Component::className();
        if ($nodeOrComponent instanceof $Component) {
            (string) $GroupOfNodes = GroupOfNodes::className();
            (array) $componentElements = ($nodeOrComponent->elements() instanceof $GroupOfNodes)? $nodeOrComponent->elements()->asArray() : [$nodeOrComponent->elements()];
            static::$nodesQueue = array_merge(static::$nodesQueue, $componentElements);
        } else {
            static::$nodesQueue[] = $nodeOrComponent;
        }
    }
    public function __construct()
    {
        $this->viewCacheMap = new ViewCacheMap;
    }

    public function clearAll()
    {
        $this->viewCacheMap->clearMap();
        $this->removeCacheDirectoriesAndFiles();
    }

    public function write()
    {
        if (!Environment::is()->production()) {
            return;
        }

        (string) $Element = Element::className();
        (string) $cachedView = $this->namespaces() . PHP_EOL .
                               '<!DOCTYPE html>' . PHP_EOL .
                               $this->openingHTMLTag();

        foreach (static::$nodesQueue  as $node) {

            (object) $writer = EOMNodeWriter::createFrom($node);
            $writer->setIsCachingView(true);

            if (($node instanceof $Element) && $node->is('body')) {
                $cachedView.= $writer->getOpeningTag();
                (object) $bodyWriter = $writer; 
            } else {
                $cachedView.= $writer->get();
            }

            

        }

        $cachedView.= $bodyWriter->getClosingTag() . 
                      '</html>';

        $this->saveCachedView($cachedView);
    }

    protected function saveCachedView($cachedView)
    {
        (string) $generatedCacheFile = $this->viewCacheMap->generatedFileName();
        $this->viewCacheMap->saveMap();

        file_put_contents($generatedCacheFile, $cachedView);
    }

    protected function openingHTMLTag()
    {
        (string) $language = function_exists('get_language_attributes')? get_language_attributes() : '';
        return "<html {$language}>";
    }

    protected function namespaces()
    {
        return "<?php

use Stratum\Original\Presentation\Balance\Flusher; 
use Stratum\Original\Presentation\Writer\EOMNodeWriter; 

?>

";
    }

    protected function removeCacheDirectoriesAndFiles()
    {
        (object) $finder = new Finder;
        (string) $viewCacheDirectory = STRATUM_ROOT_DIRECTORY . '/Storage/Balance/Cache/Views/Files';

        $finder->directories()->in($viewCacheDirectory)->depth('== 0');

        foreach ($finder as $directory) {
            array_map('unlink', glob("{$directory->getRealPath()}/*.*"));
            rmdir($directory->getRealPath());
        }

        mkdir("$viewCacheDirectory/aa");
        
    }


}