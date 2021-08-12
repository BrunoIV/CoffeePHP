<?php

namespace App\Controller;
use \Core\Controller;

class IndexController extends Controller {

	public function index() {
		$srv = new \App\Service\HomeService();
		$srv->getStartPage();
	}

}
