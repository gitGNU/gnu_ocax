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
/* @var $form CActiveForm */
?>

<script>
function updateSchedule(el, day){
	schedule = $('#Vault_schedule').val();
	if($(el).is(':checked'))
		checked = '1';
	else
		checked = '0';
	schedule = schedule.substring(0, day) + checked + schedule.substring(day+1);
	$('#Vault_schedule').val(schedule);
}
</script>

<?php
	// get the available days for backups specified by remote admin	
	$opts = array('http' => array(
							'method'  => 'GET',
							'header'  => 'Content-type: application/x-www-form-urlencoded',
							'ignore_errors' => '1',
							'timeout' => 15,
							'user_agent' => 'ocax-'.getOCAXVersion(),
						));			
	$vaultName = rtrim($model->host2VaultName(Yii::app()->getBaseUrl(true)), '-remote');				
	$context = stream_context_create($opts);
	
	$reply=Null;
	$reply = @file_get_contents($model->host.'/vault/getSchedule?key='.$model->key.'&vault='.$vaultName, false, $context);
	if($reply !== Null && strlen($reply) == 7){
		$availableDays = $reply;
	}
?>

<div class="form" style="margin-top:-20px;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'remote-vault-schedule-form',
	'action' => Yii::app()->createUrl('vault/configureSchedule/'.$model->id),
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php /* echo $form->labelEx($model,'schedule'); */ ?>
		<p><?php
			$text = __('The admin at %s offers you these days');
			echo str_replace("%s", $model->host, $text).'. ';
			echo __('Select the day(s) you want to make the copy').'.';
		?></p>

		<?php
			echo $form->hiddenField($model, 'schedule');
			$day = 0;
			while($day < 7){
				if($availableDays[$day] == 1){
					echo '<span>';
					echo $model->getHumanDays($day);
					echo '<input type="checkbox" onclick="js:updateSchedule(this, '.$day.')">';
					echo '</span><br />';
				}
				$day++;
			}
			echo $form->error($model,'schedule');
		?>
		<?php echo $form->error($model,'schedule'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(__('Programme')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
