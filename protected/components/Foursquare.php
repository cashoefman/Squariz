<?php
Yii::import('application.extensions.foursquare.*');

/**
 * A wrapper for Foursquare API
 *
 */
class Foursquare extends EpiFoursquare{
	private $initialized = false;
	
	public function getIsInitialized(){
		return $this->initialized;
	}
	
	public function init(){
		$this->initialized = true;
	}
	
	public function getErrorMessage($error){
		switch($error){
			case 'invalid_auth': return 'OAuth token was not provided or was invalid.';
			case 'param_error': return 'A required parameter was missing or a parameter was malformed. This is also used if the resource ID in the path is incorrect.';
			case 'endpoint_error': return 'The requested path does not exist.';
			case 'not_authorized': return 'Although authentication succeeded, the acting user is not allowed to see this information due to privacy restrictions.';
			case 'rate_limit_exceeded': return 'Rate limit for this hour exceeded.';
			case 'deprecated': return 'Something about this request is using deprecated functionality, or the response format may be about to change.';
			case 'server_error': return 'Server is currently experiencing issues. Check status.foursquare.com for updates.';
			
			default: return 'Some other type of error occurred.';
		}
	}
	
	/**
	 * Date of the used API
	 * v=YYYYMMDD
	 */
	private $apiDate = '20120116';
	
	/**
	 * Automatically adds version param to the request
	 */
	
	public function delete($endpoint, $params = null)
	{
		$params['v']=$this->apiDate;
		return $this->request('DELETE', $endpoint, $params);
	}
	
	public function get($endpoint, $params = null)
	{
		$params['v']=$this->apiDate;
	    return $this->request('GET', $endpoint, $params);
	}
	
	public function post($endpoint, $params = null)
	{
	  	$params['v']=$this->apiDate;
	    return $this->request('POST', $endpoint, $params);
	}
}