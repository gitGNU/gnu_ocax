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

/* @var $this BudgetController */
/* @var $model Budget */

/*
if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
}
*/

if(Yii::app()->clientScript->isScriptRegistered('jquery.js'))
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
if(Yii::app()->clientScript->isScriptRegistered('jquery.min.js'))
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
if(Yii::app()->clientScript->isScriptRegistered('jquery.ba-bbq.js'))
	Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;

$this->layout='//layouts/column1';

$root_budget = $model->findByAttributes(array('csv_id'=>$model->csv_id[0], 'year'=>$model->year));
if(!$root_budget){
	$this->render('//site/error',array('code'=>'Budget not found', 'message'=>__('Budget with internal code').' "'.$model->csv_id[0].'" '.__('is not defined')));
	Yii::app()->end();
}

$dataProvider=new CActiveDataProvider('Enquiry', array(
	'criteria'=>array(
		'condition'=>'budget = '.$model->id.' AND state >= '.ENQUIRY_ACCEPTED,
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

	echo '<div>';
		echo '<div style="width:450px;padding:0px;margin-left:10px;margin-top:-5px;float:right;">';
		$this->renderPartial('_enquiryView',array(	'model'=>$model,
													'showCreateEnquiry'=>1,
													'showLinks'=>1,
													'noConcept'=>1,
													'showMore'=>1,
												),false,true);
	echo '</div>';	
	
	echo '<p  style="margin-top:15px;">';
	if($description = $model->getDescription()){
		echo $description;
	}	
	
	echo '<div style="font-size:1.3em;margin-top:35px;">';
	if(!$dataProvider->getData()){
		echo '<p style="margin-bottom:10px">'.__('No enquiries have been made about this budget yet').'.</p>'.
			CHtml::link(__('Do you wish to make an enquiry').'?' ,array('enquiry/create', 'budget'=>$model->id));
	}
	echo '</div>';
	echo '</p>';
	echo '</div>';

?>
<div style="clear:both"></div>

<?php

if(count($dataProvider->getData()) > 0){
	echo '<p>';
	if(count($dataProvider->getData()) == 1)
		echo '<div style="font-size:1.3em;margin-top:25px;">'.__('One enquiry has already been made about this budget').'</div>';
	else{
		$str = str_replace("%s", count($dataProvider->getData()), __('%s enquiries have already been made about this budget'));
		echo '<div style="font-size:1.3em;margin-top:25px;">'.$str.'</div>';
	}

	$this->widget('PGridView', array(
		'id'=>'budgets-enquiry-grid',
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
					'header'=>__('Enquiry'),
					'name'=>'title',
					'value'=>'$data[\'title\']',
				),
				array(
					'header'=>__('Formulated'),
					'name'=>'created',
					'value'=>'format_date($data[\'created\'])',
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

