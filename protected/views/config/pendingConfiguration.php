
<div class="sub_title"><?php echo __('Admin tasks');?>
<?php echo '<img src="'.Yii::app()->request->baseUrl.'/images/alert.png" />';?>
</div>

<p>
<?php
$cnt =1;
$config = Config::model();
$config->updateSiteConfigurationStatus();

if(!$config->findByPk('siteConfigStatusUptodate')->value){
	echo $cnt.'. '.'<a href="'.getInlineHelpURL(':upgrade').'">'.__('New version available').'. '.__('Upgrade now').'</a>';
	$cnt +=1;
	echo '<br />';
}
if(!$config->findByPk('siteConfigStatusBudgetDescriptionsImport')->value){
	echo $cnt.'. <span style="color:red">'.
				__('Installation is incomplete').'.</span> '.
				__('Budget descriptions table is empty. Please read INSTALL');
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusInitials')->value){
	echo $cnt.'. '.CHtml::link(__("The Observatory's initials have not been configured"),array('config/observatory'));
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusEmail')->value){
	echo $cnt.'. '.CHtml::link(__('Email has not been configured'),array('config/email'));
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusObservatoryName')->value){
	echo $cnt.'. '.CHtml::link(__("The Observatory's name has not been configured"),array('config/observatory'));
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusLanguage')->value){
	echo $cnt.'. '.CHtml::link(__('Language(s) have not been configured'),array('config/locale'));
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusAdministrationName')->value){
	echo $cnt.'. '.CHtml::link(__('Administration name has not been configured'),array('config/observatory'));
	$cnt +=1;
	echo '<br />';
}

if(!$config->findByPk('siteConfigStatusEmailTemplates')->value){
	echo $cnt.'. '.CHtml::link(__('Email templates need to be defined'),array('emailtext/admin'));
	$cnt +=1;
	echo '<br />';
}

?>
</p>
