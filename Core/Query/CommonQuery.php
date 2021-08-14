<?php

namespace Core\Query;
use \Core\Helpers\ArrayHelper;

class CommonQuery {
	private $columns = array();
	private $usedTables = array(); //Tablas usadas en FROM o JOIN's

	/**
     * Genera el nombre de la tabla y el alias
     * @param mixed $data - Nombre de la clase o array ['Clase' => 'alias']
     * @return string - Nombre de la tabla [espacio] alias
     */
    protected function getTableWithAlias($data) {

        if (is_array($data) && ArrayHelper::isAssoc($data)) {
			//Si es un array asociativo me quedo con el alias, que debe ser único
            $idTable = $data[key($data)];
        } else {
			//Si es un string el nombre la entidad es el identificador
            $idTable = $data;
		}

        //A partir del nombre/alias de la entidad se obtiene el nombre real de la tabla
        $table = $this->getRealTable($idTable);

        return $table . ' ' . $idTable;
    }


	/**
     * Obtiene el nombre real de la tabla a partir del nombre de la clase o su alias
     * @param string $classOrAlias - Nombre de la clase o su alias
     * @return string
     */
    protected function getRealTable(string $classOrAlias) {
        $c = $this->usedTables[$classOrAlias];
        $c = $this->generateEntityName($c);
        return $c::TABLE;
    }

	/**
	 * Agrega un array de columnas transformandolas a objetos Column
	 */
	protected function addArrayColumns(array $columns) {

		//Las columnas son un array, que dentro pueden contener strings o arrays asocativos
        foreach ($columns as $col) {

            //Si el array es anonimo el alias es el nombre de la columna
            if (ArrayHelper::isAssoc($col)) {
				$name = key($col);
				$alias = $col[$name];

                //Si usas func() retorna una columna directamente
                if ($alias instanceof Column) {
                    array_push($this->columns, $alias);
				} else {
                    array_push($this->columns, new Column($name, $alias));
                }
            } else {
                array_push($this->columns, new Column($col));
            }
        }
    }

	/**
     * Agrega una entidad y su alias al array de entidades usadas usando el alias como clave
     * @param string|array $class - Nombre de la entidad o array ['Entidad' => 'Alias']
     */
    protected function addUsedTable($data) {
		//Si $data es un string el nombre y alias son el mismo
        $alias = $this->getAlias($data);
        $entityName = $this->getEntityName($data);

		//Si el alias (que identifica la entidad) no está en uso
        if (empty($this->usedTables[$alias])) {
			//Guardo la asociación ['alias' => 'Entidad']
			$this->usedTables[$alias] = $entityName;

			//Construyo la entidad (para asegurarme que existe) y la guardo generar la SQL al final
            $this->tables[$alias] = $this->createEntity($entityName);
        } else {
            die('No puedes usar mas de una vez el mismo nombre/alias de tabla: ' . $alias);
        }
    }


	/**
     * Obtiene o genera el alias a partir de un string (nombre clase) o array (clase => alias)
     * @param mixed $data - String con el nombre de la clase o array asociativo ['Clase' => 'Alias']
     * @return string
     */
    protected function getAlias($data) {
        if (is_array($data)) {
            return $data[key($data)];
        } else {
            return $data;
        }
    }

    /**
     * Obtiene el nombre de la entidad de un array asociativo o string
     * @param mixed $data - String con el nombre de la entidad o array asociativo ['Entidad' => 'Alias']
     * @return string
     */
    protected function getEntityName($data) {
        if (is_array($data)) {
            return key($data);
        } else {
            return $data;
        }
    }

	/**
     * Crea y retorna un objeto Entity a partir del nombre de la entidad
     * (sin la palabra reservada entity)
     * @example 'Usuarios' -> new UsuariosEntity()
     * @param string $entity - Nombre de la entidad sin la palabra reservada Entity
     * @return \Core\Entity - Instancia de la entidad
     */
    private function createEntity(string $entity): Entity {
        $class = $this->generateEntityName($entity);

        if (!class_exists($class)) {
            die('La entidad "' . $entity . 'Entity" no existe, revisa la claúsula FROM');
        }

        return new $class();
    }

	/**
     * Genera el nombre completo de la entidad incluyendo namespace
     * @example Productos => \App\Dao\Entity\ProductosEntity
     * @param string $entity
     * @return string
     */
    private function generateEntityName(string $entity): string {
        return '\\App\\Dao\\Entity\\' . $entity . 'Entity';
    }

