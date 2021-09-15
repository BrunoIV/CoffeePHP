<?php

namespace App\Dao\Entity;
use Core\Query\Entity;

/**
 * Entidad de la tabla PRODUCTOS
 */
class ProductEntity extends Entity {

    const PK = 'PRO_ID';
    const TABLE = 'PRO_PRODUCTOS';
    const JOINS = array(
        //'Entidad' => '{{ALIAS_ORI}}.ID = {{ALIAS_DEST}}.ID_PROD'
    );

    private $map = array(
        'PRO_ID' => 'id',
        'PRO_NOMBRE' => 'nombre',
        'PRO_ACTIVO' => 'activo'
    );

    protected $id;
    protected $nombre;
    protected $activo;

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

    public function getActivo() {
        return $this->activo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }
}
