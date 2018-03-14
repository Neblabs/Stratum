<?php 

namespace Stratum\Original\Autoloader;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Original\Establish\Environment;

Class Autoloader
{	
	protected $fullyQualifiedClassname;
	protected $topLevelNamespace;
	protected $NamespaceWithoutTopLevelNamespace;
	protected $singleClassName;
	protected $NamespaceWithoutStratumAndClassName;
	protected $subNamespaces = [];
	protected static $cachedNamespaces = [];
	protected static $cachedClassFileNames = [];
	protected static $nonCachableClasses;
	public $loadedFromCache = false;
	public $hasMapSet = false;
	protected $baseDirectory = '/';
	public $generatedPaths = [];
	protected $classDirectory = '/';
	protected $pathToClass = '/';
	protected $classFileExists = false;


	public static function register()
	{
		spl_autoload_register(['Stratum\Original\Autoloader\Autoloader', 'load']);
	}

	public static function load($class)
	{
		(new autoloader)->autoload($class);
	}

	public function autoload($class)
	{	
		$this->fullyQualifiedClassname = $class;

		if ($this->IsNotStratumNamespace()) return false;

		$this->attemptToLoadClass();
		
	}

	/**
	 * Checks if the top level namespace name is not 'stratum'.
	 */
	protected function IsNotStratumNamespace()
	{
		(integer) $stratumNamespacePosition = 0;
		(boolean) $stratumIsNotTheFirstWordInNamespace = strpos($this->fullyQualifiedClassname, 'Stratum') !== $stratumNamespacePosition;

		if ($stratumIsNotTheFirstWordInNamespace) {

			return true;

		}	

	}

	protected function attemptToLoadClass()
	{
		if ($this->classIsInCache()) {
			$this->loadCachedClassFile();

			return;
		}

		if ($this->namespaceIsNotInCache()) {
			
			$this->generateFullPathFromNamespace();

		}

		$this->loadClass();


	}

	protected function namespaceIsNotInCache()
	{
		
		(integer) $fullyQualifiedClassLenght = strlen($this->fullyQualifiedClassname);
		(string) $namespaceSeparator = '\\';
		(string) $stratumPosition = strlen('Stratum');
		(integer) $LastBackslashPosition = strrpos($this->fullyQualifiedClassname, $namespaceSeparator);
		(string) $negativeSingleClassNameLenght = ($fullyQualifiedClassLenght - $LastBackslashPosition) * -1;
		(string) $this->namespaceWithoutclassName = substr_replace($this->fullyQualifiedClassname, '', $negativeSingleClassNameLenght);

		$this->NamespaceWithoutStratumAndClassName = substr($this->namespaceWithoutclassName, $stratumPosition);
		
		(boolean) $namespaceIsNotInArray = !isset($this->cachedNamespaces()[$this->namespaceWithoutclassName]);
		
		$this->classDirectory = isset($this->cachedNamespaces()[$this->namespaceWithoutclassName]) ? $this->cachedNamespaces()[$this->namespaceWithoutclassName] : '/';
		(array) $fullyQualifiedNamespaceAsArray = explode('\\', $this->fullyQualifiedClassname);
		$this->singleClassName = $fullyQualifiedNamespaceAsArray[count($fullyQualifiedNamespaceAsArray) - 1];

		
		if ($namespaceIsNotInArray) {
			$this->namespaceIsInCache = false;
			return true;

		} else {
			$this->namespaceIsInCache = true;
			return false;
		}


	}

	protected function generateFullPathFromNamespace()
	{
		$this->generatePathsFromNamespace();

		foreach($this->generatedPaths as $path) {
			(string) $classDirectory = STRATUM_ROOT_DIRECTORY . "{$this->baseDirectory}{$path}" . DIRECTORY_SEPARATOR;
			(string) $pathToClass = "{$classDirectory}{$this->singleClassName}.php";

			

			if (file_exists($pathToClass)) {

				$this->classDirectory = $classDirectory;
				$this->pathToClass = $pathToClass;
				$this->classFileExists = true;
				$this->addNamespaceToCacheFile();

			}
			
		}
	}

	protected function generatePathsFromNamespace()
	{
		$this->setBaseDirectoryIfMapExisits();
		// generate corect namespaxe mapping using forecah and file exists
		(array) $namespaces = array_filter(explode('\\', $this->NamespaceWithoutStratumAndClassName));
		(array) $originalNamespaces = $namespaces;
		(array) $generatedDirectories = [];
		(array) $pluralAndSingularNamespaces = [];
		(integer) $numberOfNamespaces = count($namespaces);
		

		foreach($namespaces as $index => $namespace) {
			
			(array) $verfiedDirectories = $index !== 1 ? (implode(array_slice($namespaces, 0, $index -1), DIRECTORY_SEPARATOR) . '/') : '';
			
			(boolean) $directoryNameInPluralExists = is_dir(STRATUM_ROOT_DIRECTORY . "{$this->baseDirectory}{$verfiedDirectories}" . Inflector::Pluralize($namespace));


			(boolean) $directoryNameInSingularExists = is_dir(STRATUM_ROOT_DIRECTORY . "{$this->baseDirectory}{$verfiedDirectories}" . Inflector::Singularize($namespace));

			(boolean) $directoryNameAsIsExists = is_dir(STRATUM_ROOT_DIRECTORY . "{$this->baseDirectory}{$verfiedDirectories}" . $namespace);
			
			if ($directoryNameInPluralExists) {

				$namespaces[$index] = Inflector::Pluralize($namespace);

			} elseif ($directoryNameInSingularExists) {
				$namespaces[$index] = Inflector::Singularize($namespace);

			} elseif ($directoryNameAsIsExists) {
				$namespaces[$index] = $namespace;

			} else {
			
				$this->classDirectory = '/';
				
				break;
			}

			$this->classDirectory = implode($namespaces, DIRECTORY_SEPARATOR) . '/';

		}


		// $this->addToCacheFile
	}

	protected function setBaseDirectoryIfMapExisits()
	{
		(array) $DirectoryMaps = include STRATUM_ROOT_DIRECTORY . '/Original/Autoloader/BaseDirectories.php';

		foreach ($DirectoryMaps as $NamespaceMap => $linkedDirectory) {

			(integer) $baseNamespacePosition = 0;
			(boolean) $baseNamespaceIsInTheFirstPosition = strpos($this->namespaceWithoutclassName, $NamespaceMap) === $baseNamespacePosition;
			
			if ($baseNamespaceIsInTheFirstPosition) {

				(integer) $positionAfterBaseNamespace = strlen($NamespaceMap);
				
				$this->baseDirectory = "/{$linkedDirectory}" . DIRECTORY_SEPARATOR;
				$this->NamespaceWithoutStratumAndClassName = substr($this->namespaceWithoutclassName, $positionAfterBaseNamespace);
				$this->hasMapSet = true;
				
				break;
				
			}

			
			
		}

	}

	protected function addNamespaceToCacheFile()
	{
		
	}

	protected function loadClass()
	{

		$this->pathToClass = STRATUM_ROOT_DIRECTORY . "{$this->baseDirectory}{$this->classDirectory}{$this->singleClassName}.php";

		if (file_exists($this->pathToClass)) {

			
			require_once($this->pathToClass);

			if ($this->namespaceIsInCache) {
				$this->loadedFromCache = true;
			} else {
				$this->addPathToCacheFile();
			}

			$this->addFileNameToCachedClassesFile();
			$this->addFileContentsToCachedClasses();
			
		}
		
	}

	protected function loadCachedClassFile()
	{
		require_once $this->cachedClassFileNames()[$this->fullyQualifiedClassname];
	}

	protected function addPathToCacheFile()
	{

		static::$cachedNamespaces[$this->namespaceWithoutclassName] = "{$this->baseDirectory}{$this->classDirectory}";

		$cache = var_export($this->cachedNamespaces(), true);

		$phpFileThatReturnsArray = "<?php return {$cache};";

		file_put_contents(STRATUM_ROOT_DIRECTORY . '/Storage/Autoloader/CachedNamespaces.php', $phpFileThatReturnsArray);
		
	}


	protected function cachedNamespaces()
	{
		if (empty(static::$cachedNamespaces)) {
			static::$cachedNamespaces = include STRATUM_ROOT_DIRECTORY . '/Storage/Autoloader/CachedNamespaces.php';
		}

		return static::$cachedNamespaces;
	}

	protected function classIsInCache()
	{
		(array) $cachedClassFileNames = $this->cachedClassFileNames();

		return isset($cachedClassFileNames[$this->fullyQualifiedClassname]);
	}

	protected function cachedClassFileNames()
	{
		if (static::$cachedClassFileNames == null) {
			static::$cachedClassFileNames = include $this->cachedClassFileNamesFile();
		}

		return static::$cachedClassFileNames;
	}

	protected function addFileNameToCachedClassesFile()
	{

		if (!Environment::is()->production()) { return; }

		static::$cachedClassFileNames[$this->fullyQualifiedClassname] = str_replace('//', '/', $this->pathToClass);

		$cache = var_export(static::$cachedClassFileNames, true);

		$phpFileThatReturnsArray = "<?php return {$cache};";

		file_put_contents($this->cachedClassFileNamesFile(), $phpFileThatReturnsArray);
		
	}

	protected function cachedClassFileNamesFile()
	{
		return STRATUM_ROOT_DIRECTORY . '/Storage/Autoloader/CachedClassFilePaths.php';
	}

	protected function addFileContentsToCachedClasses()
	{
		if ($this->classCannotBeCached() or (!Environment::is()->production())) { return; }
		
		(string) $cachedClassesFile = STRATUM_ROOT_DIRECTORY . '/Storage/Autoloader/CachedClasses.php';
		file_put_contents(
			$cachedClassesFile, 
			PHP_EOL . str_replace('<?php', '', file_get_contents($this->pathToClass)),
			FILE_APPEND|LOCK_EX
		);
	}

	protected function classCannotBeCached()
	{
		return ($this->fullyQualifiedClassname === Environment::className())
				||
			   in_array($this->fullyQualifiedClassname, $this->nonCachableClasses()['Do Not Cache'])
			    ||
			   $this->classIsFromANonCachableNamespace();
	}

	protected function nonCachableClasses()
	{
		if (static::$nonCachableClasses == null) {
			static::$nonCachableClasses = require STRATUM_ROOT_DIRECTORY . '/Establish/Autoloading.php';
		}

		return static::$nonCachableClasses;
	}

	protected function classIsFromANonCachableNamespace()
	{
		(array) $nonCachableNamespaces = $this->nonCachableClasses()['Do Not Cache From Namespace'];

		foreach ($nonCachableNamespaces as $nonCachableNamespace) {
			if (strpos($this->fullyQualifiedClassname, $nonCachableNamespace) !== false) {
				return true;
			}
		}

		return false;
	}


}