<?php
namespace Core\Query\Functions;
use \Core\Config\ConfigFactory;

class SqlFunction {
	private $cfg;
	public function __construct() {
		$this->cfg = ConfigFactory::getConfig();
	}

	public function getConfig() {
		return $this->cfg;
	}

}
