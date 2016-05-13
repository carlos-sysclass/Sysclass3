<?php 
/**
 * @package PlicoLib\Controllers\Utilities
 */
class bitbucketClientController extends oAuthClientController
{
	public function init($url, $method, $format, $root=NULL, $basePath="")
	{
		parent::init($url, $method, $format, $root, $basePath);
		$this->host = "bitbucket.org";
		$this->endpoints = array(
			"request"	=> "/api/1.0/oauth/request_token",
	    	"access" 	=> "/api/1.0/oauth/access_token",
	      	"user" 		=> "/api/1.0/user",
	      	"issues"  	=> "/api/1.0/repositories/{accountname}/{repo_slug}/issues",
	      	"issue"  	=> "/api/1.0/repositories/{accountname}/{repo_slug}/issues/{issue_id}"
		);

		$this->oAuthHelper = $this->getOAuthHandler();

		if (!$this->checkAuthorization()) {
			$this->doAuthorization();
		}
		$plicolib = PlicoLib::instance();

		$this->endpoint_params = $plicolib->get('bitbucket/endpoint_params');
	}

	/**
	 *
	 * @url GET /bitbucket/issues
	 */
	public function doGetIssues()
	{
		//$this->doAuthorization();
		$response = $this->oAuthHelper->doRequest('issues', "GET", array('kind' => '!proposal', 'sort' => 'status'), $this->endpoint_params);

		if (!$response) {
			return $this->invalidRequestError();
		}

		return $response;
	}

	/*
	public function doGetIssues()
	{
		var_dump(array_merge($this->endpoint_params, array("issue_id" => 2)));
		$response = $this->oAuthHelper->doRequest('issue', "GET", array('kind' => 'proposal'), array_merge($this->endpoint_params, array("{issue_id}" => 2)));

		return $response;
	}
	*/
}
