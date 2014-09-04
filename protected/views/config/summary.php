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

/* @var $this ConfigController */
/* @var $model Config */
?>

<style>
.sub_title { text-decoration: underline; margin-bottom: 8px; }
</style>

<div>
<h1 style="float:left"><?php echo __('General site statistics');?></h1>
<p style="float:right">OCAx version <?php echo getOCAXVersion();?></p>
</div>
<div class="clear"></div>

<div style="float:left; width:400px;">
<?php
	$criteria=new CDbCriteria;
	$criteria->addCondition('is_team_member = 1 OR is_editor = 1 OR is_manager = 1 OR is_admin =1');
	$ocmMembers = count(User::model()->findAll($criteria));
	echo '<div class="sub_title">'.__('Users').'</div>';
	echo '<p>';
	echo __('Total users').': '.count(User::model()->findAll()).'<br />';
	echo __('OCM members').': '.$ocmMembers.'<br />';	
	echo '</p>';
?>
</div>
<div style="float:left">
<?php
	echo '<div class="sub_title">'.__('Disk usage (approx)').'</div>';
	$stats = getDiskUsageStatistics();
	echo '<p>';
	echo __('Used').': '.$stats['used'].' ('.$stats['percent_used'].'%)<br />';
	echo __('Total').': '.$stats['total'].'<br />';	
	echo '</p>';
?>
</div>
<div class="clear"></div>

<div style="float:left; width:400px;">
<?php
	$criteria=new CDbCriteria;
	$criteria->condition = 'parent is NULL';
	$root_budgets = Budget::model()->findAll($criteria);
	echo '<div class="sub_title">'.__('Budgets').'</div>';
	echo '<p>';
	foreach($root_budgets as $root_budget){
		$criteria=new CDbCriteria;
		$criteria->condition = 'year = '.$root_budget->year.' AND parent is not NULL';
		echo $root_budget->year.': '.count($root_budget->findAll($criteria)).' '.__('budgets').'<br />';
	}
	echo '</p>';
?>
</div>
<div style="float:left">
<?php
	echo '<div class="sub_title">'.__('Disk usage (approx)').'</div>';
	$stats = getDiskUsageStatistics();
	echo '<p>';
	echo __('Used').': '.$stats['used'].' ('.$stats['percent_used'].'%)<br />';
	echo __('Total').': '.$stats['total'].'<br />';	
	echo '</p>';
?>
</div>
<div class="clear"></div>
