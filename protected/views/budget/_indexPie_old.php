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
?>

<style>
.loader_gif {
	float:right;
	font-size:1.4em;
	display:none;
}
.loader_gif img {
	margin-top:5px;
}
</style>

<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jqplot.pieProperties.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.jqplot.css" />

<script>
function slideInChild(parent_id,child_id){
	graph_container=$('#'+child_id);
	graph_container.hide();
	$('.loader_gif').hide();
	group=$("#"+parent_id).parents('.graph_pie_group');

	if(graph_container.attr('is_parent') == 0){		
		budget_details=graph_container.children('.budget_details');
		budget_details.hide();
		group.children('.graph_container').hide();
		graph_container.show();	
		budget_details.fadeIn(700);
		graph_container.find('.legend_item[budget_id='+child_id+']').css('font-weight','bold');
		addColorKeyToBudgetDetails(graph_container,child_id);	
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

function addColorKeyToBudgetDetails(graph_container,budget_id){
	item = graph_container.find('span[budget_id='+budget_id+']');
	swatch = $(item).parent().prev('td');
	swatch = swatch.find('.jqplot-table-legend-swatch');
	concept = graph_container.find('.budget_details').find('th:first');
	concept.css('border-left', '5px solid '+swatch.css('background-color'));
}

function goBack(parent_id){
	parent_graph_container=$('#'+parent_id);
	parent_graph_container.show("slide",{ direction: "left" },	500);

	group=parent_graph_container.parents('.graph_pie_group');
	group.children(".graph_container").hide();
}

function getPie(budget_id, clicked_el){
	$('.jqplot-highlighter-tooltip').hide();
	if($("#"+budget_id).length){
		slideInChild($("#"+budget_id).attr('parent_id'),budget_id);
		return;
	}
	loading_gif = $(clicked_el).parents('.graph_pie_group').find('.loader_gif');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getPieData/'+budget_id,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){ $('.loader_gif').hide(); $(loading_gif).show(); },
		complete: function() { $('.loader_gif').hide(); },
		success: function(data){
			graph_container=$('<div id="'+budget_id+'" class="graph_container"></div>');
			graph_container.attr('parent_id',data.params.parent_id);
			graph_container.attr('is_parent',data.params.is_parent);
			
			if(data.params.is_parent){
				title=	'<a href="<?php echo Yii::app()->request->baseUrl;?>/budget/view/'+budget_id+
						'" onclick="js:showBudget('+budget_id+', this);return false;">'+data.params.title+'</a>';
			}else{
				title=	'<a href="<?php echo Yii::app()->request->baseUrl;?>/budget/view/'+data.params.parent_id+
						'" onclick="js:showBudget('+data.params.parent_id+', this);return false;">'+data.params.title+'</a>';			
			}
			graph_container.append('<div class="graph_title">'+title+'</div>');
			graph_container.append(data.params.budget_details);
			
			graph=$('<div id="'+budget_id+'_graph" class="graph"></div>');
			graph_container.append(graph);
			
			group=$("#"+data.params.parent_id).parents('.graph_pie_group');
			group.append(graph_container);
			createPie(budget_id+'_graph', data);
			
			if(data.params.go_back_id){
				back_button='<div class="prev_budget_arrow" onclick="javascript:goBack('+data.params.go_back_id+');return false;"></div>';	 
				$('#'+budget_id+'_graph').append(back_button);
			}
			legend = $(clicked_el).closest('.jqplot-table-legend-container');
			if($(legend).length > 0){
				scroll = $(legend).scrollTop();
				brothers = group.find('div[parent_id="'+data.params.parent_id+'"]'); // graphs with the same parent as this.
				$.each( brothers, function( key, value ) {
					$(value).find('.jqplot-table-legend-container').scrollTop(scroll);
				});
			}
			slideInChild(data.params.parent_id,budget_id);
		},
		error: function() {
			alert("Error on get Pie Data");
			return;
		}
	});
}

function createPie(div_id, data){
	chart= $.jqplot(div_id, [data.data], pie_properties);

	// fix for IExplorer Does not respect table height property. we wrap it with a <div>
	legend = $('#'+div_id).find('table');
	legendContainer=$('<div class="jqplot-table-legend-container"></div>');
	legendContainer.attr('style',legend.attr('style'));
	legend.attr('style','');
	legend.before( legendContainer );
	legendContainer.append( legend );

	if(data.params.actual_provision==0){
		$('#'+div_id).append('<div style="position:absolute;top:20px;left:30px;font-size:20em;color:grey;">0€</div>');
	}else{
		//http://www.kathyw.org/jQPlot/LinkTest.html
		$('#'+div_id).bind('jqplotDataClick', 
			function (ev, seriesIndex, pointIndex, data) {
				getPie(data[2], this);
				return false;
			}
		);
		$('#'+div_id).bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','pointer');}); 
		$('#'+div_id).bind('jqplotDataUnhighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','default');});
	}
}

$(function() {
	$('#pie_display').delegate('.legend_item','click', function() {	
		budget_id = $(this).attr('budget_id');
		getPie(budget_id, this);
		return false;
	});

	$('#pie_display').on('mouseleave', '.jqplot-target', function() {
		$('.jqplot-highlighter-tooltip').fadeOut('fast');
	});
	
	$.jqplot.config.enablePlugins = true;
	//http://phpchart.net/phpChart/examples/data_labels.php
	<?php
		foreach($featured as $budget){ ?>
			data = <?php echo $this->actionGetPieData($budget->id);?>

			group=$('<div class="graph_pie_group graph_group" id="anchor_<?php echo $budget->id;?>"></div>');
			header=$('<div></div>');
			header.append('<div style="font-size:1.5em;float:left;margin-bottom:-5px;"><?php echo $budget->getCategory();?></div>');
			loader=$('<div class="loader_gif"></div>');
			loader.append('<img style="margin-right:5px" src="<?php echo Yii::app()->request->baseUrl;?>/images/preloader.gif"/></div>');
			header.append(loader);
			header.append('<div style="clear:both"></div>');
			group.append(header);
			$('#pie_display').append(group);
			graph_container=$('<div id="<?php echo $budget->id?>" class="graph_container"></div>');
			graph_container.attr('is_parent',data.params.is_parent);
			title= '<a href="<?php echo Yii::app()->request->baseUrl;?>/budget/view/<?php echo $budget->id;?>" onclick="js:showBudget(<?php echo $budget->id;?>, this);return false;"><?php echo CHtml::encode($budget->getConcept());?></a>';	
			graph_container.append('<div class="graph_title"> '+title+'</div>');
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