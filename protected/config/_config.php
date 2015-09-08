<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

require_once(dirname(__FILE__).'/../includes/utils.php');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'name'=>'OCAx',
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
			'ipFilters'=>array('127.0.0.1','::1'),
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
			'basePath'=>null,
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
				array('graph/script', 'pattern'=>'graph/<script:\w+>', 'verb'=>'GET'),
				array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
				//array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
				//array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
				//array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				//array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
				
				'archive/d/<folder:[a-zA-Z0-9-_.\/]+>'=>'archive/index',
				'p/<pageURL:[a-z0-9-]+>'=>'sitePage/show',
				'e/<id:\d+>'=>'enquiry/view',
				'b/<id:\d+>'=>'budget/view',
				'n/<id:\d+>'=>'newsletter/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>', // comment out for Gii
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
		),
		'widgetFactory'=>array(
			'widgets'=>array(
				'CLinkPager'=>array(
					'cssFile'=>(strlen(dirname($_SERVER['SCRIPT_NAME']))>1 ? dirname($_SERVER['SCRIPT_NAME']) : '' ) . '/css/pager.css',
					'header'		=> '',
					'firstPageLabel'=> '<<',
					'prevPageLabel' => '<',
					'nextPageLabel' => '>',
					'lastPageLabel' => '>>',
					'maxButtonCount'=>6,
				),
				'CDetailView'=>array(
					'cssFile'=>(strlen(dirname($_SERVER['SCRIPT_NAME']))>1 ? dirname($_SERVER['SCRIPT_NAME']) : '' ) . '/css/pdetailview.css',
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
