<?php

namespace Core;
use \Core\Config\ConfigFactory;

class Helper {
    public function getConfig() {
        $configFactory = new ConfigFactory();
        return $configFactory->getConfig();
    }
}
