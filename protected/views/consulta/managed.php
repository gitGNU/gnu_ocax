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

<h1>Consultas encomendadas a mi</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<div class="row">
		<?php echo $form->label($model,'user'); ?>
		<?php echo $form->textField($model,'user'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'assigned'); ?>
		<?php echo $form->textField($model,'assigned'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', array(''=>'Sin filtrar') + $model->humanTypeValues);?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->dropDownList($model, 'state', array(''=>'Sin filtrar') + $model->getHumanStates());?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>30,'maxlength'=>255)); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'body'); ?>
		<?php echo $form->textField($model,'body',array('size'=>30,'maxlength'=>255)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>
<?php $this->endWidget(); ?>

</div>
</div><!-- search-form -->

<?php
$this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
    'onClick'=>array(
        'type'=>'url',
        'call'=>'teamView',
    ),
	'ajaxUpdate'=>true,
/*
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
*/
	'columns'=>array(
	        array(
				'header'=>'Consulta',
				'name'=>'title',
				'value'=>'$data->title',
			),
			'assigned',
			'state',
			/*
			'type',
			'capitulo',
			'title',
			'body',
			*/
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>


