<?php

namespace Core;
use \App\Dao\Daos;

abstract class Service {
	public function getDaos() {
		return new Daos();
	}
}
