<?php
/**
 * Handles everything related to the Tropo api
 */
class TropoController extends CController{
	public $defaultAction = 'incoming';
	
	public function sanitize($s){
		/*$cut = array(',', '"', '@', '(', ')', '#', "'");
		
		foreach($cut as $c){
			$s = str_replace($c, '', $s);
		}*/
		
		return CHtml::encode($s);
	}
	
	/**
	 * Handles incoming call
	 */
	public function actionIncoming(){
		/** @var $tropo Tropo */
		$tropo = Yii::app()->tropo;
		$tropo->say('Hi');
		
		$tropo->ask('Who are you trying to locate?', array(
			'choices'=>$this->createAbsoluteUrl('grammar'),
			'event'=>array(
				'nomatch:1'=>'I could not understand the name. Say again please.',
				'nomatch:2'=>'I still could not understand. Try saying first name followed by last name.'
			),
			'bargein'=>"false",
			'attempts'=>3
		));
		
		// Tell Tropo how to continue if a successful choice was made
		$tropo->on(array('event' => 'continue', 'next'=> $this->createUrl('sayPlace')));
		
		$tropo->renderJSON();
	}
	
	public function actionSayPlace(){
		// Get the friends first
		$token = file_get_contents(Yii::getPathOfAlias('application.foursquare'));
		Yii::app()->foursquare->setAccessToken($token);
		
		// Have to have an extra query due to Tropo bug
		$r = Yii::app()->foursquare->get('/users/self/friends');
		$friends = $r->response->friends;
		
		$tropo = Yii::app()->tropo;
	    @$result = new Result();
	    $answer = $result->getValue();
	    //$answer = 'self';
	    
	    foreach($friends->items as $friend){
	    	if(stripos($this->sanitize($friend->firstName . ' '.$friend->lastName), $answer)!==false){
	    		$target = $friend->id; // Our user
	    		break;
	    	}
	    }
		
		if(empty($target)){
			$tropo->say('Sorry, no user was found.');
			$tropo->RenderJson();
			return;
		}
	    
		$r = Yii::app()->foursquare->get('/users/'.$target);
		$r = $r->response;
			
		$user = $r->user;
		$checkin = $user->checkins->items[0];
			
		if($user->checkins->count == 0 || empty($checkin)){
			$tropo->say(sprintf('Sorry, %s %s has no checkins.', $user->firstName, $user->lastName));
			$tropo->RenderJson();
			return;
		}
		
		$say = sprintf("%s %s was last seen at <prosody rate='0.9'><break time='120ms' /> %s <break time='200ms' /> in %s</prosody>",
										$user->firstName, 
										$user->lastName, 
										$checkin->venue->name,
										$checkin->venue->location->city
									);
									
		$dayNames = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$say .= ' on ' . $dayNames[date('N', $checkin->createdAt)];
		$say .= ' '.date('jS').' of '.date('F');
		
		$tropo->say('<speak>' . $say . '</speak>');
		
		// Repeat
		$tropo->say("<speak><break time='4s' />' . $say . '</speak>");
		
	    $tropo->RenderJson();
	}
	
	/**
	 * Fetches usernames as a grammar
	 */
	public function actionGrammar(){
		$token = file_get_contents(Yii::getPathOfAlias('application.foursquare'));
		Yii::app()->foursquare->setAccessToken($token);
		
		$r = Yii::app()->foursquare->get('/users/self/friends');
		$friends = $r->response->friends;
		
		$this->renderPartial('grammar', array(
			'friends'=>$friends->items
		));
	}
}