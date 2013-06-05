<?php
/* @var $this UserController */
/* @var $model User */

$this->menu=array(
	array('label'=>__('Change user\'s roles'), 'url'=>array('updateRoles', 'id'=>$model->id)),
	array('label'=>__('List all users'), 'url'=>array('admin')),
);

if(!$model->enquirys){
	$item= array(	array(	'label'=>__('Delete user'), 'url'=>'#',
							'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>__('Are you sure you want to delete this item?'))
					));
	array_splice( $this->menu, 1, 0, $item );	
}
?>

<style>
   	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getMegaDelete/'+enquiry_id,
		type: 'GET',
		async: false,
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#mega_delete_content").html(data);
				$('#mega_delete_button').attr('enquiry_id', enquiry_id);
				$('#mega_delete').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show mega delete");
		}
	});
}
function megaDelete(el){
	enquiry_id = $(el).attr('enquiry_id');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/megaDelete/'+enquiry_id,
		type: 'POST',
		async: false,
		//data: { 'id' : enquiry_id },
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$('#mega_delete').bPopup().close();
			if(data != 0){
				window.location = '<?php echo Yii::app()->request->baseUrl; ?>/user/view/<?php echo $model->id;?>';
			}
		},
		error: function() {
			alert("Error on megaDelete");
		}
	});
}

</script>

<div class="form">
<div class="title"><?php echo __('Username').': '.$model->username; ?></div>
<div class="row" style="margin:-15px -10px -10px -10px;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'fullname',
		'email',
		'joined',
		'is_socio',
		'is_team_member',
		'is_editor',
		'is_manager',
		'is_admin',
	),
)); ?>
</div>
</div>
<p></p>
<?php
if($enquirys->getData()){
echo '<span style="font-size:1.5em">'.__('Enquiries made by').' '.$model->fullname.'</span>';
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$enquirys,
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
				'header'=>'Enquirys',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			'created',
			array(
				'header'=>'Estat',
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}else
echo '<p style="font-size:1.5em">'.$model->fullname.' '.__('has not made a enquiry').'</p>';
?>

<div id="mega_delete" style="display:none;width:850px;">
	<div style="background-color:white;padding:5px;">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div id="mega_delete_content"></div>
	</div>
</div>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>
