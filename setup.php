<?php
	// Define base directory
	define('DOCROOT', __DIR__);

	// Include autoload PHP file for Composer packages
	require __DIR__ . '/vendor/autoload.php';

	$instance = \App\Database::getInstance();
	$db = $instance->getConfig();

	$github = new \App\Github();
	$git = $github->getConfig();
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
				<li class="nav-item active">
					<a class="nav-link" href="setup.php">Setup</a>
				</li>
				<li class="nav-item">
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
		<h1 class="display-4">Setup Application</h1>
		<p>If you have read the README.md file and followed the setup steps, then all the credentials and config parameters
			should be in the .env file. This includes your GitHub username and personal access token, and database connection
			credentials with the desired table name to create and store the repositories.
		</p>
		<p><a class="btn btn-lg btn-success" href="execute.php" role="button">Create Database Table</a></p>
		<p>Check the table below to validate that all values are correct. If a row is red, there is an issue with retrieving
			the variable. Hopefully all rows are green and it's time for the next step.
		</p>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<h4>Application Credentials</h4>
			<ul class="list-group">
				<li class="list-group-item list-group-item-action<?= (!$git->token) ? ' list-group-item-danger' : ' list-group-item-success' ?>">GITHUB_TOKEN="<?= $git->token ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$git->userAgent) ? ' list-group-item-danger' : ' list-group-item-success' ?>">GITHUB_USERAGENT="<?= $git->userAgent ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$db->host) ? ' list-group-item-danger' : ' list-group-item-success' ?>">DB_HOST="<?= $db->host ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$db->database) ? ' list-group-item-danger' : ' list-group-item-success' ?>">DB_DATABASE="<?= $db->database ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$db->user) ? ' list-group-item-danger' : ' list-group-item-success' ?>">DB_USER="<?= $db->user ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$db->pass) ? ' list-group-item-danger' : ' list-group-item-success' ?>">DB_PASS="<?= str_repeat('*', strlen($db->pass)) ?>"</li>
				<li class="list-group-item list-group-item-action<?= (!$db->table) ? ' list-group-item-danger' : ' list-group-item-success' ?>">DB_REPO_TABLE="<?= $db->table ?>"</li>
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
