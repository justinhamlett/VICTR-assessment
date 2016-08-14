<?php

namespace App;

/**
 * Class Github
 *
 * Class connects to Github's API through Curl requests and manages and structures data
 * needed for requests and data returned from API. Also uses /App/Database object to store
 * repositories returned from Github's API.
 *
 * @package App
 */
class Github extends Config
{
	/**
	 * Github's API URL with defined PHP request variables
	 *
	 * @var string
	 */
	private $apiUrl = "https://api.github.com/search/repositories?q=language:php&sort=stars&order=desc&per_page=100&page=";
	private $reposPerPage = 100;
	private $maxResultsCnt = 1000;

	private $repoArray = [];
	
	public $page = 1;

	/**
	 * Config '.env' file array.
	 *
	 * $envVars[$key => $value]
	 * $key - Config object variable
	 * $value - '.env' file variable
	 *
	 * @var array
	 */
	protected $githubArray = [
		'token' 	=> 'GITHUB_TOKEN',
		'userAgent' => 'GITHUB_USERAGENT'
	];

	/**
	 * Github constructor calls parent calls to set Github config values
	 */
	public function __construct()
	{
		parent::__construct($this->githubArray);
	}

	/**
	 * Executes a Curl request to retrieve repository data
	 *
	 * @param $url
	 * @return mixed
	 */
	private function curlRequest($url)
	{
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->config->userAgent);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: token ' . $this->config->token]);

		$curlResponse = curl_exec($curl);

		if ($curlResponse === false) {
			print_r(curl_getinfo($curl));

			curl_close($curl);
		}

		curl_close($curl);

		return json_decode($curlResponse);
	}

	/**
	 * Calculate the total number of PHP starred repositories returned from Github API
	 *
	 * @return mixed
	 */
	private function getTotalRepoCount()
	{
		$results = $this->curlRequest($this->apiUrl . $this->page);

		return $results->total_count;
	}

	/**
	 * Calculates how many pages of PHP starred repositories that the Github API returns
	 *
	 * @return float
	 */
	private function setTotalPageCount()
	{
		// Retrieve total amount of PHP starred repositories
		$totalRepos = $this->getTotalRepoCount();

		// Calculate and return total pages of PHP starred repositories
		return ceil($totalRepos/$this->reposPerPage);
	}

	/**
	 * Retrieve a page of PHP starred repositories from the Github API
	 *
	 * @return bool|mixed
	 */
	public function getRepoPage()
	{
		// Get total repositories page count
		$totalPages = $this->setTotalPageCount();

		// Checks if downloading is past last page, if not download page of PHP starred repositories
		while (($this->page <= $totalPages) && ($this->page <= ($this->maxResultsCnt/$this->reposPerPage))) {
			$this->repoArray = $this->curlRequest($this->apiUrl . $this->page);
			$this->formatRepoArray();
			$this->nextPage();
		}

		return FALSE;
	}

	/**
	 * Loops through downloaded page of repositories and formats them to be saved to database
	 */
	private function formatRepoArray()
	{
		foreach ($this->repoArray->items as $repo)
		{
			$formatted = $this->buildRepoArray($repo);
			$this->storeRepo($formatted);
		}
	}

	/**
	 * Stores repository in database
	 *
	 * @param $repo
	 */
	private function storeRepo($repo)
	{
		$instance = Database::getInstance();

		// Check if repository exists in database already
		if ($instance->repoExists($repo)) {
			// Update repository in table with new data
			$instance->updateRepo($repo);
		} else {
			// Inserts new repository into the table
			$instance->insertRepo($repo);
		}

	}

	/**
	 * Increments the 'page' parameter by one for Github API requests
	 */
	public function nextPage()
	{
		$this->page++;
	}

	/**
	 * Builds an array of the Github repository, formatted for the MySQL table
	 *
	 * @param $repo
	 * @return array
	 */
	public function buildRepoArray($repo)
	{
		$this->repoArray = [
			'repo_id' => $repo->id,
			'name' => $repo->full_name,
			'url' => $repo->owner->html_url,
			'created_date' => date("Y-m-d H:i:s", strtotime($repo->created_at)),
			'last_push_date' => date("Y-m-d H:i:s", strtotime($repo->pushed_at)),
			'description' => $repo->description,
			'stars' => $repo->stargazers_count
		];

		return $this->repoArray;
	}
}