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

class EnquiryController extends Controller
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
			'postOnly + delete, megaDelete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','index','getEnquiry','feed'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','edit','subscribe','delete'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('teamView','managed','validate','changeType',
								 'submit','unSubmit','assess','reformulate'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow',
				'actions'=>array('adminView','admin','manage'),
				'expression'=>"Yii::app()->user->isManager()",
			),
			array('allow',
				'actions'=>array('getMegaDelete', 'megaDelete'),
				'expression'=>"Yii::app()->user->isManager() || Yii::app()->user->isAdmin()",
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
		$this->layout='//layouts/column1';
		$model=$this->loadModel($id);
		$this->pageTitle=__('Enquiry').': '.$model->title;

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$this->pageTitle=__('Enquiries').' '.Config::model()->findByPk('councilName')->value;
		$model=new Enquiry('search');
		$model->unsetAttributes();  // clear any default values
		$model->addressed_to = Null;
		if(isset($_GET['Enquiry']))
			$model->attributes=$_GET['Enquiry'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionGetEnquiry($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('view',array('model'=>$model),true,true)));
		}else
			echo 0;
	}

	public function actionSubscribe()
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		if(isset($_POST['enquiry']) && isset($_POST['subscribe']))
		{
			$user = Yii::app()->user->getUserID();
			$criteria = new CDbCriteria;
			$criteria->condition = 'enquiry = '.$_POST['enquiry'].' AND user = '.$user;
			$model=EnquirySubscribe::model()->find($criteria);
			
			if($model && $_POST['subscribe']=='false'){
				$model->delete();
				echo '-1';
			}elseif ($model && $_POST['subscribe']=='true'){
				// do nothing. user probably just made a comment and we automatically subscribed him
				echo '0';
			}else{
				$model=new EnquirySubscribe;
				$model->enquiry = $_POST['enquiry'];	// should check if enquiry id is valid.
				$model->user = $user;
				$model->save();
				echo '1';
			}
			Yii::app()->end();		
		}
		echo '0';
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column1';
		$this->pageTitle=CHtml::encode(__('New enquiry'));
		$model=new Enquiry;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_GET['budget'])){
			$model->budget=$_GET['budget'];
			$model->type = 1;
		}
		$model->addressed_to = ADMINISTRATION;

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->modified = date('c');
			$model->state = ENQUIRY_PENDING_VALIDATION;
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, class, id'));

			if($model->save()){
				$description = new EnquiryText;
				$description->enquiry=$model->id;
				$description->title=$model->title;
				$description->body=trim(strip_tags(str_replace("<br />", " ", $model->body)));
				$description->save();
				
				$subscription=new EnquirySubscribe;
				$subscription->user = $model->user;
				$subscription->enquiry = $model->id;
				$subscription->save();

 				$mailer = new Mailer();

				$mailer->AddAddress($model->user0->email);
				$recipients=$model->user0->email.',';


				$managers=User::model()->findAllByAttributes(array('is_manager'=>'1'));
				foreach($managers as $manager){
					$mailer->AddBCC($manager->email);
					$recipients=$recipients.' '.$manager->email.',';
				}

				$recipients = substr_replace($recipients ,'',-1);

				$mailer->SetFrom(Config::model()->findByPk('emailNoReply')->value, Config::model()->findByPk('siglas')->value);
				$mailer->Subject=$model->getHumanStates($model->state);
				$mailer->Body=Emailtext::model()->findByPk($model->state)->getBody($model);

				$email = new Email;

				$email->created = date('c');
				$email->sender=Null;	//app generated email
				$email->sent_as=Config::model()->findByPk('emailNoReply')->value;
				$email->title=$mailer->Subject;
				$email->body=$mailer->Body;
				$email->recipients=$recipients;
				$email->enquiry=$model->id;

				if($mailer->send()){
					$email->sent=1;
					$email->save();
					Yii::app()->user->setFlash('success', __('We have sent you an email'));
				}else{
					$email->sent=0;
					$email->save();
					Yii::app()->user->setFlash('success', __('Your enquiry has been registered correctly'));
				}
				$this->redirect(array('/user/panel'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/*
	 * Only the assigned team_member can reformulate an enquiry
	*/
	public function actionReformulate()
	{
		$this->pageTitle=CHtml::encode(__('Reformulate enquiry'));
		$model=new Enquiry;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_GET['related'])){
			$model->related_to=$_GET['related'];
			$related_enquiry=Enquiry::model()->findByPk($model->related_to);
			
			if(Yii::app()->user->getUserID() != $related_enquiry->team_member)
				$this->redirect(array('/site/index'));
				
			if($related_enquiry->budget){
				$model->budget=$related_enquiry->budget;
				$model->type = $related_enquiry->type;
			}
		}else
			$this->redirect(array('/site/index'));

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->state = ENQUIRY_ACCEPTED;
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, class, id'));

			//$related_enquiry=Enquiry::model()->findByPk($model->related_to);
			$model->team_member=$related_enquiry->team_member;
			$model->assigned=date('Y-m-d');
			$model->modified = date('c');
			$model->state=ENQUIRY_ACCEPTED;

			if($model->save()){
				$description = new EnquiryText;
				$description->enquiry=$model->id;
				$description->title=$model->title;
				$description->body=trim(strip_tags(str_replace("<br />", " ", $model->body)));
				$description->save();
				
				// subscribe users to this new enquiry
				foreach($related_enquiry->subscriptions as $old_subscription){
					$subscription=new EnquirySubscribe;
					$subscription->user = $old_subscription->user;
					$subscription->enquiry = $model->id;
					$subscription->save();					
				}
				
				Yii::app()->user->setFlash('success', __('New reformulated enquiry created OK'));			
				$this->redirect(array('teamView','id'=>$model->id));
			}
		}
		$this->render('reformulate',array(
			'model'=>$model,
		));	
	
	}


	/**
	 * If save is successful, the browser will be redirected to the 'view' page.
	 * team_memeber edits a $model->body and $model->type.
	 */
	public function actionEdit($id)
	{
		$this->pageTitle=CHtml::encode(__('Modify enquiry'));
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$userid = Yii::app()->user->getUserID();
		
		if(!($userid == $model->user || $userid == $model->team_member)){
			$this->redirect(array('/site/index'));
			Yii::app()->end();
		}		
		
		if($model->state > ENQUIRY_PENDING_VALIDATION && $userid != $model->team_member){
			$this->redirect(array('/user/panel'));
			Yii::app()->end();
		}
		
		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, class, id'));
			if($model->save()){
				$description=EnquiryText::model()->findByPk($model->id);
				$description->title=$model->title;
				$description->body= trim(strip_tags(str_replace("<br />", " ", $model->body)));
				$description->save();
								
				if($userid == $model->team_member){
					$this->redirect(array('teamView','id'=>$model->id));
				}else{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		if($model->team_member == $userid)
			$this->render('teamEdit',array('model'=>$model));
			
		elseif($model->user == $userid){
			$this->layout='//layouts/column1';
			$this->render('edit',array('model'=>$model));
		}	
	}

	/**
	 * Change Generic, Budgetary, budget->id
	 */
	public function actionChangeType($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if( $model->team_member != Yii::app()->user->getUserID() ){
			$this->render('/site/index');
			Yii::app()->end();
		}

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			if($model->type == 0)
				$model->budget = Null;
			if($model->save()){
				$this->render('teamView',array(
					'model'=>$model,
				));
				Yii::app()->end();
			}
		}
		$budget=new Budget('changeTypeSearch');
		$budget->unsetAttributes();  // clear any default values

		if(isset($_GET['Budget']))
			$budget->attributes=$_GET['Budget'];

		$this->render('changeType',array(
			'model'=>$model,
			'filterBudgetModel'=>$budget,
		));
	}

	/**
	 * team_member submits the enquiry to the administration
	 */
	public function actionSubmit($id)
	{
		$model=$this->loadModel($id);
		$model->scenario = 'submitted_to_council';
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if( $model->team_member != Yii::app()->user->getUserID() || $model->state < ENQUIRY_ACCEPTED){
			$this->render('/user/panel');
			Yii::app()->end();
		}

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];

			if($model->validate()){
				if($model->state == ENQUIRY_ACCEPTED){
					$model->state = ENQUIRY_AWAITING_REPLY;
					$model->modified = date('c');
				}
			}
			if(Yii::app()->request->isAjaxRequest){
				//http://www.yiiframework.com/forum/index.php/topic/37075-form-validation-with-ajaxsubmitbutton/
				if($model->save())
					echo CJSON::encode(array('status'=>'success'));
				else
					echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			if($model->save()){
				if($model->documentation){
					$model->promptEmail();			
					$this->redirect(array('teamView','id'=>$model->id));
				}
			}
		}
		$this->render('submit',array(
			'model'=>$model,
		));
	}

	/**
	 * team_member deletes documentation
	 */
	public function actionUnSubmit($id)
	{
		$model=$this->loadModel($id);

		if( $model->team_member != Yii::app()->user->getUserID()){
			$this->render('/user/panel');
			Yii::app()->end();
		}
		if($model->documentation){
			$file=$model->documentation0;
			$model->documentation = Null;
			//if(!$model->state > ENQUIRY_AWAITING_REPLY)
			//	$model->state=ENQUIRY_ACCEPTED;
			$model->save();
			if(!$file->delete()){
				$model->documentation = $file->id;
				$model->save();
			}			
		}

		$this->render('submit',array(
			'model'=>$model,
		));
	}

	/**
	 * View for team_member.
	 */
	public function actionTeamView($id)
	{
		$model=$this->loadModel($id);
		if( $model->team_member == Yii::app()->user->getUserID()){
			if($model->state == ENQUIRY_ASSIGNED)
				$this->redirect(array('validate','id'=>$model->id));
			else
				$this->render('teamView',array('model'=>$model));
		}
		else{
			$this->redirect(array('view','id'=>$model->id));
		}
	}

	/**
	 * Updates a model
	 * All attribs except $body
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAssess($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->modified = date('c');
			if($model->save()){
				$model->promptEmail();
				$this->redirect(array('teamView','id'=>$model->id));
			}
		}
		$this->render('assess',array(
			'model'=>$model,
		));
	}

	public function actionManaged()
	{
		// grid of enquirys by team_member
		$this->layout='//layouts/column1';

		$model=new Enquiry('search');
		$model->unsetAttributes();  // clear any default values
		//$model->team_member = Yii::app()->user->getUserID();
		if(isset($_GET['Enquiry']))
			$model->attributes=$_GET['Enquiry'];
		$this->render('managed',array(
			'model'=>$model,
		));
	}

	/**
	 * Validate a model
	 * Team member accepts or rejects enquiry
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionValidate($id)
	{
		$model=$this->loadModel($id);	
		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->modified = date('c');

			if($model->state == ENQUIRY_ACCEPTED && $model->addressed_to == OBSERVATORY)	
				$model->state = ENQUIRY_AWAITING_REPLY;	// skip the 'submit to administration' step.
				
			if($model->save()){
				$model->promptEmail();
				if($model->state == ENQUIRY_REJECTED && $model->team_member == 	Yii::app()->user->getUserID()){
					// somehow send an email to manager
				}
			}			
		}		
		$this->render('validate',array(
			'model'=>$model,
		));		
	}


	public function actionManage($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Enquiry']))
		{
			$saveMe=1;
			$model->manager=Yii::app()->user->getUserID();
			$team_member=$model->team_member;
			$model->attributes=$_POST['Enquiry'];

			if($model->state == 'rejected'){
				$model->state = ENQUIRY_REJECTED;
				$model->assigned = Null;
				$model->team_member = Null;
			}
			elseif(!$model->team_member){
				Yii::app()->user->setFlash('notice', __('You must assign a team member'));
				$model->team_member = $team_member;
				$saveMe=Null;
			}
			elseif($team_member != $model->team_member){
				if($model->team_member){
					$model->assigned=date('Y-m-d');
					$model->modified=date('c');
					if($model->state <= ENQUIRY_REJECTED) // maybe enquiry was already accepted and has higher state.
						$model->state=ENQUIRY_ASSIGNED;
				}else{
					Yii::app()->user->setFlash('notice', __('You must assign a team member'));
					$saveMe=Null;
				}
			}
			if($saveMe && $model->save()){
				if($model->team_member){
					if(!EnquirySubscribe::model()->find(array('condition'=>'enquiry='.$model->id. ' AND user='.$model->team_member))){
						$subscription=new EnquirySubscribe;
						$subscription->user = $model->team_member;
						$subscription->enquiry = $model->id;
						$subscription->save();
					}
				}
				if($model->state == ENQUIRY_ASSIGNED || $model->state == ENQUIRY_REJECTED)
					$model->promptEmail();
				else{
					Yii::app()->user->setFlash('success', __('New team member assigned'));
					//$this->redirect(array('adminView','id'=>$model->id));
				}
			}//else
			//	$model=$this->loadModel($id);	// render an unchanged model.
		}
		$team_members = user::model()->findAll(array("condition"=>"is_team_member =  1","order"=>"username"));
		$this->render('manage',array(
			'model'=>$model,
			'team_members'=>$team_members,
		));
	}

	public function actionAdminView($id)
	{
		$model=$this->loadModel($id);
		if($model->state == ENQUIRY_PENDING_VALIDATION)
			$this->redirect(array('manage','id'=>$model->id));
		else
			$this->render('adminView',array('model'=>$model));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/column1';
		$model=new Enquiry('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Enquiry']))
			$model->attributes=$_GET['Enquiry'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionFeed()
	{
		Yii::import('application.vendors.*');
		require_once 'Zend/Loader/Autoloader.php';
		spl_autoload_unregister(array('YiiBase','autoload')); 
		spl_autoload_register(array('Zend_Loader_Autoloader','autoload')); 
		spl_autoload_register(array('YiiBase','autoload'));

		$enquiries = Enquiry::model()->getEnquiriesForRSS();
		// convert to the format needed by Zend_Feed
		$entries=array();
		foreach($enquiries as $enquiry)
		{
			$date = new DateTime($enquiry->created);
			$entries[]=array(
				'title'=>$enquiry->title,
				'link'=>Yii::app()->createAbsoluteUrl('enquiry/view',array('id'=>$enquiry->id)),
				'description'=>$enquiry->body,
				'lastUpdate'=>$date->getTimestamp(),
			);
		}
		// generate and render RSS feed
		$feed=Zend_Feed::importArray(array(
			'title'   => Config::model()->findByPk('siglas')->value.' '.__('Enquiries'),
			'link'    => Yii::app()->createUrl('enquiry'),
			'charset' => 'UTF-8',
			'entries' => $entries,      
			), 'rss');
		$feed->send();  
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$user=Yii::app()->user->getUserID();
		if($model->state==ENQUIRY_PENDING_VALIDATION && ($model->user == $user || Yii::app()->user->isManager()) ){
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])){
				Yii::app()->user->setFlash('success', __('Enquiry has been deleted'));				
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
		}
		$this->redirect(array('/site/index'));
	}

	/**
	 * Deletes a enquiry and all references.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionGetMegaDelete($id)
	{
		$model = $this->loadModel($id);
		$object_count = $model->countObjects();
		echo $this->renderPartial('_megaDelete',array('model'=>$model,'object_count'=>$object_count),true,true);
	}

	public function actionMegaDelete($id)
	{
		$model=$this->loadModel($id);
		$model->delete();
		Yii::app()->user->setFlash('success', __('Enquiry has been deleted'));
		echo $id;
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Enquiry::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='enquiry-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
