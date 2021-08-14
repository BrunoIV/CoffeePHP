<?php

namespace Core\Helpers;

class ArrayHelper extends \Core\Helper {

	/**
	 * Determina si el parámetro recibido es un array asociativo
	 * @param $arr - Array
	 * @return bool
	 */
	 public static function isAssoc($arr) {
		 if (!is_array($arr) || array() === $arr) return false;
		 return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
