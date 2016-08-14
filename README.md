# VICTR-assessment

### Assessment Details:

> ##### Popular PHP Repositories on GitHub Using PHP and MySQL, complete the following:
> 
>  1. Use the GitHub API to retrieve the most starred public PHP projects. Store the list of repositories in a MySQL table. The table must contain the repository ID, name, URL, created date, last push date, description, and number of stars. This process should also be able to update existing project details.
> 
>  2. Using the data in the table created in step 1, create an interface that displays a list of the GitHub repositories and allows the user to click through to view details on each one. Be sure to include all of the fields in step 1 – displayed in either the list or detailed view.
> 
>  3. Create a README file with a description of your architecture and notes on installation of your application. You are free to use any PHP, JavaScript, or CSS frameworks as you see fit.

### Project Architecture:

##### Requirements:
    * Apache or NGINX web server
    * PHP 5.4.0 or newer
    * MySQL
    * Node.js and npm
    * Composer

##### Technologies Used:
    * PHP
    * MySQL
    * Composer
    * Bower
    * npm
    * Gulp w/multiple related processing packages
    * SASS Preprocessor
    * PHP dotenv

### Installation:

1. Clone the VICTR-assessment Github repo to your local machine.

    * `git clone https://github.com/justinhamlett/VICTR-assessment.git VICTR-assessment`
    
2. In terminal, navigate to the directory, `VICTR-assessment/`, the Github repository base directory.

    * `cd VICTR-assessment/`
    
3. Install the Composer dependencies listed in the `composer.json` file. *(PHP backend requirements)*

	* `composer install`
    
4. Copy the PHP dotenv file `.env.example` and name it `.env`.

	* `cp -a .env.example .env`
    
5. Generate a Github personal access token with a scope that allows access to public repositories.
 
    * <https://help.github.com/articles/creating-an-access-token-for-command-line-use/>

6. Update the `GITHUB_TOKEN` variable in the `.env` file with the generated Github personal access token.

	* `GITHUB_TOKEN="8b516e1f8jhes4a245gro8724gitgu8581dee45aecf"`

7. Update the `GITHUB_USERAGENT` variable in the `.env` file with your Github username.

	* `GITHUB_USERAGENT="github_username"`

8. Create a MySQL database for the VICTR-assessment app and a corresponding user to access the created database. For example:

	* `CREATE DATABASE victr_github;`
	* `CREATE USER 'victr'@'localhost' IDENTIFIED BY 'password';`
	* `GRANT ALL PRIVILEGES ON *.* TO 'victr'@'localhost';`
	* `GRANT ALL PRIVILEGES ON victr_github.* TO 'victr'@'localhost';`
	* `FLUSH PRIVILEGES;`
    
9. Update the `.env` file with the new database and user credentials.

	* `DB_HOST="localhost"`
	* `DB_DATABASE="victr_github"`
	* `DB_USER="victr"`
	* `DB_PASS="password"`

10. Update the `DB_REPO_TABLE` variable in the `.env` file that will be used to store Github repositories.

	* `DB_REPO_TABLE="repos"`

11. Install the npm dependencies listed in the `package.json` file. *(Bower, Gulp and Gulp package requirements)*

	* `npm install`

12. Install the Bower dependencies listed in the `bower.json` file. *(Frontend requirements)*

	* `bower install`


