<?php
use \Core\Config\ConfigFactory;

class SqlFunction {
	private $cfg;
	public __construct() {
		$this->cfg = ConfigFactory::getConfig();
	}

	public getConfig() {
		return $this->cfg;
	}

}
