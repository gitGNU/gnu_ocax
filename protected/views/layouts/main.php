<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/fontello/css/fontello.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/mainmenu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foot.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/color.css" />
	
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

	<title><?php echo (($this->pageTitle) ? $this->pageTitle : Config::model()->findByPk('siglas')->value); ?></title>

</head>

<body>
<div style="position:relative;">

<div id="header_bar">
<ul>
	<?php
		$languages=explode(',', Config::model()->findByPk('languages')->value);
		if(isset($languages[1])){
			echo '<li><span style="float:right; position:relative">';
			foreach($languages as $lang){
				echo '<a class="language_link" href="'.Yii::app()->request->baseUrl.'/site/language?lang='.$lang.'">'.$lang.'</a> ';
			}
			echo '</span></li>';
		}
	?>
    <li>
		<?php
			if(Yii::app()->user->isGuest){
				echo CHtml::link('<i class="icon-user-female header-icons"></i>', array('/site/login'));
				echo '<span>'.CHtml::link(__('Login'), array('/site/login')).'</span>';
			}else{
				echo CHtml::link('<i class="icon-user-female header-icons"></i>', array('/site/logout'));
				echo '<span>'.CHtml::link(__('Logout').' ('.Yii::app()->user->id.')', array('/site/logout')).'</span>';
			}
		?>
	</li>
	<?php
		$fbURL = Config::model()->findByPk('socialFacebookURL')->value;
		$twURL = Config::model()->findByPk('socialTwitterURL')->value;
		if($fbURL || $twURL){
			echo '<li>';
			if($fbURL)
				echo '<a href="'.$fbURL.'" target="_blank"><i class="icon-facebook-squared header-icons"></i></a> ';
			if($twURL)
				echo '<a href="'.$twURL.'" target="_blank"><i class="icon-twitter header-icons"></i></a> ';
			echo '</li>';
		}
	?>
	<li>
	<?php
		//echo CHtml::link('<i class="icon-mail-2 header-icons"></i>', array('/newsletter'));
		echo CHtml::link('<i class="icon-megaphone header-icons"></i>', array('/newsletter'));
		echo '<span>'.CHtml::link(__('Newsletters'), array('/newsletter')).'</span>';
	?>
	</li>
	<li>
	<?php
		echo CHtml::link('<i class="icon-folder-1 header-icons"></i>', array('/archive'));
		echo '<span>'.CHtml::link(__('Archive'), array('/archive')).'</span>';
	?>
	</li>
	<li>
	<?php
		echo CHtml::link('<i class="icon-home header-icons"></i>', array('/site/index'));
		echo '<span>'.CHtml::link(__('Home'), array('/site/index')).'</span>';
	?>
	</li>
</ul>

</div>

<?php
if(Config::model()->findByPk('socialActivateNonFree')->value && !Yii::app()->user->getState('cookiesAccepted')){
	$this->renderPartial('//site/cookieAlert', array());
}
?>

<div id="header" >
	<div id="observatoryTitle">
		<span style="cursor:pointer" onclick="window.location='<?php echo Yii::app()->baseUrl;?>/';">
		<?php echo Config::model()->getSiteTitle(); ?>
		</span>
	</div>
</div>
<?php $this->renderPartial('//layouts/mainmenu', array()); ?>

<div class="container" id="page">
	<?php echo $content; ?>
	<div class="clear"></div>
</div><!-- page -->

<?php
// foot files get updated when a global parameter is saved
if(file_exists(Yii::app()->basePath.'/runtime/html/foot/'.Yii::app()->language.'.html'))
	echo file_get_contents(Yii::app()->basePath.'/runtime/html/foot/'.Yii::app()->language.'.html');
?>

</body>
</html>
