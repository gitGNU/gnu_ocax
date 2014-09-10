
<div class="sub_title"><?php echo __('Admin tasks');?>
<?php echo '<img src="'.Yii::app()->request->baseUrl.'/images/alert.png" />';?>
</div>

<p>
<?php
$cnt =1;

$sql = "SELECT COUNT(*) FROM budget_desc_common";
$descriptions = intval(Yii::app()->db->createCommand($sql)->queryScalar());
if($descriptions == 0){
	echo $cnt.'. '.__('Budget descriptions table is empty. Please read INSTALL');
	$cnt +=1;
	echo '<br />';
}

if(!Config::model()->findByPk('siteConfigStatusEmail')->value){
	echo $cnt.'. '.CHtml::link(__('Email has not been configured'),array('config/email'));
	$cnt +=1;
	echo '<br />';
}

if(!Config::model()->findByPk('siteConfigStatusLanguage')->value){
	echo $cnt.'. '.CHtml::link(__('Language(s) have not been configured'),array('config/locale'));
	$cnt +=1;
	echo '<br />';
}

if(!Config::model()->findByPk('siteConfigStatusEmailTemplates')->value){
	echo $cnt.'. '.CHtml::link(__('Email templates need to be defined'),array('emailtext/admin'));
	$cnt +=1;
	echo '<br />';
}

?>
</p>
