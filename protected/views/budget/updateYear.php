<?php

/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2013 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

$totalBudgets = count(Budget::model()->findAllBySql('SELECT id FROM budget WHERE year = '.$model->year.' AND parent IS NOT NULL'));

$this->menu=array(
	array('label'=>__('Import budgets'), 'url'=>array('csv/importCSV/'.$model->year)),
	array('label'=>__('List Years'), 'url'=>array('adminYears')),
);

if($totalBudgets){
	$featured = array( array('label'=>__('Featured budgets'), 'url'=>array('budget/featured', 'id'=>$model->year)));
	array_splice( $this->menu, 1, 0, $featured );
	$downloadCsv = array( array('label'=>'Export budgets', 'url'=>array('csv/download', 'id'=>$model->year)));
	array_splice( $this->menu, 1, 0, $downloadCsv );
	$deleteDatos = array( array( 'label'=>'Delete budgtes', 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:deleteBudgets();') ) );
	array_splice( $this->menu, 1, 0, $deleteDatos );
}elseif($model->year != Config::model()->findByPk('year')->value){
	$deleteYear= array(	array(	'label'=>__('Delete year'), 'url'=>'#',
								'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')));
	array_splice( $this->menu, 1, 0, $deleteYear );
}
$this->helpURL='http://ocax.net/pad/p/r.LEojRuTIPvGUscJQ';
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
function deleteBudgets(){
	ans = confirm('Are you sure you want to delete <?php echo $totalBudgets;?> budgets?');
	if (ans)
		window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/deleteYearsBudgets/<?php echo $model->id ;?>';
}
function showEnquiry(enquiry_id){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/getMegaDelete/'+enquiry_id,
		type: 'GET',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			if(data != 0){
				$("#mega_delete_content").html(data);
				$('#mega_delete_button').attr('enquiry_id', enquiry_id);
				$('#mega_delete').bPopup({
                    modalClose: false
					, follow: ([false,false])
					, fadeSpeed: 10
					, positionStyle: 'absolute'
					, modelColor: '#ae34d5'
                });
			}
		},
		error: function() {
			alert("Error on show mega delete");
		}
	});
}
function megaDelete(el){
	enquiry_id = $(el).attr('enquiry_id');
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/enquiry/megaDelete/'+enquiry_id,
		type: 'POST',
		//beforeSend: function(){ $('#right_loading_gif').show(); },
		//complete: function(){ $('#right_loading_gif').hide(); },
		success: function(data){
			$('#mega_delete').bPopup().close();
			if(data != 0){
				window.location = '<?php echo Yii::app()->request->baseUrl; ?>/budget/updateYear/<?php echo $model->id;?>';
				//$('#enquirys-grid').yiiGridView('update'); // get this working again (jquery overwritten)
			}
		},
		error: function() {
			alert("Error on megaDelete");
		}
	});
}

</script>

<?php $title=__('Edit year').' '.$model->year;?>
<?php echo $this->renderPartial('_formYear', array('model'=>$model, 'title'=>$title, 'totalBudgets'=>$totalBudgets)); ?>

<?php
if($enquirys->getData()){
echo '<div class="horizontalRule" style="margin-top:20px"></div>';
echo '<div style="font-size:1.5em">'.__('Budgetary enquiries for').' '.$model->year.'</div>';
$this->widget('PGridView', array(
	'id'=>'enquirys-grid',
	'dataProvider'=>$enquirys,
    'onClick'=>array(
        'type'=>'javascript',
        'call'=>'showEnquiry',
    ),
	'ajaxUpdate'=>true,
	'columns'=>array(
		array(
			'name'=>'enquiry title',
			'value'=>'$data->title',
		),
		array(
			'name'=>'internal code',
			'value'=>'$data->budget0->csv_id',
		),
		'state',
		array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	),
));

}
?>
<div id="mega_delete" class="modal" style="width:850px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="mega_delete_content"></div>
</div>

<?php /*echo $this->renderPartial('//enquiry/_megaDelete');*/ ?>

<?php if(Yii::app()->user->hasFlash('csv_generated')):?>
    <div class="flash_success" id="csv_generated_ok">
		<p style="margin-top:25px;"><b><?php echo Yii::app()->user->getFlash('csv_generated');?></b></p>
    </div>
<?php endif; ?>
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


