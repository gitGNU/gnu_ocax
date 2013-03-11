<?php

$this->menu=array(
	array('label'=>'List Years', 'url'=>array('adminYears')),
);

?>

<?php echo $this->renderPartial('_formYear', array('model'=>$model, 'title'=>'Create year', 'totalBudgets'=>0)); ?>

<?php if(Yii::app()->user->hasFlash('badYear')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 1750);
		});
	</script>
    <div class="flash_prompt">
		<p style="margin-top:25px;">
		<b>Has intentado crear una partida del año <?php echo Yii::app()->user->getFlash('badYear');?><br />
		pero el año no exite en la base de datos. Crealo ahora.</b></p>
    </div>
<?php endif; ?>

