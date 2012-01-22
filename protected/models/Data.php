<?php
/**
 * Simple data class that is used to debug Tropo
 * @author undsoft
 *
 */
class Data extends CActiveRecord{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'data';
	}
	
	public function getContents(){
		return $this->data;
	}
	
	public function setContents($val){
		$this->data = print_r($val, true);
	}
}