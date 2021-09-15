<?php

namespace App\Dao\Daos;
use Core\Dao;

class Daos {

	/**
	 * Retorna el DAO que gestiona los productos
	 */
	public function getProducts() :ProductsDao {
		return new ProductsDao();
	}
}
