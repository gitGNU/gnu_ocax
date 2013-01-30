<?php

// http://www.yiiframework.com/wiki/60/
 

class WebUser extends CWebUser {
 
  // Store model to not repeat query.
  private $_model;
 
  // Return first name.
  // access it by Yii::app()->user->first_name
  function getFirst_Name(){
    $user = $this->loadUser(Yii::app()->user->id);
    return $user->first_name;
  }

  function getUserID(){
	if(Yii::app()->user->isGuest)
		return 0;
    $user = $this->loadUser(Yii::app()->user->id);
	return intval($user->id);
  }
 



  // access it by Yii::app()->user->isAdmin()
  function isTeamMember(){
	if(Yii::app()->user->isGuest)
		return 0;
    $user = $this->loadUser(Yii::app()->user->id);
	return intval($user->is_team_member);
  }

  function isEditor(){
	if(Yii::app()->user->isGuest)
		return 0;
    $user = $this->loadUser(Yii::app()->user->id);
	return intval($user->is_editor);
  }

  function isManager(){
	if(Yii::app()->user->isGuest)
		return 0;
    $user = $this->loadUser(Yii::app()->user->id);
	return intval($user->is_manager);
  }

  // access it by Yii::app()->user->isAdmin()
  function isAdmin(){
	if(Yii::app()->user->isGuest)
		return 0;
    $user = $this->loadUser(Yii::app()->user->id);
	return intval($user->is_admin);
  }
 
  // Load user model.
  protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
				$this->_model = User::model()->findByAttributes(array('username'=>$id));
        }
        return $this->_model;
    }
}
?>
