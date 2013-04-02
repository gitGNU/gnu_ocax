<?php

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
				'actions'=>array('view','index','getEnquiry'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','edit','subscribe','delete'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('teamView','update','managed'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow',
				'actions'=>array('adminView','admin','manage'),
				'expression'=>"Yii::app()->user->isManager()",
			),
			array('allow',
				'actions'=>array(/*'getEnquiryForTeam',*/ 'getMegaDelete', 'megaDelete'),
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
		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

		$this->render('view',array(
			'model'=>$model,
			'replys'=>$replys,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$model=new Enquiry('search');
		$model->unsetAttributes();  // clear any default values
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
			$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
			echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('view',array('model'=>$model,'replys'=>$replys),true,true)));
		}else
			echo 0;
	}

/*
	public function actionGetEnquiryForTeam($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		$model=$this->loadModel($id);
		if($model){
			$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
			echo CJavaScript::jsonEncode(array('html'=>$this->renderPartial('_teamView',array('model'=>$model,'replys'=>$replys),true,true)));
		}else
			echo 0;
	}
*/

	public function actionSubscribe()
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();

		if(isset($_POST['enquiry']))
		{
			$user = Yii::app()->user->getUserID();
			$criteria = new CDbCriteria;
			$criteria->condition = 'enquiry = '.$_POST['enquiry'].' AND user = '.$user;
			$model=EnquirySubscribe::model()->find($criteria);
			if($model){
				$model->delete();
				echo '0';
			}else{
				$model=new EnquirySubscribe;
				$model->enquiry = $_POST['enquiry'];	// should check if enquiry id is valid.
				$model->user = $user;
				$model->save();
				echo '1';
			}			
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column1';
		$model=new Enquiry;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_GET['budget'])){
			$model->budget=$_GET['budget'];
			$model->type = 1;
		}
		if(isset($_GET['related'])){
			$model->related_to=$_GET['related'];
			$related_enquiry=Enquiry::model()->findByPk($model->related_to);
			if($related_enquiry->budget){
				$model->budget=$related_enquiry->budget;
				$model->type = $related_enquiry->type;
			}
		}

		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->state = 1;
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, style, class, id'));
			if($model->related_to){
				$related_enquiry=Enquiry::model()->findByPk($model->related_to);
				$model->team_member=$related_enquiry->team_member;
				$model->assigned=date('Y-m-d');
				$model->state=2;
			}
			if($model->save()){
				$subscription=new EnquirySubscribe;
				$subscription->user = $model->user;
				$subscription->enquiry = $model->id;
				$subscription->save();

				if($model->state==2 && $model->user!=$model->team_member){
					$subscription=new EnquirySubscribe;
					$subscription->user = $model->team_member;
					$subscription->enquiry = $model->id;
					$subscription->save();
				}

 				$mailer = new Mailer();

				$mailer->AddAddress($model->user0->email);
				$recipients=$model->user0->email.',';

				if($model->state==1){
					$managers=User::model()->findAllByAttributes(array('is_manager'=>'1'));
					foreach($managers as $manager){
						$mailer->AddBCC($manager->email);
						$recipients=$recipients.' '.$manager->email.',';
					}
				}
				if($model->state==2){
					$mailer->AddBCC($model->teamMember->email);
					$recipients=$recipients.' '.$model->teamMember->email.',';
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
					Yii::app()->user->setFlash('success', __('Enquiry has been published').'<br/>'.__('We have sent you an email'));
				}else{
					$email->sent=0;
					$email->save();
					Yii::app()->user->setFlash('success', __('Enquiry has been published'));
				}
				$this->redirect(array('/user/panel'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * If save is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit($id)	//team_memeber edits a $model->body and $model->type.
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$userid= Yii::app()->user->getUserID();
		if( !($model->team_member == $userid || ($model->state==1 && $model->user == $userid) )){
			$this->render('/site/index');
			Yii::app()->end();
		}

		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			$model->title = htmLawed::hl($model->title, array('elements'=>'-*', 'keep_bad'=>0));
			$model->body = htmLawed::hl($model->body, array('safe'=>1, 'deny_attribute'=>'script, style, class, id'));
			if($model->save()){
				if(Yii::app()->user->getUserID() == $model->team_member){
					$model->promptEmail();
					$this->redirect(array('teamView','id'=>$model->id));
				}else{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		$menu=Null;
		if(isset($_GET['menu']) && ($userid == $model->team_member))
			$menu=$_GET['menu'];
		$this->render('edit',array(
			'model'=>$model,
			'menu'=>$menu,
		));
	}

	/**
	 * View for team_member.
	 */
	public function actionTeamView($id)
	{
		$model=$this->loadModel($id);
		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));

		$this->render('teamView',array(
			'model'=>$model,
			'replys'=>$replys,
		));
	}

	/**
	 * Updates a model
	 * All attribs except $body
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
		if(isset($_POST['Enquiry']))
		{
			$model->attributes=$_POST['Enquiry'];
			if($model->save()){
				$model->promptEmail();
				$this->redirect(array('teamView','id'=>$model->id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'replys'=>$replys,
		));
	}

	public function actionManaged()
	{
		// grid of enquirys by team_member
		$this->layout='//layouts/column1';

		$model=new Enquiry('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Enquiry']))
			$model->attributes=$_GET['Enquiry'];
		$model->team_member = Yii::app()->user->getUserID();

		$this->render('managed',array(
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
			$team_member=$model->team_member;
			$model->attributes=$_POST['Enquiry'];
			if($team_member != $model->team_member){
				if($model->team_member){
					$model->assigned=date('Y-m-d');
					if($model->state == 1)	// not working !!!
						$model->state=2;	// recibido por el OCA(x)
				}else{
					$model->assigned=Null;
					$model->state=0;
				}
			}
			if($model->save() && $model->team_member){
				if(!EnquirySubscribe::model()->find(array('condition'=>'enquiry='.$model->id. ' AND user='.$model->team_member))){
					$subscription=new EnquirySubscribe;
					$subscription->user = $model->team_member;
					$subscription->enquiry = $model->id;
					$subscription->save();
				}
				$model->promptEmail();
				$team_members = user::model()->findAll(array("condition"=>"is_team_member =  1","order"=>"username"));
				//$this->redirect(array('manage','id'=>$model->id,'team_members'=>$team_members,));

				//can we use this instead of render? $this->refresh();
				$this->render('manage',array(
					'model'=>$model,
					'team_members'=>$team_members,
				));
				Yii::app()->end();
			}
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
		$replys = Reply::model()->findAll(array('condition'=>'enquiry =  '.$model->id));
		$this->render('adminView',array(
			'model'=>$model,
			'replys'=>$replys,
		));
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

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$user=Yii::app()->user->getUserID();
		if($model->state==1 && ($model->user == $user || Yii::app()->user->isManager()) ){
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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