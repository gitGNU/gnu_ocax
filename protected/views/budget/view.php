<?php
/* @var $this BudgetController */
/* @var $model Budget */

$this->layout='//layouts/column1';
$root_budget = $model->findByAttributes(array('csv_id'=>$model->csv_id[0], 'year'=>$model->year));
if(!$root_budget){
	$this->render('//site/error',array('code'=>'Budget not found', 'message'=>__('Budget with internal code').' "'.$model->csv_id[0].'" '.__('is not defined')));
	Yii::app()->end();
}
$dataProvider=new CActiveDataProvider('Enquiry', array(
    'criteria'=>array(
        'condition'=>'budget = '.$model->id,
        'order'=>'created DESC',
    ),
    'pagination'=>array(
        'pageSize'=>20,
    ),
));
?>

<script></script>

<?php
	echo '<div style="font-size:1.6em">'.$model->getTitle().'</div>';

	echo '<div style="margin-top:15px;">';	


		echo '<div class="view" style="width:450px;padding:0px;margin-left:10px;float:right;">';
		echo $this->renderPartial('_enquiryView',array(	'model'=>$model,
														'showCreateEnquiry'=>1,
														'showLinks'=>1,
														'noConcept'=>1,
												),false,true);
		echo '</div>';	
	
	if($description = $model->getDescription()){
		echo $description;
	}	
	
	echo '<div style="font-size:1.3em;margin-top:15px;">';
	if($dataProvider->getData()){
		echo __('Do you wish to').' '.CHtml::link(__('make an enquiry'),array('enquiry/create', 'budget'=>$model->id));
		echo ' '.__('about this budget').'?';
	}else{
		echo __('No enquiries have been made about this budget yet').'.<br />'.
			 __('Do you wish to').' '.
			CHtml::link(__('make an enquiry'),array('enquiry/create', 'budget'=>$model->id)).'?';
	}
	echo '</div>';
	echo '</div>';
?>
<div style="clear:both"></div>

<p>
<?php

if($dataProvider->getData()){
echo '<div style="font-size:1.3em;margin-top:25px;">'.count($dataProvider->getData()).' '.__('enquiry(s) already made by citizens').':</div>';
$this->widget('PGridView', array(
	'id'=>'enquiry-grid',
	'dataProvider'=>$dataProvider,
    'onClick'=>array(
        'type'=>'url',
        'call'=>Yii::app()->request->baseUrl.'/enquiry/view',
    ),
	'template' => '{items}{pager}',
	'ajaxUpdate'=>true,
	'pager'=>array('class'=>'CLinkPager',
					'header'=>'',
					'maxButtonCount'=>6,
					'prevPageLabel'=>'< Prev',
	),
	'columns'=>array(
			array(
				'header'=>'Enquiry',
				'name'=>'title',
				'value'=>'$data[\'title\']',
			),
			array(
				'header'=>'Estat',
				'name'=>'state',
				'type' => 'raw',
				'value'=>'$data->getHumanStates($data[\'state\'])',
			),
            array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
)));
}
?>
</p>

