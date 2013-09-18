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

/* @var $this BudgetController */
/* @var $model Budget */

//$year = Config::model()->findByPk('year')->value;
$year = $model->year;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
//$criteria->order = 'weight ASC';
$root_budget = Budget::model()->find($criteria);

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

?>

<style>
.button {
   border-top: 1px solid #96d1f8;
   background: #A1A150;
   padding: 13.5px 27px;
   color: white;
   font-size: 19px;
   font-family: Helvetica, Arial, Sans-Serif;
   text-decoration: none;
   vertical-align: middle;
   }
.button:hover {
   border-top-color: #28597a;
   background: #28597a;
   color: #ccc;
   }
.button:active {
   border-top-color: #1b435e;
   background: #1b435e;
   }
   
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.highlight.js"></script>

<script>
var budgetCache=new Array();

$(function() {
	$('#Budget_concept').focus(function () {
		$('#Budget_code').val('');
	});
});

function _showBudget(budget_id){
	$("#budget_popup_body").html(budgetCache[budget_id]);
	$('#budget_popup').bPopup({
		modalClose: false
		, follow: ([false,false])
		, fadeSpeed: 10
		, positionStyle: 'absolute'
		, modelColor: '#ae34d5'
	});
}

function showBudget(budget_id, element){
	if(budgetCache[budget_id]){
		_showBudget(budget_id);
		return;	
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		async: false,
		beforeSend: function(){
						$('.loading_gif').remove();
						$(element).after('<img style="vertical-align:top;" class="loading_gif" src="<?php echo Yii::app()->theme->baseUrl;?>/images/loading.gif" />');
					},
		complete: function(){ $('.loading_gif').remove(); },
		success: function(data){
			if(data != 0){
				budgetCache[budget_id]=data;
				_showBudget(budget_id);
			}
		},
		error: function() {
			alert("Error on show budget");
		}
	});
}
function highlightResult(){
	stringArray=$('#Budget_concept').val().split(" ");
	for (var i = 0; i < stringArray.length; i++) {
		if(stringArray[i].length > 3)
	    	$('.highlight_text').highlight(stringArray[i], { wordsOnly: true });
		else
			continue;
	}
}
function afterSearch(){
	if($.fn.yiiListView.getKey('search-results', 0)){
		$('#the_graphs').hide();
		$('#no_results').hide();
		$('#search_results_container').show();
		highlightResult();
		$("html,body").animate({scrollTop:0},0);
	}
	else{
		$('#search_results_container').hide();
		$('#no_results').hide();
		$('#no_results').fadeIn('fast');
		$('#the_graphs').show();
	}
}
</script>

<style>           
	.outer{width:100%; padding: 0px;}
	.left{width: 49%; float: left;  margin: 0px;}
	.right{width: 49%; float: left; margin: 0px;}
	.clear{clear:both;}
</style>

<div class="outer">
<div class="left" style="height:140px">

<div>
	<div id="big_budget_icon" style="float:left"></div>
	<div id="budget_titulo_j" style=""><?php echo __('Budgets');?></div>
</div>

<div id="budget_search_j"><!-- search-form start -->
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<?php echo $form->hiddenField($model,'year'); ?>

<div>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?> 
		<?php echo $form->textField($model,'concept',array('size'=>10,'maxlength'=>200)); ?>

		<span style="margin-left:15px;">
		<?php echo $form->label($model,'code'); ?> 
		<?php echo $form->textField($model,'code',array('size'=>2,'maxlength'=>10)); ?>
		</span>
		<span style="margin-left:15px;"><?php echo CHtml::submitButton(__('Search')); ?></span>
	</div>


</div>
<?php $this->endWidget(); ?>

<?php
	echo '<div id="no_results" style="display:none;float:right">'.
		__('No search results').
		'</div>';
?>

</div><!-- search-form end-->


</div>
<div class="right">


<!--  Select year start  -->
<div style="float:right; width:48%; margin-bottom:30px;">

<?php
if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL','order'=>'year DESC'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1','order'=>'year DESC'));

if(count($years) > 1){
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});
		echo '<div style="float:right">';
		echo __('Available years').'<br />';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
		echo '</div>';
		echo '<div style="margin-right:45px" id="change_to_bar" ></div>';	// cambia por icono de 'años disponibles'
}
?>
</div>
<!--  Select year finished  -->

<!--  Change graph type start  -->
<div style="clear:right">
<?php
	echo '<div style="float:right; width:50%; padding-top:10px; border-top: 2px dashed #555555;">';
	$change=Yii::app()->request->baseUrl.'/budget?graph_type';
	echo 'this is some text';
	echo '<div id="change_to_bar" onclick="window.location=\''.$change.'=bar\'"></div>';
	echo '<div id="change_to_pie" onclick="window.location=\''.$change.'=pie\'"></div>';
	echo '</div>';
	
	if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
		echo '<div style="float:left; width:48%; padding-top:10px; border-top: 2px dashed #555555;">';
		echo __('Download database');
		echo '<div id="download_database" onclick="window.location=\''.$zip->webPath.'\'"></div>';
		echo '</div>';
	}
?>
</div>
<!--  Change graph type finish  -->

</div>

</div>
<div style="clear:both"></div>




<div>
<?php
	echo '<div id="search_results_container" style="display:none">';
	$this->widget('zii.widgets.CListView', array(
		'id'=>'search-results',
		'ajaxUpdate' => true,
		'dataProvider'=> $model->publicSearch(),
		'itemView'=>'_searchResults',
		'enableHistory' => true,
		'afterAjaxUpdate' => 'js:function(){ afterSearch(); }',
	));
	echo '</div>';

	echo '<div id="the_graphs">';
	if(!$root_budget){
		echo '<h1>'. __('No data available').'</h1>';
	}else{
		if($graph_type == 'bar')
			$this->renderPartial('_indexBar',array('model'=>$model));
		else
			$this->renderPartial('_indexPie',array('model'=>$model));
	}
	echo '</div>';
?>
</div>

<div id="budget_popup" class="popup_content_j" style="display:none;width:900px;">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div id="budget_popup_body"></div>
</div>


