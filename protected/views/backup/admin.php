<?php
/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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

/* @var $this BackupController */

$this->menu=array(
	array('label'=>__('Show schedule'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showSchedule(); return false;')),
	array('label'=>__('Manual backup'), 'url'=>array('manualCreate')),
	array('label'=>__('Create Vault'), 'url'=>array('vault/create')),
);
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script>
function getVaultDetails(id){
	
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/vault/view/'+id,
		type: 'GET',
		success: function(data){
			if(data != 0){
				$('#all_backups').hide();
				$('#vault_details').html(data);
				$('#vault_details').fadeIn('fast');
		}
		},
		error: function() {
			alert("Error on vault/view");
		}
	});
}
function showSchedule(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/vault/schedule',
		type: 'GET',
		beforeSend: function(){ /* */ },
		success: function(html){
			if(html != 0){
				$("#schedule_body").html(html);
				$('#schedule').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
					, speed: 10
                });
			}
		},
		error: function() {
			alert("Error on show schedule");
		}
	});
}
</script>
<style>           
	#vaults { font-size: 1.2em; width:100%; }
	#vaults .sub_title { text-align: center }
	.left { float: left; width:49%; border-right: 2px solid grey; }
	.right { float: right; width:49%; border-left: 2px solid grey; }
	#vault_details { display:none; margin-top:20px; }

</style>

<h1>Manage Backups</h1>

<div id="vaults">
<div class="left">
<div class="sub_title"><?php echo __('Local vaults');?></div>
<?php echo __('They save their copies on your server');?>
<?php $this->widget('PGridView', array(
	'id'=>'localvault-grid',
	'dataProvider'=>$localVaults,
	'template' => '{items}',
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'getVaultDetails',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
		'host',
		array(
	        'type'=>'raw',
	        'value'=>function($data,$row){return Vault::getHumanStates($data->state);},
		),
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	),
)); ?>

</div>
<div class="right">
<div class="sub_title"><?php echo __('Remote vaults');?></div>
<?php echo __('You save your copies on their server');?>
<?php $this->widget('PGridView', array(
	'id'=>'remotevault-grid',
	'dataProvider'=>$remoteVaults,
	'template' => '{items}',
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'getVaultDetails',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
		'host',
		array(
	        'type'=>'raw',
	        'value'=>function($data,$row){return Vault::getHumanStates($data->state);},
		),
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	),
)); ?>

</div>
</div>
<div class="clear"></div>

<div id="vault_details"></div>

<div id="all_backups">
<h1><?php echo __('All Backups');?></h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'backup-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'vault',
		'filename',
		'initiated',
		'completed',
		'checksum',
		'state',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
</div>

<div id="schedule" class="modal" style="width:800px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="schedule_body"></div>
</div>
