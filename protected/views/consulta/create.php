<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

?>


<?php
/*
if($model->type == 1){
	$year = Config::model()->findByPk('year')->value;

	$criteria = new CDbCriteria;
	$criteria->condition = 'year = '.$year.' AND parent is NULL';
	//$criteria->order = 'weight ASC';
	//$budget_raiz = Budget::model()->find($criteria);
	$budget_raiz = Budget::model()->findByPk($model->budget);

	//$this->renderPartial('//budget/_index',array('budgets_raiz'=>$budgets_raiz, 'total_budget'=>$total_budget));
	echo $this->renderPartial('//budget/_index',array('model'=>$budget_raiz));
}
*/
?>

<script>
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		$.ajax({
			url: '<?php echo Yii::app()->request->baseUrl;?>/budget/getBudgetDetails/',
			type: 'GET',
			async: false,
			dataType: 'json',
			data: {'id': budget_id },
			beforeSend: function(){ /*$ ('#right_loading_gif').show(); */ },
			complete: function(){ /* $('#right_loading_gif').hide(); */ },
			success: function(data){
				if(data != 0){
					$('#budget_concept').html(data.html);
					$('#Consulta_budget').val(data.code);
				}
			},
			error: function() {
				alert("Error on get comment form");
			}
		});
	});
});
</script>


<h1>Create Consulta</h1>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 75%; float: left;  margin: 0px;}
	.right{width: 23%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left">


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


</div>
<div class="right">

<p style="font-size:1.5em">Procedimiento</p>
<p>Tu creas la consulta</p>
<p>Nosotros la asignamos a una persona de nuestro equipo quien se encargará de ella.</p>
<p>Recibirás correos informándote del proceso.</p>
<p>más cosas</p>
<p>más cosas</p>

</div>
</div>


