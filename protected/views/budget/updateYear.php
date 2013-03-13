<?php

$totalBudgets = count(Budget::model()->findAllBySql('SELECT id FROM budget WHERE year = '.$model->year.' AND parent IS NOT NULL'));

$this->menu=array(
	array('label'=>'Import budgets', 'url'=>array('csv/importCSV/'.$model->year)),
	array('label'=>'List Years', 'url'=>array('adminYears')),
);

if($totalBudgets){
	$downloadCsv = array( array('label'=>'Export budgets', 'url'=>array('csv/download', 'id'=>$model->year)));
	array_splice( $this->menu, 1, 0, $downloadCsv );
	$deleteDatos = array( array( 'label'=>'Delete budgtes', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:deleteBudgets();') ) );
	array_splice( $this->menu, 1, 0, $deleteDatos );
	$showBudgets = array( array('label'=>'Manage budgets', 'url'=>array('admin?year='.$model->year)));
	array_splice( $this->menu, 1, 0, $showBudgets );
}
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script>
function deleteBudgets(){
	ans = confirm('Are you sure you want to delete <?php echo $totalBudgets;?> budgets?');
	if (ans)
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/deleteYearsBudgets/<?php echo $model->id;?>';
}
function showConsulta(consulta_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/getConsultaForTeam/'+consulta_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#consulta_body").html(data.html);
				$('#mega_delete_button').attr('consulta_id', consulta_id)
				$('#consulta').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show consulta");
		}
	});
}
function megaDelete(el){
	consulta_id = $(el).attr('consulta_id');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/megaDelete/'+consulta_id,
		type: 'POST',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$('#consulta').bPopup().close();
			if(data != 0){
				//alert('deleted: '+data);
				$('#consultas-grid').yiiGridView('update');
			}
		},
		error: function() {
			alert("Error on megaDelete");
		}
	});

}
</script>

<?php $title='Edit year '.($model->year).' - '.($model->year + 1);?>
<?php echo $this->renderPartial('_formYear', array('model'=>$model, 'title'=>$title, 'totalBudgets'=>$totalBudgets)); ?>

<?php
if($consultas->getData()){
echo '<p></p><div style="font-size:1.5em">Consultas presupuestarias del '.($model->year).' - '.($model->year + 1).'</div>';
$this->widget('PGridView', array(
	'id'=>'consultas-grid',
	'dataProvider'=>$consultas,
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'showConsulta',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
		array(
			'name'=>'consulta title',
			'value'=>'$data->title',
		),
		array(
			'name'=>'csv budget code',
			'value'=>'$data->budget0->csv_id',
		),
		'state',
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	),
));

}
?>
<?php echo $this->renderPartial('//consulta/_megaDelete'); ?>

<?php if(Yii::app()->user->hasFlash('csv_generated')):?>
    <div class="flash_success" id="csv_generated_ok">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('csv_generated');?></b></p>
    </div>
<?php endif; ?>


