<?php

namespace App;

use \PDO;

class Database
{
	private static $instance = null;
	private $conn;

	private $host;
	private $database;
	private $user;
	private $pass;

	/**
	 * Database constructor.
	 *
	 * @param array $db_config
	 */
	private function __construct($db_config = array())
	{
		$this->host = $db_config['host'];
		$this->database = $db_config['database'];
		$this->user = $db_config['user'];
		$this->pass = $db_config['pass'];

		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		} catch (\PDOException $e) {
			return $e->getMessage();
		}

		return null;
	}

	/**
	 * Returns database instance
	 *
	 * @param array $db_config
	 * @return Database|null
	 */
	public static function getInstance($db_config = array())
	{
		// check if instance already exists
		if (self::$instance == null) {
			self::$instance = new Database($db_config);
		}

		return self::$instance;
	}

	/**
	 * Returns database connection
	 *
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->conn;
	}

	/**
	 * Create Github repository table
	 *
	 * @return string
	 */
	public function createTable()
	{
		// Define SQL to create Github repository table
		$github_table = <<<EOSQL
			CREATE TABLE repos(
				repo_id int(11) NOT NULL,
				name varchar(255) NOT NULL,
				url varchar(255) NOT NULL,
				created_date date NOT NULL,
				last_push_date date NOT NULL,
				description text NOT NULL,
				stars int(11) NOT NULL,
				PRIMARY KEY (repo_id)
			) ENGINE=InnoDB
EOSQL;

		// Execute SQL to create table
		$table = $this->conn->exec($github_table);

		// Check if table was created successfully
		if ($table !== FALSE) {
			$msg = "Tables are created successfully.";
		} else {
			$msg = "Error creating the Github repository table.";
		}

		return $msg;
	}
}
