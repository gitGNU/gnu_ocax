<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    
	<!-- blueprint CSS framework -->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/color.css" />

	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

	<title><?php echo (($this->pageTitle) ? $this->pageTitle : Config::model()->findByPk('siglas')->value); ?></title>
	
</head>

<body>
<div  style="position:relative">

<div id="header_bar">
<div id="header_bar_container">

	<div class="header_last_block">
   		<?php
		$languages=explode(',', Config::model()->findByPk('languages')->value);
		if(isset($languages[1])){
			echo '<span style="float:right; position:relative">';
			foreach($languages as $lang){
				echo '<a class="language_link" href="'.Yii::app()->request->baseUrl.'/site/language?lang='.$lang.'">'.$lang.'</a> ';
			}
			echo '</span>';
		}
	?>
	</div>
    
    <div class="header_block">
		<?php
			if(Yii::app()->user->isGuest){
				echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/user.png"/>', array('/site/login'));
				echo CHtml::link(__('Login'), array('/site/login'));
			}else{
				echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/user.png"/>', array('/site/logout'));
				echo CHtml::link(__('Logout').' ('.Yii::app()->user->id.')', array('/site/logout'));				
			}
		?>
	</div>

	<?php
	$fbURL = Config::model()->findByPk('socialFacebookURL')->value;
	$twURL = Config::model()->findByPk('socialTwitterURL')->value;

	if($fbURL || $twURL){
		echo '<div class="header_block">';
		if($fbURL)
			echo '<a href="'.$fbURL.'" style="margin-top:3px"><img src="'.Yii::app()->theme->baseUrl.'/images/fb_bar.gif" /></a> ';
		if($twURL)
			echo '<a href="'.$twURL.'" style="margin-top:3px"><img src="'.Yii::app()->theme->baseUrl.'/images/tw_bar.gif" /></a>';
		echo '</div>';
	} ?>    

	<div class="header_block">	
	<?php
		echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/home.png"/>', array('/archive'));
		echo CHtml::link(__('Archive'), array('/archive'));
	?>
	</div>

	<div class="header_block">	
	<?php
		echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/home.png"/>', array('/site/index'));
		echo CHtml::link(__('Home'), array('/site/index'));
	?>
	</div>

</div> 
</div>   

<div id="header" >
	<div id="logo">
		<span style="cursor:pointer" onclick="window.location='<?php echo Yii::app()->baseUrl;?>/';">
		<?php echo Config::model()->getSiteTitle(); ?>
		</span>
	</div>

	<div id="mainmenu">
		<?php
			$items=array(
				array('label'=>__('Budgets'), 'url'=>array('/budget'),'active'=> (strcasecmp(Yii::app()->controller->id, 'budget') === 0)  ? true : false),
				array('label'=>__('Enquiries'), 'url'=>array('/enquiry'),'active'=> (strcasecmp(Yii::app()->controller->id, 'enquiry') === 0)  ? true : false),
			);
			$criteria=new CDbCriteria;
			$criteria->condition = 'weight = 0 AND published = 1';
			$criteria->order = 'block DESC';
			$cms_pages=CmsPage::model()->findAll($criteria);
			foreach($cms_pages as $page){
				$page_content = $page->getContentForModel(Yii::app()->language);
				$item = array( array(	'label'=>CHtml::encode($page_content->pageTitle),
										'url'=>array('/p/'.$page_content->pageURL),
										'active'=> ($page->isMenuItemHighlighted()) ? true : false,
								));
				array_spliceice( $items, 0, 0, $item );
			}
			if(!Yii::app()->user->isGuest){
				$item = array( array(	'label'=>__('My page'),
										'url'=>array('/user/panel'),
						));
				array_splice( $items, 0, 0, $item );		
			}
			$this->widget('zii.widgets.CMenu',array(
				'items'=>$items,
			));
		?>
	</div><!-- mainmenu -->
</div>


<div class="container" id="page">
	<?php echo $content; ?>
	<div class="clear"></div>
</div><!-- page -->

<div id="footer">
	<div style="width: 600px; float: left; margin:10px 0 10px 0;">
		<b><?php echo Config::model()->getObservatoryName();?></b><br />
		<u><?php echo __('Contact information')?></u><br />
		<?php if($blog = Config::model()->findByPk('observatoryBlog')->value)
			echo '<a href="'.$blog.'">'.$blog.'</a><br />';
		?>
		<?php echo __('Email').': '.Config::model()->findByPk('emailContactAddress')->value;?><br />
		<?php if($telf = Config::model()->findByPk('telephone')->value)
			echo __('Telephone').': '.$telf.'<br />';
		?>
	</div>
	
	<div style="width: 250px; float:right; margin:10px 0 10px 0;">
		<div style="float: left;">
			AGPLv3 Copyright &copy; <?php echo date('Y'); ?><br />
			<a href="http://ocax.net">http://ocax.net</a><br />
			<a href="http://ocmunicipal.net">http://ocmunicipal.net</a><br/>
		</div>
		<div style="float:right;margin-left:20px">
			<a href="http://auditoriaciudadana.net">
			<img src="<?php echo Yii::app()->request->baseUrl;?>/images/logopacd_small.png"/>
			</a>		
		</div>
	</div>
	
	<div style="clear:both;"></div>	
</div><!-- footer -->

<div class="poweredBy"><?php echo Yii::powered(); ?></div>
</div>
</body>
</html>
