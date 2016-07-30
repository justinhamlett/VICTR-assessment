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

	private $table;

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
			$this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (\PDOException $e) {
			return 'Error: ' . $e->getMessage();
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
	 * Sets table name that will be used to store Github repositories
	 *
	 * @param $table
	 */
	public function setTableName($table)
	{
		$this->table = $table;
	}

	/**
	 * Check the information_scheme database to see if specified table exists
	 *
	 * @return string
	 */
	public function tableExists()
	{
		try {
			$stmt = $this->conn->prepare('SELECT count(*) FROM information_schema.tables WHERE table_schema = :database AND table_name = :table LIMIT 1');
			$stmt->execute(['database' => $this->database, 'table' => $this->table]);

			$result = $stmt->fetchColumn();

			return $result;

		} catch(\PDOException $e) {
			$msg = 'Error: ' . $e->getMessage();

			return $msg;
		}
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
			CREATE TABLE {$this->table}(
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

		try {
			// Execute SQL to create table
			$table = $this->conn->exec($github_table);

			$msg = $this->table . ' was created successfully.';
		} catch(\PDOException $e) {
			$msg = 'Error: ' . $e->getMessage();
		}

		return $msg;
	}

	/**
	 * Checks if repository exists in the table
	 *
	 * @param $repo
	 * @return bool|string
	 */
	public function repoExists($repo)
	{
		try {
			$stmt = $this->conn->prepare("SELECT count(*) FROM {$this->table} WHERE repo_id = :repo_id");
			$stmt->execute(['repo_id' => $repo['repo_id']]);

			$result = $stmt->fetchColumn();

			if ($result == 0) {
				return FALSE;
			} else {
				return TRUE;
			}

		} catch(\PDOException $e) {
			$msg = 'Error: ' . $e->getMessage();
		}

		return $msg;
	}

	/**
	 * Inserts a new repository into the table
	 *
	 * @param array $repo
	 */
	public function insertRepo($repo = array())
	{
		try {
			$stmt = $this->conn->prepare("INSERT INTO {$this->table} VALUES(:repo_id, :name, :url, :created_date, :last_push_date, :description, :stars)");
			$stmt->execute([
				'repo_id' => $repo['repo_id'],
				'name' => $repo['name'],
				'url' => $repo['url'],
				'created_date' => $repo['created_date'],
				'last_push_date' => $repo['last_push_date'],
				'description' => $repo['description'],
				'stars' => $repo['stars']
			]);

		} catch(\PDOException $e) {
			$msg = 'Error: ' . $e->getMessage();
		}
	}

	/**
	 * Updates repository in the table with new data
	 *
	 * @param array $repo
	 */
	public function updateRepo($repo = array())
	{
		try {
			$stmt = $this->conn->prepare("UPDATE {$this->table} SET name = :name, url = :url, created_date = :created_date, last_push_date = :last_push_date, description = :description, stars = :stars WHERE repo_id = :repo_id");
			$stmt->execute([
				'repo_id' => $repo['repo_id'],
				'name' => $repo['name'],
				'url' => $repo['url'],
				'created_date' => $repo['created_date'],
				'last_push_date' => $repo['last_push_date'],
				'description' => $repo['description'],
				'stars' => $repo['stars']
			]);

		} catch(\PDOException $e) {
			$msg = 'Error: ' . $e->getMessage();
		}
	}
}
