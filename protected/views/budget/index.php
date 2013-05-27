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
.highlight { background-color: #FFEC8B; }
.button {
   border-top: 1px solid #96d1f8;
   background: #65a9d7;
   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
   background: -o-linear-gradient(top, #3e779d, #65a9d7);
   padding: 13.5px 27px;
   -webkit-border-radius: 8px;
   -moz-border-radius: 8px;
   border-radius: 8px;
   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
   box-shadow: rgba(0,0,0,1) 0 1px 0;
   text-shadow: rgba(0,0,0,.4) 0 1px 0;
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
   
   	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.highlight.js"></script>

<script>
function showBudget(budget_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/getBudget/'+budget_id,
		type: 'GET',
		async: false,
		dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#budget_popup_body").html(data.html);
				$('#budget_popup').bPopup({
                    modalClose: false
					, fadeSpeed: 10
					, positionStyle: 'fixed'
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

<div style="font-size:2.5em;text-align:center;margin-top:-10px;">
<?php echo __('Budgets');?>

</div>

<div style="
	margin-bottom:15px;
	font-size:1.5em;
	float:right;
	">
<?php
if(Yii::app()->user->isAdmin())
	$years=$model->findAll(array('condition'=>'parent IS NULL','order'=>'year DESC'));
else
	$years=$model->findAll(array('condition'=>'parent IS NULL AND code = 1','order'=>'year DESC'));

if(count($years) > 1){
	$list=CHtml::listData($years, 'year', function($year) {
		return $year->getYearString();
	});
		echo __('Available years').' ';
		echo CHtml::dropDownList('budget', $model->year, $list,
								array(	'id'=>'selectYear',
										'onchange'=>'location.href="'.Yii::app()->request->baseUrl.'/budget?year="+this.options[this.selectedIndex].value'
								));
}
?>
</div>
<div style="clear:both"></div>

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


<div style="margin-top:10px;margin-bottom:20px;height:10px;">
<?php
echo 	'<div id="no_results" style="float:left;font-size:1.3em;margin-top:15px;display:none">'.
		__('No search results').
		'</div>';
		
$change=Yii::app()->request->baseUrl.'/budget?graph_type';
echo '<img style="float:right;cursor:pointer;margin-right:20px;" src="'.Yii::app()->theme->baseUrl.
																		'/images/graph_type_bar.png" onclick="window.location=\''.$change.'=bar\'" />';
echo '<img style="float:right;cursor:pointer;margin-right:20px;" src="'.Yii::app()->theme->baseUrl.
																		'/images/graph_type_pie.png" onclick="window.location=\''.$change.'=pie\'" />';

	if($zip = File::model()->findByAttributes(array('model'=>'DatabaseDownload'))){
		echo '<div style="margin-top:22px;float:right;margin-right:20px;">';
		echo '<a class="button" href="'.$zip->webPath.'">'.__('Download database').'</a>';
		echo '</div>';
	}

?>
</div>
<div style="clear:both;"></div>

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
<div style="background-color:white;padding:10px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="budget_popup_body"></div>
</div>
<p>&nbsp;</p>
</div>
