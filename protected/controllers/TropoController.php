<?php
/**
 * Handles everything related to the Tropo api
 */
class TropoController extends Controller{
	public $defaultAction = 'incoming';
	
	public function sanitize($s){
		/*$cut = array(',', '"', '@', '(', ')', '#', "'");
		
		foreach($cut as $c){
			$s = str_replace($c, '', $s);
		}*/
		
		return CHtml::encode($s);
	}
	
	/**
	 * Simple function that dumps some data into the db.
	 * Useful to debug tropo calls.
	 */
	protected function debug($data){
		$tropo = Yii::app()->tropo;
		
		$d = new Data();
		$d->contents = $data;
		$d->insert();
		
		$tropo->say('Thanks');
		$tropo->renderJSON();
	}
	
	/**
	 * Handles incoming call
	 */
	public function actionIncoming(){
		// Trying to identify a user
		$tropo = Yii::app()->tropo;
		$session = new Session();
		$caller = $session->getFrom();
				
		if(!in_array($caller['network'], array('SKYPE', 'SIP'))){
			$tropo->say('Hi');
			$tropo->say('Sorry, this application supports ordinary phone and skype calls only at the moment.');
			$tropo->renderJSON();
			return;
		}
		
		$user = User::model()->byNetwork($caller['network'], $caller['name'])->find();
		
		if(empty($user)){
			$tropo->say('Hi');
			$tropo->say("<speak>You don't seem to be registered in the application. Visit <prosody rate='0.9'>" . Yii::app()->params['canonicalDomain'] . '</prosody> for more details.</speak>');
		}
		else{
			$tropo->say('Hello '. $user->fname);
			
			$tropo->ask('Who are you trying to locate?', array(
				'choices'=>$this->createAbsoluteUrl('grammar', array('token'=>$user->token)),
				'event'=>array(
					'nomatch:1'=>'I could not understand the name. Say again please.',
					'nomatch:2'=>'I still could not understand. Try saying first name followed by last name.'
				),
				'bargein'=>"false",
				'attempts'=>3
			));
			
			// Tell Tropo how to continue if a successful choice was made
			$tropo->on(array('event' => 'continue', 'next'=> $this->createUrl('sayPlace', array('token'=>$user->token))));
		}
		
		$tropo->renderJSON();
	}
	
	public function actionSayPlace($token){
		// Get the friends first
		Yii::app()->foursquare->setAccessToken($token);
		$tropo = Yii::app()->tropo;
		
		@$result = new Result();
	    $answer = $result->getValue();
	    //$answer = 'self';
	    
		// Have to have an extra query due to Tropo bug
		$r = Yii::app()->foursquare->get('/users/self/friends');
		$friends = $r->response->friends;
	   
	    foreach($friends->items as $friend){
	    	if(stripos($this->sanitize($friend->firstName . ' '.$friend->lastName), $answer)!==false){
	    		$target = $friend->id; // Our user
	    		break;
	    	}
	    }
		
		if(empty($target)){ // This condition is actually impossible. This piece of code is here as a respect for Murphy laws
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
		}
		else{
			$dayNames = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			$when = $dayNames[date('N', $checkin->createdAt)-1].', '.date('F', $checkin->createdAt). ' ' .date('jS', $checkin->createdAt);
			
			$say = sprintf("%s %s was last seen on %s at <prosody rate='0.9'><break time='120ms' /> %s <break time='200ms' /> in %s</prosody>",
											$user->firstName, 
											$user->lastName,
											$when,
											$checkin->venue->name,
											$checkin->venue->location->city
										);
			
			$tropo->say('<speak>' . $say . '</speak>');
			
			// Repeat
			$tropo->say("<speak><break time='3s' />' . $say . '<break time='1s' /></speak>");
		}
		
		$tropo->ask('To locate someone else just say their name or hangup when you are done.', array(
			'choices'=>$this->createAbsoluteUrl('grammar', array('token'=>$token)),
			'event'=>array(
				'nomatch:1'=>'I could not understand the name. Say again please.',
				'nomatch:2'=>'I still could not understand. Try saying first name followed by last name.'
			),
			'attempts'=>3
		));
			
		$tropo->on(array('event' => 'continue', 'next'=> $this->createUrl('sayPlace', array('token'=>$token))));
		
	    $tropo->RenderJson();
	}
	
