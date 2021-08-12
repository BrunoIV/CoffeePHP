<?php

	//Activo los mensajes de error
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	//Constantes obligatorias del framework
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', realpath(dirname(__FILE__)) . DS);
	define('CORE_PATH', ROOT . 'Core' . DS);
	define('SUBDIRECTORY', DS);
	define('APP_URL', 'http://' . $_SERVER['HTTP_HOST'] . SUBDIRECTORY);

	//Auto-carga de clases
	function autoload(string $className) {
		$name = explode('\\', $className); //Namespace
		$path = ROOT . array_shift($name) . DS . implode('/', $name) . '.php';
		if (file_exists($path)) {
			require_once($path);
		}
	}

	spl_autoload_register("autoload");

	\Core\Bootstrap::run(new \Core\Request());
