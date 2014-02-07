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
//Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

?>

<style>
.loader_gif {
	margin-left:20px;
	float:right;
	display:none;
}
.loader_gif img {
	margin-top:5px;
 	margin-left:5px;
}
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/ocax.js"></script>
<script>
$(function() {
	$('.budget').bind('click', function() {
		showBudget($(this).attr('budget_id'), $(this).find('span').eq(0));
	});
	theme_color = rgb2hex($('.actual_provision_bar').first().css("background-color"));
	$('.executed_bar').css("background-color",lightenDarkenColor(theme_color,-15));
});

function toggleChildren(id){
	if ($('#budget_children_'+id).is(":visible")){
		$('#budget_children_'+id).slideUp('fast');
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->request->baseUrl;?>/images/plus_icon.png');
	}else{
		$('#budget_children_'+id).slideDown('fast');
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->request->baseUrl;?>/images/minus_icon.png');
	}
}
</script>

<?php
function echoChildBudgets($parent_budget, $indent, $graph_width, $globals){
	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$parent_budget->id.' AND actual_provision != 0';
	$criteria->order = 'code ASC';
	$child_budgets = Budget::model()->findAll($criteria);

	foreach($child_budgets as $budget){

		$budget_indent = 0;
		if($indent > 0)
			$budget_indent = 32;

		echo '<div style="margin-left:'.$budget_indent.'px;margin-top:20px;">';
			if($budget->budgets)
				echo '<div style="margin-left:'. (-16 - 4) .'px">';	// 16 width of icon
			else
				echo '<div style="margin-left:0px">';

			if($budget->budgets){
			echo '<div style="float:left;" class="showChildrenIcon">';
			echo '<img id="toggle_'.$budget->id.'" src="'.Yii::app()->request->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
			}
			echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';
				echo '<span class="barBudgetConcept">'.$budget->code.'. '
						.$budget->getConcept().' '.format_number($budget->actual_provision)
						//.', root actual '.$globals['root_actual_provision']	
						//.', executed '.format_number($budget->getExecuted())
						//.', root executed '.$globals['root_executed']				
						.'</span> ';
						
/*					
				echo '<p>';
				foreach($globals['largest_provisions'] as $key => $value)
					echo $key.' '.$value.'<br />';
				echo '</p>';
*/
				
				$percent=percentage($budget->actual_provision,$globals['root_actual_provision']);
				$width = $graph_width*(percentage($budget->actual_provision,$globals['largest_provision']) / 100);
				echo '<div class="actual_provision_bar" style="width:'.$width.'px;">';
				echo '<div class="graph_bar_percent">'.$percent.'%</div>';
				echo '</div>';
				
				if($executed=$budget->getExecuted()){
					$percent=percentage($executed, $globals['root_actual_provision']);
					$width = $graph_width*(percentage($executed, $globals['largest_provision']) / 100);
					echo '<div class="executed_bar" style="width:'.$width.'px;">';
					echo '<div class="graph_bar_percent">'.$percent.'%</div>';
					echo '</div>';
				}
				
			echo '</div>';
		echo '</div>';
		echo '<div style="clear:both"></div>';

		if($budget->budgets){
			echo '<div id="budget_children_'.$budget->id.'" style="display:none">';
			echoChildBudgets($budget, $indent+1, $width, $globals);
			echo '</div>';
		}
		echo '</div>';
	}
}

?>

<?php
	$graph_width=929;
		
	echo '<div id="bar_display" style="margin: 5px 0 15px 0px">';
	foreach($featured as $featured_budget){
	
		$criteria = new CDbCriteria;
		$criteria->condition = 'parent = '.$featured_budget->id;
	
		$largest_provisions = array('actual'=>0, 'executed'=>0);
		foreach(Budget::model()->findAll($criteria) as $budget){
			if($budget->actual_provision > $largest_provisions['actual'])
				$largest_provisions['actual'] = $budget->actual_provision;
				
			if($budget->getExecuted() > $largest_provisions['executed'])
				$largest_provisions['executed'] = $budget->getExecuted();
		}
		arsort($largest_provisions);
		reset($largest_provisions);
		//$largest_provision = max($largest_provisions);
				
		//foreach($largest_provisions as $key => $value)
		//	echo '<p>'.$key.' '.$value.'</p>';
		//echo 'largest_provision -'.$largest_provision.'-';
	
		$globals=array(	'root_executed' => $featured_budget->getExecuted(),
						'root_actual_provision' => $featured_budget->actual_provision,
						'largest_provision'=> max($largest_provisions),
						'queried_budget' => $featured_budget->id,
		);
	
		echo '<div class="graph_bar_group graph_group">';
				
		echo '<a  class="graph_title" href="'.Yii::app()->request->baseUrl.'/budget/view/'.$featured_budget->id.'" onclick="js:showBudget('.$featured_budget->id.', this);return false;">';
		echo CHtml::encode($featured_budget->getConcept()).'</a>';
		echo '<span class="graph_title" style="margin-left:30px;">'.format_number($featured_budget->actual_provision).'</span>';
		echo '<div class="graph_bar_container">';
			echoChildBudgets($featured_budget, 0, $graph_width, $globals);
		echo '</div>';
		echo '</div>';

	}
	echo '</div>';
?>

<div id="bar_loader_gif" class="loader_gif">
<img src="<?php echo Yii::app()->request->baseUrl;?>/images/preloader.gif"/></div>
<div style="clear:both"></div>
</div>
