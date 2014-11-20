<?php

/**
 * OCAX -- Citizen driven Observatory software
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

Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

$year = $model->year;
$showFeaturedMenu=0;
$featured = array();

if(isset($root_budget)){
	$featured[]=$root_budget;
}else{
	$criteria = new CDbCriteria;
	$criteria->condition = 'year = '.$year.' AND parent is NULL';
	$root_budget = Budget::model()->find($criteria);
	if($root_budget){
		if(!Yii::app()->user->isAdmin() && !$model->isPublished())
			$featured = array();
		else{
			$featured=$root_budget->getFeatured();
		}
		if(count($featured) > 2)
			$showFeaturedMenu=1;
	}
}

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $('#search_loader_gif').show();
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/budget.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.highlight.js"></script>


<script>
var budgetCache=new Array();
var showingBudgetDetails='';	// we got asked to show the budgetDetails of this budget->id

$(function() {
	$(window).scroll(function() {
		if($(this).scrollTop() > 300)
			$('.goToTop').fadeIn(500);
		else
			$('.goToTop').fadeOut(500);
	});
	$(".goToTop").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 0);
		$('.goToTop').hide();
	});
	$('#Budget_concept').focus(function () {
		$('#Budget_code').val('');
	});
	$("#featured_menu > li").click(function(){
		$("#featured_menu").hide();
		$('html,body').animate({scrollTop: $($(this).attr('anchor')).offset().top}, 0);
	});
	$("#featured_menu").mouseleave(function(){
		$("#featured_menu").fadeOut();
	});
});
function toggleFeaturedMenu(){
	if($("#featured_menu").is(':visible'))
		$("#featured_menu").fadeOut();
	else
		$("#featured_menu").show("slide", { direction: "right" }, 250);
}
function _showBudget(budget_id){
	$("#budget_popup_body").html(budgetCache[budget_id]);
	$('#budget_popup').bPopup({
		modalClose: false
		, follow: ([false,false])
		, speed: 10
		, positionStyle: 'absolute'
		, modelColor: '#ae34d5'
	});
}
function showBudget(budget_id, element){
	showingBudgetDetails=budget_id;
	if(budgetCache[budget_id]){
		_showBudget(budget_id);
		return;
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		beforeSend: function(){
						if('bar' == '<?php echo $graph_type;?>'){
							$('#bar_loader_gif').appendTo(element);
							$('#bar_loader_gif').show();
						}else{
							$('.loader_gif').hide();
							loader = $(element).closest('.ocaxjqplot').find('.loader_gif');
							loader.show();
						}
					},
		complete: function(){ $('.loader_gif').hide(); },
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
	$('#search_loader_gif').hide();
	if($.fn.yiiListView.getKey('search-results', 0)){
		$('#the_graphs').hide();
		$('#featured_menu_icon').hide();
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
		$('#featured_menu_icon').show();
	}
}
<?php if(Yii::app()->user->canEditBudgetDescriptions()){ ?>
function budgetDetailsUpdated(){
	delete budgetCache[showingBudgetDetails];
	$('#budget_popup').bPopup().close();
}
<?php } ?>
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
	<div class="bigTitle"><?php echo __('Budgets');?></div>
</div>

<div id="budgetSearchForm" style="margin-top:30px"><!-- search-form start -->
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<?php echo $form->hiddenField($model,'year'); ?>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('maxlength'=>200,'style'=>'width:160px;')); ?>
		<span style="margin-left:13px;">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('maxlength'=>15,'style'=>'width:50px;')); ?>
		</span>
		<span style="margin-left:10px;"><?php echo CHtml::submitButton(__('Search')); ?></span>
		<img id="search_loader_gif" style="vertical-align:middle;display:none" src="<?php echo Yii::app()->request->baseUrl;?>/images/loading.gif" />
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form end-->

<?php
	echo '<p id="no_results" style="display:none;margin-left:80px;">'.
		__('No search results').
		'</p>';
?>

</div>
<div class="right">

<!--  Select year start  -->
<?php
if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL','order'=>'year DESC'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1','order'=>'year DESC'));

if(count($years) > 1){
	echo '<div class="budgetOptions" >';
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});
		echo '<span style="margin-right:15px; float:left;">';
		include(svgDir().'graph-type-pie.svg');
		echo '</span>';
		
		
		echo '<div style="float:left">';
		echo __('Available years').'<br />';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
		echo '</div>';
	echo '</div>';
}else
	echo '<div style="height:70px"></div>';
?>
<!--  Select year finished  -->

	<!--  Change graph type start  -->
	<div style="clear:right">
	<?php
	
		echo '<div class="budgetOptions" style="position:relative">';
		$change=Yii::app()->request->baseUrl.'/'.Yii::app()->request->pathInfo.'?graph_type';
		echo '<span style="cursor:pointer" onclick="window.location=\''.$change.'=pie\'">';
		include(svgDir().'graph-type-pie.svg');
		echo '</span>';
		echo '<span style="cursor:pointer" onclick="window.location=\''.$change.'=bar\'">';
		include(svgDir().'graph-type-bar.svg');
		echo '</span>';
		if($showFeaturedMenu){
			//echo '<img id="featured_menu_icon" src="'.
			//	Yii::app()->theme->baseUrl.'/images/menuitems.png" onclick="js:toggleFeaturedMenu()" />';
			echo '<i id="featured_menu_icon" class="icon-indent-left color" style="font-size:38px" onclick="js:toggleFeaturedMenu()"></i>';
			echo '<ul id="featured_menu">';
			foreach($featured as $budget)
				echo '<li anchor="#anchor_'.$budget->id.'">'.$budget->getConcept().'</li>';
			echo '</ul>';
		}
		echo '</div>';

		if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
			echo '<div class="budgetOptions" style="float:left; margin-bottom:-20px;">';
			echo '<span id="download_database" onclick="window.location=\''.$zip->webPath.'\'">';
			include(svgDir().'download-database.svg');
			echo '</span>';
			echo '<p class="link" style="margin-top:15px;font-weight:bold;" onclick="window.location=\''.
				 $zip->webPath.'\'">'.__('Download data').'</p>';
			echo '</div>';
		}
	?>
	</div>
	<!--  Change graph type finish  -->
</div>

</div>
<div style="clear:both;"></div>

<div>
<?php
	echo '<div id="search_results_container" style="display:none">';
	echo '<div class="horizontalRule"></div>';
	$this->widget('zii.widgets.CListView', array(
		'id'=>'search-results',
		'ajaxUpdate' => true,
		'dataProvider'=> $model->publicSearch(),
		'itemView'=>'_searchResults',
		'loadingCssClass'=>'',
		'enableHistory' => true,
		'afterAjaxUpdate' => 'js:function(){ afterSearch(); }',
	));
	echo '</div>';

	echo '<div id="the_graphs">';
	if(!$featured){
		echo '<div class="sub_title">'. __('No data available').'</div>';
	}else{
		if($graph_type == 'bar')
			$this->renderPartial('_indexBar',array('model'=>$model,'featured'=>$featured));
		else
			$this->renderPartial('_indexPie',array('model'=>$model,'featured'=>$featured));
	}
	echo '</div>';
?>
</div>

<div id="budget_popup" class="modal" style="width:900px;">
	<i class='icon-cancel-circled modalWindowButton bClose'></i>
	<i class='icon-popup modalWindowButton bModal2Page' onclick="js:budgetModal2Page();"></i>
	<div id="budget_popup_body"></div>
</div>

<style>
.loading {
	background-color:transparent;
	opacity: .6;
}
</style>
<div class="goToTop">&#x25B2;&nbsp;&nbsp;&nbsp;<?php echo __('go to top');?></div>
<div id="preloader" class="loading"></div>


