<?php
namespace Core\Query;

class QueryBuilder {

	/**
	 * Inicia una consulta de tipo select, con los atributos que recibe como paŕametro
	 * Usando un array asociativo puede poner alias a las columnas
	 * @example array('id', 'name' => 'nombre')
	 * @param array $columns
	 * @return SelectQuery
	 */
	public function select(array $columns = array()): SelectQuery {
		return new SelectQuery($columns);
	}


}
