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
?>

<?php
/* @var $this UserController */
/* @var $model User */
/*
 * @property integer $is_team_member
 * @property integer $is_editor
 * @property integer $is_manager
 * @property integer $is_admin
*/

$column=0;
function changeColumn()
{
	global $column;
	if($column==0)
	{
		echo '<div class="clear"></div>';
		echo '<div class="panel_left">';
		$column=1;
	}
	else
	{
		echo '<div class="panel_right">';
		$column=0;
	}
}
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.clear{clear:both;}
</style>

<?php if(!$model->is_active){
	echo '<div class="sub_title">'.__('Welcome').'</div>';
	$this->renderPartial('_notActiveInfo', array('model'=>$model));
}?>

<div class="outer">
<div class="panel_left" style="width:60%">
<div id="nueva_consulta"></div>
<div class="clear"></div>
<div class="sub_title"><?php echo CHtml::link(__('New enquiry'),array('enquiry/create/'));?></div>
<p>
<?php
$str = __('ENQUIRY_NEW_MSG');
echo str_replace('%s', CHtml::link(__('Budgets'),array('/budget')), $str);
?>
</p>
</div>
<div class="panel_right" style="width:27%">
<div id="datos_usuario"></div>
<div class="clear"></div>
<div class="sub_title"><?php echo CHtml::link(__('My user information'),array('user/update/'));?></div>
<p>
<?php echo __('Change your profile');?><br />
<?php echo __('Configure your email');?><br />
<?php echo __('Change your password');?></p>
</div>

<?php

$panel_separator_added=0;
function addPanelSeparator(){
	global $panel_separator_added;
	if(!$panel_separator_added){
		echo '<div class="panel_separator">';
		echo '<div id="control_panel"></div>';
		echo '<div class="clear"></div>';
		echo '</div>';	
		$panel_separator_added=1;
	}
}

if($model->is_team_member){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.CHtml::link(__('Entrusted enquiries'),array('enquiry/managed')).'</div>';
	echo 	'<p>'.__('Manage the enquiries you are responsable for').'<br />'.
			'<a href="http://ocax.net/?El_software:Team_member" target="_new">'.__('more info').'</a>'.	
			'</p>';
	echo '</div>';
}

if($model->is_editor){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">CMS editor options</div>';
	echo '<p>';
		echo CHtml::link(__('Introduction pages'), array('/introPage/admin')).'<br />';
		echo CHtml::link(_('Site pages'), array('/cmsPage/admin')).'<br />';
	echo '</p>';
	echo '</div>';
}

if($model->is_manager){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.CHtml::link(__('Manage enquiries'),array('enquiry/admin')).'</div>';
	echo 	'<p>'.__('Assign enquiries to team members and check status').'<br />'.
			'<a href="http://ocax.net/?El_software:Team_manager" target="_new">'.__('more info').'</a>'.		
			'</p>';
	echo '</div>';
}

if($model->is_admin){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">Administator\'s options</div>';
	echo '<div style="float:left">';
		echo CHtml::link('Users and roles',array('user/admin')).'<br />';
		echo CHtml::link('Email text templates',array('emailtext/admin')).'<br />';
		echo CHtml::link('Bulk email',array('bulkEmail/admin')).'<br />';
		echo CHtml::link('Zip file',array('file/databaseDownload')).'<br />';
	echo '</div>';
	echo '<div style="float:right">';
		echo CHtml::link('Years and budget data',array('budget/adminYears')).'<br />';	
		echo CHtml::link('Budget descriptions',array('budgetDescription/admin')).'<br />';
		echo CHtml::link('Global parameters',array('config/admin')).'<br />';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

?>

</div>


<div class="panel_separator" style="width:100%;">
<div id="panel_consulta"></div>
<div class="clear"></div>
</div>

<?php
$noEnquiries=1;

if($enquirys->getData()){
$noEnquiries=0;
echo '<div class="sub_title">'.__('My enquiries').'</div>';
$this->widget('zii.widgets.grid.CGridView', array(
	'htmlOptions'=>array('class'=>'pgrid-view pgrid-cursor-pointer'),
	'cssFile'=>Yii::app()->request->baseUrl.'/css/pgridview.css',
	'loadingCssClass'=>'pgrid-view-loading',
	'id'=>'enquiry-grid',
	'selectableRows'=>1,
	'selectionChanged'=>'function(id){ location.href = "'.$this->createUrl('enquiry/view').'/"+$.fn.yiiGridView.getSelection(id);}',
	'template' => '{items}{pager}',
	'dataProvider'=>$enquirys,
	'ajaxUpdate'=>true,
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
)));
}
?>

<?php

if($subscribed->getData()){
$noEnquiries=0;
echo '<div class="sub_title">'.__('I am subscribed to these enquirytions').'</div>';
echo '<span class="hint">'.__('You will be sent an email when these enquiries are updated').'</span>';
$this->widget('PGridView', array(
	'id'=>'subscribed-grid',
	'template' => '{items}{pager}',
	'dataProvider'=>$subscribed,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'ajaxUpdate'=>true,
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
}

if($noEnquiries){
	echo '<div class="sub_title">';
	echo __('Enquiries of your interest will be displayed here');
	echo '</div>';
}

?>


<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 3500);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('newActivationCodeError')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_prompt').fadeOut('fast');
    	}, 3500);
		});
	</script>
    <div class="flash_prompt">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('newActivationCodeError');?></b></p>
    </div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('prompt_blockuser')){
	list($name, $user_id) = explode("|", Yii::app()->user->getFlash('prompt_blockuser'));
    echo '<div class="flash_prompt">';
		echo '<p style="margin-top:5px;font-weight:bold;">'.__('Do you want to block').' '.$name.'?</p>';
		$url=Yii::app()->request->baseUrl.'/user/block/'.$user_id.'?confirmed=1';
		echo '<button onclick="js:window.location=\''.$url.'\'">'.__('Yes').'</button>'.'&nbsp;&nbsp;&nbsp;';
		echo '<button onclick="$(\'.flash_prompt\').slideUp(\'fast\')">'.__('No').'</button>';
	echo '</div>';
} ?>
