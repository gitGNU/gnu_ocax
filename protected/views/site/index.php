<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
//$this->layout='//column1';
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 60%; float: left;  margin: 0px;}
	.right{width: 35%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer" style="position:relative">
<div class="left">

<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.jpg" />

<p style="position: absolute; top:15px; left: 250px; color:#009ACD; font-size:2.5em; font-weight:bold; text-align:right; ">
BUROCRÀ.. QUÈ?<br />
#JUNTESPODEM!
</p>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>

</div>
<div class="right">
<p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/box1.jpg" /></p>
<p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/box2.jpg" /></p>
<p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/box3.jpg" /></p>
</div>
</div>

