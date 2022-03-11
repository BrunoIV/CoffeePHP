<?php

namespace Core\Query;
use \PDO;
use \Core\Helpers\ArrayHelper;

class DeleteQuery extends \Core\Query\CommonQuery {
    protected $from;
    protected $restrictions = [];

	/**
	 * Elimina registros de la entidad que recibe como parámetro
	 * @param $data - Nombre de la entidad o array [nombre => alias]
	 */
    public function __construct($data) {
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

    private function generateDelete() :string {
        return 'DELETE FROM ' . $this->getTableWithAlias($this->from);
    }

    public function getSql(): string {
		return $this->generateDelete() . $this->generateWhere();
	}
}