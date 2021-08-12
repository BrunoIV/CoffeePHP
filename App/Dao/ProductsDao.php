<?php

namespace App\Dao;
use Core\Dao;

class ProductsDao extends Dao {

	/**
	* Retorna un array con los nombres de productos
	*/
	function getAllProducts() {
		return ['iPhone', 'iPod', 'iMac'];
	}

}
