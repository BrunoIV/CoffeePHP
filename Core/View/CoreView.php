<?php

namespace Core\View;
use Core\Helpers\HelperProvider;

abstract class CoreView {
	public function render() {
		$this->_html();
	}

	/**
	 * Retorna un objeto con métodos para acceder a los diferentes Helpers
	 */
	public function getHelpers() {
		return new HelperProvider();
	}
}
