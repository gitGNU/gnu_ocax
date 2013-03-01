<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('consulta-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage consultas</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		'created',
        array(
            'name'=>'team_member',
			'value'=>'(($data->team_member) ? $data->teamMember->username : "")',
        ),
		'state',
		/*
		'type',
		'capitulo',
		'title',
		'body',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'buttons'=>array(
/*
				'view' => array(
					'label'=>'View',
		            'url'=>'Yii::app()->createUrl("consulta/adminView", array("id"=>$data->id))',
				),
*/
				'update' => array(
					'label'=>'Assign',
		            'url'=>'Yii::app()->createUrl("consulta/manage", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 1750);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>
