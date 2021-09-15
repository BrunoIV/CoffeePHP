<?php

namespace App\Dao\Daos;
use Core\Dao;

class ProductsDao extends Dao {

	/**
	* Retorna un array con los nombres de productos
	*/
	public function getAllProducts() {
		$qb = new \Core\Query\QueryBuilder();
		$sql = $qb->select()->from(['Product' => 'p'])->execute();
		var_dump($sql);

		return ['iPhone', 'iPod', 'iMac'];
	}

}
