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

/* @var $this VaultController */
/* @var $model Vault */

$this->menu=array(
	//array('label'=>'Delete Vault', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>__('Show schedule'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showSchedule(); return false;')),
	array('label'=>'Manage Backups', 'url'=>array('backup/admin')),
);
?>

<?php
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
} else { ?>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<?php } ?>

<?php
if($model->type == LOCAL){
	echo '<h1>'.__('Local vault').'</h1>';
	echo '<div class="sub_title" style="margin-bottom:5px;">'.$model->host.' &rarr; '.Yii::app()->getBaseUrl(true).'</div>';
	echo '<p>'.__('They save their copies on your server').'</p>';
}else{
	echo '<h1>'.__('Remote vault').'</h1>';
	echo '<div class="sub_title" style="margin-bottom:5px;">'.Yii::app()->getBaseUrl(true).' &rarr; '.$model->host.'</div>';
	echo '<p>'.__('You save your copies on their server').'</p>';
}
?>

<div>
<div style="float: left; width:49%;">
<?php
if($model->type == LOCAL && $model->state == CREATED){
	$text = __('Tell the Admin at %s that the vault has been created and the key is:');
	$text = str_replace("%s", $model->host, $text);
	echo "<h2>You need to:</h2><p>$text ".$model->key."</p>";
}
if($model->type == REMOTE && $model->state == CREATED){
	$text = __('Ask the Admin at %s to create a vault for you. Your URL is').' '.Yii::app()->getBaseUrl(true);
	$text = str_replace("%s", $model->host, $text);
	echo "<h2>You need to:</h2><p>$text</p>";
	echo '<p>'.str_replace("%s", $model->host, __('%s will send you a key')).'</p>';
	$this->renderPartial('//vault/_configKey', array('model'=>$model));
}

if($model->type == LOCAL && $model->state == VERIFIED){
	$text = __('You are waiting for the admin at %s to choose one or more of the following days').'.';
	$text = str_replace("%s", $model->host, $text);
	echo "<h2>".__('Waiting...')."</h2>";
	echo "<p>".$text."</p>";
	echo "<p>".$model->getHumanSchedule()."</p>";
}
if($model->type == REMOTE && $model->state == VERIFIED){
	echo "<h2>".__('Choose day(s) to backup')."</h2>";
	$this->renderPartial('//vault/_configSchedule', array('model'=>$model));
}
?>

</div>
<div style="float: right; width:44%;">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'host',
		array(
	        'label'=>__('State'),
			'type' => 'raw',
			'value'=> $model->getHumanStates($model->state),
		),
		//'schedule',
		array(
	        'label'=>__('Schedule'),
			'type' => 'raw',
			'value'=> ($model->state == READY)? $model->getHumanSchedule() : __('Pending'),
		),

	),
)); ?>
</div>
</div>
<div class="clear"></div>

<?php if($model->state >= READY){
	echo '<h1>'.__('Backups').'</h1>';

	if($model->type == LOCAL){
		$this->widget('PGridView', array(
			'id'=>'backup-grid',
			'dataProvider'=>$backups,
			'onClick'=>array(
				'type'=>'url',
				'call'=>Yii::app()->request->baseUrl.'/backup/downloadBackup',
			),
			'columns'=>array(
				'filename',
				'initiated',
				'completed',
				'filesize',
				array(
					'header'=>__('State'),
					'type' => 'raw',
					'value'=>'$data->getHumanState()',
				),
				array('class'=>'PHiddenColumn','value'=>'$data->id'),
			),
			
		));
	}
	if($model->type == REMOTE){
		$this->widget('zii.widgets.grid.CGridView', array(
			'htmlOptions'=>array('class'=>'pgrid-view'),
			'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
			'loadingCssClass'=>'pgrid-view-loading',
			'id'=>'backup-grid',
			'dataProvider'=>$backups,
			'columns'=>array(
				'filename',
				'initiated',
				'completed',
				'filesize',
				array(
					'header'=>__('State'),
					'type' => 'raw',
					'value'=>'$data->getHumanState()',
				),
			),
			
		));
	}
}
?>

<?php if(Yii::app()->request->isAjaxRequest){ ?>
	<div id="schedule" class="modal" style="width:800px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="schedule_body"></div>
	</div>
<?php } ?>
