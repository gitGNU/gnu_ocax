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
		$mypage = array( array('label'=>__('My page'), 'url'=>array('/user/panel')) );
		$this->menu = array_merge( $this->menu, $mypage );

		$title=__('Options');
		if($this->inlineHelp){
			$help = CHtml::link(
				'<i style="float:right;font-size:23px;color:#f5f1ed;" class="icon-help-circled"></i>', 
				'#',
				array('onClick'=>'js:showHelp("'.getInlineHelpURL($this->inlineHelp).'");','title'=>__('Help'))
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
				array('onClick'=>'js:viewLog('.$param.');','title'=>__('Log'))
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
			'encodeLabel'=>false,
		));
		if($this->extraText)
			echo '<div id="extraText">'.$this->extraText.'</div>';		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>

