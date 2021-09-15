<?php

namespace Core;
use \App\Dao\Daos\Daos;

abstract class Service {
	public function getDaos() {
		return new Daos();
	}
}
