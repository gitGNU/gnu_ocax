<?php
/* @var $this ConsultaController */
/* @var $model Consulta */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').slideDown();
	return false;
});
$('.search-form form').submit(function(){
	$('.search-form').slideUp();
	$.fn.yiiGridView.update('consulta-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 48%; float: left;  margin: 0px;}
	.right{width: 48%; float: left; margin: 0px;}
	.clear{clear:both;}

	.bClose{
		cursor: pointer;
		position: absolute;
		right: 10px;
		top: 5px;
	}
</style>

<script>
function showConsulta(consulta_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/consulta/getConsulta/'+consulta_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				url='<?php echo Yii::app()->request->baseUrl; ?>/consulta/'+consulta_id;
				$("#consulta_link").html('<p>Direct link a <a href="'+url+'">esta consulta</a></p>');
				$("#consulta_body").html(data.html);

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
</script>

<div class="outer">
<div class="left">
<p>
Aqui tienes una lista de todas las consultas hechas hasta la fecha de hoy.
Puedes <?php echo CHtml::link('definir filtros','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_searchPublic',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
</p>
<?php
$this->widget('PGridView', array(
	'id'=>'consulta-grid',
	'dataProvider'=>$model->search(),
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'showConsulta',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'Consultas',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			//'state',
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>

</div>
<div class="right">
<p style="font-size:1.5em">Como hacer una nueva consulta</p>
<p><b>Crear una cuenta</b><br />Antes de enviarnos una consulta, hace falta
<a href="<?php echo Yii::app()->request->baseUrl; ?>/site/register">registrarse</a></p>
<p><b>Tu página de gestión</b><br />En tu panel de gestión hay un enlace para crear una nueva consulta</p>
<p><b>Explicar los dos tipos de consultas</b><br />Se pueden realizar consultas del tipo genérica o presupuestaria</p>
<p><b>Más</b><br />Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas </p>
<p><b>Más</b><br />Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas </p>
</div>
</div>

<div class="clear"></div>

<div id="consulta" style="display:none;width:850px;">
<div style="background-color:white;padding:5px;">
<a class="bClose">x</a>
<div id="consulta_link"></div>
<div id="consulta_body"></div>
</div>
<p>&nbsp;</p>
</div>


