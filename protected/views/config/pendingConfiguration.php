
<div class="sub_title"><?php echo __('Admin tasks');?>
<?php echo '<img src="'.Yii::app()->request->baseUrl.'/images/alert.png" />';?>
</div>

<p>
<?php

$sql = "SELECT COUNT(*) FROM budget_desc_common";
$descriptions = intval(Yii::app()->db->createCommand($sql)->queryScalar());
if($descriptions == 0)
	echo 'Budget descriptions table is empty. Please read INSTALL<br />';

if(!Config::model()->findByPk('siteConfigStatusLanguage')->value){
	echo CHtml::link(__('Language(s) have not been configured'),array('config/locale'));
	echo '<br />';
}

if(!Config::model()->findByPk('siteConfigStatusEmail')->value){
	echo CHtml::link(__('Email has not been configured'),array('config/email'));
	echo '<br />';
}

if(!Config::model()->findByPk('siteConfigStatusEmailTemplates')->value){
	echo CHtml::link(__('Email templates need to be defined'),array('emailtext/admin'));
	echo '<br />';
}

?>
</p>
