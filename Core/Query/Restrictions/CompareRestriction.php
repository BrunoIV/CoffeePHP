<?php

namespace Core\Query\Restrictions;

class CompareRestriction {
	private $column;
	private $comparation;
	private $type;

	public function __construct($column, $comparation, string $type) {
		$this->setColumn($column);
		$this->setComparation($comparation);
		$this->setType($type);
	}

	public function setColumn($column) {
		$this->column = $column;
	}

	public function setComparation($comparation) {
		$this->comparation = $comparation;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getColumn() {
		return $this->column;
	}

	public function getComparation() {
		return $this->comparation;
	}

	public function getType() {
		return $this->type;
	}
}
