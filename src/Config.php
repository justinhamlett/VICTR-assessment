<?php

namespace App;

use Dotenv\Dotenv;

class Config
{

	protected $config;

	protected function __construct($envConfig = [])
	{
		if (isset($envConfig)) {
			$dotenv = new Dotenv(DOCROOT);
			$dotenv->load();

			$this->config = new \stdClass();

			foreach ($envConfig as $key => $value) {
				$this->config->{$key} = getenv($value);
			}
		}
	}


}

