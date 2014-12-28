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
 
/* @var $this NewsletterController */
/* @var $dataProvider CActiveDataProvider */

?>

<div style="margin:-10px 0 10px 0">
<?php echo '<span class="bigTitle">'.__('Newsletters').'</span>';?>
</div>

<div style="position:relative">
<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'template'=>'<div style="position:absolute; top:-5px; right:0px">{pager}</div>{items}',
	'pager' => array(
			'class'			=> 'CLinkPager',
			'header'		=> '',
			'firstPageLabel'=> '<<',
			'prevPageLabel' => '<',
			'nextPageLabel' => '>',
			'lastPageLabel' => '>>',
		),
));
?>
</div>
