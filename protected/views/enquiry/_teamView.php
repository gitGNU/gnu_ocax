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

if(Yii::app()->request->isAjaxRequest){
	Yii::app()->clientScript->scriptMap['jquery.js'] = false;
	Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
}
?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.bpopup-0.8.0.min.js"></script>


<?php echo '<h1>'.$model->title.'</h1>';?>

<div>
<?php
$this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->theme->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('State'),
	        'value'=>$model->getHumanStates($model->state),
		),
		array(
	        'label'=>__('Formulated by'),
	        'value'=>$model->user0->fullname.' '.__('on the').' '.$model->created.' ('.$model->user0->email.')',
		),
		array(
	        'label'=>__('Assigned to'),
	        'value'=>($model->team_member) ? $model->teamMember->fullname.' '.__('on the').' '.$model->assigned : "",
		),
		array(
	        'label'=>__('Type'),
	        'value'=>($model->related_to) ? $model->getHumanTypes($model->type).' ('.__('reformulated').')' : $model->getHumanTypes($model->type),
		),
		array(
	        'label'=>__('Subscribed users'),
	        'value'=>count($model->subscriptions),
		),
	),
));

if($model->state >= ENQUIRY_AWAITING_REPLY){
	$document=', Doc: ';
	if($model->documentation)
		$document .='<a href="'.$model->documentation0->getWebPath().'" target="_new">'.$model->documentation0->name.'</a>';
	else
		$document .='<span style="color:red">'.__('missing').'</span>';
	$submitted_info=$model->submitted.', '.__('Registry number').': '.$model->registry_number.$document;

	$this->widget('zii.widgets.CDetailView', array(
	'cssFile' => Yii::app()->theme->baseUrl.'/css/pdetailview.css',
	'data'=>$model,
	'attributes'=>array(
		array(
	        'label'=>__('Submitted'),
			'type'=>'raw',
	        'value'=>$submitted_info,
		),
	),
	));
}

if($model->budget){
	$budget=Budget::model()->findByPk($model->budget);
	$this->renderPartial('//budget/_enquiryView', array('model'=>$budget,'showMore'=>1));
}

if($reformulatedDataprovider = $model->getReformulatedEnquires()){
	$providerData = $reformulatedDataprovider->getData();

	echo '<style>.highlight_row{background:#FFDEAD;}</style>';
	echo '<div style="font-size:1.3em">'.__('The enquiry').' "'.$providerData[0]->title.'" '.__('has been reformulated').
		 ' '. (count($providerData)-1) .' '.__('time(s)').'</div>';

	$this->widget('PGridView', array(
		'id'=>'reforumulated-enquiry-grid',
		'dataProvider'=>$reformulatedDataprovider,
		'template' => '{items}{pager}',
		'rowCssClassExpression'=>'($data->id == '.$model->id.')? "highlight_row":"row_id_".$row." ".($row%2?"even":"odd")',
	    'onClick'=>array(
	        'type'=>'url',
	        'call'=>Yii::app()->request->baseUrl.'/enquiry/teamView',
	    ),
		'columns'=>array(
				array(
					'header'=>__('Enquiry'),
					'value'=>'$data[\'title\']',
				),
				array(
					'header'=>__('State'),
					'type' => 'raw',
					'value'=>'$data->getHumanStates($data[\'state\'])',
				),
				array(
					'header'=>__('Formulated'),
					'value'=>'$data[\'created\']',
				),
				array('class'=>'PHiddenColumn','value'=>'"$data[id]"'),
	)));
}
?>
</div>

<div style="background-color:white;padding:10px;">
<h2><?php echo __('The Enquiry')?></h2>
<?php echo $this->renderPartial('//enquiry/_view', array('model'=>$model)); ?>
</div>




