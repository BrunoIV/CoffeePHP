<?php

namespace App\Service;
use Core\Service;

class HomeService extends Service {

	/**
	 * Obtiene la portada
	 */
	public function getStartPage() {
		$products = $this->getDaos()->getProducts()->getAllProducts();

		$view = new \App\View\Views\Home\GridProductsView($products);
		$layout = new \App\View\Layouts\MainLayout('Portada');
		$layout->addItem($view);

		$layout->render();
	}
}
