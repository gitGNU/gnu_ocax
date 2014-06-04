<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
?>

<div class="sub_title">Error <?php echo $code; ?></div>

<p>
<?php echo CHtml::encode($message); ?>
</p>
