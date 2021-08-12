<?php

namespace Core;
use \PDO;
use \Core\Config\ConfigFactory;

/**
 * Permite ejecutar consultas SQL contra una base de datos MySQL
 * @author bruno
 */
class Database {

	private $dbh = false;

	public function __construct() {
		$cfg = ConfigFactory::getConfig();

		//Establece la conexión con la BBDD
		try {
			$this->dbh = new PDO(
				'mysql:host=' . $cfg->getDatabaseHost() . ';dbname=' . $cfg->getDatabaseName(),
				$cfg->getDatabaseUser(), $cfg->getDatabasePassword(),
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);

			$this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
		} catch (Exception $exc) {
			die('No ha sido posible conectar con la BBDD');
		}
	}


	/**
	 * Ejecuta la consulta SQL que recibe como parámetro.
	 * Opcionalmente puede recibir un array con los "binds"
	 * @example execute("select * from mitabla where id = :id", [":id" => 5])
	 */
	public function execute(string $query, array $params = array()) {
		$stmt = $this->dbh->prepare($query);

		foreach ($params as $key => &$val) {
			$stmt->bindParam($key, $val, PDO::PARAM_STR);
		}

		$stmt->execute();

		return $stmt->fetchAll();
	}

}
