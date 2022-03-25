<?php

namespace Core\Config;

/**
 * Parámetros de configuración para el servidor local
 */
class ConfigLocal extends \Core\Config\Config {
	public function __construct() {
		$this->setDatabaseName('coffeephp');
		$this->setDatabaseHost('localhost');
		$this->setDatabaseUser('root');
		$this->setDatabasePassword('passwd');
		$this->setDriver('mysql');
	}
}
