<?php

namespace App;

use \PDO;

/**
 * Class Database
 * 
 * Singleton class that is the database layer between the Github API and storing and
 * retrieving information. This class uses the PDO class to create the Github table
 * and store and retrieve Github data.
 * 
 * @package App
 */
class Database extends Config
{
	/**
	 * Static Database class instance.
	 * 
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Database PDO connection.
	 * 
	 * @var PDO
	 */
	private $conn;

	/**
	 * Database PDO errors array.
	 * 
	 * @var array
	 */
	private $errors = [];

	/**
	 * Config '.env' file array.
	 *
	 * $envVars[$key => $value]
	 * $key - Config object variable
	 * $value - '.env' file variable
	 * 
	 * @var array
	 */
	protected $envVars = [
		'host' 		=> 'DB_HOST',
		'database' 	=> 'DB_DATABASE',
		'user' 		=> 'DB_USER',
		'pass' 		=> 'DB_PASS',
		'table' 	=> 'DB_REPO_TABLE'
	];

	/**
	 * Database constructor calls Config class constructor to define database connection
	 * variables and creates PDO connection.
	 */
	protected function __construct()
	{
		// Creates Database class config object values
		parent::__construct($this->envVars);

		// Create PDO connection or throw a PDO error
		try {
			$this->conn = new PDO("mysql:host={$this->config->host};dbname={$this->config->database}", $this->config->user, $this->config->pass);

			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (\PDOException $e) {
			$this->errors[] = 'Error: ' . $e->getMessage();

			// Display connection errors
			$this->displayErrors();
		}
	}

	/**
	 * Creates and/or returns database instance.
	 *
	 * @return Database|null
	 */
	public static function getInstance()
	{
		// check if instance already exists
		if (self::$instance == null) {
			self::$instance = new Database();
		}

		return self::$instance;
	}

	/**
	 * Executes prepared SQL statements and catches errors
	 *
	 * @param $sql
	 * @param array $params
	 * @param bool $return
	 * @return array
	 */
	private function executeSQL($sql, $params = [], $return = TRUE)
	{
		// PDO objects array
		$result = [];

		try {
			// Prepares connection with the SQL parameter
			$stmt = $this->conn->prepare($sql);
			// Execute prepared connection with SQL parameter values
			$stmt->execute($params);

			// If connection returns data, iterate and store in array
			if ($return == TRUE) {
				// Iterate and store SQL result objects in array
				while ($rows = $stmt->fetchObject()) {
					$result[] = $rows;
				}
			}
		} catch (\PDOException $e) {
			$this->errors[] = 'Error: ' . $e->getMessage();

			// Call method to display errors
			$this->displayErrors();
		}

		// Returned PDO objects array
		return $result;
	}

	/**
	 * Prints SQL transaction errors
	 */
	public function displayErrors()
	{
		print_r($this->errors);
	}

	/**
	 * Check the 'information_scheme' database to see if specified table exists
	 *
	 * @return array
	 */
	public function tableExists()
	{
		// Sets SELECT SQL statement to check if specified table exists
		$sql = "SELECT count(*) as cnt FROM information_schema.tables WHERE table_schema = :database AND table_name = :table LIMIT 1";
		$parms = [
			'database' => $this->config->database,
			'table' => $this->config->table
		];

		return $this->executeSQL($sql, $parms);
	}

	/**
	 * Create Github repository table
	 */
	public function createTable()
	{
		// Checks if table exists before executing 'CREATE TABLE' SQL statement
		$exists = $this->tableExists();

		if ($exists[0]->cnt == 0) {
			// Set CREATE SQL statement to create new table with specified name
			$sql = <<<EOSQL
			CREATE TABLE {$this->config->table}(
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

			// No results returned since third parameter set to FALSE
			$this->executeSQL($sql, [], FALSE);
		}
	}

	/**
	 * Checks if repository exists in the table
	 *
	 * @param $repo
	 * @return array
	 */
	public function repoExists($repo)
	{
		// Sets SELECT SQL statement to check if repository exists
		$sql = "SELECT count(*) as cnt FROM {$this->config->table} WHERE repo_id = :repo_id";
		$parms = [
			'repo_id' => $repo['repo_id']
		];

		return $this->executeSQL($sql, $parms);
	}

	/**
	 * Inserts a new repository into the table
	 *
	 * @param array $repo
	 * @return array
	 */
	public function insertRepo($repo = [])
	{
		// Sets INSERT SQL statement with all repository values
		$sql = "INSERT INTO {$this->config->table} VALUES(:repo_id, :name, :url, :created_date, :last_push_date, :description, :stars)";
		$parms = [
			'repo_id' 			=> $repo['repo_id'],
			'name' 				=> $repo['name'],
			'url' 				=> $repo['url'],
			'created_date' 		=> $repo['created_date'],
			'last_push_date' 	=> $repo['last_push_date'],
			'description' 		=> $repo['description'],
			'stars' 			=> $repo['stars']
		];

		return $this->executeSQL($sql, $parms, FALSE);
	}

	/**
	 * Updates repository in the table with new data
	 *
	 * @param array $repo
	 * @return array
	 */
	public function updateRepo($repo = [])
	{
		// Sets UPDATE SQL statement with all repository values
		$sql = "UPDATE {$this->config->table} SET name = :name, url = :url, created_date = :created_date, last_push_date = :last_push_date, description = :description, stars = :stars WHERE repo_id = :repo_id";
		$parms = [
			'repo_id' 			=> $repo['repo_id'],
			'name' 				=> $repo['name'],
			'url' 				=> $repo['url'],
			'created_date' 		=> $repo['created_date'],
			'last_push_date' 	=> $repo['last_push_date'],
			'description' 		=> $repo['description'],
			'stars' 			=> $repo['stars']
		];

		return $this->executeSQL($sql, $parms, FALSE);
	}

	/**
	 * Retrieves repository records from the database
	 *
	 * @param int $count
	 * @return array
	 */
	public function getRepos($count = 100)
	{
		// Set SELECT SQL statement to retrieve repository values from database
		$sql = "SELECT * FROM {$this->config->table} LIMIT {$count}";

		return $this->executeSQL($sql);
	}
}
