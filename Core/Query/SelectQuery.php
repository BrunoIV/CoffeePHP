<?php

namespace Core\Query;
use \PDO;
use \Core\Helpers\ArrayHelper;

class SelectQuery extends \Core\Query\CommonQuery {

	private $limit = 0;
	private $order = [];
	private $restrictions;

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

	public function where($restrictions) {
		$this->restrictions = $restrictions;
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
			$select .= '*';
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
	 * Agrega comillas simples si lo que recibe es un string
	 */
	private function addQuotes($value) {
		if(is_string($value)) {
			return "'" . $value . "'";
		}

		return $value;
	}

	private function restrictionToSql($restriction) {

		$sql = '';

		$column = new Column($this->from[key($this->from)] . '.' . $restriction->getColumn());
		$alias = $this->getRealColumn($column);
		$sql = $alias . ' ' . $restriction->getType() . ' ';

		if($restriction->getType() == 'IN') {
			//Si es una subconsulta
			if($restriction->getComparation() instanceof SelectQuery) {
				$column = new Column($this->from[key($this->from)] . '.' . $restriction->getColumn());
				$sql .= "(" . $restriction->getComparation()->getSql() . ")";
			} else {
				$sql .= "(";
				$size =  sizeof($restriction->getComparation());
				for($i = 0; $i < $size; $i++) {
					$comparation = $restriction->getComparation()[$i];

					$sql .= $this->addQuotes($comparation);
					if($i < ($size - 1)) {
						$sql .= ", ";
					}

				}
				$sql .= ")";
			}
		} else {
			$sql .= $this->addQuotes($restriction->getComparation());
		}
		return $sql;
	}

	private function loopRestrictions($restrictions, $type) {
		$sql = '';

		$len = count($restrictions->getRestrictions());
		$i = 0;
		foreach ($restrictions->getRestrictions() as $res) {
			if($res instanceof \Core\Query\Restrictions\AndOrRestriction) {
				$sql.= '(' . $this->loopRestrictions($res, $res->getType()) . ') ' . $type . ' ';
			} else {
				$sql .= $this->restrictionToSql($res) . ($i < ($len - 1) ? ' '.$type.' ' : '');
			}
			$i++;
		}
		return $sql;
	}

	private function generateWhere() {
		$restrictions = $this->restrictions;
		$sql = '';

		if(!empty($restrictions)) {
			$sql = ' WHERE ';
			if($restrictions instanceof \Core\Query\Restrictions\AndOrRestriction) {
				$sql .= $this->loopRestrictions($restrictions, $restrictions->getType());
			} else {
				$sql  .= $this->restrictionToSql($restrictions);
			}
		}

		return $sql;
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

	/**
	 * Transforma el nombre del atributo al nombre de la columna y lo agrega a la lista
	 * de orders
	 * @param $attribute - Nombre del atributo de la entidad
	 * @param $order - asc, desc o '' (opcional)
	 */
	private function addOrder(string $attribute, string $order = '') {
		$realColumnName = $this->getRealColumn(new Column($attribute));
		array_push($this->order, $realColumnName . ($order != '' ? ' ' . strtoupper($order) : ''));
	}

	public function orderBy($data) {
		if(!ArrayHelper::isAssoc($data) && !is_string($data)) {
			die('Utiliza un array asociativo para el orderBy: ["id" => "asc"] o un string');
		}

		if(is_string($data)) {
			$this->addOrder($data);
		} else {
			foreach ($data as $column => $order) {

				if(strtoupper($order) != 'ASC' && strtoupper($order) != 'DESC' && $column != '') {
					die('El tipo de ordenación debe ser "asc" o "desc"');
				}

				//Si en lugar de un array ['id'=>'asc'] pasas un string ['id'] $orden para a ser la columna
				if($column == '') {
					$this->addOrder($order);
				} else {
					$this->addOrder($column, $order);
				}
			}
		}

		return $this;
	}

	private function getOrder() :string {
		$sql = '';

		$size = sizeof($this->order);
		if($size > 0) {
			$sql .= ' ORDER BY ';
			for($i = 0; $i < $size; $i++) {
				$sql .= $this->order[$i];
				if($i < $size -1 ) {
					$sql .= ", ";
				}
			}
		}

		return $sql;
	}

	public function getSql(): string {
		return $this->generateSelect() . $this->generateFrom() .
		$this->generateWhere() . $this->getOrder() . $this->getLimit();
	}

	public function execute() {
		$sql = $this->getSql();
		$db = new \Core\Database();
		return $db->executeFetchClass($sql, array(), '\\App\\Dao\\Entity\\' . key($this->from) . 'Entity');
	}

}
