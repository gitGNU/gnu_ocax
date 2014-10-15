<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<div class="span-19">
	<div id="content" style="margin:-15px -10px 0 -10px;">
		<?php
		echo $content;
		if($this->inlineHelp){
			$this->widget('InlineHelp');
		}
		if($this->viewLog){
			$this->widget('ViewLog');
		}		
		?>
	</div><!-- content -->
</div>

<div class="span-5 last">
	<div id="sidebar">
	<?php

		$validate = array( array('label'=>__('My page'), 'url'=>array('/user/panel')) );
		$this->menu = array_merge( $this->menu, $validate );
		/*
		if($this->inlineHelp){
			$help= array( array('label'=>__('Context help'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showHelp("'.getInlineHelpURL($this->inlineHelp).'");')));
			array_splice( $this->menu, 0, 0, $help );
		}
		*/
		$title=__('Options');
		if($this->inlineHelp){
			$help = CHtml::link(
				'<i style="float:right;font-size:23px;color:#f5f1ed;" class="icon-help-circled"></i>', 
				'#',
				array('onClick'=>'js:showHelp("'.getInlineHelpURL($this->inlineHelp).'");')
			);
			$title=$title.$help;
		}
		if($this->viewLog){
			$params = explode('|',$this->viewLog);
			$param = '"'.$params[0].'"';
			if(isset($params[1]))
				$param = $param.','.$params[1];
			$log = CHtml::link(
				'<i style="float:right;font-size:23px;color:#f5f1ed;" class="icon-book"></i>', 
				'#',
				array('onClick'=>'js:viewLog('.$param.');')
			);			
			$title=$title.$log;
		}
		
		//http://www.yiiframework.com/doc/blog/1.1/en/portlet.menu
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>$title,
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();

		
		//if($this->sidebarText)
		//	echo '<p>'.$this->sidebarText.'</p>';
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>

