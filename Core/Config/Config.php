<?php

namespace Core\Config;

abstract class Config {

	//Database
	private $databaseUser;
	private $databasePassword;
	private $databaseName;
	private $databaseHost;
	private $driver;

	//Paths
	private $cssUrl = APP_URL . 'public' . DS . 'css' . DS;
	private $jsUrl = APP_URL . 'public' . DS . 'js' . DS;
	private $imgUrl = APP_URL . 'public' . DS . 'images' . DS;


	public function setDatabaseName(string $databaseName) {
		$this->databaseName = $databaseName;
	}

	public function getDatabaseName() :string {
		return $this->databaseName;
	}

	public function setDatabaseUser(string $databaseUser) {
		$this->databaseUser = $databaseUser;
	}

	public function getDatabaseUser() :string {
		return $this->databaseUser;
	}

	public function setDatabasePassword(string $databasePassword) {
		$this->databasePassword = $databasePassword;
	}

	public function getDatabasePassword() :string {
		return $this->databasePassword;
	}

	public function setDatabaseHost(string $databaseHost) {
		$this->databaseHost = $databaseHost;
	}

	public function getDatabaseHost() :string {
		return $this->databaseHost;
	}

	public function setCssUrl(string $cssUrl) {
		$this->cssUrl = $cssUrl;
	}

	public function getCssUrl() :string {
		return $this->cssUrl;
	}
	public function setJsUrl(string $jsUrl) {
		$this->jsUrl = $jsUrl;
	}

	public function getJsUrl() :string {
		return $this->jsUrl;
	}

	public function setDriver(string $driver) {
		$this->driver = $driver;
	}

	public function getDriver($driver) :string {
		return $this->driver;
	}
}
