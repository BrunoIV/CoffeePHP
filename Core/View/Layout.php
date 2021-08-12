<?php

namespace Core\View;

/**
 * Un layout es una vista que actua como una plantilla. Generalmente está compuesto
 * por una serie de vistas que se muestran siempre (como un header o un footer)
 * y por otras vistas que van variando según sea necesario (un grid, un formulario, etc).
 */
abstract class Layout extends CoreView {
	private $items = array();

	/**
	 * Agrega un objeto que herede de CoreView, como una vista, celda, otro layout, etc
	 * @param CoreView $item - Celda, vista, layout
	 */
	public function addItem(CoreView $item) {
		array_push($this->items, $item);
	}

	/**
	 * Retorna un array con todas las vistas, celdas y sublayouts del layout
	 */
	public function getItems() {
		return $this->items;
	}
}
