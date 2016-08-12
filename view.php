<?php
	// Define base directory
	define('DOCROOT', __DIR__);

	// Include autoload PHP file for Composer packages
	require __DIR__ . '/vendor/autoload.php';

	$instance = \App\Database::getInstance();
	$repos = $instance->getRepos();
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
				<li class="nav-item">
					<a class="nav-link" href="execute.php">Execute</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="view.php">View</a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<div class="container">

	<div class="jumbotron">
		<h1 class="display-4">GitHub's Top Starred PHP Repositories</h1>
		<p>The table below only shows the 100 top starred PHP repositories at a time. Use the pager at the bottom of the
			table to view the next page of repositories.
		</p>
	</div>
</div>
<div class="container">
	<table class="table table-bordered repo-table">
		<thead>
			<tr>
				<th>ID (repo_id)</th>
				<th>Name (name)</th>
				<th>URL (url)</th>
				<th>Created Date (created_date)</th>
				<th>Last Push Date (last_push_date)</th>
				<th>Description (description)</th>
				<th>Number of Stars (stars)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($repos as $repo): ?>
			<tr>
				<td><?= $repo->repo_id ?></td>
				<td><?= $repo->name ?></td>
				<td><?= $repo->url ?></td>
				<td><?= $repo->created_date ?></td>
				<td><?= $repo->last_push_date ?></td>
				<td><?= $repo->description ?></td>
				<td><?= $repo->stars ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

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
