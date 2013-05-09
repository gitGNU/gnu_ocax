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
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div  style="position:relative">
<div id="header" >

	<div id="logo"><?php echo CHtml::encode(Config::model()->findByPk('observatoryName')->value); ?></div>

	<div id="mainmenu">
		<?php
			$items=array(
				array('label'=>__('Home'), 'url'=>array('/site/index')),
				array('label'=>__('My page'), 'url'=>array('/user/panel'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>__('Budgets'), 'url'=>array('/budget')),
				array('label'=>__('Enquiries'), 'url'=>array('/enquiry')),
				//array('label'=>__('Contact'), 'url'=>array('/site/contact')),
				array('label'=>__('Register'), 'url'=>array('/site/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>__('Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>__('Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			);
			$criteria=new CDbCriteria;
			$criteria->condition = 'weight = 0 AND published = 1';
			$criteria->order = 'block DESC';
			$cms_pages=CmsPage::model()->findAll($criteria);
			foreach($cms_pages as $page){
				$item = array( array('label'=>$page->pageTitle, 'url'=>array('/page/'.$page->pagename)) );
				array_splice( $items, 4, 0, $item );	
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
	<div style="width: 40%; float: left;  margin: 0px;">
		<b><?php echo __('Contact information')?></b><br />
		<?php echo Config::model()->findByPk('observatoryName')->value;?><br />
		<?php echo __('Email').': '.Config::model()->findByPk('emailContactAddress')->value;?><br />
		<?php echo __('Telephone').': '.Config::model()->findByPk('telephone')->value;?><br />
	</div>
	<div style="width: 20%; float: left;  margin: 0px;">
		Copyright &copy; <?php echo date('Y'); ?> por <?php echo CHtml::encode(Yii::app()->name); ?><br/>
		AGPL<br/>
		<a href="https://github.com/buttle/ocax">Code @ github</a><br />
	</div>
	</div>
	<div style="clear:both;"></div>
	</div><!-- footer -->
	<div class="poweredBy"><?php echo Yii::powered(); ?></div>
</div>

</body>
</html>
