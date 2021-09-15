<?php

namespace App\Dao\Daos;
use Core\Dao;

class ProductsDao extends Dao {

	/**
	* Retorna un array con los nombres de productos
	*/
	public function getAllProducts() {
		$qb = new \Core\Query\QueryBuilder();
		return $qb->select()->from(['Product' => 'p'])->execute();
	}

}
