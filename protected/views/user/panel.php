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
		echo '<div class="panel_left" style="width:49%">';
		$column=1;
	}
	else
	{
		echo '<div class="panel_right" style="width:49%">';
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
	echo '<div class="horizontalRule"></div>';
}?>

<div class="outer">
<div class="panel_left">
	<div id="nueva_consulta" onclick="location.href='<?php echo $this->createUrl('enquiry/create/');?>'"></div>
	<div class="clear"></div>
	<div class="sub_title"><?php echo CHtml::link(__('New enquiry'),array('enquiry/create/'));?></div>
	<p>
	<?php
		$str = __('ENQUIRY_NEW_MSG');
		echo str_replace('%s', CHtml::link(__('Budgets'),array('/budget')), $str);
	?>
	</p>
</div>
<div class="panel_right">
	<div id="datos_usuario" onclick="location.href='<?php echo $this->createUrl('user/update/');?>'"></div>
	<div class="clear"></div>
	<div class="sub_title"><?php echo CHtml::link(__('My user information'),array('user/update/'));?></div>
	<p>
		<?php echo __('Change your profile');?><br />
		<?php echo __('Configure your email');?><br />
		<?php echo __('Change your password');?>
	</p>
</div>
</div>

<?php

$panel_separator_added=0;
function addPanelSeparator(){
	global $panel_separator_added;
	if(!$panel_separator_added){
		
		echo '<div class="outer">';	
		echo '<div class="horizontalRule" style="float:right;padding-top:10px;"></div>';
		echo '<div id="control_panel"></div>';
		
		//echo '<div class="sub_title" style="float:right;font-size: 16pt;margin-left:50px;">';
		//echo CHtml::link('social',array('site/chat'),array('target'=>'_chat'));
		//echo '</div>';
		echo '<div class="sub_title" style="float:right;font-size: 16pt;margin-left:50px;">';
		echo '<a href="http://ocax.net/pipermail/lista/" target="_list">mailing list</a>';
		echo '</div>';
		echo '<div class="sub_title" style="float:right;font-size: 16pt;">';
		echo '<a href="http://ocax.net/'.Yii::app()->user->getState('applicationLanguage').':" target="_manual">manual</a>';
		echo '</div>';

		$panel_separator_added=1;
	}
}

if($model->is_team_member){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.CHtml::link(__('Entrusted enquiries'),array('enquiry/managed')).'</div>';
	echo 	'<p><u>Team member</u><br />'.__('Manage the enquiries you are responsable for').'</p>';
	echo '</div>';
}

if($model->is_editor){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.__('CMS editor options').'</div>';
	echo '<div style="float:left"><p>';
		echo CHtml::link(__('Introduction pages'), array('/introPage/admin')).'<br />';
		echo CHtml::link(__('Site pages'), array('/cmsPage/admin'));
	echo '</p></div>';
	echo '<div style="margin-left:50px;float:left"><p>';
		echo CHtml::link(__('Wallpaper'), array('/file/wallpaper'));
	echo '</p></div>';
	echo '</div>';
}

if($model->is_manager){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.CHtml::link(__('Manage enquiries'),array('enquiry/admin')).'</div>';
	echo 	'<p><u>Team manager</u><br />'.__('Assign enquiries to team members and check status').'</p>';
	echo '</div>';
}

if($model->is_admin){
	addPanelSeparator();
	changeColumn();
	echo '<div class="sub_title">'.__('Administator\'s options').'</div>';
	if($upgrade){
		$url = getInlineHelpURL(':upgrade');
		echo '<p>'.__('New version available').'. '.__('Upgrade now').' '.
			 '<a href="'.$url.'" target="_upgrade">OCAx '.$upgrade.'</a>'.
			 '</p>';
	}
	echo '<div style="float:left"><p>';
		echo CHtml::link(__('Years and budgets'),array('budget/adminYears')).'<br />';
		echo CHtml::link(__('Newsletters'),array('newsletter/admin')).'<br />';
		echo CHtml::link(__('Zip file'),array('file/databaseDownload')).'<br />';
		echo CHtml::link(__('Budget descriptions'),array('budgetDescription/admin')).'<br />';
	echo '</p></div>';
	echo '<div style="float:right"><p>';
		echo CHtml::link(__('Users and roles'),array('user/admin')).'<br />';
		echo CHtml::link(__('Email text templates'),array('emailtext/admin')).'<br />';
		echo CHtml::link(__('Global parameters'),array('config/admin')).'<br />';
		echo CHtml::link(__('Backup'),array('backup/create')).'<br />';
 
	echo '</p></div>';
	echo '</div>';
	echo '</div>';
}
?>

<?php /*$this->widget('InlineHelp');*/ ?>

<div class="horizontalRule" style="padding-top:20px;margin-top:20px;float:right;"></div>
<div id="panelMyEnquiries"></div>
<div class="clear"></div>
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
echo '<p></p>';
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
echo '<p></p>';
}

if($noEnquiries){
	echo '<div class="sub_title">';
	echo __('Enquiries of your interest will be displayed here');
	echo '</div>';
}

?>


</div>
</div>


<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { 
			$(".flash-success").slideDown('fast');		
			setTimeout(function() {
				$('.flash-success').slideUp('fast');
    		}, 4500);
		});
	</script>
    <div class="flash-success" style="display:none">
		<?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php endif; ?>


<?php if(Yii::app()->user->hasFlash('error')):?>
    <div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error');?>
    </div>
<?php endif; ?>


<?php if(Yii::app()->user->hasFlash('newActivationCodeError')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash-error').slideUp('fast');
    	}, 4500);
		});
	</script>
    <div class="flash-error">
		<?php echo Yii::app()->user->getFlash('newActivationCodeError');?>
    </div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('prompt_blockuser')){
	list($name, $user_id) = explode("|", Yii::app()->user->getFlash('prompt_blockuser'));
    echo '<div class="flash-notice">';
		echo __('Do you want to block').' '.$name.'?';
		$url=Yii::app()->request->baseUrl.'/user/block/'.$user_id.'?confirmed=1';
		echo '<button onclick="js:window.location=\''.$url.'\'" style="margin-left:20px;margin-right:20px">'.__('Yes').'</button>';
		echo '<button onclick="$(\'.flash-notice\').slideUp(\'fast\')">'.__('No').'</button>';
	echo '</div>';
} ?>
