var pie_properties = {
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
	seriesColors: [ "#00C9DB", "#00DB80", "#C9DB00", "#DB8000", "#DB1200", "#DB005B",
        "#1AECFF", "#8000DB", "#009AA8", "#9AA800", "#A80E00"],

	//axesDefaults:[],
	seriesDefaults:{
		renderer:$.jqplot.PieRenderer,
		rendererOptions:{
			shadow:false,
			padding:0,
			//sliceMargin: 2,
			showDataLabels:true,
			fill: true,
			sliceMargin: 2,
			lineWidth: 0, 
			//dataLabelThreshold:3,
			dataLabelCenterOn:false,
			//"dataLabelPositionFactor":0.6,
			//"dataLabelNudge":0,
			//"dataLabels":["Longer","B","C","Longer","None"],
		},
    	highlighter: {
    	    show: true,
    	    formatString:'%s', 
    	    //tooltipLocation:'sw', 
    	    useAxesFormatters:false,
    	},
	}
}
