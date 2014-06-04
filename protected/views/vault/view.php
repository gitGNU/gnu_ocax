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
	//array('label'=>'Update Vault', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Delete Vault', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Backups', 'url'=>array('backup/admin')),
);

?>

<?php
if($model->type == 0){
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
if($model->type == LOCAL && $model->state == 0){
	$text = __('Tell the Admin at %s that the vault has been created and the key is:');
	$text = str_replace("%s", $model->host, $text);
	echo "<h2>You need to:</h2><p>$text ".$model->key."</p>";
}
if($model->type == REMOTE && $model->state == CREATED){
	$text = __('Ask the Admin at %s to create a vault for you. Your URL is').' '.Yii::app()->getBaseUrl(true);
	$text = str_replace("%s", $model->host, $text);
	echo "<h2>You need to:</h2><p>$text</p>";
	echo '<p>'.str_replace("%s", $model->host, __('%s will send you a key')).'</p>';
	$this->renderPartial('//vault/_updateKey', array('model'=>$model));
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
		'schedule',
		/*
		array(
	        'label'=>__('Key'),
			'type' => 'raw',
			'value'=> ($model->state == CREATED && $model->type == REMOTE)? '': $model->key,
		),
		*/
	),
)); ?>
</div>
</div>
<div class="clear"></div>

<?php if($model->state > 0){
	echo '<h1>'.__('Backups').'</h1>';

	$this->widget('zii.widgets.grid.CGridView', array(
		'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
		'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
		'loadingCssClass'=>'pgrid-view-loading',
		'id'=>'backup-grid',
		'dataProvider'=>$backups,
		//'filter'=>$model,
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
	));
}
?>
