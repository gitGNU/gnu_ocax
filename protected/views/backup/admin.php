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
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/vault/view',
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
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/vault/view',
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

<h1><?php echo __('All Backups');?></h1>

<?php $this->widget('PGridView', array(
	'id'=>'backup-grid',
	'dataProvider'=>$model->search(),
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/vault/view',
    ),
	'columns'=>array(
		array(
			'header'=>__('Vault'),
			'type' => 'raw',
			'value'=>'$data->vault0->host',
		),
		array(
			'header'=>__('Type'),
			'type' => 'raw',
			'value'=>'$data->vault0->getHumanType($data->vault0->type)',
		),
		'initiated',
		'completed',
		'filesize',
		array(
			'header'=>__('State'),
			'type' => 'raw',
			'value'=>'$data->getHumanState()',
		),
		array('class'=>'PHiddenColumn','value'=>'$data->vault0->id'),
	),
)); ?>


<div id="schedule" class="modal" style="width:800px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="schedule_body"></div>
</div>
