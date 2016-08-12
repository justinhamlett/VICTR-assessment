<?php

namespace App;

use \PDO;

class Database extends Config
{
	private static $instance = null;
	private $conn;

	private $errors = [];

	// Database config
	protected $dbArray = [
		'host' 		=> 'DB_HOST',
		'database' 	=> 'DB_DATABASE',
		'user' 		=> 'DB_USER',
		'pass' 		=> 'DB_PASS',
		'table' 	=> 'DB_REPO_TABLE'
	];

	/**
	 * Database constructor.
	 *
	 */
	protected function __construct()
	{
		parent::__construct($this->dbArray);

		try {
			$this->conn = new PDO("mysql:host={$this->config->host};dbname={$this->config->database}", $this->config->user, $this->config->pass);

			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (\PDOException $e) {
			return 'Error: ' . $e->getMessage();
		}

		return null;
	}

	/**
	 * Returns database instance
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

	public function getConfig()
	{
		return $this->config;
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
		try {
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($params);

			if ($return == TRUE) {
				$result = [];

				while ($rows = $stmt->fetchObject()) {
					$result[] = $rows;
				}

				return $result;
			}

		} catch (\PDOException $e) {
			$this->errors[] = 'Error: ' . $e->getMessage();

			return $this->errors;
		}
	}

	/**
	 * Check the information_scheme database to see if specified table exists
	 *
	 * @return array
	 */
	public function tableExists()
	{
		$sql = "SELECT count(*) as cnt FROM information_schema.tables WHERE table_schema = :database AND table_name = :table LIMIT 1";
		$parms = ['database' => $this->config->database, 'table' => $this->config->table];

		return $this->executeSQL($sql, $parms);
	}

	/**
	 * Create Github repository table
	 */
	public function createTable()
	{

		$exists = $this->tableExists();

		if ($exists[0]->cnt == 0) {
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
		$sql = "SELECT count(*) as cnt FROM {$this->config->table} WHERE repo_id = :repo_id";
		$parms = ['repo_id' => $repo['repo_id']];

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
		$sql = "INSERT INTO {$this->config->table} VALUES(:repo_id, :name, :url, :created_date, :last_push_date, :description, :stars)";
		$parms = [
			'repo_id' => $repo['repo_id'],
			'name' => $repo['name'],
			'url' => $repo['url'],
			'created_date' => $repo['created_date'],
			'last_push_date' => $repo['last_push_date'],
			'description' => $repo['description'],
			'stars' => $repo['stars']
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
		$sql = "UPDATE {$this->config->table} SET name = :name, url = :url, created_date = :created_date, last_push_date = :last_push_date, description = :description, stars = :stars WHERE repo_id = :repo_id";
		$parms = [
			'repo_id' => $repo['repo_id'],
			'name' => $repo['name'],
			'url' => $repo['url'],
			'created_date' => $repo['created_date'],
			'last_push_date' => $repo['last_push_date'],
			'description' => $repo['description'],
			'stars' => $repo['stars']
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
		$sql = "SELECT * FROM {$this->config->table} LIMIT {$count}";

		$results = $this->executeSQL($sql);

		return $results;
	}
}
