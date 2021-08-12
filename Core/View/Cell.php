<?php

namespace Core\View;
use \App\Dao\Daos;

/**
 * Una celda es un mini-controlador que accede a los datos y muestra una vista.
 *
 * Se utiliza para crear componentes reutilizables, como por ejemplo
 * un carrito o un menú de usuario, evitando tener que proporcionar esos datos
 * en cada uno de los controladores/servicios donde se use.
 */
abstract class Cell extends CoreView {
	protected abstract function _html();

	/**
	 * Retorna un objeto que permite acceder a los diferentes Daos
	 */
	protected function getDaos() {
		return new Daos();
	}
}
