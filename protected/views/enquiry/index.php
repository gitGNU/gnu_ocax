<?php
/* @var $this EnquiryController */
/* @var $model Enquiry */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
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
<span style="font-size:1.5em"><?php echo __('Enquiries made to date');?></span></br>
<?php echo __('This is a list of enquiries made by citizens like you.');?>
</p>
<?php if(count($model->publicSearch()->getData()) > 0 ){ ?>
	<p>

	<div class="search-form">
		<?php $this->renderPartial('_searchPublic',array(
			'model'=>$model,
		)); ?>
	</div><!-- search-form -->
	</p>
<?php } ?>

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
				'header'=>__('Formulated'),
				'name'=>'created',
				'value'=>'format_date($data[\'created\'])',
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
<div style="font-size:1.5em"><?php echo __('Formulate a new enquiry')?></div>

<p>
<?php
	if(Yii::app()->user->isGuest){
		echo 	__('Remember you must first').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/login">'.__('login').'</a>'.' '.__('or').' '.
				'<a href="'.Yii::app()->request->baseUrl.'/site/register">'.__('create an account').'</a>';
	}
;?>
</p>

<p><b>¿Cuales son los estados de una consulta?</b><br /><br />

1.- Esperando aceptación del Observatorio.<br /><br />

2.- Consulta aceptada por el Observatorio<br /><br />

3.- Consulta rechazada por el Observatorio.<br /><br />

4.- Consulta aceptada por el Observatorio.<br /><br />

5.- Esperando respuesta del Ayuntamiento.<br /><br />

6.- Respuesta del Ayuntamiento pendiente de valorar.<br /><br />

7.- Respuesta del Ayuntamiento satisfactoria.<br /><br />

8.- Respuesta del Ayuntamiento insatisfactoria.</p>
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


