<?php

namespace App\View\Layouts;
use \Core\View\Layout;

class MainLayout extends Layout {

	private $title;
	public function __construct(string $title) {
		$this->title = $title;
	}

	public function _html() { ?>
		<!DOCTYPE html>
		<html lang="es">
		<head>
			<title><?= $this->title; ?></title>
			<?= $this->getHelpers()->getHtml()->cssFile('coffeecss.css'); ?>
		</head>

		<body>
			<?php
				foreach ($this->getItems() as $item) {
					$item->render();
				}
			?>
		</body>
		</html>
		<?php
	}
}
