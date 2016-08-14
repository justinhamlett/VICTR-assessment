<?php

namespace App;

use Dotenv\Dotenv;

/**
 * Config Class
 *
 * Responsible for loading the PHP dotenv config file into a class object.
 *
 * Class Config
 * @package App
 */
class Config
{
	/**
	 * Config environment variable object to store all values
	 * defined in the '.env' file.
	 *
	 * @var \stdClass
	 */
	protected $config;

	/**
	 * Config constructor with an array parameter passed in from parent class
	 * defining values to store in $config object from the loaded '.env' file.
	 *
	 * @param array $envConfig
	 */
	protected function __construct($envConfig = [])
	{
		if (isset($envConfig)) {
			// Load '.env' file
			$dotenv = new Dotenv(DOCROOT);
			$dotenv->load();

			$this->config = new \stdClass();

			// Loop through parent class array to set values
			foreach ($envConfig as $key => $value) {
				$this->config->{$key} = getenv($value);
			}
		}
	}

	/**
	 * Return loaded $config object to parent class.
	 *
	 * @return \stdClass
	 */
	public function getConfig()
	{
		return $this->config;
	}
}