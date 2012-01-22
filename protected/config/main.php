<?php
/**
 * Setting error reporting
 */
error_reporting(6135);

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Squariz',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
		'foursquare'=>array(
			'class'=>'application.components.Foursquare',
			'debug'=>true,
			'isAsynchronous'=>false,
			'clientId'=>'<FOURSQUARECLIENTID>',
			'clientSecret'=>'<FOURSQUARECLIENTSECRET>'
		),
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
			'loginUrl' => array('site/auth'),
		),
		'tropo'=>array(
			'class'=>'application.components.TropoAPI'
		),
		'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => '<PHPFOGCONNECTIONSTRING>',
            'username' => '<PHPFOGDATABASEUSERNAME>',
            'password' => '<PHPFOGDATABASEPASSWORD',
            'enableProfiling' => YII_DEBUG == true ? true : false,
            'enableParamLogging' => YII_DEBUG == true ? true : false,
        ),
        'mailer'=>array(
        	'class'=>'ext.mailer.EMailer',
        	'CharSet' => 'utf-8',
        	'FromName'=>'<EMAILFROMNAME>',
        	'From'=>'<FROMEMAILADDRESS>',
        	'ContentType' => 'text/html'
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
		        array(
		            'class' => 'CDbLogRoute',
		            'connectionID' => 'db',
		        	'autoCreateLogTable'=>true
		        ),				
				array(
					'class'=>'CWebLogRoute',
					'enabled'=> YII_DEBUG == true ? true : false,
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'canonicalDomain'=>'<CNAME>.phpfogapp.com'
	)
);