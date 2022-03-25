<?php
namespace Core\Query\Functions;
use \Core\Config\ConfigFactory;

class LengthFunction extends SqlFunction {

	private $textOrColumn;
	public function __construct($textOrColumn) {
		$this->textOrColumn = $textOrColumn;
	}

	public function toSql() {

		//Si es un objeto columna obtengo el nombre real
		if($this->textOrColumn instanceof \Core\Query\Column) {
			$this->textOrColumn = $this->textOrColumn->getNameWithAlias();
		}

		if($this->getConfig()->getDriver() === 'mysql') {
			return 'CHAR_LENGTH(' . $this->textOrColumn . ')';
		} else if ($this->getConfig()->getDriver() === 'sql-server') {
			return 'LEN(' . $this->textOrColumn . ')';
		} else {
			throw new \Exception('Driver de BBDD desconocido');
		}
	}
}
