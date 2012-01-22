<?php
class User extends CActiveRecord{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'users';
	}
	
	public function rules(){
		return array(
			array('skype, phone', 'safe'),
			array('skype, phone', 'unique'),
			array('email', 'required', 'on'=>'profile'),
			array('email', 'email', 'on'=>'profile')
		);
	}
	
	public function beforeSave(){
		if($this->scenario == 'profile'){
			// Cleaning the phone number
			$this->phone = preg_replace('/\W/', '', $this->phone);
			
			$this->skype = ($this->skype == '' ? null : $this->skype);
			$this->phone = ($this->phone == '' ? null : $this->phone);

			if(!empty($this->phone))
				$this->phone = '+'.$this->phone; // Get plus sign back
			
			if(empty($this->skype) && empty($this->phone)){
				$this->addError('skype', 'You have to enter at least one ID (Skype or phone number).');
				return false;	
			}
		}
		
		return true;
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'Foursquare ID',
			'fname' => 'First Name',
			'lname' => 'Last Name',
			'token'=>'Foursquare token',
			'skype'=>'Skype login',
			'phone'=>'Phone number',
			'email'=>'Email'
		);
	}
	
	/**
	 * Named scope. Finds user by network name and type
	 * @param string $name
	 * @param string $id
	 */
	public function byNetwork($name, $id){
		switch ($name) {
			case 'SIP':
				$this->getDbCriteria()->mergeWith(array(
			        'condition'=>'`phone` = :phone',
					'params'=>array(
						':phone'=>$id
					)
			    ));
			break;
			case 'SKYPE':
				$this->getDbCriteria()->mergeWith(array(
			        'condition'=>'`skype` = :skype',
					'params'=>array(
						':skype'=>$id
					)
			    ));
			break;
		}
		
		return $this;
	}
}