<?php // include("main.php"); ?>

<?php
	require 'main.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="VICTR Assessment">
	<meta name="author" content="Justin Hamlett">

	<title>VICTR Assessment - Most Stared PHP Github Repositories</title>

	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/main.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark navbar-fixed-top bg-inverse">
	<a class="navbar-brand" href="#">Github Most Starred PHP Repositories</a>
	<div id="navbar">
		<nav class="nav navbar-nav pull-xs-left">
			<a class="nav-item nav-link" href="#">Setup</a>
			<a class="nav-item nav-link" href="#">Execute</a>
			<a class="nav-item nav-link" href="#">Update</a>
			<a class="nav-item nav-link" href="#">Reset</a>
		</nav>
		<form class="pull-xs-right">
			<input type="text" class="form-control" placeholder="Search...">
		</form>
	</div>
</nav>

<div class="container-fluid">
	<div class="row">

		<!-- <div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
				<li><a href="#">Reports</a></li>
				<li><a href="#">Analytics</a></li>
				<li><a href="#">Export</a></li>
			</ul>
		</div> -->

	<!--	<div class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 main"> -->
		<div class="col-sm-12 offset-sm-12 col-md-12 offset-md-12 main">
			<h1>Dashboard</h1>
			<h2>Most Starred PHP Repositories</h2>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>URL</th>
						<th>Created Date</th>
						<th>Last Push Date</th>
						<th>Description</th>
						<th>Stars</th>
					</tr>
					</thead>
					<tbody>
					<?php $repos = $instance->getRepos(); foreach ($repos as $repo): ?>
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
