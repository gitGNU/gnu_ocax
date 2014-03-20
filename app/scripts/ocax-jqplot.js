/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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


/*
(function ($) {	// this is a graph group.
	$.fn.ocaxjqplot1 = function( options ) {
		var options = $.extend({
			source: '',
			budget: 0,
			id: $(this).attr('id'),
		}, options);
		$(this).addClass('ocaxjqplot');
		$.fn.ocaxjqplot.getSource = function() { return options.source };
		$.fn.ocaxjqplot.getStartBudget = function() { return options.budget };
		$.fn.ocaxjqplot.preload = function(graphData){ 
									createGraph($('#'+options.id), options.budget, graphData);
									$('#'+options.id).find('.graph_container').show();
								};
		$.fn.ocaxjqplot.load = function(){
									getPie(options.budget, $('#'+options.id));
									$('#'+options.id).find('.graph_container').show();
								};
	}
}( jQuery ));
*/


http://www.websanova.com/blog/jquery/the-ultimate-guide-to-writing-jquery-plugins#.UymFmh_C0Xo

$(function() {
	//jQuery.ajax({ url: "./css/pdetailview.css", dataType: "style", cache: true, async: false });
	jQuery.ajax({ url: "./scripts/jqplot/jquery.jqplot.min.js", dataType: "script", cache: true, async: false });
	jQuery.ajax({ url: "./scripts/jqplot/plugins/jqplot.pieRenderer.min.js", dataType: "script", cache: true, async: false });
	jQuery.ajax({ url: "./scripts/jqplot/plugins/jqplot.highlighter.min.js", dataType: "script", cache: true, async: false });
	jQuery.ajax({ url: "./scripts/jqplot.pieProperties.js", dataType: "script", cache: true, async: false });
	$.jqplot.config.enablePlugins = true;
});

/*
DIVs
there is just one #pie_display. it contains one or many groups
a group contains many graph_container.
a graph_container contains just 1 'jqplot graph' and 1 'yii budget detail'
a graph is a jqplot graph

IDs
a graph_container has id = group.id+"_"+budget_id;
a graph has id = budget_id+"_graph";

Classes
a group has class ".ocaxjqplot"
a graph_container has class ".graph_container"
a graph has class ".graph_pie"
*/

(function($) {
 
$.widget( "ocax.ocaxpiegraph", {
	// Default options.
	options: {
		source: "",
		rootBudget: 0,
		rootBudgetData: "",
	},
	_create: function() {
		this.element.addClass('ocaxjqplot');

		if(!this.options.rootBudgetData)
			getPie(this.options.rootBudget, this.element );
		else
			createGraph(this.element, this.options.rootBudget, this.options.rootBudgetData);
		
		this.element.find('.graph_container').show();
	},
	// Create a public method.
	source: function() {
			return this.options.source;
	},
	rootBudget: function() {
			return this.options.rootBudget;
	},
});

})(jQuery);

function getGraphGroup(el){
	return $(el).closest('.ocaxjqplot');
}
function getSource(el){
	group = getGraphGroup(el);
	return $(group).ocaxpiegraph("source");
}
function getRootBudget(el){
	group = getGraphGroup(el);
	return $(group).ocaxpiegraph("rootBudget");
}
function getGraphContainerID(el, budget_id){
	return getGraphGroup(el).attr('id')+'_'+budget_id+'_container';
}
function getGraphID(el, budget_id){
	return getGraphGroup(el).attr('id')+'_'+budget_id+'_graph';
}
$(function() {
	$('#pie_display').delegate('.legend_item','click', function() {	
		budget_id = $(this).attr('budget_id');
		getPie(budget_id, this);
		slideInChild(this, budget_id);
		return false;
	});
	$('#pie_display').on('mouseleave', '.jqplot-target', function() {
		$('.jqplot-highlighter-tooltip').fadeOut('fast');
	});
});

function getPie(budget_id, clicked_el){

	//alert('budget: '+budget_id+' el: '+clicked_el);

	if($('#'+getGraphContainerID(clicked_el, budget_id)).length > 0 )
		return;

	source = getSource(clicked_el);
	$.ajax({
		url: source+'/budget/getPieData/',
		type: 'GET',
		dataType: 'json',
		data: { id: budget_id, rootBudget_id: getRootBudget(clicked_el) },
		async: false,
		beforeSend: function(){ /*$('.loader_gif').hide(); $(loading_gif).show();*/ },
		complete: function() { /*$('.loader_gif').hide(); */},
		success: function(data){
			createGraph(clicked_el, budget_id, data);
		},
		error: function() {
			alert("Error on get Pie Data");
			return;
		}
	});
}

