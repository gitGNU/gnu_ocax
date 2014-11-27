<?php

//http://www.yiiframework.com/wiki/208/how-to-use-an-application-behavior-to-maintain-runtime-configuration/

/**
 * ApplicationConfigBehavior is a behavior for the application.
 * It loads additional config parameters that cannot be statically 
 * written in config/main
 */
class ApplicationConfigBehavior extends CBehavior
{
	/**
	 * Declares events and the event handler methods
	 * See yii documentation on behavior
	 */
	public function events()
	{
		return array_merge(parent::events(), array(
			'onBeginRequest'=>'beginRequest',
		));
	}
 
    /**
     * Load configuration that cannot be put in config/main
     */
	public function beginRequest()
	{
		if(isset(Yii::app()->request->cookies['lang']))
			$this->owner->user->setState('applicationLanguage', Yii::app()->request->cookies['lang']->value);	
		
		elseif(!Yii::app()->user->isGuest){
			if ($lang = User::model()->findByPk(Yii::app()->user->getUserID())->language)
				$this->owner->user->setState('applicationLanguage', $lang);
		}
		else
			$this->owner->user->setState('applicationLanguage', getDefaultLanguage());	

		$this->owner->language=$this->owner->user->getState('applicationLanguage');
		
		if(!isset(Yii::app()->request->cookies['lang'])){
			$cookie = new CHttpCookie('lang', $this->owner->language);
			$cookie->expire = time()+60*60*24*180;
			Yii::app()->request->cookies['lang'] = $cookie;
		}
		if(Config::model()->isSocialNonFree()){
			if(!isset(Yii::app()->request->cookies['cookiesAccepted']))
				$this->owner->user->setState('cookiesAccepted', 0);
			else
				$this->owner->user->setState('cookiesAccepted', 1);
		}
	}
}
