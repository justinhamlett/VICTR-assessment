# VICTR-assessment

1. Clone the VICTR-assessment Github repo to your local machine.

    `git clone https://github.com/justinhamlett/VICTR-assessment.git`
    
2. Navigate to the VICTR-assessment base directory of the Github repository in terminal and install the Composer dependencies.

    `cd VICTR-assessment/`
    
    `composer install`
    
3. Copy the file `.env.example` and named it `.env`.
    
4. Generate a Github personal access token with a scope that allows access to public repositories.
 
    `https://help.github.com/articles/creating-an-access-token-for-command-line-use/`

5. Update the `GITHUB_TOKEN` variable in the `.env` file with the generated Github personal access token.

6. Update the `GITHUB_USERAGENT` variable in the `.env` file with you Github username.

7. Create a MySQL database for the VICTR-assessment app and a corresponding user to access the created database. For example:

    `create databse victr_github;`
    
8. Update the `.env` file with the new database and user credentials.

9. Update the `DB_REPO_TABLE` variable in the `.env` file that will be used to store Github repositories.