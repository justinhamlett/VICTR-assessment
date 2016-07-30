<?php

namespace App;

class Github
{
	private $apiUrl = "https://api.github.com/search/repositories?q=language:php&sort=stars&order=desc&per_page=100&page=";
	private $apiHeader = [];
	private $reposPerPage;
	private $githubUserAgent;
	private $totalRepos;
	private $totalPages;
	private $maxResultsCnt;
	private $errorMessage = NULL;
	private $repoCnt;

	private $repoArray = [];
	
	public $page;

	/**
	 * Github API constructor.
	 *
	 * @param $githubUserAgent
	 * @param $userToken
	 */
	public function __construct($githubUserAgent, $userToken)
	{
		$this->githubUserAgent = $githubUserAgent;
		$this->apiHeader[] = 'Authorization: token ' . $userToken;
		$this->reposPerPage = 100;
		$this->page	= 1;
		$this->maxResultsCnt = 1000;
		$this->repoCnt = 0;
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
		curl_setopt($curl, CURLOPT_USERAGENT, $this->githubUserAgent);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->apiHeader);

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
	public function setTotalRepoCount()
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
		if (($this->page <= $this->totalPages) && ($this->page <= ($this->maxResultsCnt/$this->reposPerPage))) {
			return $this->curlRequest($this->apiUrl . $this->page);
		}

		return FALSE;
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