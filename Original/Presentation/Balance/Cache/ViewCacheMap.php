<?php

namespace Stratum\Original\Presentation\Balance\Cache;

use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\Request;
use Stratum\Original\Presentation\Balance\Map\HighPerformantMap;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

Class ViewCacheMap
{
    protected static $viewCacheMap;
    protected $generatedFileName;

    public function __construct()
    {
        $this->requireMapOnce();
        $this->map = new HighPerformantMap(static::$viewCacheMap);
        $this->request = new GETRequest(SymfonyRequest::createFromGlobals());
    }

    public function aViewForTheCurrentRequestExists()
    {
        return $this->map->exists($this->uri());
    }

    public function cachedFileNameForCurrentRequest()
    {
        return $this->map->get($this->uri());
    }

    public function generatedFileName()
    {
        if (empty($this->generatedFileName)) {
            $this->generateFileName();
        }

        return $this->generatedFileName;
    }

    public function saveMap()
    {
        if ($this->aViewForTheCurrentRequestExists()) {
            $this->map->remove($this->uri());
        }

        $this->map->add([
            'key' => $this->uri(),
            'value' => $this->generatedFileName()
        ]);

        $this->updateMapFile();
    }

    public function clearMap()
    {
        $this->map->clear();

        $this->updateMapFile();
    }

    protected function updateMapFile()
    {
        file_put_contents($this->viewCacheMapFileName(), $this->map->export());
    }

    protected function generateFileName()
    {
        (string) $currentDirectory = $this->currentDirectory();
        (array) $letters = range('a', 'z');

        shuffle($letters);

        (string) $fileName = implode('', $letters) . random_int(10000000000, 99999999999) . '.php';

        $this->generatedFileName = "{$currentDirectory}/$fileName";
    }

    protected function currentDirectory()
    {
        (object) $finder = new Finder;
        (string) $viewCacheDirectory = STRATUM_ROOT_DIRECTORY . '/Storage/Balance/Cache/Views/Files';

        $finder->directories()->in($viewCacheDirectory)->sortByName();

        (array) $directories = array_values(iterator_to_array($finder));
        (object) $currentDirectory = $directories[count($directories) - 1];

        (object) $fileSystemIterator = new \FileSystemIterator($currentDirectory->getRealPath());
        (integer) $numberOfFilesInDirectory = iterator_count($fileSystemIterator);

        if ($numberOfFilesInDirectory >= 100) {
            (string) $nextDirectory = $this->nextDirectoryName($currentDirectory);
            (string) $absoluteDirectoryPath = "{$viewCacheDirectory}/$nextDirectory";
            mkdir($absoluteDirectoryPath);

            return $absoluteDirectoryPath;
        } else {
            return $currentDirectory->getRealPath();
        }

    }

    protected function nextDirectoryName(SplFileInfo $directory)
    {
        (string) $fileName = $directory->getFileName();
        (string) $directoryLetter = substr($fileName, 0, 1);
        ++$directoryLetter;

        return $directoryLetter . $directoryLetter;

    }

    protected function requireMapOnce()
    {
        if (empty(static::$viewCacheMap)) {
            static::$viewCacheMap = file_get_contents($this->viewCacheMapFileName());
        }
    }

    protected function viewCacheMapFileName()
    {
        return STRATUM_ROOT_DIRECTORY . '/Storage/Balance/Cache/Views/ViewCacheMap.stratum.map';
    }

    protected function uri()
    {
        return $this->request->http->URL->uri;
    }
}