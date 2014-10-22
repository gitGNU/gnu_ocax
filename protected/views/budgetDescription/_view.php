<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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

?>

<?php
if(!$fieldsForDisplay['label'] && $model->label)
	$fieldsForDisplay['label'] = $model->label;
if(!$fieldsForDisplay['concept'] && $model->concept)
	$fieldsForDisplay['concept'] = $model->concept;
?>

<div class="modalTitle"><?php echo __('Budget description');?>
	<?php
	if(!$fieldsForDisplay['label'] && !$fieldsForDisplay['concept'] && !$fieldsForDisplay['description']){
		if($model->label || $model->concept)
			echo ': '.__('Using data imported with CSV files');
	}
	?>
</div>

<h1><?php echo $fieldsForDisplay['label'];?>: <?php echo $fieldsForDisplay['concept'];?></h1>

<div style="font-size:16px"><?php echo $fieldsForDisplay['description'];?></div>
