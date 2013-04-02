<?php
/* @var $this BudgetController */
/* @var $dataProvider CActiveDataProvider */

?>


<style>
.queriedBudget{
	color:#33A1C9;
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



function getBackGroundColor($color=0){
	$colors = array(
		0 => 'red',
		1 => 'blue',
		2 => 'green',
		3 => 'yellow'
	);
	$color=$color+1;
	if($color>3)
		$color=0;
	return array($color, 'background-color:lightgrey;');
	return array($color, 'background-color:'.$colors[$color].';');
}



function echoChildBudgets($parent_budget, $indent, $graph_width, $color_count, $globals){
	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$parent_budget->id;
	$criteria->order = 'weight ASC';
	$child_budgets = Budget::model()->findAll($criteria);

	list($color_count, $background_color) = getBackGroundColor($color_count);
	foreach($child_budgets as $budget){
		$percent=percentage($budget->initial_provision,$parent_budget->initial_provision);
		$width=$graph_width*($percent / 100);

		// is this budget a parent? (Can we avoid this query?)
		$criteria = new CDbCriteria;
		$criteria->condition = 'parent = '.$budget->id;
		$is_parent=$budget->find($criteria);

		$budget_indent = 0;
		if($indent > 0)
			$budget_indent = 32;


		echo '<div style=" margin-left:'.$budget_indent.'px;">';
			if($is_parent)
			echo '<div style="margin-left:'. (-16 - 4) .'px">';	// 16 width of icon
			else
			echo '<div style="margin-left:0px">';

			if($is_parent){
			echo '<div style="float:left;">';
			echo '<img class="showChildren" src="'.Yii::app()->theme->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
			}
			echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';
				$highlight=null;
				if($budget->id == $globals['queried_budget'])
					$highlight = 'queriedBudget';
				echo '<div>';
				echo '<span class="'.$highlight.'">'.$budget->concept.' '.number_format($budget->initial_provision).'€.</span> ';
				//echo $percent.'% del total.';
				echo '</div>';
			echo '<div class="initial_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			$percent=percentage($budget->initial_provision,$globals['yearly_initial_provision']);
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';
			echo '<div class="actual_provision_bar '.$highlight.'" style="width:'.$width.'px;">';
			$percent=percentage($budget->actual_provision,$globals['yearly_actual_provision']);
			echo '<div class="graph_bar_percent">'.$percent.'%</div>';
			echo '</div>';

			echo '</div>';
		echo '</div>';
		echo '<div style="clear:both"></div>';

		if($is_parent){
			echo '<div id="budget_children_'.$budget->id.'" style="display:none">';
			echoChildBudgets($budget, $indent+1, $width, $color_count, $globals);
			echo '</div>';
		}
		echo '</div>';
	}
}



$globals=array(	'yearly_initial_provision' => $root_budget->initial_provision,
				'yearly_actual_provision' => $root_budget->actual_provision,
				'queried_budget' => $model->id,
);

$budget_onclick='';
if($parent_budget->parent && $parent_budget->parent0->parent){
	$budget_onclick='class="budget" budget_id="'.$parent_budget->id.'"';

}/*else{
	$grandparent=$model->findByPk($parent_budget->parent);
	if($grandparent->parent && $grandparent->parent0->parent){
		$budget_onclick='class="budget" budget_id="'.$parent_budget->id.'"';
	}
}
*/

/*
echo '<div '.$budget_onclick.'>';
echo $parent_budget->concept.'. '.number_format($parent_budget->initial_provision).'€<br />';

echo '<div style="width:'.$graph_width.'px; background-color:lightgrey; text-align:right;">'.
	 number_format($parent_budget->initial_provision).'€ '.$percent.'%</div><br />';
echo '</div>';

echo '<p style="font-size:1.3em;text-decoration:underline;">"'.$parent_budget->concept.'" '.__('is composed of the following budgets').'</p>';
*/
echoChildBudgets($parent_budget, 0, $graph_width, 0, $globals);

?>

<div id="budget_options" style="display:none;width:350px;">
	<div style="background-color:white;padding:5px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="budget_options_content">hello</div>
</div>
<p>&nbsp;</p>
</div>


