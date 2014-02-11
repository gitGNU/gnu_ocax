<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');


require_once(dirname(__FILE__).'/../includes/utils.php');


// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'name'=>'OCA(x)',
	'language' => 'en',
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
/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.172.48','192.168.172.49'),
		),
*/
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

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
				array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
				array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
				array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
				
				'p/<pageURL:[a-z0-9-]+>'=>'cmsPage/show',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>', // comment out for Gii
				'<controller:\w+>/<action:\w+>/<id:[\a-z0-9-]+>'=>'<controller>/<action>',	// BudgetDescription IDs
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
		),
		'widgetFactory'=>array(
			'widgets'=>array(
				'CLinkPager'=>array(
					'cssFile'=>(strlen(dirname($_SERVER['SCRIPT_NAME']))>1 ? dirname($_SERVER['SCRIPT_NAME']) : '' ) . '/css/pager.css',
				),
			),
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
);
