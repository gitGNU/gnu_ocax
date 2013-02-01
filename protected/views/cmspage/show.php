
<?php
$this->setPageTitle($model->pageTitle);
?>

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 15%; float: left;  margin: 0px;}
	.right{width: 83%; float: left; margin: 0px;}
	.clear{clear:both;}
	.activeItem a{color: red;}
</style>


<div class="outer">

<div class="left">
	<?php
	foreach ($items as $item) {
		$itemclass='';
		if($model->pagename == $item->pagename)
			$itemclass='class="activeItem"';
		echo '<div '.$itemclass.'>';
		echo CHtml::link($item->pageTitle,array('page/'.$item->pagename));
		echo '</div>';
		echo '<br />';
	}
?>
</div>

<div class="right">
	<div style="font-size:1.5em;text-align:center;padding-bottom:20px;"><?php echo $model->pageTitle; ?></div>
	<?php echo $model->body; ?>
</div>
</div>

<div class="clear"></div>

