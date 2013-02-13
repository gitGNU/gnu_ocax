<?php
/* @var $this BudgetController */
/* @var $model Budget */

$criteria = new CDbCriteria;
$criteria->condition = 'parent IS NULL AND year ='.$model->year;
$this_year=$model->find($criteria);

$this->menu=array(
	array('label'=>'Modificar año '.$model->year, 'url'=>array('/budget/updateYear/'.$this_year->id)),
	array('label'=>'Show gráfico', 'url'=>array('index')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#budget-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<script>
function changeYear(el){
	year=$('#changeYear').val();
	window.location='<?php echo Yii::app()->request->baseUrl;?>/budget/admin?year='+year;
}
function createBudget(parent_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/create',
		type: 'GET',
		async: false,
		data: {'parent_id': parent_id },
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#form_container").empty();
				$("#form_container").html(data.html);
				$("#form_container").show();
			}
		},
		error: function() {
			alert("Error on get Create form");
		}
	});
}
function updateBudget(budget_id){
	if(<?php echo $this_year->id;?> == budget_id){
		window.location='<?php echo Yii::app()->request->baseUrl;?>/budget/updateYear/'+budget_id;
	}else{
		$.ajax({
			url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/update',
			type: 'GET',
			async: false,
			data: {'id': budget_id },
			dataType: 'json',
			//beforeSend: function(){ $('#right_loading_gif').show(); },
			//complete: function(){ $('#right_loading_gif').hide(); },
			success: function(data){
				if(data != 0){
					$("#form_container").empty();
					$("#form_container").html(data.html);
					$("#form_container").show();
				}
			},
			error: function() {
				alert("Error on get Update form");
			}
		});
	}
}
</script>

<div>
<div style="float:left">
<h1>Gestionar partidas</h1>
</div><div style="float:right">
<?php
	$criteria = new CDbCriteria;
	$criteria->condition='parent IS NULL';
	$criteria->order='year ASC';

	$yearly_budgets= CHtml::listData($model->findAll($criteria),'year',function($budget) {
		return CHtml::encode($budget->year.' - '. ($budget->year + 1));
	});
	echo  CHtml::activeDropDownList($model, 'year', $yearly_budgets,array('id'=>'changeYear','onchange'=>'js:changeYear(this);'));
?>
</div>
</div><div style="clear:both"></div>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'budget-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'year',
		'concept',
		'code',
		'provision',
        array(
            'name'=>'parent',
			'value'=>'(($data->parent) ? $data->parent0->concept : $data->year )',
        ),
		'weight',
		//'spent',
		array(
			'class'=>'CButtonColumn',
			'buttons' => array(
				'update' => array(
					'label'=> 'Add partida',
					'url'=> '"javascript:updateBudget(\"".$data->id."\");"',
					'visible' => 'true',
				),
				'insert' => array(
					'label'=> 'Add partida',
					'url'=> '"javascript:createBudget(\"".$data->id."\");"',
					'imageUrl' => Yii::app()->theme->baseUrl.'/images/insert_icon.png',
					'visible' => 'true',
				)
			),
			'template'=>'{delete} {update} {insert}',
		),
	),
)); ?>

<div id="saved_ok" style="	display :none;
							position: absolute;
							z-index: 1;
							left: 45%;
							margin-left: -200px;
							margin-bottom: 10px;
							width: 400px;
							font-size: 1.6em;
							text-align: center;
							background-color: #98FB98;
							">
Partida guardada correctamente
</div>

<div>Editar una partida arriba seleccionando
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/update.png" />
o añadirle una sub-partida con
<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/insert_icon.png" />
</div>

<div id="form_container"></div>

