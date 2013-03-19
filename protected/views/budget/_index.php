<?php
/* @var $this BudgetController */
/* @var $model Budget */

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

$year = $model->year;
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
// this is for interactive graphic
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		content = '';
		if(1 == 1){	// why did I if this?
			consulta_link='<?php echo Yii::app()->request->baseUrl;?>/consulta/create?budget='+budget_id;
			consulta_link='<a href="'+consulta_link+'">hacer una consulta</a>';
			content=content+'Deseas '+consulta_link+'?';
		}
		$('#budget_options_content').html(content);
		//alert($(this).text());
		$('#budget_options').bPopup({
			modalClose: false
			, position: ([ 'auto', 200 ])
			, follow: ([false,false])
			, fadeSpeed: 10
			, positionStyle: 'absolute'
			, modelColor: '#ae34d5'
		});
	});
});
$(function() {
	$("#Budget_concept").on("click", function(event){
		$("#Budget_code").val('');
	});
	$("#Budget_code").on("click", function(event){
		$("#Budget_concept").val('');
	});
});
</script>

<div style="
	margin-top:-10px;
	margin-bottom:15px;
	font-size:1.5em;
	">
<?php echo __('Budget for').' '.$year.' - '. ($year+1);?>
<span style="float:right;text-align:right"> <?php echo __('Total').': '.number_format($budget_raiz->provision).'€';?></span>
</div>

<div style="
	border-top: 1px solid #C9E0ED;
	border-bottom: 1px solid #C9E0ED;
	padding:20px;
	margin-left:-30px;
	margin-right:-40px;
	background-color:#F0F8FF;
	-webkit-box-shadow: 0 8px 6px -3px grey;
	-moz-box-shadow: 0 8px 6px -3px grey;
	box-shadow: 0 8px 6px -3px grey;
	">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<?php echo $form->hiddenField($model,'year'); ?>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>40,'maxlength'=>255)); ?>

		<span style="margin-left:15px">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>5,'maxlength'=>255)); ?>
		</span>
		<span style="margin-left:150px;"><?php echo CHtml::submitButton(__('Filter')); ?></span>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div style="clear:both"></div>


<div style="font-size:1.5em;margin-top:15px;margin-bottom:5px"><?php echo __('Filtered results')?></div>


<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'search-results',
	'ajaxUpdate' => true,
	'dataProvider'=> $model->publicSearch(),
	'itemView'=>'_searchResults',
	'enableHistory' => true, 
)); ?>



