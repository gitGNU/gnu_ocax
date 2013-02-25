<?php
/* @var $this BudgetController */
/* @var $model Budget */
$this->layout='//layouts/column1';

$yearStr = CHtml::encode($model->year).' - '.CHtml::encode($model->year +1);
?>

<style>
.graph {
background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/images/graph_paper.png');
background-repeat:repeat;
}
</style>

<script>
// this is for interactive graphic
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/view/'+budget_id;
	});
});
</script>


<?php echo '<h1>'.CHtml::encode($model->concept).' '.$yearStr.'</h1>';?>

<?php
$dataProvider=new CActiveDataProvider('Consulta', array(
    'criteria'=>array(
        'condition'=>'budget = '.$model->id,
        'order'=>'created DESC',
    ),
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
?>

<?php
if($dataProvider->getData()){
	echo '<p style="font-size:1.3em">Deseas  '.CHtml::link('hacer una consulta',array('consulta/create', 'budget'=>$model->id));
	echo ' sobre esta partida presupuestaria?</p>';
}else{
	echo '<p style="font-size:1.3em">Aun no se ha hecho ninguna consulta sobre esta partida presupuestaria. ';
	echo CHtml::link('Deseas hacer una',array('consulta/create', 'budget'=>$model->id)).'?</p>';
}
?>

<div class="view" style="padding:3px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		'concept',
		array(
	        'name'=>'provision',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->provision), 2, ',', '.').' €',
		),
		array(
	        'name'=>'spent',
			'type' => 'raw',
	        'value'=>number_format(CHtml::encode($model->spent), 2, ',', '.').' €',
		),
	),
)); ?>
</div>

<p>
<?php
if($dataProvider->getData()){
echo '<div style="font-size:1.3em;margin-top:25px;">Consultas ya realizadas por ciudadanos:</div>';
$this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$dataProvider,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/consulta/view',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'Consulta',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>'Estat',
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}
?>
</p>

<p style="font-size:1.3em">Opcionalmente puedes navegar partidas por próximidad.<br />
<div class="graph">
<?php $this->renderPartial('_interactive',array('model'=>$model));?>
</div>




