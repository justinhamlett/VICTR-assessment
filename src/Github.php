<?php

namespace App;

class Github extends Config
{
	private $apiUrl = "https://api.github.com/search/repositories?q=language:php&sort=stars&order=desc&per_page=100&page=";
	private $reposPerPage;
	private $totalRepos;
	private $totalPages;
	private $maxResultsCnt;
	private $errorMessage = NULL;
	private $repoCnt;

	private $repoArray = [];
	
	public $page;

	// GitHub config
	protected $githubArray = [
		'token' 	=> 'GITHUB_TOKEN',
		'userAgent' => 'GITHUB_USERAGENT'
	];

	/**
	 * Github API constructor.
	 *
	 */
	public function __construct()
	{
		parent::__construct($this->githubArray);

		$this->reposPerPage = 100;
		$this->page	= 1;
		$this->maxResultsCnt = 1000;
		$this->repoCnt = 0;

		$this->setTotalRepoCount();

	}

	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Executes a Curl request to retrieve repository data
	 *
	 * @param $url
	 * @return bool|mixed
	 */
	private function curlRequest($url)
	{
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->config->userAgent);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: token ' . $this->config->token]);

		$curlResponse = curl_exec($curl);

		if ($curlResponse === false) {
			$this->errorMessage = curl_getinfo($curl);

			curl_close($curl);

			return FALSE;
		}

		curl_close($curl);

		return json_decode($curlResponse);
	}

	/**
	 * Preform a Curl request to calculate the total number of repositories returned from Github API
	 */
	private function setTotalRepoCount()
	{
		$results = $this->curlRequest($this->apiUrl . $this->page);

		if ($results !== FALSE) {
			$this->totalRepos = $results->total_count;
			
			$this->setTotalPageCount();
		}
	}

	/**
	 * Calculates how many pages of repositories that the Github API returns
	 */
	private function setTotalPageCount()
	{
		$this->totalPages = ceil($this->totalRepos/$this->reposPerPage);
	}

	/**
	 * Retrieve a page of repositories from the Github API
	 *
	 * @return bool|mixed
	 */
	public function getRepoPage()
	{
		while (($this->page <= $this->totalPages) && ($this->page <= ($this->maxResultsCnt/$this->reposPerPage))) {
			$this->repoArray = $this->curlRequest($this->apiUrl . $this->page);
			$this->formatRepoArray();
			$this->nextPage();
		}

		return FALSE;
	}

	/**
	 *
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
	 *
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