	/**
	 * Sms messaging service.
	 * Certain things are different for messaging, so let's have another function
	 */
	public function actionSms(){
		$tropo = Yii::app()->tropo;
		$session = new Session();
		$caller = $session->getFrom();
		
		/* Check whether can access the app */
		
		if(!in_array($caller['network'], array('SKYPE', 'SMS'))){
			$tropo->say('Sorry, this application supports only ordinary phone and skype calls at the moment.');
			$tropo->renderJSON();
			return;
		}
		
		if($caller['network'] == 'SMS') // An exception for SMS. Phone numbers don't have + sign at the beginning
			$caller['id'] = '+' . $caller['id'];
		
		$user = User::model()->byNetwork($caller['network'], $caller['id'])->find();
		
		if(empty($user)){
			$tropo->say("Sorry, you don't seem to be registered in the application. Visit " . Yii::app()->params['canonicalDomain'] . ' for more details.');
			$tropo->RenderJson();
			return;
		}
		
		$answer = $session->getInitialText();
		
		if(empty($answer)){
			$tropo->say('Send a name of a person you want to locate');
			$tropo->RenderJson();
			return;
		}
		
		/* We have a username here */
		Yii::app()->foursquare->setAccessToken($user->token);
		
		// Have to have an extra query due to Tropo bug
		$r = Yii::app()->foursquare->get('/users/self/friends');
		$friends = $r->response->friends;
		$possibleTargets = array();
	   
	    foreach($friends->items as $friend){
	    	if(stripos($friend->firstName . ' '.$friend->lastName, $answer)!==false){
	    		$possibleTargets[] = $friend;
	    	}
	    }
		
	    if(count($possibleTargets) == 0){
	    	$tropo->say("Sorry, I can't find a user with such a name.");
			$tropo->RenderJson();
			return;
	    }
	    elseif(count($possibleTargets)>1){
	    	// More than 1 hit
	    	// Find the closest
	    	$target = $possibleTargets[0];
	    	$closest = -1;
	    	foreach($possibleTargets as $p){
	    		// Find levenshtein
	    		$lev = levenshtein($p->firstName . ' '.$p->lastName, $answer);
	    		if($lev <= $closest || $closest < 0){
	    			$closest = $lev;
	    			$target = $p;
	    		}
	    	}
	    	
	    	$target = $target->id;
	    }
	    else
	    	$target = $possibleTargets[0]->id;
	    
		$r = Yii::app()->foursquare->get('/users/'.$target);
		$r = $r->response;
			
		$target = $r->user;
		$checkin = $target->checkins->items[0];
			
		if($target->checkins->count == 0 || empty($checkin)){
			$tropo->say(sprintf('Sorry, %s %s has no checkins.', $target->firstName, $target->lastName));
			$tropo->RenderJson();
			return;
		}
		
		$dayNames = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$when = $dayNames[date('N', $checkin->createdAt)-1].', '.date('F', $checkin->createdAt). ' ' .date('jS', $checkin->createdAt);
		
		$say = sprintf("%s %s was last seen on %s at %s in %s",
									$target->firstName, 
									$target->lastName,
									$when,
									$checkin->venue->name,
									$checkin->venue->location->city
								);
		
		$tropo->say($say);
		
	    $tropo->RenderJson();
	}
	
	/**
	 * Fetches usernames as a grammar
	 */
	public function actionGrammar($token){
		Yii::app()->foursquare->setAccessToken($token);
		
		$r = Yii::app()->foursquare->get('/users/self/friends');
		$friends = $r->response->friends;
		
		$this->renderPartial('grammar', array(
			'friends'=>$friends->items
		));
	}
}