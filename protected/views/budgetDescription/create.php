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

/* @var $this BudgetDescriptionController */
/* @var $model BudgetDescription */

$this->menu=array(
	array('label'=>__('View description'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'js:viewDescription();')),
	array('label'=>__('Manage descriptions'), 'url'=>array('admin')),
);
$this->inlineHelp=':budget_descriptions';
$this->viewLog='BudgetDescription';
$this->extraText = $this->renderPartial('_legend',null,true,false);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
