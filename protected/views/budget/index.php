<?php
/* @var $this BudgetController */

$year = Config::model()->findByPk('year')->value;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
$criteria->order = 'weight ASC';
$budget_raiz = Budget::model()->find($criteria);

?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
$(function() {
	$('.budget').bind('click', function() {
		budget_id = $(this).attr('budget_id');
		content = '';
		if(1 == 1){
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

<?php echo $this->renderPartial('_index',array('parent_budget'=>$budget_raiz)); ?>


