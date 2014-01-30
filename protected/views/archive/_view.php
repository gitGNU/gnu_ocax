<?php
/**
 * OCAX -- Citizen driven Municipal Observatory software
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

/* @var $this ArchiveController */
/* @var $data Archive */
?>

<div class="archive" >
	<?php

		echo '<span class="created">'.format_date($data->created).'</span>';
		if($data->author == $user_id || $is_admin)
			echo '<span class="delete" onClick="js:deleteArchive('.$data->id.')">'.__('Delete').'</span>';

		echo '<a href="'.$data->getWebPath().'">';
		echo '<div style="padding-left:3px">';
		echo '<img class="icon" src="'.Yii::app()->baseUrl.'/images/fileicons/'.$data->extension.'.png"/>';
		echo '<span class="name">'.CHtml::encode($data->name).'</span>';
		echo '</div>';
		echo '<div style="clear:both"></div>';
		echo '<div class="description">'.CHtml::encode($data->description).'</div>';

		echo '</a>';
	?>
</div>
