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
						<li class="nav-item active">
							<a class="nav-link" href="index.php">Home</a>
						</li>
						<li class="nav-item">
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
				<h1 class="display-4">GitHub Most Starred PHP Repositories</h1>
				<p class="lead">Ever wonder which GitHub PHP repositories has the most stars? Well you've come to the right
					place. This web application will use the GitHub API to retrieve the most starred public PHP projects
					and store each repository's basic details into a MySQL database for future retrieval.
				</p>
				<p><a class="btn btn-lg btn-success" href="setup.php" role="button">Application Setup</a></p>
			</div>

			<div class="row marketing">
				<div class="col-lg-6">
					<h4><strong><u>First step!</u></strong></h4>
					<p>Read the README.md file in this repository and follow the steps to configure the application to
						execute correctly.
					</p>

					<h4>GitHub API Access</h4>
					<span>Due to GitHub API rate limits, this application requires:</span>
						<ul>
							<li>Your GitHub's Personal API Access Token</li>
							<li>Your GitHub's User Agent which is your GitHub's username</li>
						</ul>

					<h4>MySQL</h4>
					<p>As explained in the README.md, you will need to create a MySQL user/pass and a database that the newly
						created user has privileges to.
					</p>

				</div>

				<div class="col-lg-6">

					<h4>Requirements:</h4>
						<ul>
							<li>Apache or NGINX web server</li>
							<li>PHP 5.4.0 or newer</li>
							<li>MySQL 14.14 or newer</li>
							<li>Node.js and npm</li>
							<li>Composer</li>
						</ul>

					<h4>Technologies Used:</h4>
						<ul>
							<li>PHP</li>
							<li>MySQL</li>
							<li>Composer</li>
							<li>Bower</li>
							<li>npm</li>
							<li>Gulp w/multiple related processing packages</li>
							<li>SASS Preprocessor</li>
							<li>PHP dotenv</li>
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
