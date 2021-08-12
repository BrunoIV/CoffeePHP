<?php

namespace Core\Config;

/**
 * Parámetros de configuración para el servidor de producción
 */
class ConfigProduction extends \Core\Config\Config {
	public function __construct() {
		$this->setDatabaseName('coffeephp');
		$this->setDatabaseHost('localhost');
		$this->setDatabaseUser('root');
		$this->setDatabasePassword('passwd-prod');
	}
}
