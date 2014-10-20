<?php
/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

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

class EmailtextController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(/*'index','create',*/'view','update','admin'),
				'expression'=>"Yii::app()->user->isAdmin()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(/*'admin','delete'*/),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Emailtext']))
		{
			$model->attributes=$_POST['Emailtext'];
			$model->updated=1;
			if($model->save()){
				$configuredTemplatesTotal = count($model->findAllByAttributes(array('updated'=>1)));
				$totalTemplates = count( $model->findAll() );
				if( $configuredTemplatesTotal == $totalTemplates){
					$config = Config::model()->findByPk('siteConfigStatusEmailTemplates');
					$config->value = 1;
					$config->save();
				}
				Log::model()->write('EmailTemplate', __('Template').' "'.Enquiry::model()->getHumanStates($model->state).'" '.__('updated'));
				$this->redirect(array('view','id'=>$model->state));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Emailtext('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Emailtext']))
			$model->attributes=$_GET['Emailtext'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Emailtext the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Emailtext::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Emailtext $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='emailtext-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
