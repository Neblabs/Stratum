<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Request\Controller;
//use Stratum\Original\HTTP\Request\Validator;
use Stratum\Original\HTTP\Exception\UnexistentClassException;
use Stratum\Original\HTTP\Exception\UnexistentMethodException;
use Stratum\Original\HTTP\Exception\InvalidTypeException;

Abstract Class Route
{

	protected $validators = [];
	protected $controller = [];


	public function addValidator($fullyQualifiedValidatorName)
	{
		(array) $validatorData = $this->getClassNameAndMethodFrom($fullyQualifiedValidatorName);

		$this->throwExceptionIfNoValidatorNorMethodExistIn($validatorData);

		$this->validators[] = $validatorData;
	}

	public function setController($fullyQualifiedControllerName)
	{
		(array) $controllerData = $this->getClassNameAndMethodFrom($fullyQualifiedControllerName);

		$this->throwExceptionIfNoControlerNorMethodExistIn($controllerData);

		$this->controller = $controllerData;
	}

	public function validators()
	{
		return $this->validators;
	}

	public function controller()
	{
		return $this->controller;
	}

	protected function getClassNameAndMethodFrom($fullyQualifiedName)
	{
		$classData = explode('.', trim($fullyQualifiedName));

		return [
			'className' => $classData[0],
			'methodName' =>$classData[1]
		];
	}

	protected function throwExceptionIfNoControlerNorMethodExistIn($controllerData)
	{

		(string) $fullyQualifiedClassName = "Stratum\\Custom\\Controller\\{$controllerData['className']}";
		(boolean) $noControllerClassExists = !class_exists($fullyQualifiedClassName);
		(boolean) $requestedControllerMethodDoesNotExist = !method_exists($fullyQualifiedClassName, $controllerData['methodName']);
		(boolean) $isForbiddenMethodName = strtolower($controllerData['methodName']) === 'execute';

		if ($noControllerClassExists) {
			throw new UnexistentClassException("No controller class was found for {$fullyQualifiedClassName}");
		}

		if ($isForbiddenMethodName) {
			throw new UnexistentMethodException("The method {$controllerData['methodName']}() may not be used as a client controller method in: {$fullyQualifiedClassName}");
		}

		if ($requestedControllerMethodDoesNotExist) {
			throw new UnexistentMethodException("The method {$controllerData['methodName']}() was not found in {$fullyQualifiedClassName}");
		}

	}

	protected function throwExceptionIfNoValidatorNorMethodExistIn($validatorData)
	{
		(string) $fullyQualifiedClassName = "Stratum\\Custom\\Validator\\{$validatorData['className']}";
		(boolean) $noValidatorClassExists = !class_exists($fullyQualifiedClassName);
		(boolean) $requestedValidatorMethodDoesNotExist = !method_exists($fullyQualifiedClassName, $validatorData['methodName']);

		if ($noValidatorClassExists) {
			throw new UnexistentClassException("No validator class was found for {$fullyQualifiedClassName}");
		}

		if ($requestedValidatorMethodDoesNotExist) {
			throw new UnexistentMethodException("The method {$validatorData['methodName']}() was not found in {$fullyQualifiedClassName}");
		}

	}
























}