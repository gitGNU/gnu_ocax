<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('enquiry-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 63%; float: left;  margin: 0px;}
	.right{width: 33%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">

<h1><?php echo __('Manage enquiries');?></h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

</div>
<div class="right">
	<b><?php echo __('States');?></b>
	<a href="http://ocax.net/?El_software:Workflow_y_estados" target="_new"><?php echo __('more info');?></a>
	<br />
	<?php
		foreach($model->getHumanStates() as $key=>$value){
			echo $key.'&nbsp&nbsp'.$value.'<br />';
		}
	?>
</div>
</div>
<div class="clear"></div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view'),
	'cssFile'=>Yii::app()->theme->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'enquiry-grid',
	'dataProvider'=>$model->adminSearch(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		'created',
		array(
			'header'=>__('Assigned to'),
			'name'=>'username',
			'value'=>'($data->teamMember) ? $data->teamMember->username : ""',
		),
		'state',
/*
		array(
			'name'=>'state',
			'type'=>'raw',
			'value'=>'Enquiry::getHumanStates($data->state)',
		),
*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=>array(
				'update' => array(
					'label'=>'Assign',
		            'url'=>'Yii::app()->createUrl("enquiry/adminView", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 3000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>
