<?php
class SiteController extends Controller
{
	public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    
	public function accessRules() {
        return array(
            array('deny', // deny anonymous user to the profile page
            	'actions' => array('profile', 'delete'),
                'users' => array('?'),
            ),
        );
    }
    
	/**
	 * Authorizes the foursquare user.
	 */
	public function actionAuth(){
		if(!Yii::app()->user->isGuest)
			$this->redirect(array('profile'));
		
		$backUrl = $this->createAbsoluteUrl('auth');
		
		if(!empty($_GET['code'])){
			// Response from foursquare received
			$token = Yii::app()->foursquare->getAccessToken($_GET['code'], $backUrl);
			$token = $token->access_token;
			Yii::app()->foursquare->setAccessToken($token);
			
			$self = Yii::app()->foursquare->get('/users/self');
			$self = $self->response->user;
			
			// Check if user is already present
			$user = User::model()->findByPk($self->id);
			
			if(empty($user)){
				// New registration
				$user = new User();
				$user->id = $self->id;
				
				if(!empty($self->firstName))
					$user->fname = $self->firstName;
					
				if(!empty($self->lastName))
					$user->lname = $self->lastName;
				
				$user->token = $token;
				if(!$user->insert())
					throw new CHttpException(500, 'Unable to create user.').$user->errors;
			}				
			
			$identity = new UserIdentity($user->id, $token);
			$identity->authenticate(); // Fake authenticate
			
			$duration = 3600 * 24 * 7; // 7 days
			Yii::app()->user->login($identity, $duration);
			
			// Redirect to profile
			$this->redirect(array('profile'));
		}
		else if(!empty($_GET['error'])){
			Yii::app()->user->logout();
			
			$this->render('error', array(
				'message'=>Yii::app()->foursquare->getErrorMessage($_GET['error'])
			));
			
			return;
		}
		
		$this->redirect(Yii::app()->foursquare->getAuthorizeUrl($backUrl));
	}
	
	/**
	 * Deleting the user account
	 */
	public function actionDelete(){
		if(isset($_POST['delete']) && $_POST['del_key'] == Yii::app()->user->model->token){
			Yii::app()->user->model->delete();
			Yii::app()->user->logout();
			$this->redirect(array('site/index', 'deleted'=>1));
		}
		
		$this->redirect(array('site/index'));
	}
	
	/**
	 * Editing user profile
	 */
	public function actionProfile(){
		$user = Yii::app()->user->model;
		$user->scenario = 'profile';
		
		if(empty($user)){
			Yii::app()->user->logout();
			$this->redirect(array('index'));
		}
		
		$previousEmail = $user->email;
		
		if(isset($_POST['User'])){
			$user->attributes = $_POST['User'];
			
			if($user->save()){
				if(empty($previousEmail)){
					// This is the first time user has entered email
					Yii::app()->mailer->AddAddress($user->email, $user->fname . ' ' . $user->lname);
					Yii::app()->mailer->Subject = 'Welcome to Tropo demo';
					Yii::app()->mailer->getView('registration');
					Yii::app()->mailer->Send();
					
					$message = "We've sent you an email with further instructions.";
				}
			}
		}
		
		$this->render('profile', array(
			'model'=>$user,
			'message'=>$message
		));
	}
	
	/**
	 * Welcome page
	 */
	public function actionIndex($deleted = 0){
		if($deleted == 1)
			$message = 'Your account has been deleted.';
		
		$this->render('index', array(
			'message'=>$message
		));
	}
}