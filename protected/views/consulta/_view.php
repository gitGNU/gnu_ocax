<?php
/* @var $this ConsultaController */
/* @var $model Consulta */
?>



<div style="margin-top:15px">
<?php echo $model->body;?>

<?php
$respuestas = Respuesta::model()->findAll(array('condition'=>'consulta =  '.$model->id));

foreach($respuestas as $respuesta){
	echo '<hr>';
	echo '<p>';
	echo '<b>Respuesta: '.date_format(date_create($respuesta->created), 'Y-m-d').'</b><br />';
	echo '<p>'.$respuesta->body.'</p>';
	echo '</p>';
}?>
</div>

