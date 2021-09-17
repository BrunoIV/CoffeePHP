<?php
namespace Core\Query\Restrictions;

use \Core\Query\Restrictions\CompareRestriction;

/**
 * Permite crear restricciones para consultas SQL
 */
class AndOrRestriction {

	const SYMBOL = "AND";

	private $restrictions = [];
	private $type;

	public function __construct($restrictions, string $type) {
		$this->type = $type;

		foreach ($restrictions as $restriction) {
			//Solo admito comparadoradores (equals, in, like) u otro OR/AND
			if($restriction instanceof CompareRestriction || $restriction instanceof AndOrRestriction) {
				array_push($this->restrictions, $restriction);
			} else {
				throw new \Exception("Alguno uno de parámetros no es una comparación ni un OR/AND");
			}
		}
		return $this;
	}

	public function getRestrictions() {
		return $this->restrictions;
	}

	public function getType() :string {
		return $this->type;
	}
}
