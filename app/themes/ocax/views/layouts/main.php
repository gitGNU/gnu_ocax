<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    


	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/additional.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />

	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div  style="position:relative">



<div id="header_bar_j">
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
    
    <div class="header_block_j">
		<?php
			if(Yii::app()->user->isGuest){
				echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/user.png"/>', array('/site/login'));
				echo CHtml::link(__('Login'), array('/site/login'));
			}else{
				echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/user.png"/>', array('/site/logout'));
				echo CHtml::link(__('Logout'), array('/site/logout'));				
			}
		?>
	</div>   
    
    <div class="header_block_j">
    <a href="<?php echo Config::model()->findByPk('socialFacebookURL')->value;?>"><img src="<?php echo Yii::app()->theme->baseUrl;?>/images/fb_bar.gif" /></a>
    <a href="<?php echo Config::model()->findByPk('socialTwitterURL')->value;?>"><img src="<?php echo Yii::app()->theme->baseUrl;?>/images/tw_bar.gif" /></a>
	</div>    
    
	<div class="header_block_j">	
	<?php
		echo CHtml::link('<img src="'.Yii::app()->theme->baseUrl.'/images/home.png"/>', array('/site/index'));
		echo CHtml::link(__('Home'), array('/site/index'));
	?>
	</div>

</div> 
</div>   





<div id="header" >
    
	<div id="logo"><div>Observatori Ciutadà <br>de l'<span id="nombre_ocax"><?php echo CHtml::encode(Config::model()->findByPk('councilName')->value); ?></span></div></div>

	<div id="mainmenu">
		<?php
			$items=array(
				//array('label'=>__('Home'), 'url'=>array('/site/index')),
				//array('label'=>__('My page'), 'url'=>array('/user/panel'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>__('Budgets'), 'url'=>array('/budget'),'active'=> (strcasecmp(Yii::app()->controller->id, 'budget') === 0)  ? true : false),
				array('label'=>__('Enquiries'), 'url'=>array('/enquiry'),'active'=> (strcasecmp(Yii::app()->controller->id, 'enquiry') === 0)  ? true : false),
				//array('label'=>__('Contact'), 'url'=>array('/site/contact')),
				//array('label'=>__('Register'), 'url'=>array('/site/register'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>__('Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>__('Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			);
			$criteria=new CDbCriteria;
			$criteria->condition = 'weight = 0 AND published = 1';
			$criteria->order = 'block DESC';
			$cms_pages=CmsPage::model()->findAll($criteria);
			foreach($cms_pages as $page){
				$page_content = $page->getContentForModel(Yii::app()->language);
				$item = array( array(	'label'=>CHtml::encode($page_content->pageTitle),
										'url'=>array('/p/'.$page->id.'/'.$page_content->pageURL),
										'active'=> ($page->isMenuItemHighlighted()) ? true : false,
								));
				array_splice( $items, 0, 0, $item );
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

	<div style="width:100%; padding: 0px; float: left;">
	<div style="width: 33%; float: left;  margin: 0px;">
		<b><?php echo __('Contact information')?></b><br />
		PACD, Plataforma Auditoría <br>Ciudadana de la Deuda
		<?php echo Config::model()->findByPk('observatoryName')->value;?><br />
		<?php echo __('Email').': '.Config::model()->findByPk('emailContactAddress')->value;?><br />
		<?php echo __('Telephone').': '.Config::model()->findByPk('telephone')->value;?><br />
	</div>
	<div style="width: 28%; float: left;  margin: 0px;">
	
		Una iniciativa de: <br /><br /><img src="<?php echo Yii::app()->theme->baseUrl;?>/images/logopacd.png"/><br />
	</div>
    <div style="width: 33%; float: left;  margin: 0px;">
		Copyright &copy; <?php echo date('Y'); ?> por OCA(x)<br/>
		AGPLv3<br/>
		<a href="https://gitorious.org/ocax/">gitorious.org/ocax</a><br />
	</div>
	</div>
	<div style="clear:both;"></div>
	</div><!-- footer -->
	<div class="poweredBy"><?php echo Yii::powered(); ?></div>
</div>

</body>
</html>
