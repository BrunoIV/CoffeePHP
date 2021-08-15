<?php

namespace Core\Query;

/**
 * Una entidad es la representación de una tabla
 */
class Entity {

    private $map;

    /**
     *
     */
    public function __construct($data) {
        $this->map = $this->getMap();
    }

    /**
     * Llena el objeto usando un array asociativo
     * @param type $data
     */
    public function fill($data) {
        if($data != null) {
            foreach ($data as $key => $val) {
                $this->setAttribute($key, $val);
            }
        }
    }

    protected function setAttribute(string $attribute, $value) {
		$map = $this->map($attribute);
		$method = 'set' . ucfirst($map);

        if (!empty($map)) {

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } else {
                $this->{$map} = $value;
            }
        } else {
            //Si no hay mapeo agrega el atributo como si fuera un stdClass
            $this->{$attribute} = $value;
        }
    }

    /**
     * Setea la propiedad con el valor que recibe como parámetro. Si existe un
     * setter lo utiliza, y si no existe, settea el valor directamente.
     * @param string $attribute - Nombre del atributo
     * @param mixed $value - Valor del atributo
     */
    public function __set(string $attribute, $value) {
        $this->setAttribute($attribute, $value);
    }

    /**
     * Retorna el valor del atributo que recibe como parámetro. Si existe un
     * getter lo utiliza, y si no existe, retorna el valor directamente.
     * @param string $key
     * @return type
     * @example llama a getId() y si no existe retorna el attributo id
     */
    public function __get(string $key) {
        $method = 'get' . ucfirst($key);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->{$key};
    }

    /**
     * Retorna el nombre de la tabla en la BBDD
     * @return string
     */
    public function getTableName(): string {
        return $this::TABLE;
    }

    /**
     * Mapea un atributo con una columna de la BBDD. Si no existe un mapeo usa
     * el nombre del atributo
     * @param string $atribute
     * @return string
     */
    public function mapAttribute(string $atribute): string {
        $map = array_flip($this->getMap());

        if (!empty($map{$atribute})) {
            return $map{$atribute};
        }

        return '';
    }


}