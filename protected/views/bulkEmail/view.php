<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/* @var $this BulkEmailController */
/* @var $model BulkEmail */


$this->menu=array(
	array('label'=>__('Create bulk email'), 'url'=>array('create')),
	array('label'=>__('Manage bulk email'), 'url'=>array('admin')),
);

if($model->sent == 0){
	$delete = array(
			array('label'=>__('Delete draft'), 'url'=>'#', 'linkOptions'=>array(
																			'submit'=>array('delete',
																					'id'=>$model->id
																			),
																			'confirm'=>__('Are you sure you want to delete this item?'))
																		));
	array_splice( $this->menu, 0, 0, $delete );
}

?>

<style>           
.outer{width:100%; padding: 0px; float: left;}
.left{width: 38%; float: left;  margin: 0px;}
.right{width: 58%; float: left; margin: 0px;}
.clear{clear:both;}

#recipients_link{
	cursor:pointer;
	text-decoration:underline;
}
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script>
function showRecipients(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/bulkEmail/showRecipients/<?php echo $model->id?>',
		type: 'GET',
		success: function(data){
			if(data != 0){
				$("#recipients_body").html(data);
				$('#recipients').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, speed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show recipients");
		}
	});
}
function send(){
	$('input').prop('disabled', true);
	$('#loading').show();
	location.href='<?php echo Yii::app()->request->baseUrl;?>/bulkEmail/send/<?php echo $model->id?>';
}

</script>

<div class="form">
	<div class="title">
		<b><?php echo __('Subject');?></b>: <?php echo CHtml::encode($model->subject);?>
	</div>

<div class="outer">
<div class="left">

	<p style="margin-bottom:30px">

	<b><?php echo CHtml::encode($model->getAttributeLabel('created'));?>:</b>
	<?php echo CHtml::encode($model->created); ?><br />

	<b><?php echo CHtml::encode($model->getAttributeLabel('sent'));?>:</b>
	<?php echo CHtml::encode($model->getHumanSentValues($model->sent));?><br />


	<b><?php echo CHtml::encode($model->getAttributeLabel('sender'));?>:</b>
	<?php echo CHtml::encode($model->sender0->fullname);?><br />

	<b><?php echo CHtml::encode($model->getAttributeLabel('sent_as')); ?>:</b>
	<?php echo CHtml::encode($model->sent_as); ?><br />


	<b><?php echo $total_recipients.' '.__('BCC Recipients');?></b>: <span id="recipients_link" onClick="js:showRecipients();">Show</span>
	</p>


</div>
<div class="right" style="margin-top:15px">
<?php if($model->sent == 0){

echo CHtml::button(__('Edit draft'), array('onclick'=>'js:document.location.href="'.Yii::app()->request->baseUrl.'/bulkEmail/update/'.$model->id.'"'));
echo CHtml::button(__('Send now'), array('onclick'=>'js:send();','style'=>'margin-left:100px;'));
echo '<img id="loading" src="'.Yii::app()->theme->baseUrl.'/images/small_loading.gif" style="vertical-align:middle;margin-left:15px;display:none"/>';

}?>
</div>
</div>
<div class="clear"></div>

	<div style="background-color:white;margin:-10px;padding:10px;">
		<?php echo $model->body; ?><br />
	</div>

</div>

<div id="recipients" class="modal" style="width:600px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="recipients_body"></div>
</div>


<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-success').slideUp('fast');
    	}, 1750);
		});
	</script>
    <div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-error').slideUp('fast');
    	}, 1750);
		});
	</script>
    <div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error');?>
    </div>
<?php endif; ?>
