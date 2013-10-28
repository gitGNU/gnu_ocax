<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content" style="margin:-15px -10px 0 -10px;">
		<?php
		echo $content;
		if($this->helpURL){
			$this->widget('InlineHelp');
		}
		?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php

		$validate = array( array('label'=>__('My page'), 'url'=>array('/user/panel')) );
		$this->menu = array_merge( $this->menu, $validate );
		if($this->helpURL){
			$help= array( array('label'=>__('Context help'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showHelp("'.$this->helpURL.'");')));
			array_splice( $this->menu, 0, 0, $help );
		}
	
		//http://www.yiiframework.com/doc/blog/1.1/en/portlet.menu
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>__('Options'),
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	
		
		if($this->sidebarText)
			echo '<p>'.$this->sidebarText.'</p>';
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>

