<?php
/* @var $this BudgetController */
/* @var $model Budget */
//Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

?>

<style>
.graph {
	background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/images/graph_paper.png');
	background-repeat:repeat;
}
.actual_provision_bar{
	background-color:#00cadc;
	padding: 8px 4px 15px 4px;
	font-weight:bold;
	color:#000000;
	font-size:18px;
	margin-top:5px;
	margin-bottom:10px;
}
.initial_provision_bar{
	margin-bottom:20px;
	background-color:#59bac3;
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
.queriedBudget{
	color:#4682B4;
	font-weight:bold;
}
.graph_bar_percent{
	padding-left:5px;
	padding-right:5px;
	text-align:right;
}
.showChildren{
	margin-right:4px;
	cursor:pointer;
}
.budget:hover {
    opacity: 0.7;
	cursor:pointer;
}
.bClose{
	cursor: pointer;
	position: absolute;
	right: -21px;
	top: -21px;
}
</style>

<script>

$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		if($('.budget_details[budget_id='+budget_id+']').length>0){
			$('.budget_details').hide();
			$('.budget_details[budget_id='+budget_id+']').show();
			return;
		}
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
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->theme->baseUrl?>/images/plus_icon.png');
	}else{
		//$('.budget_details').hide();		
		$('#budget_children_'+id).slideDown('fast');
		$('#toggle_'+id).attr('src','<?php echo Yii::app()->theme->baseUrl?>/images/minus_icon.png');
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
			echo '<img id="toggle_'.$budget->id.'" class="showChildren" src="'.Yii::app()->theme->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
			}
			echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';
				$highlight=null;
				if($budget->id == $globals['queried_budget'])
					$highlight = 'queriedBudget';
				echo '<div>';
				echo '<span class="'.$highlight.'">'.$budget->getConcept().' '.format_number($budget->actual_provision).' €</span> ';
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
	
		echo '<span style="font-size:1.3em">'.CHtml::encode($featured_budget->getConcept()).'</span><br />';
		echo '<div class="graph">';
		//echo '<span style="float:right;font-weight:bold">'.$graph_percentage.' % of total budget</span><div style="clear:both"></div>';
		echoChildBudgets($featured_budget, 0, $graph_width, $globals);
		echo '</div>';
		echo '<hr style="margin-top:20px;margin-bottom:20px" />';
	}
	echo '</div>';
?>
