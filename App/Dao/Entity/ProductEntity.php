<?php

namespace App\Dao\Entity;
use Core\Query\Entity;

/**
 * Entidad de la tabla PRODUCTOS
 */
class ProductEntity extends Entity {

    const PK = 'ID';
    const TABLE = 'PRODUCTOS';
    const JOINS = array(
        //'Entidad' => '{{ALIAS_ORI}}.ID = {{ALIAS_DEST}}.ID_PROD'
    );

    private $map = array(
        'ID' => 'id',
        'NOMBRE_PRODUCTO' => 'nombre'
    );

    protected $id;
    protected $nombre;

    public function __construct($data = array()) {
        parent::__construct($data);
    }

    public function getMap() {
        return $this->map;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}
