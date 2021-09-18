<?php
namespace Core\Query\Restrictions;

use Core\Query\Restrictions\CompareRestriction;
use Core\Query\SelectQuery;

class EqualsRestriction extends CompareRestriction {

	/**
	 *
	 * @param $column - Nombre de la columna
	 * @param $comparation - Valor, Subconsulta
	 */
	public function __construct(String $column, $comparation) {
		parent::__construct($column, $comparation, '=');
	}

}
