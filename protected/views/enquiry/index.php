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

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 60%; float: left;  margin: 0px;}
	.right{width: 38%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<script>
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getEnquiry/'+enquiry_id,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){ $('#enquiry-grid').addClass('pgrid-view-loading'); },
		complete: function(){ 
						$('#enquiry-grid').removeClass('pgrid-view-loading');
						FB.XFBML.parse();
						twttr.widgets.load();
					},
		success: function(data){
			if(data != 0){
				$("#enquiry_body").html(data.html);
				$('#enquiry').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
					, speed: 10
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

<p style="text-align:center">
<b><?php echo __('What are the different states of an enquiry?');?></b><br /><br />
<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/workflow/workflow-<?php echo Yii::app()->user->getState('applicationLanguage');?>.png"/>
</p>

</div>
</div>

<div class="clear"></div>

<div id="enquiry" class="modal" style="width:870px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="enquiry_body"></div>
</div>


