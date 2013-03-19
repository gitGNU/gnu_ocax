<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

?>

<h1><?php echo __('Formulate a').' '?>
<?php
if($model->budget)
	echo __('budgetary enquiry');
else
	echo __('generic enquiry');
?>
</h1>
<?php
if(!$model->budget){
	echo '<div style="margin-top:-10px;margin-bottom:15px;">';
	echo __('If you wish to formulate a budgetary enquiry, you must first').' '.CHtml::link('buscar el concepto presupestario',array('/budget')).'</div>';

}
?>
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


