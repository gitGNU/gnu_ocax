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
.actual_provision_bar{
	padding: 8px 4px 15px 4px;
	font-weight:bold;
	color:#000000;
	font-size:18px;
	margin-top:5px;
	margin-bottom:10px;
}
.initial_provision_bar{
	margin-bottom:20px;
	padding: 8px 4px 15px 4px;
	font-weight:bold;
	color:#000000;
	font-size:18px;
	margin-top:5px;
	margin-bottom:10px;

}
.key{
	padding:2px;
	padding-left:10px;
	padding-right:60px;
}

</style>

<script>

/*
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		
		if($('.budget_details[budget_id='+budget_id+']').length>0){
			$('.budget_details').hide();
			$('.budget_details[budget_id='+budget_id+']').show();
			alert('cache');
		}else{
			el = $(this);
			$.ajax({
				url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudgetDetailsForBar/'+budget_id,
				type: 'GET',
				async: false,
				//dataType: 'json',
				beforeSend: function(){ },
				success: function(data){
					$('.budget_details').hide();
					el.append(data);
				},
				error: function() {
					alert("Error on get budget details");
				}
			});
		}
		$('.no-highlightGraphBar').removeClass('no-highlightGraphBar').addClass('highlightGraphBar');
		$(this).removeClass('highlightGraphBar').addClass('no-highlightGraphBar');
	});
});
*/
$(function() {
	$('.budget').bind('click', function() {
		showBudget($(this).attr('budget_id'), $(this).find('span').eq(0));
	});
});


/*
$(function() {
	$("#Budget_concept").on("click", function(event){
		$("#Budget_code").val('');
	});
	$("#Budget_code").on("click", function(event){
		$("#Budget_concept").val('');
	});
});
*/
function toggleChildren(id){
	if ($('#budget_children_'+id).is(":visible")){
		$('#budget_children_'+id).slideUp('fast');
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->request->baseUrl;?>/images/plus_icon.png');
	}else{
		//$('.budget_details').hide();		
		$('#budget_children_'+id).slideDown('fast');
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->request->baseUrl;?>/images/minus_icon.png');
	}
}
</script>



<?php

function echoChildBudgets($parent_budget, $indent, $graph_width, $globals){
	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$parent_budget->id;
	$criteria->order = 'actual_provision DESC';
	$child_budgets = Budget::model()->findAll($criteria);

	foreach($child_budgets as $budget){

		$budget_indent = 0;
		if($indent > 0)
			$budget_indent = 32;

		echo '<div style=" margin-left:'.$budget_indent.'px;margin-top:20px;">';
			if($budget->budgets)
				echo '<div style="margin-left:'. (-16 - 4) .'px">';	// 16 width of icon
			else
				echo '<div style="margin-left:0px">';

			if($budget->budgets){
			echo '<div style="float:left;">';
			echo '<img id="toggle_'.$budget->id.'" class="showChildren" src="'.Yii::app()->request->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
			}
			//echo '<div class="budget highlightGraphBar" budget_id="'.$budget->id.'" style="float:left;">';
			echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';
				$highlight=null;
				//if($budget->id == $globals['queried_budget'])
				//	$highlight = 'queriedBudget';
				echo '<div>';
				echo '<span>'.$budget->getConcept().' '.format_number($budget->actual_provision).' €</span> ';
				echo '</div>';

			$percent=percentage($budget->actual_provision,$globals['yearly_actual_provision']);
			//$width=$graph_width*(percentage($budget->actual_provision,$parent_budget->actual_provision) / 100);
			$width=$graph_width*(percentage($budget->actual_provision,$globals['largest_provision']) / 100);
			echo '<div class="actual_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';
			

	/*		$percent=percentage($budget->initial_provision,$globals['yearly_actual_provision']);
			$width=$graph_width*(percentage($budget->initial_provision,$parent_budget->actual_provision) / 100);
			echo '<div class="initial_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';
*/
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
	$featured=$model->findAllByAttributes(array('year'=>$model->year, 'featured'=>1));
	$graph_width=897;
	
	echo '<div id="bar_display" style="margin-top:5px;margin-bottom:15px;">';
	foreach($featured as $featured_budget){
	
		$criteria = new CDbCriteria;
		$criteria->condition = 'parent = '.$featured_budget->id;
	
		$largest_provision=0;
		foreach(Budget::model()->findAll($criteria) as $budget){
			if($budget->actual_provision > $largest_provision)
				$largest_provision = $budget->actual_provision;
		}
		$graph_percentage=percentage($largest_provision, $featured_budget->actual_provision);
	
		$globals=array(	'yearly_initial_provision' => $featured_budget->initial_provision,
						'yearly_actual_provision' => $featured_budget->actual_provision,
						'largest_provision'=> $largest_provision,
						'queried_budget' => $featured_budget->id,
		);
	
		echo '<div class="graph_bar_group">';
		
		echo '<a  class="graph_title" href="'.Yii::app()->request->baseUrl.'/budget/view/'.$featured_budget->id.'" onclick="js:showBudget('.$featured_budget->id.', this);return false;">';
		echo CHtml::encode($featured_budget->getConcept()).'</a>';
		echo '<div class="graph_bar_container">';
			echoChildBudgets($featured_budget, 0, $graph_width, $globals);
		echo '</div>';
		echo '</div>';

	}
	echo '</div>';
?>
