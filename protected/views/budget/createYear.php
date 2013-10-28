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

$this->menu=array(
	array('label'=>__('Manage years'), 'url'=>array('adminYears')),
);
$this->helpURL='http://ocax.net/pad/p/r.LEojRuTIPvGUscJQ';
?>

<?php echo $this->renderPartial('_formYear', array('model'=>$model, 'title'=>'Create year', 'totalBudgets'=>0)); ?>

<?php if(Yii::app()->user->hasFlash('badYear')):?>
	<script>
		$(function() { setTimeout(function() {
			$('.flash_success').fadeOut('fast');
    	}, 1750);
		});
	</script>
    <div class="flash_prompt">
		<p style="margin-top:25px;">
		<b>Has intentado crear una partida del año <?php echo Yii::app()->user->getFlash('badYear');?><br />
		pero el año no exite en la base de datos. Crealo ahora.</b></p>
    </div>
<?php endif; ?>

