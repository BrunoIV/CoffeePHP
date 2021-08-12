<?php

namespace App\Dao;
use Core\Dao;

class Daos {

	/**
	 * Retorna el DAO que gestiona los productos
	 */
	public function getProducts() :ProductsDao {
		return new ProductsDao();
	}
}
