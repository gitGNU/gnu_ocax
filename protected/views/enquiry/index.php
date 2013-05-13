<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').slideDown();
	return false;
});
$('.search-form form').submit(function(){
	$('.search-form').slideUp();
	$.fn.yiiGridView.update('enquiry-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 60%; float: left;  margin: 0px;}
	.right{width: 38%; float: left; margin: 0px;}
	.clear{clear:both;}

	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>

<script>
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getEnquiry/'+enquiry_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#enquiry_body").html(data.html);
				$('#enquiry').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show enquiry");
		}
	});
}
</script>

<div class="outer">
<div class="left">
<p>
Aqui tienes una lista de todas las enquirys hechas hasta la fecha de hoy.<br />
Puedes <?php echo CHtml::link('definir filtros','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_searchPublic',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
</p>
<?php
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$model->publicSearch(),
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'showEnquiry',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>__('Enquiries'),
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>__('State'),
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
			array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>

</div>
<div class="right">
<p style="font-size:1.5em">Como hacer una nueva enquiry</p>
<p><b>Crear una cuenta</b><br />Antes de enviarnos una enquiry, hace falta
<a href="<?php echo Yii::app()->request->baseUrl; ?>/site/register">registrarse</a></p>
<p><b>Tu página de gestión</b><br />En tu panel de gestión hay un enlace para crear una nueva enquiry</p>
<p><b>Explicar los dos tipos de enquirys</b><br />Se pueden realizar enquirys del tipo genérica o presupuestaria</p>
<p><b>Más</b><br />Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas </p>
<p><b>Más</b><br />Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas Cosas </p>
</div>
</div>

<div class="clear"></div>

<div id="enquiry" style="display:none;width:870px;">
<div style="background-color:white;padding:10px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="enquiry_body"></div>
</div>
<p>&nbsp;</p>
</div>


