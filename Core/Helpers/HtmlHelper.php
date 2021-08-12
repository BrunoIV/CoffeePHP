<?php

namespace Core\Helpers;

class HtmlHelper extends \Core\Helper {


	/**
	 * Enlaza una hoja de estilos ubicada por defecto en /public/css
	 * @param $file - Fichero de la carpeta Css
	 */
	public function cssFile(string $file) :string {
		return  '<link rel="stylesheet" type="text/css" href="' .  $this->getConfig()->getCssUrl() . $file . '">';
	}
}
