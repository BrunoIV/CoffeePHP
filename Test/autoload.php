<?php

	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', realpath(dirname(dirname(__FILE__))) . DS);
	define('CORE_PATH', ROOT . 'Core' . DS);
	define('SUBDIRECTORY', DS);


	function autoload(string $className) {
		$name = explode('\\', $className); //Namespace
		$path = ROOT . array_shift($name) . DS . implode('/', $name) . '.php';


		if (file_exists($path)) {
			require_once($path);
		}
	}

	spl_autoload_register("autoload");
