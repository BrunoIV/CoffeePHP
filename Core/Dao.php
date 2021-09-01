<?php

namespace Core;
use \Core\Query\Entity;

abstract class Dao {
	public final function insert(Entity $entity) {
		$columns = '';
		$values = '';
		$where = array();

		//Foreach in the map
		foreach ($entity->getMap() as $key => $value) {
			$val = $entity->{$value};

			//if has value
			if (!empty($val)) {
				$columns .= '`' . $key . '`, ';
				$values .= ':' . $key . ', ';
				$where[':' . $key] = $val;
			}
		}

		//SQL without the last ", "
		$sql = "INSERT INTO " . $entity::TABLE . '(' . substr($columns, 0, -2) .
				') VALUES(' . substr($values, 0, -2) . ');';
		return $this->execute($sql, $where);
	}

	private function execute(string $query, array $params = array()) {
        $db = new Database();
		return $db->execute($query, $params);
    }
}
