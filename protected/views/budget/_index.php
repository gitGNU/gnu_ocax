<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

$featured=$model->findAllByAttributes(array('year'=>$model->year, 'featured'=>1));
?>

<style>
.graph_group{
	border-top-style:solid;
	border-width:1px;
	margin-bottom:20px;
}
.loading{
	font-size:1em;
	float:right;
	display:none;
}
.graph_container{

}
.graph{
	width:450px;
	height:450px;
}
.budget_details{
	width:450px;
	float:right;
}
.budget_details > a {
	margin-top:-15px;
}
table.jqplot-table-legend{
    display: block;
    height: 180px;
    overflow-y: auto;
}

</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>

<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/jquery.jqplot.css" />



<script>
function slideInChild(parent_id,child_id){
	graph_container=$('#'+child_id);
	group=$("#"+parent_id).parents('.graph_group');

	if(graph_container.attr('is_parent') == 0){
		budget_details=graph_container.children('.budget_details');
		budget_details.hide();
		group.children('.graph_container').hide();
		//$('#'+parent_id).hide();
		$('#'+child_id).show();	
		budget_details.fadeIn(700);
		graph_container.find('.legend_item[budget_id='+child_id+']').css('font-weight','bold');
		return false;
	}
	$('#'+child_id).hide();
	$('#'+parent_id).hide(	"slide",
							{ direction: "left" },
							600,
							function(){
								//$('#'+child_id).css("visibility","visible");
								$('#'+child_id).fadeIn(200);
								return false;
							;}
						);
	return false;
}
function goBack(parent_id){
	parent_graph_container=$('#'+parent_id);
	parent_graph_container.show("slide",{ direction: "left" },	500);
	group=parent_graph_container.parents('.graph_group');
	group.children(".graph_container").hide();
	return false;
}

function getPie(budget_id){
	if($("#"+budget_id).length){
		slideInChild($("#"+budget_id).attr('parent_id'),budget_id);
		return false;
	}
	//graph_container=$('<div id="'+budget_id+'" style="visibility:hidden" class="graph_container"></div>');
	graph_container=$('<div id="'+budget_id+'" class="graph_container"></div>');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getPieData/'+budget_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		beforeSend: function(){ },
		success: function(data){
			title=	'<div style="font-size:1.5em;">'+
					'<img style="vertical-align:text-bottom;cursor:pointer" src="<?php echo Yii::app()->theme->baseUrl?>/images/go_back.png" '+
					'onclick="javascript:goBack('+data.params.go_back_id+');" />'+data.params.title+
					'</div>';
			graph_container.attr('parent_id',data.params.parent_id);
			graph_container.attr('is_parent',data.params.is_parent);
			graph_container.append(title);
			budget_details=$('<div class="budget_details"><div class="view" style="padding:0px">'+data.params.budget_details+'</div></div>');
			budget_details.append(data.params.enquiry_link);
			graph_container.append(budget_details);
			graph=$('<div id="'+budget_id+'_graph" class="graph"></div>');
			graph_container.append(graph);
			
			group=$("#"+data.params.parent_id).parents('.graph_group');
			group.append(graph_container);
			createPie(budget_id+'_graph', data);
			slideInChild(data.params.parent_id,budget_id);
		},
		error: function() {
			alert("Error on get Pie Data");
		}
	});
	return false;
}

var pie_properties = {
	//http://www.jqplot.com/docs/files/plugins/jqplot-pieRenderer-js.html
	grid:{
			drawGridlines:false,
			background:"#ffffff",
			drawBorder:false,
			shadow:false
	},
	legend:{
		show:true,
		placement:"outside",
		location:"se",
		rowSpacing:'0.1em',
		marginBottom:'0px',
		border:'none',
		rendererOptions:{
			//numberColumns:2,
		}
	},
	//axesDefaults:[],
	seriesDefaults:{
		renderer:$.jqplot.PieRenderer,
		rendererOptions:{
			shadow:false,
			padding:0,
			//sliceMargin: 2,
			showDataLabels:true,
			dataLabelThreshold:3,
			dataLabelCenterOn:false,
			//"dataLabelPositionFactor":0.6,
			//"dataLabelNudge":0,
			//"dataLabels":["Longer","B","C","Longer","None"],
		},
    highlighter: {
        show: true,
        formatString:'%s', 
        //tooltipLocation:'sw', 
        useAxesFormatters:false
    }
	}
}
function createPie(div_id, data){
	chart= $.jqplot(div_id, [data.data], pie_properties);

	//http://www.kathyw.org/jQPlot/LinkTest.html
	$('#'+div_id).bind('jqplotDataClick', 
		function (ev, seriesIndex, pointIndex, data) {
			 //alert('series: ' + seriesIndex + ', point: ' + pointIndex + ', data: ' + data);
			getPie(data[2]);
			return false;
		}
	);
	$('.legend_item').on('click', function() {
		budget_id = $(this).attr('budget_id');
		getPie(budget_id);
		return false;
	});
	//http://jsfiddle.net/Boro/5QA8r/ highlight splice from lengend
	/*$('.legend_item').on('mouseover', function() {
		budget_id = $(this).attr('budget_id');
		alert(budget_id);
	});*/
}

