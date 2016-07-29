<?php

// Include autoload PHP file for Composer packages
require __DIR__ . '/vendor/autoload.php';

// Retrieve environment variables defined in the .env file
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Load database configuration array with the defined variables in the .env file
$db_config = [
	'host' => getenv('DB_HOST'),
	'database' => getenv('DB_DATABASE'),
	'user' => getenv('DB_USER'),
	'pass' => getenv('DB_PASS')
];

// Initiate database connection
$instance = App\Database::getInstance($db_config);

// Create new table to store Github's most starred public PHP projects
$msg = $instance->createTable();

//echo $msg;

https://api.github.com/search/repositories?q=language:php&sort=stars&order=desc