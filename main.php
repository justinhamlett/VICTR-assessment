<?php

// Define base directory
define('DOCROOT', __DIR__);

// Include autoload PHP file for Composer packages
require __DIR__ . '/vendor/autoload.php';


// Sets OAuth configuration to connect to Github API
$githubRepos = new App\Github();


// Retrieves each page of 100 repositories at a time
while ($repos = $githubRepos->getRepoPage()) {
	// Loops through each repository on each page
	foreach ($repos->items as $repo) {

		// Builds an array with defined columns to insert into the database
		$repoArray = $githubRepos->buildRepoArray($repo);

		// Check if repository exists in database already
		if ($instance->repoExists($repoArray)) {
			// Update repository in table with new data
//			$instance->updateRepo($repoArray);
		} else {
			// Inserts new repository into the table
			$instance->insertRepo($repoArray);
		}
	}
	// Increments and sets the page parameter in the API endpoint URL
	$githubRepos->nextPage();
}




//$repoArray = [
//	'repo_id' => '20693',
//	'name' => 'jmathai/epiphany',
//	'url' => 'https://github.com/jmathai',
//	'created_date' => date("Y-m-d H:i:s", strtotime('2008-05-30')),
//	'last_push_date' => date("Y-m-d H:i:s", strtotime('2016-03-10')),
//	'description' => 'A micro PHP framework that\'s fast, easy, clean and RESTful. The framework does not do a lot of magic under the hood. It is, by design, very simple and very powerful.',
//	'stars' => '681'
//];