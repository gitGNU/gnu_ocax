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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/ocax-jqplot.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.jqplot.css" />

<script>
$(function() {
	//$('#graph1').ocaxjqplot({ source: 'http://dev.bcn.uned/ocax', budget: 960 });
	//$('#graph2').ocaxjqplot({ source: 'http://dev.bcn.uned/ocax', budget: 13981 });
	<?php 
		foreach($featured as $budget){ ?>
			$("#anchor_<?php echo $budget->id;?>").ocaxjqplot({ source: 'http://dev.bcn.uned/ocax', budget: <?php echo $budget->id;?> });
	<?php } ?>
});
</script>

<?php
echo '<div id="pie_display">';
foreach($featured as $budget){
		echo '<div id="anchor_'.$budget->id.'"></div>';
}
echo '</div>';
?>

