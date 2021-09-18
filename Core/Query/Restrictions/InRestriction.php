<?php
namespace Core\Query\Restrictions;

use Core\Query\Restrictions\CompareRestriction;
use Core\Query\SelectQuery;

/**
 * Permite agregar una clúsula IN al WHERE del QueryBuilder
 */
class InRestriction extends CompareRestriction {

	/**
	 * Constructor
	 * @param $column - Nombre de la columna
	 * @param $comparation - Array de valores o consulta
	 */
	public function __construct(String $column, $comparation) {
		if(!is_array($comparation) && !$comparation instanceof \Core\Query\SelectQuery) {
			throw new \Exception("La claúsula IN solo admite un array de valores u otra consulta");
		}
		parent::__construct($column, $comparation, 'IN');
	}

}
