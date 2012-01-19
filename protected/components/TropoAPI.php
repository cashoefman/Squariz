<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tropo'.DIRECTORY_SEPARATOR.'tropo.class.php';

/**
 * A wrapper for Tropo API
 */
class TropoAPI extends Tropo{
	private $initialized = false;
	
	public function getIsInitialized(){
		return $this->initialized;
	}
	
	public function init(){
		$this->initialized = true;
	}
	
	public function say($say, Array $params=NULL){
		if(is_string($say))
			$say = strtr($say, '"', "'");
			
		parent::say($say, $params);
	}
	
	public function ask($ask, Array $params=NULL){
		if(is_string($ask))
			$ask = strtr($ask, '"', "'");
			
		parent::ask($ask, $params);
	}
}