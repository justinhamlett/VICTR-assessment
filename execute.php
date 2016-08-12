<?php
	// Define base directory
	define('DOCROOT', __DIR__);

	// Include autoload PHP file for Composer packages
	require __DIR__ . '/vendor/autoload.php';

	// Initiate Database class and create table defined in config
	$instance = \App\Database::getInstance();
	$instance->createTable();
	$db = $instance->getConfig();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="VICTR Assessment">
	<meta name="author" content="Justin Hamlett">

	<title>VICTR Assessment - Most Stared PHP GitHub Repositories</title>

	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/main.min.css" rel="stylesheet">
</head>

<body>

<div class="container">
	<nav class="navbar navbar-light bg-faded">
		<button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header">
			&#9776;
		</button>
		<div class="collapse navbar-toggleable-xs" id="navbar-header">
			<ul class="nav navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="setup.php">Setup</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="execute.php">Execute</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="view.php">View</a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<div class="container">

	<div class="jumbotron">
		<h1 class="display-4">Created Repository Database Table</h1>
		<p>The database table that will store the GitHub's PHP most starred repositories has been created. Below are the
			fields that were created inside the new table. Click the button below to see a table of the most starred GitHub's
			PHP repositories.
		</p>
		<p><a class="btn btn-lg btn-success" href="view.php" role="button">View the Most Starred PHP Repositories</a></p>
		<p>Note: The next page may have a delay in loading the repositories due to the application verifying all repository
			data is up-to-date.
		</p>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h4>The '<?= $db->table ?>' table has been created with the following fields:</h4>
			<ul class="list-group">
				<li class="list-group-item">ID (repo_id)</li>
				<li class="list-group-item">Name (name)</li>
				<li class="list-group-item">URL (url)</li>
				<li class="list-group-item">Created Date (created_date)</li>
				<li class="list-group-item">Last Push Date (last_push_date)</li>
				<li class="list-group-item">Description (description)</li>
				<li class="list-group-item">Number of Stars (stars)</li>
			</ul>
		</div>
	</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>')</script>
<script src="assets/js/tether.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
