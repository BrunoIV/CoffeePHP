<?php

namespace Core\Config;

/**
 * Factoria de configuraciones. Retorna una instancia de Config en función de
 * los condicionales especificados, por defecto la en función de la IP.
 */
class ConfigFactory{
	public static function getConfig() {
		if(getHostByName(getHostName()) === '127.0.0.1') {
			return new \Core\Config\ConfigLocal();
		}

		return new \Core\Config\ConfigProduction();
	}
}
