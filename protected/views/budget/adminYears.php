<?php
/* @var $this BudgetController */
/* @var $model Budget */

$this->menu=array(
	array('label'=>'Create Year', 'url'=>array('createYear')),
	array('label'=>'Prepare zip file', 'url'=>array('file/databaseDownload')),
);
if(File::model()->findByAttributes(array('model'=>'Budget'))){
	$restore = array( array('label'=>__('Restore database'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:showBudgetDumps();')));
	array_splice( $this->menu, 1, 0, $restore );
}
?>

<script>
function showBudgetDumps(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/file/showBudgetFiles',
		type: 'POST',
		async: false,
		//data: { 'id' : enquiry_id },
		//dataType: 'json',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#budget_dumps_content").html(data);
				$('#budget_dumps').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show budget dumps");
		}
	});
}
function restoreBudgets(file_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/budget/restoreBudgets/'+file_id,
		type: 'POST',
		async: false,
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$('#budget_dumps').bPopup().close();
			location.reload(true);
		},
		error: function() {
			alert("Error on restore budgets");
		}
	});
}
</script>

<h1>Manage years</h1>

<?php
$this->widget('PGridView', array(
	'id'=>'budget-grid',
	'dataProvider'=>$years,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/budget/updateYear',
    ),
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
		'year',
		//'concept',
		//'provision',
		//'spent',
		array(
			'header'=>'Published',
			'name'=>'code',
			'value'=>'$data[\'code\']',
		),
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<style>           
	.bClose{
		cursor: pointer;
		position: absolute;
		right: -21px;
		top: -21px;
	}
</style>
<div id="budget_dumps" style="display:none;width:600px;">
	<div style="background-color:white">
		<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
		<div id="budget_dumps_content"></div>
	</div>
</div>

<?php if(Yii::app()->user->hasFlash('success')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 2000);
		});
	</script>
    <div class="flash_success">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('success');?></b></p>
    </div>
<?php endif; ?>


