<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

require_once(dirname(__FILE__).'/../includes/localization.php');
require_once(dirname(__FILE__).'/../includes/utils.php');


// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'OCA(x)',
	'theme'=>'green',
	'language' => 'ca',

	'behaviors' => array('ApplicationConfigBehavior'),
	
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.mailer.Mailer',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.172.48','192.168.172.49'),
		),

	),

	// application components
	'components'=>array(
		'messages' => array(
			'class' => "CGettextMessageSource",
			'useMoFile' => TRUE,
		),
        'coreMessages'=>array(
            'basePath'=>'../protected/messages',
        ),
		'user'=>array(
			'class' => 'WebUser', // http://www.yiiframework.com/wiki/60/
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'autoUpdateFlash' => false,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'p/<pageURL:[a-z0-9-]+>'=>'cmsPage/show',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>', // comment out for Gii
				'<controller:\w+>/<action:\w+>/<id:[\a-z0-9-]+>'=>'<controller>/<action>',	// BudgetDescription IDs
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
		),

		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ocax',
			'emulatePrepare' => true,
                        'username' => 'ocax',
                        'password' => 'password',
			'charset' => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
