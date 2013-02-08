<?php
/* @var $this BudgetController */
/* @var $dataProvider CActiveDataProvider */
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>
.graph_bar{
	float:left;
	margin-bottom:10px;
}
.graph_bar_percent{
	background-color:#EDEDED;
	padding-left:5px;
	padding-right:5px;
	float:left;
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
		$('#budget_children_'+id).slideUp();
	else
		$('#budget_children_'+id).slideDown();
}
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		content = '';
		if(1 == 1){
			consulta_link='<?php echo Yii::app()->request->baseUrl;?>/consulta/create?budget='+budget_id;
			consulta_link='<a href="'+consulta_link+'">hacer una consulta</a>';
			content=content+'Deseas '+consulta_link+'?';
		}
		$('#budget_options_content').html(content);
		//alert($(this).text());
		$('#budget_options').bPopup({
			modalClose: false
			, position: ([ 'auto', 200 ])
			, follow: ([false,false])
			, fadeSpeed: 10
			, positionStyle: 'absolute'
			, modelColor: '#ae34d5'
		});
	});
});


</script>

<?php

function percentage($val1, $val2){
	return round( ($val1 / $val2) * 100);
}
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
	return array($color, 'background-color:black;');
	return array($color, 'background-color:'.$colors[$color].';');
}

function echoChildBudgets($parent_budget, $indent, $graph_width, $color_count){
	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$parent_budget->id;
	$criteria->order = 'weight ASC';
	$child_budgets = Budget::model()->findAll($criteria);
	list($color_count, $background_color) = getBackGroundColor($color_count);
	foreach($child_budgets as $budget){

		$percent=percentage($budget->provision,$parent_budget->provision);
		$width=$graph_width*($percent / 100);

		// is this budget a parent? (Can we avoid this query?)
		$criteria = new CDbCriteria;
		$criteria->condition = 'parent = '.$budget->id;
		$is_parent=$budget->find($criteria);

		$budget_indent = $indent * 30;
		if($is_parent)
			$budget_indent = $indent * 30 - 16 - 4 ;	// 16 is the width of the icon amnd the left:margin

		echo '<div style=" margin-left:'.$budget_indent.'px;">';	//contains budget plus show_more icon
		if($is_parent){
			echo '<div style="float:left;">';
			echo '<img class="showChildren" src="'.Yii::app()->theme->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
			echo '</div>';
		}
		echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left;">';	//budget starts
			echo '<div>';
			echo $budget->concept.' '.number_format($budget->provision).'€. ';
			echo $percent.'% de '.$parent_budget->concept;
			echo '</div>';

		echo '<div class="graph_bar" style="width:'.$width.'px; '.$background_color.'">&nbsp;</div>';
		echo '<div class="graph_bar_percent">'.$percent.'%</div>';
		echo '</div>';	// budget ends
		echo '<div style="clear:both"></div>';

		if($is_parent)
			echo '<div id="budget_children_'.$budget->id.'" style="display:none">';
		echoChildBudgets($budget, $indent+1, $width, $color_count);
		if($is_parent)
			echo '</div>';
		echo '</div>';
	}
}

$graph_width=700;

//echo '<div style="width:'.$graph_width.'px; background-color:lightgrey;">Total: '.number_format($total_budget).'</div><br />';

// it'd be nice to pass all this to echoChildBudgets()

foreach($budgets_raiz as $budget){
	$percent=percentage($budget->provision,$total_budget);
	$width=$graph_width*($percent / 100);

	// is this budget a parent? (Can we avoid this query?)
	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$budget->id;
	$is_parent=$budget->find($criteria);


	$indent =1;
	$budget_indent = 0;
	if($is_parent)
		$budget_indent = $indent - 16 - 4 ;	// 16 is the width of the icon amnd the left:margin

	list($color_count, $background_color) = getBackGroundColor();

	echo '<div style=" margin-left:'.$budget_indent.'px;">';	//contains budget plus show_more icon
	if($is_parent){
		echo '<div style="float:left">';
		echo '<img class="showChildren" src="'.Yii::app()->theme->baseUrl.'/images/plus_icon.png" onClick="js:toggleChildren('.$budget->id.');"/>';
		echo '</div>';
	}
	echo '<div class="budget" budget_id="'.$budget->id.'" style="float:left">';
		echo '<div>';
		echo $budget->concept.' '.number_format($budget->provision).'€ '.$percent.'% del total';
		echo '</div>';

		echo '<div class="graph_bar" style="width:'.$width.'px; '.$background_color.'">&nbsp;</div>';

	echo '</div>';
	echo '</div>';
	echo '<div style="clear:both"></div>';

	$criteria = new CDbCriteria;
	$criteria->condition = 'parent = '.$budget->id;

	if($is_parent){
		echo '<div id="budget_children_'.$budget->id.'" style="display:none">';
		echoChildBudgets($budget, 1, $width, $color_count);
		echo '</div>';
	}
}
?>

<div id="budget_options" style="display:none;width:350px;">
	<div style="background-color:white;padding:5px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="budget_options_content">hello</div>
</div>
<p>&nbsp;</p>
</div>


