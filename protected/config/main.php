<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Tropo demo',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(),

	// application components
	'components'=>array(
		'foursquare'=>array(
			'class'=>'application.components.Foursquare',
			'debug'=>true,
			'isAsynchronous'=>false,
			'clientId'=>'FOURSQUARECLIENTIDGOESHERE',
			'clientSecret'=>'FOURSQUARECLIENTSECRETGOESHERE'
		),
		'cfile'=>array(
			'class'=>'application.extensions.cfile.Cfile',
		),
		'tropo'=>array(
			'class'=>'application.components.TropoAPI'
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array()
);