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

$year = Config::model()->findByPk('year')->value;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
$criteria->order = 'weight ASC';
$budget_raiz = Budget::model()->find($criteria);


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
</script>


<h1>Presupuestos de <?php echo $year;?> Total: <?php echo number_format($budget_raiz->provision);?>€</h1>


<div class="">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'id'=>'budget-form',
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->hiddenField($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'concept'); ?>
		<?php echo $form->textField($model,'concept',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->


<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'search-results',
	'ajaxUpdate' => true,
	'dataProvider'=> $model->publicSearch(),
	'itemView'=>'_searchResults',
)); ?>



