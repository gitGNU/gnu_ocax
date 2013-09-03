<?php
/* @var $this BudgetController */
/* @var $model Budget */

//$year = Config::model()->findByPk('year')->value;
$year = $model->year;

$criteria = new CDbCriteria;
$criteria->condition = 'year = '.$year.' AND parent is NULL';
//$criteria->order = 'weight ASC';
$root_budget = Budget::model()->find($criteria);

Yii::app()->clientScript->registerScript('search', "
  $('#budget-form').submit(function(){
  $.fn.yiiListView.update('search-results', {
  data: $(this).serialize()
  });
  return false;
});
");

?>

<style>
.button {
   border-top: 1px solid #96d1f8;
   background: #A1A150;
   padding: 13.5px 27px;
   color: white;
   font-size: 19px;
   font-family: Helvetica, Arial, Sans-Serif;
   text-decoration: none;
   vertical-align: middle;
   }
.button:hover {
   border-top-color: #28597a;
   background: #28597a;
   color: #ccc;
   }
.button:active {
   border-top-color: #1b435e;
   background: #1b435e;
   }
   
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.highlight.js"></script>

<script>
$(function() {
	$('#Budget_concept').focus(function () {
		$('#Budget_code').val('');
	});
});

function showBudget(budget_id, element){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		async: false,
		//dataType: 'json',
		beforeSend: function(){
						$('.loading_gif').remove();
						$(element).after('<img style="vertical-align:middle;" class="loading_gif" src="<?php echo Yii::app()->theme->baseUrl;?>/images/loading.gif" />');
					},
		complete: function(){ $('.loading_gif').remove(); },
		success: function(data){
			if(data != 0){
				$("#budget_popup_body").html(data);
				$('#budget_popup').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show budget");
		}
	});
}
function highlightResult(){
	stringArray=$('#Budget_concept').val().split(" ");
	for (var i = 0; i < stringArray.length; i++) {
		if(stringArray[i].length > 3)
	    	$('.highlight_text').highlight(stringArray[i], { wordsOnly: true });
		else
			continue;
	}
}
function afterSearch(){
	if($.fn.yiiListView.getKey('search-results', 0)){
		$('#the_graphs').hide();
		$('#no_results').hide();
		$('#search_results_container').show();
		highlightResult();
		$("html,body").animate({scrollTop:0},0);
	}
	else{
		$('#search_results_container').hide();
		$('#no_results').hide();
		$('#no_results').fadeIn('fast');
		$('#the_graphs').show();
	}
}
</script>


<div style="
	margin-right:0px;
    margin-top:10px;
	">
<div id="budget_titulo_j" style="float:left;">
<?php echo __('Budgets');?>

</div>

<div style="
	margin-bottom:15px;
	font-size:1.2em;
    float:right;
	">
<?php

echo ' .';	// julio, antes teniamos un string aqui. ahora al quitarlo, sube lo de abajo.

if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL','order'=>'year DESC'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1','order'=>'year DESC'));

if(count($years) > 1){
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});
		echo '<span style="float:right">';
		echo __('Available years').' ';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
		echo '</span>';
}
?>
</div>

</div>

<div style="clear:both"></div>

<div id="budget_search_j">

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


<div style="margin-top:10px;margin-bottom:20px;height:10px;">
<?php
echo 	'<div id="no_results" style="float:left;font-size:1.3em;margin-top:15px;display:none">'.
		__('No search results').
		'</div>';
		
$change=Yii::app()->request->baseUrl.'/budget?graph_type';
echo '<img style="float:right;cursor:pointer;" src="'.Yii::app()->theme->baseUrl.'/images/graph_type_bar.png" onclick="window.location=\''.$change.'=bar\'" />';
echo '<img style="float:right;cursor:pointer;" src="'.Yii::app()->theme->baseUrl.'/images/graph_type_pie.png" onclick="window.location=\''.$change.'=pie\'" />';

	if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
		echo '<div style="margin-top:22px;float:right;margin-right:20px;">';
		echo '<a class="button" href="'.$zip->webPath.'">'.__('Download database').'</a>';
		echo '</div>';
	}

?>
</div>
<div style="clear:both;"></div>

<div>
<?php
	echo '<div id="search_results_container" style="display:none">';
	$this->widget('zii.widgets.CListView', array(
		'id'=>'search-results',
		'ajaxUpdate' => true,
		'dataProvider'=> $model->publicSearch(),
		'itemView'=>'_searchResults',
		'enableHistory' => true,
		'afterAjaxUpdate' => 'js:function(){ afterSearch(); }',
	));
	echo '</div>';

	echo '<div id="the_graphs">';
	if(!$root_budget){
		echo '<h1>'. __('No data available').'</h1>';
	}else{
		if($graph_type == 'bar')
			$this->renderPartial('_indexBar',array('model'=>$model));
		else
			$this->renderPartial('_indexPie',array('model'=>$model));
	}
	echo '</div>';
?>


<div id="budget_popup" style="display:none;width:900px;">
<div id="popup_content_j">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="budget_popup_body"></div>
</div>
<p>&nbsp;</p>
</div>

</div>
