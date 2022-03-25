<?php
namespace Core\Query\Functions;
use \Core\Config\ConfigFactory;

class CurrentDateFunction extends SqlFunction {

	public function toSql() {
		if($this->getConfig()->getDriver() === 'mysql') {
			return 'SYSDATE()';
		} else if ($this->getConfig()->getDriver() === 'sql-server') {
			return 'GETDATE()';
		} else {
			throw new \Exception('Driver de BBDD desconocido');
		}
	}
}
