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
// Load the table name that will be used to store Github repos in the database
$repoTableName = getenv('DB_REPO_TABLE');

// Define the personal access token and user agent to authorize use of the Github API
$githubToken = getenv('GITHUB_TOKEN');
$githubUserAgent = getenv('GITHUB_USERAGENT');

// Initiate database connection
$instance = App\Database::getInstance($db_config);

// Sets the table name variable in the Database class
$instance->setTableName($repoTableName);

// Check if table exists before creating a table
if ($instance->tableExists() == 0) {
	// Create new table to store Github's most starred public PHP projects
	$msg = $instance->createTable();
}

// Sets OAuth configuration to connect to Github API
$githubRepos = new App\Github($githubUserAgent, $githubToken);

// Sets the total amount of repos that the API returns of the matched search criteria
$githubRepos->setTotalRepoCount();

// Retrieves each page of 100 repositories at a time
while ($repos = $githubRepos->getRepoPage()) {
	// Loops through each repository on each page
	foreach ($repos->items as $repo) {

		// Builds an array with defined columns to insert into the database
		$repoArray = $githubRepos->buildRepoArray($repo);

		// Check if repository exists in database already
		if ($instance->repoExists($repoArray)) {
			// Update repository in table with new data
			$instance->updateRepo($repoArray);
		} else {
			// Inserts new repository into the table
			$instance->insertRepo($repoArray);
		}
	}
	// Increments and sets the page parameter in the API endpoint URL
	$githubRepos->nextPage();
}
