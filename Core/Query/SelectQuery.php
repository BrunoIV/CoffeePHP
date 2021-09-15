<?php

namespace Core\Query;
use \PDO;
use \Core\Helpers\ArrayHelper;

class SelectQuery extends \Core\Query\CommonQuery {

	private $limit = 0;
	private $order = [];

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

	public function orderBy($data) {
		if(!ArrayHelper::isAssoc($data) && !is_string($data)) {
			die('Utiliza un array asociativo para el orderBy: ["id" => "asc"] o un string');
		}

		if(is_string($data)) {
			$realColumnName = $this->getRealColumn(new Column($data));
			array_push($this->order, $realColumnName . ' ASC');
		} else {
			foreach ($data as $column => $order) {
				$order = strtoupper($order);
				if($order != 'ASC' && $order != 'DESC') {
					die('El tipo de ordenación debe ser "asc" o "desc"');
				}

				if($this->entityNameIsValid($column)) {
					$realColumnName = $this->getRealColumn(new Column($column));
					array_push($this->order, $realColumnName . ' ' . $order);
				}
			}
		}

		return $this;
	}

	private function getOrder() :string {
		$sql = '';
		if(sizeof($this->order) > 0) {
			$sql .= ' ORDER BY ';
			foreach ($this->order as $order) {
				$sql .= $order;
			}
		}

		return $sql;
	}

	private function getSql(): string {
		return $this->generateSelect() . $this->generateFrom() .
		$this->getOrder() . $this->getLimit();
	}

	public function execute() {
		$sql = $this->getSql();
		$db = new \Core\Database();
		return $db->executeFetchClass($sql, array(), '\\App\\Dao\\Entity\\' . key($this->from) . 'Entity');
	}

}
