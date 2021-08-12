<?php

namespace Core;

/**
 * Esta clase otiene el el controlador, función y parámetros a partir de la url
 */
class Request {
	private $_controller;
	private $_method;
	private $_params;

	public function __construct() {

		if (isset($_GET['url'])) {

			$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS);

			//Divide la URl y elimina / repetidas
			$url = array_filter(explode('/', $url));

			//array_shift obtiene y elimina el 1er elemento del array
			$this->_controller = strtolower(array_shift($url));
			$this->_method = strtolower(array_shift($url));
			$this->_params = $url;
		}

		//Si no hay controlador se asume que es IndexController
		if (!$this->_controller) {
			$this->_controller = 'Index';
		}

		//Si no hay método se asume que es index
		if (!$this->_method) {
			$this->_method = 'index';
		}

		//Si no hay parámetros uso un array vacío
		if (!isset($this->_params)) {
			$this->_params = array();
		}

	}

	public function getController() {
		return $this->_controller;
	}

	public function getMethod() {
		return $this->_method;
	}

	public function getParams() {
		return $this->_params;
	}

}