$(function() {
	$.jqplot.config.enablePlugins = true;
	//http://phpchart.net/phpChart/examples/data_labels.php
	<?php
		foreach($featured as $budget){?>
			data = <?php echo $this->actionGetPieData($budget->id);?>

			group=$('<div class="graph_group"></div>');
			group.append('<span style="font-size:1.3em"><?php echo CHtml::encode($budget->parent0->concept);?></span><br />');
			$('#pie_display').append(group);
			graph_container=$('<div id="<?php echo $budget->id?>" class="graph_container"></div>');
			graph_container.attr('is_parent',data.params.is_parent);
			graph_container.append('<div style="font-size:1.5em;"><?php echo CHtml::encode($budget->concept);?></div>');

			budget_details=$('<div class="budget_details"><div class="view" style="padding:0px">'+data.params.budget_details+'</div></div>');
			budget_details.append(data.params.enquiry_link);
			graph_container.append(budget_details);

			graph_container.append('<div id="<?php echo $budget->id?>_graph" class="graph"></div>');
			group.append(graph_container);
			createPie("<?php echo $budget->id;?>_graph", data);

	<?php } ?>
});

</script>

<div style="font-size:2.5em;text-align:center;margin-top:-10px;">
<?php echo Config::model()->findByPk('councilName')->value;?>

</div>

<div style="
	margin-bottom:15px;
	font-size:1.5em;
	">
<?php echo __('Budget for').' '.$model->getYearString();

if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1'));

if(count($years) > 1){
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});

	echo '<span style="float:right">';
		echo __('Available years').' ';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
	echo '</span>';
}
?>
</div>

<div style="
	border-top: 1px solid #C9E0ED;
	border-bottom: 1px solid #C9E0ED;
	padding:20px;
	margin-left:-30px;
	margin-right:-40px;
	background-color:#F0F8FF;
	-webkit-box-shadow: 0 8px 6px -3px grey;
	-moz-box-shadow: 0 8px 6px -3px grey;
	box-shadow: 0 8px 6px -3px grey;
	">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<?php echo $form->hiddenField($model,'year'); ?>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>40,'maxlength'=>255)); ?>

		<span style="margin-left:15px">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>5,'maxlength'=>255)); ?>
		</span>
		<span style="margin-left:150px;"><?php echo CHtml::submitButton(__('Filter')); ?></span>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div style="clear:both"></div>

<?php
$dataProvider = $model->publicSearch();
$data = $dataProvider->getData();
if( count($data) > 0){ ?>
	<div style="font-size:1.5em;margin-top:15px;margin-bottom:5px"><?php echo __('Filtered results')?></div>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'search-results',
		'ajaxUpdate' => true,
		'dataProvider'=> $dataProvider,
		'itemView'=>'_searchResults',
		'enableHistory' => true, 
	));
}else{
?>
<style>
.button {
   border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 13.5px 27px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
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

<div style="margin-top:10px;height:10px;">


<?php

if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	echo '<a style="float:right" href="'.$zip->webPath.'">'.__('Download database').'</a>';
}?>
</div>

<div id="pie_display" style="margin-top:20px"></div>


<?php

if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
	echo '<div style="margin-top:40px;">';
	echo '<a class="button" href="'.$zip->webPath.'">'.__('Download database').'</a>';
	echo '</div>';
}

?>


<?php


	foreach($featured as $budget){
		echo '<div style="margin-top:40px;">';
		echo CHtml::link($budget->concept,array('budget/view','id'=>$budget->id), array('class'=>'button'));
		echo '</div>';
	}
}
?>



