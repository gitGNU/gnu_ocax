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

<h1>Enquiries encomendadas a mi</h1>

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
	<b><?php echo __('States');?></b><br />
	<?php
		foreach($model->getHumanStates() as $key=>$value){
			echo $key.'&nbsp&nbsp'.$value.'<br />';
		}
	?>
</div>
</div>
<div class="clear" style="margin-bottom:15px"></div>

<?php
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$model->teamMemberSearch(),
	//'filter'=>$model,
    'onClick'=>array(
        'type'=>'url',
        'call'=>'teamView',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
	        array(
				'header'=>'Enquiry',
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


