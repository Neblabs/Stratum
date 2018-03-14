<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Autoloader\Autoloader;

class AutoloaderTest extends TestCase
{
	protected $autoloader;
	protected static $preloadedArrayWithCachedNamespaces = [
		'Stratum\NamespacenName' => 'default/directory',
		'Stratum\Custom\Controller' => 'Design/Control/Controllers/'
	];

	public static function setupBeforeClass()
	{
		//example controller
		(string) $exampleClass = file_get_contents(STRATUM_ROOT_DIRECTORY . "/Original/Autoloader/NamespaceTests/ExampleController.php");
		
		file_put_contents(STRATUM_ROOT_DIRECTORY . "/Design/Control/Controllers/StratumTestExampleController1.php", $exampleClass);

		//people finder	
		(string) $exampleClass = file_get_contents(STRATUM_ROOT_DIRECTORY . "/Original/Autoloader/NamespaceTests/peopleFinder.php");
		mkdir(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/finders/mysql", 0777, true);
		file_put_contents(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/finders/mysql/peopleFinder.php", $exampleClass);


		//create cache file from preloadedArray
		$cache = var_export(static::$preloadedArrayWithCachedNamespaces, true);

		$phpFileThatReturnsArray = "<?php return {$cache};";

		file_put_contents(STRATUM_ROOT_DIRECTORY . '/Original/autoloader/cachedNamespaces.php', $phpFileThatReturnsArray);
	}

	public static function tearDownAfterClass()
	{
		unlink(STRATUM_ROOT_DIRECTORY . "/Design/Control/controllers/StratumTestExampleController1.php");

		unlink(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/finders/mysql/peopleFinder.php");
		rmdir(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/finders/mysql/");
		rmdir(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/finders/");
		rmdir(STRATUM_ROOT_DIRECTORY . "/Prebuilt/testExamples/");
		
		//  analyzing whether cache file should be restored to empty after the tests.
		
	}

	public function setUp()
	{
		$this->autoloader = new Autoloader;

	}

	public function test_Returns_False_If_Given_Namespace_Does_Not_Start_With_Stratum()
	{
		$this->assertFalse($this->autoloader->autoload('non\stratum\top\level\namespaceName'));
		$this->assertFalse($this->autoloader->autoload('GlobalClassName'));
		$this->assertFalse($this->autoloader->autoload('generic\NamespaceName'));
	}

	public function test_loads_cached_array_from_file_when_trying_to_load_a_stratum_namespace()
	{
		$this->autoloader->autoload('Stratum\NamespacenName\Classname');
		$this->assertSame(static::$preloadedArrayWithCachedNamespaces, $this->autoloader->cachedNamespaces);

	}


	public function test_does_not_use_cached_namespace_from_cache_file_when_namespace_is_not_in_the_cache()
	{
		$this->autoloader->autoload('Stratum\unexistent\namespaceName\in\cache');
		$this->assertFalse($this->autoloader->loadedFromCache);

		$this->autoloader->autoload('Foreign\namespaceName');
		$this->assertFalse($this->autoloader->loadedFromCache);
	}

	public function test_uses_map_when_available() 
	{
		$this->assertFalse($this->autoloader->hasMapSet);

		$this->autoloader->autoload('Stratum\second\third\subnamespace\classname');

		$this->assertTrue($this->autoloader->hasMapSet);

		$freshAutoloader = new Autoloader;
		$freshAutoloader->autoload('Unexistent\namespace\map\set');
		$this->assertFalse($freshAutoloader->hasMapSet);
	}

	public function test_loads_another_class_with_map_created_dynamically()
	{
		$this->expectOutputString('Example Controller!');
		new Stratum\Custom\Controller\StratumTestExampleController1;
	}

	public function test_load_class_with_map_in_different_main_directory()
	{
		$this->expectOutputString("you found me!");
		new Stratum\BuiltIn\finder\Mysql\peopleFinder;
	}

	public function test_loads_class_from_cache_file()
	{
		$this->autoloader->autoload('Stratum\Custom\Controller\StratumTestExampleController1');
		$this->assertTrue($this->autoloader->loadedFromCache);
	}

	public function test_loads_different_class_from_previously_cached_file_by_another_class_in_the_same_namespace()
	{
		$this->autoloader->autoload('Stratum\Custom\Controller\StratumTestExampleController1');
		$this->assertTrue($this->autoloader->loadedFromCache);
	}

	public function test_returns_false_when_not_loaded_from_cache_file()
	{
	
		
		$this->autoloader->autoload('Stratum\Mapped\namespacesWithMaps\ExampleObject');
		
		$this->assertFalse($this->autoloader->loadedFromCache);
		
	}

	public function test_loads_class_with_map()
	{
		$this->expectOutputString('Autoloaded Succesfully!');
		new Stratum\Mapped\namespacesWithMaps\ExampleObject;
	}

	public function test_loads_class_with_the_same_number_of_namespaces_and_directories()
	{
		$this->expectOutputString('Autoloaded Succesfully!');
		new Stratum\Original\Autoloader\NamespaceTest\realPathNamespace\ExampleObject2;	
	}

	public function test_verifies_namespace_is_cached()
	{
		new Stratum\Original\Autoloader\NamespaceTest\realPathNamespace\ExampleObject2;

		(array) $cachedNamespaces = include(STRATUM_ROOT_DIRECTORY . '/Original/autoloader/cachedNamespaces.php');
		
		$this->assertArrayHasKey('Stratum\\Original\\Autoloader\\NamespaceTest\\realPathNamespace', $cachedNamespaces);

	}














}