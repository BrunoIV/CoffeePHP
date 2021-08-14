<?php

namespace Core\Query;
class Column {

    private $name;
	private $alias;
    private $idTable;
    private $function;
    private $className;

    /**
     * Constructor del objeto Column. que representa una columna de una consulta SQL
     * @param string $fullName - NombreEntidadOrAliasEntidad.Atributo
     * @param string $alias - Alias de la columna
     */
    public function __construct(string $fullName = '', string $alias = '') {
        $this->setName($fullName);
        $this->alias = $alias;
    }

    /**
	 * Retorna el nombre de la columna sin el alias/nombre de la tabla
     * @param string $fullName - Columna o tabla.columna
     * @return string
	 */
    private function getColumnFromFullName(string $fullName): string {
        $ar = explode('.', $fullName);

        if (sizeof($ar) > 1) {
            return $ar[1];
        }

        return $ar[0];
    }

    /**
     * Returns the name of the table without the column name
     * @param string $fullName - Column or table.column
     * @return string
	 */
    private function getTableFromFullName(string $fullName): string {
        $ar = explode('.', $fullName);

        if (sizeof($ar) > 1) {
            return $ar[0];
        }

        return '';
    }

    /**
     * Retorna el nombre de la columna
     * @return string
	 */
    public function getName() {
        return $this->name;
    }

    /**
	 * Establece el nombre de la columna, función e id de la tabla
     * @param string $fullName - Nombre completo: entidad.nombre o funcion(entidad.nombre)
	 * @example count(pro.id)
	 */
    public function setName(string $fullName) {
		$name = $fullName;

        if (!$this->isLiteral($name)) {
            if ($this->isFunction($fullName)) {
                $name = $this->getColumnOfFunction($fullName);
                $this->setFunction($this->getFunctionOfString($fullName));
            }

            $this->name = $this->getColumnFromFullName($name);
            $this->idTable = $this->getTableFromFullName($name);
        } else {
			$this->name = $fullName;
		}
    }


	/**
	 * Retorna el id de la tabla dentro de la consulta actual. El Id puede
	 * ser el nombre de una entidad o el alias que tenga en la consulta.
	 */
	public function getIdTable(): string {
		return $this->idTable;
	}

	/**
	 * Establece el identificador de la tabla para una consulta
	 * @param $idTable
	 */
	public function setIdTable(String $idTable) {
		$this->idTable = $idTable;
	}

    /**
     * Retorna el alias de una columna
     * @return string
	 */
    public function getAlias(): string {
        return $this->alias;
    }

	/**
	 * Establece el alias de una columna
	 * @param $alias
	 */
    public function setAlias(string $alias) {
        $this->alias = $alias;
    }

	/**
	 * Retorna el nombre de la función o null si no tiene
	 * @return string
	 */
    public function getFunction() {
        return $this->function;
    }

	/**
	 * Estable el nombre de la función
	 * @param $function - Nombre función
	 */
    public function setFunction(string $function) {
        $this->function = $function;
    }


	/**
	 * Retorna el nombre de la columna junto con su alias (si tiene)
	 * @return string
	 */
    public function getNameWithAlias() {
        $alias = (!empty($this->getAlias()) ? ' AS ' . $this->getAlias() : '');
        return $this->getName() . $alias;
    }

    /**
     * Checkea si el nombre de la columna contiene una función, ejemplo: count(pro.id)
     * @param string $column
     * @return bool
	 */
    private function isFunction(string $column): bool {
        if (strpos($column, ')') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Extrae el nombre de la columna de un string que contiene además el nombre
     * de la función, por ejemplo: si recibe count(pro.id) retorna pro.id
     * @param string $data
	 */
    private function getColumnOfFunction(string $data) {
        return substr($data, strpos($data, '(') + 1, -1);
    }

	/**
	 * Extrae el nombre de la función de una columna
	 * @param $data - Nombre de columna
	 * @example trim(id) -> 'trim'
	 */
    private function getFunctionOfString(string $data) {
        return substr($data, 0, strpos($data, '('));
    }

    /**
     * Determina si el valor de la columna es un valor literal que no debe ser mapeado
	 * Como por ejemplo "'Clave'" o '"Clave"' dependiendo de qué tipo de comillas uses
     * @return bool
	 */
    public function isLiteral(): bool {
        $value = $this->getName();
        return (substr($value, 0, 1) == '"' &&
                substr($value, strlen($value) - 1, 1) == '"') ||
				(substr($value, 0, 1) == "'" &&
		                substr($value, strlen($value) - 1, 1) == "'");
    }

}
