<?php
class SiteController extends CController
{	
	/**
	 * Authorizes the foursquare user.
	 */
	public function actionAuth(){
		$backUrl = $this->createAbsoluteUrl('auth');
		
		if(!empty($_GET['code'])){
			// Response from foursquare received
			
			$token = Yii::app()->foursquare->getAccessToken($_GET['code'], $backUrl);

			// Save token
			file_put_contents(Yii::getPathOfAlias('application.foursquare'), $token->access_token);
			
			$this->render('token_saved');
			return;
		}
		else if(!empty($_GET['error'])){
			$this->render('error', array(
				'message'=>Yii::app()->foursquare->getErrorMessage($_GET['error'])
			));
			return;
		}
		
		// <meta http-equiv="refresh" content="5; url=http://example.com/">
		$goToUrl = '3; url=' . Yii::app()->foursquare->getAuthorizeUrl($backUrl);
		Yii::app()->clientScript->registerMetaTag($goToUrl, null, 'refresh');
		
		$this->render('auth', array());
	}
	
	/**
	 * Welcome page
	 */
	public function actionIndex(){
		$this->render('index');
	}
}