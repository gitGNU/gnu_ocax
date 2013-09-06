<?php
/* @var $this BudgetController */
/* @var $model Budget */
Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );

$featured=$model->findAllByAttributes(array('year'=>$model->year, 'featured'=>1));
?>



<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/jqplot.pieProperties.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.jqplot.css" />


<script>
function slideInChild(parent_id,child_id){
	graph_container=$('#'+child_id);
	graph_container.hide();
	group=$("#"+parent_id).parents('.graph_group');

	if(graph_container.attr('is_parent') == 0){		
		budget_details=graph_container.children('.budget_details');
		budget_details.hide();
		group.children('.graph_container').hide();
		$('#'+child_id).show();	
		budget_details.fadeIn(700);
		graph_container.find('.legend_item[budget_id='+child_id+']').css('font-weight','bold');
		return;
	}
	group.children('.graph_container:visible').hide("slide",
													{ direction: "left" },
													600,
													function(){
														$('#'+child_id).fadeIn(200);
													;}
					);
}

function goBack(parent_id){
	parent_graph_container=$('#'+parent_id);
	parent_graph_container.show("slide",{ direction: "left" },	500);

	group=parent_graph_container.parents('.graph_group');
	group.children(".graph_container").hide();
}

function getPie(budget_id, element){
	$('.jqplot-highlighter-tooltip').hide();
	if($("#"+budget_id).length){
		slideInChild($("#"+budget_id).attr('parent_id'),budget_id);
		return;
	}
	
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getPieData/'+budget_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		beforeSend: function(){ $('.pie_loading_gif').hide(); $(element).show(); },
		complete: function() { $('.pie_loading_gif').hide(); return false; },
		success: function(data){
			graph_container=$('<div id="'+budget_id+'" class="graph_container"></div>');
			graph_container.attr('parent_id',data.params.parent_id);
			graph_container.attr('is_parent',data.params.is_parent);
			
			if(!data.params.is_parent){
				title=	'<a href="<?php echo Yii::app()->request->baseUrl;?>/budget/view/'+data.params.parent_id+
						'" onclick="js:showBudget('+data.params.parent_id+', this);return false;">'+data.params.title+'</a>';
			}else
				title=data.params.title;
			graph_container.append('<div class="pie_graph_title">'+title+'</div>');
			
			graph_container.append(data.params.budget_details);
			
			graph=$('<div id="'+budget_id+'_graph" class="graph"></div>');
			graph_container.append(graph);
			
			group=$("#"+data.params.parent_id).parents('.graph_group');
			group.append(graph_container);
			createPie(budget_id+'_graph', data);
			
			back_button='<div class="prev_budget_arrow" onclick="javascript:goBack('+data.params.go_back_id+');return false;"></div>';	 
			$('#'+budget_id+'_graph').append(back_button);		
			
			slideInChild(data.params.parent_id,budget_id);
		},
		error: function() {
			alert("Error on get Pie Data");
		}
	});
}

function createPie(div_id, data){
	chart= $.jqplot(div_id, [data.data], pie_properties);
	
	if(data.params.actual_provision==0){
		$('#'+div_id).append('<div style="position:absolute;top:20px;left:30px;font-size:20em;color:grey;">0€</div>');
	}else{
		//http://www.kathyw.org/jQPlot/LinkTest.html
		$('#'+div_id).bind('jqplotDataClick', 
			function (ev, seriesIndex, pointIndex, data) {
				 //alert('series: ' + seriesIndex + ', point: ' + pointIndex + ', data: ' + data);
				getPie(data[2], $(this).parents('.graph_group').find('.pie_loading_gif'));
				return false;
			}
		);
		$('#'+div_id).bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','pointer');}); 
		$('#'+div_id).bind('jqplotDataUnhighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','default');});
	}
	
	//http://jsfiddle.net/Boro/5QA8r/ highlight splice from lengend
	/*$('.legend_item').on('mouseover', function() {
		budget_id = $(this).attr('budget_id');
		alert(budget_id);
	});*/
}

$(function() {
	
	$('#pie_display').delegate('.legend_item','click', function() {	
		budget_id = $(this).attr('budget_id');
		getPie(budget_id, $(this).parents('.graph_group').find('.pie_loading_gif'));
		return false;
	});
	
	$.jqplot.config.enablePlugins = true;
	//http://phpchart.net/phpChart/examples/data_labels.php
	<?php
		foreach($featured as $budget){ ?>
		
			data = <?php echo $this->actionGetPieData($budget->id);?>

			group=$('<div class="graph_group"></div>');
			group.append('<span style="font-size:1.3em"><?php echo CHtml::encode($budget->parent0->getConcept());?></span>');
			group.append(' <img style="vertical-align:middle;display:none" class="pie_loading_gif" src="<?php echo Yii::app()->theme->baseUrl;?>/images/loading.gif" />');
			$('#pie_display').append(group);
			graph_container=$('<div id="<?php echo $budget->id?>" class="graph_container"></div>');
			graph_container.attr('is_parent',data.params.is_parent);
			graph_container.append('<div class="pie_graph_title"><?php echo CHtml::encode($budget->getConcept());?></div>');
			graph_container.append(data.params.budget_details);
			graph_container.append('<div id="<?php echo $budget->id?>_graph" class="graph"></div>');
			group.append(graph_container);
			createPie("<?php echo $budget->id;?>_graph", data);

	<?php } ?>
});
</script>

<?php
echo '<div id="pie_display"></div>';
?>
