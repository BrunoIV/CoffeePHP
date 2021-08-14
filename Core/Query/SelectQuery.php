<?php

namespace Core\Query;
use \PDO;

class SelectQuery extends \Core\Query\CommonQuery {

	protected $limit = 0;

	/**
	 * Selecciona los atributos que recibe como parámetro.
	 * Usando un array asociativo puedes poner alias a las columnas
	 * @example array('id', 'name' => 'nombre')
	 * @param array $columns
	 * @return SelectQuery
	 */
	public function __construct(array $columns) {
		$this->addArrayColumns($columns);
		return $this;
	}

	/**
	 * Agrega una sentencia <b>FROM</b> a la consulta
	 * @param string|array $data - Nombre de la entidad o array ['Entidad' => 'alias']
	 * @return \Query
	 */
	public function from($data): SelectQuery {
		if(!$this->entityNameIsValid($data)) {
			die('La función "from" solo admite un string con el nombre es una entidad o un array asociativo ["entidad" => "alias"]');
		}

		//Agrega la entidad al listado de entidades usadas en la consulta
		$this->addUsedTable($data);

		//Obtiene el alias (si no tiene alias se usa el nombre de la entidad)
		$alias = $this->getAlias($data);

		//Obtiene el nombre de la entidad
		$entity = $this->getEntityName($data);

		$this->from = [$entity => $alias];

		return $this;
	}

	/**
	 * Establece el nº de resultados máximo que va a retornar la consulta
	 * @param int $limit
	 * @return SelectQuery
	 */
	public function limit(int $limit): SelectQuery {
		$this->limit = $limit;
		return $this;
	}

	private function generateSelect() {
		$select = 'SELECT ';

		//Concatena las columnas (o todas si no hay ninguna)
		if (!empty($this->getColumns())) {
			$select .= $this->generateColumns();
		} else {
			$select .= '* ';
		}

		return $select;
	}

	/**
	 * Genera el FROM de la consulta
	 * @return type
	 */
	private function generateFrom() {
		return ' FROM ' . $this->getTableWithAlias($this->from);
	}

	/**
	 * Genera la claúsula LIMIT de la consulta
	 * @return string
	 */
	protected function getLimit(): string {
		$limit = '';
		if ($this->limit !== 0) {
			$limit = ' LIMIT ' . $this->limit;
		}
		return $limit;
	}

	public function getSql(): string {
		return $this->generateSelect() . $this->generateFrom() . $this->getLimit();
	}

}