function createGraph(clicked_el, budget_id, data){
	group = getGraphGroup(clicked_el);
	source = getSource(clicked_el);
	
	container_id = getGraphContainerID(clicked_el, budget_id);
	graph_id = getGraphID(clicked_el, budget_id);
	
	graph_container=$('<div id="'+container_id+'" class="graph_container"></div>');
	graph_container.attr('parent_id',data.params.parent_id);
	graph_container.attr('is_parent',data.params.is_parent);
	
	if(data.params.is_parent){
		title=	'<a href="'+source+'/budget/view/'+budget_id+
				'" onclick="js:showBudget('+budget_id+', this);return false;">'+data.params.title+'</a>';
	}else{
		title=	'<a href="'+source+'/budget/view/'+data.params.parent_id+
				'" onclick="js:showBudget('+data.params.parent_id+', this);return false;">'+data.params.title+'</a>';			
	}
	graph_container.append('<div class="graph_title">'+title+'</div>');
	graph_container.append(data.params.budget_details);
			
	graph=$('<div id="'+graph_id+'" class="graph_pie"></div>');
	graph_container.append(graph);
	group.append(graph_container);
			
	createPie(graph_id, data);
			
	if((group.ocaxpiegraph("rootBudget") != budget_id) && data.params.go_back_id){
		go_back_to = getGraphContainerID(clicked_el, data.params.go_back_id);
		back_button='<div class="prev_budget_arrow" onclick="javascript:goBack(\''+go_back_to+'\');return false;"></div>';	 
		$('#'+graph_id).append(back_button);
	}

	legend = $(clicked_el).closest('.jqplot-table-legend-container');
	if($(legend).length > 0){
		scroll = $(legend).scrollTop();
		brothers = group.find('div[parent_id="'+data.params.parent_id+'"]'); // graphs with the same parent as this.
		$.each( brothers, function( key, value ) {
			$(value).find('.jqplot-table-legend-container').scrollTop(scroll);
		});
	}
}

/*
function addGraph(el){
	alert($(el).ocaxjqplot.getSource());
	$(el).append('<div>this is the graph</div>');
	
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
}
*/

function createPie(target_div_id, data){
	chart= $.jqplot(target_div_id, [data.data], pie_properties);
	target_div = $('#'+target_div_id);

	// fix for IExplorer Does not respect table height property. we wrap it with a <div>
	legend = target_div.find('table');
	legendContainer=$('<div class="jqplot-table-legend-container"></div>');
	legendContainer.attr('style',legend.attr('style'));
	legend.attr('style','');
	legend.before( legendContainer );
	legendContainer.append( legend );

	if(data.params.actual_provision==0){
		target_div.append('<div style="position:absolute;top:20px;left:30px;font-size:20em;color:grey;">0€</div>');
	}else{
		//http://www.kathyw.org/jQPlot/LinkTest.html
		target_div.bind('jqplotDataClick', 
			function (ev, seriesIndex, pointIndex, data) {
				getPie(data[2], this);
				slideInChild(this, data[2]);
				return false;
			}
		);
		target_div.bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','pointer');}); 
		target_div.bind('jqplotDataUnhighlight', function(ev, seriesIndex, pointIndex, data) {$(this).css('cursor','default');});
	}
}

function slideInChild(clicked_el, child_id){
	child_budget_graph_container=$('#'+getGraphContainerID(clicked_el, child_id));
	child_budget_graph_container.hide();
	//$('.loader_gif').hide();
	
	group=getGraphGroup(clicked_el);

	if(child_budget_graph_container.attr('is_parent') == 0){		
		budget_details=child_budget_graph_container.children('.budget_details');
		budget_details.hide();
		group.children('.graph_container').hide();
		child_budget_graph_container.show();	
		budget_details.fadeIn(700);
		child_budget_graph_container.find('.legend_item[budget_id='+child_id+']').css('font-weight','bold');
		addColorKeyToBudgetDetails(child_budget_graph_container, child_id);	
		return;
	}
	group.children('.graph_container:visible').hide("slide",
													{ direction: "left" },
													600,
													function(){
														child_budget_graph_container.fadeIn(200);
													;}
					);
}

function addColorKeyToBudgetDetails(graph_container, budget_id){
	item = graph_container.find('span[budget_id='+budget_id+']');
	swatch = $(item).parent().prev('td');
	swatch = swatch.find('.jqplot-table-legend-swatch');
	concept = graph_container.find('.budget_details').find('th:first');
	concept.css('border-left', '5px solid '+swatch.css('background-color'));
}

function goBack(parent_container_id){
	parent_graph_container=$('#'+parent_container_id);
	parent_graph_container.show("slide",{ direction: "left" },	500);
	group=parent_graph_container.closest('.ocaxjqplot');
	group.children(".graph_container").hide();
}