	/**
     * Retorna el nombre que tiene la columna en la BBDD a partir de un objeto Column
     * @param Column $column
     */
    protected function getRealColumn(Column $column) {
        $realColumnName = '';

        //Si la columna tiene el id/alias de la tabla -> usuarios.id
        if (empty($column->getIdTable())) {

			//Recorro las tablas en uso para buscar la entidad a la que pertenece el atributo
            foreach ($this->usedTables as $usedTable) {
                $entity = $this->createEntity($usedTable);

				//Si está en varias entidades no puedo obtener el nombre real de la columna
				if($realColumnName != '') {
					die('El atributo "' . $column->getName() . '" se encuentra en varias entidades de tu consulta. Por favor, especifica el alias de la columna. Ejemplo: "productos.' . $column->getName() . '" en lugar de "'.$column->getName().'"');
				}

                $realColumnName = $entity->mapAttribute($column->getName());
            }
        } else {
			//Obtiene el nombre de la entidad (y la contruye) a partir del alias
            $entityName = $this->getEntityNameFromTheAlias($column->getIdTable());
            $entity = $this->createEntity($entityName);

            $realColumnName = $entity->mapAttribute($column->getName());
        }

        if ($realColumnName != '') {
            return $realColumnName;
        }

        die('No existe mapeo para la columna: ' . $column->getName());
    }

	/**
     * Retorna el nombre de la entidad a partir de su alias
     * @param string $alias
     * @return String
     */
    private function getEntityNameFromTheAlias(string $alias) {
        if (!empty($this->usedTables[$alias])) {
            return $this->usedTables[$alias];
        } else {
            die('No existe el alias "' . $alias . '", revisa sentencias FROM y JOIN');
        }
    }

	/**
	 * Genera un string con los verdaderos nombres de las columnas en BBDD
	 * @param bool $noTableAlias - Indica si la columna tendrá delante el ID/Alias de la tabla
	 * @return string
	 */
	protected function generateColumns(bool $noTableAlias = false) {
		$select = '';

		foreach ($this->columns as $column) {

			//Si la columna no es literal -> '"verde"'
			if (!$column->isLiteral()) {

				//Obtiene el nombre real de la columna (o falla si no existe)
				$realColumnName = $this->getRealColumn($column);

				//Si tiene alias se agrega
				if (!empty($column->getAlias())) {
					$realColumnName .= ' AS ' . $column->getAlias();
				}

				//si no se conoce la tabla
				if ($column->getIdTable() == '') {
					$column->setIdTable($this->getTableOfColumn($column->getName()));
				} else {
					$column->setIdTable($column->getIdTable());
				}

				$col = '';

				//Concatena ID/Alias de la tabla al nombre de la columna en BBDD
				if ($noTableAlias === false) {
					$col .= $column->getIdTable() . '.';
				}
				$col .= $realColumnName;

				if (!empty($column->getFunction())) {
					$col = $column->getFunction() . '(' . $col . ')';
				}

			} else { //fin isLiteral
				$col = $column->getNameWithAlias();
			}

			if ($col != '') {
				$select .= $col . ', ';
			}
		}

		//Elimina el último ', '
		$select = substr($select, 0, -2);

		return $select;
	}

	/**
	 * Obtiene el nombre de la tabla a partir de la columna que recibe
	 * @param String $column
	 */
	public function getTableOfColumn(String $column) {
		$count = 0;
		$ta = '';

		foreach ($this->tables as $alias => $entity) {

			//Recorre las columnas de la entidad
			foreach ($entity->getMap() as $c) {

				//Si encuentra una columna que coincida guarda el alias de la tabla
				if ($c === $column) {
					$count++;
					$ta = $alias;
				}
			}
		}
		return $ta;
	}


	protected function getColumns() {
		return $this->columns;
	}

	/**
	 * Valida si el tipo de parámetro que ha recibido es un string o array asociativo
	 */
	protected function entityNameIsValid($data) {
		return is_string($data) && trim($data) != '' || ( //Sea string
			is_array($data) && //O sea un array
			ArrayHelper::isAssoc($data) && //asociativo
			sizeof($data) == 1 && //de un único elemento
			is_string($data[key($data)]) //y el valor es un string
		);
	}

}
