<?php

namespace Core;

class Bootstrap {

	public static function run(\Core\Request $request) {
		$controller = ucfirst($request->getController()) . 'Controller';
		$method = $request->getMethod();
		$params = $request->getParams();

		$controllerPath = ROOT . 'App' . DS . 'Controller' . DS . $controller . '.php';

		//Si existe y no tiene errores
		if (is_readable($controllerPath)) {

			//Include código del controlador y crea un objeto
			require_once $controllerPath;

			$controllerWithNamespace = '\\App\\Controller\\' . $controller;
			$controller = new $controllerWithNamespace;

			//Si no es posible llamar al metodo de ESE método llama al método index (index SIEMPRE está)
			if (!is_callable(array($controller, $method))) {
				$method = 'index';
			}

			//Si tiene parámetros los pasa
			if (isset($params)) {
				call_user_func_array(array($controller, $method), $params);
			} else {
				call_user_func(array($controller, $method));
			}
		}
	}

}
