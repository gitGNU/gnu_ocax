<?php
/* @var $this BudgetController */
/* @var $dataProvider CActiveDataProvider */

?>

<style>
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
function toggleChildren(id){
	if ($('#budget_children_'+id).is(":visible"))
		$('#budget_children_'+id).slideUp('fast');
	else
		$('#budget_children_'+id).slideDown('fast');
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

		echo '<div style=" margin-left:'.$budget_indent.'px;">';
			if($budget->budgets)
			echo '<div style="margin-left:'. (-16 - 4) .'px">';	// 16 width of icon
			else
			echo '<div style="margin-left:0px">';

			if($budget->budgets){
			echo '<div style="float:left;">';
			echo '<img class="showChildren" src="'.Yii::app()->theme->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
			}
			echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';
				$highlight=null;
				if($budget->id == $globals['queried_budget'])
					$highlight = 'queriedBudget';
				echo '<div>';
				echo '<span class="'.$highlight.'">'.$budget->concept.' '.format_number($budget->actual_provision).' €</span> ';
				echo '</div>';

			$percent=percentage($budget->actual_provision,$globals['yearly_actual_provision']);
			$width=$graph_width*(percentage($budget->actual_provision,$parent_budget->actual_provision) / 100);
			echo '<div class="actual_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';

			$percent=percentage($budget->initial_provision,$globals['yearly_actual_provision']);
			$width=$graph_width*(percentage($budget->initial_provision,$parent_budget->actual_provision) / 100);
			echo '<div class="initial_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';

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

$globals=array(	'yearly_initial_provision' => $root_budget->initial_provision,
				'yearly_actual_provision' => $root_budget->actual_provision,
				'queried_budget' => $model->id,
);

echoChildBudgets($parent_budget, 0, $graph_width, $globals);
?>


<div id="budget_options" style="display:none;width:350px;">
	<div style="background-color:white;padding:5px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="budget_options_content">hello</div>
</div>
<p>&nbsp;</p>
</div>


