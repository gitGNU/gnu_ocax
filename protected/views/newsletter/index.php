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
 
/* @var $this NewsletterController */
/* @var $dataProvider CActiveDataProvider */

?>

<div style="margin:0px 0 20px 0; position:relative;">
<?php echo '<span class="bigTitle">'.__('Newsletters').'</span>';?>
<a style="position:absolute;top:-25px;left:935px;" href="<?php echo Yii::app()->createAbsoluteUrl('newsletter/feed');?>">
<img src="<?php echo Yii::app()->baseUrl;?>/images/rss-16x16.png"/>
</a>
</div>

<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'template'=>'{pager}{items}',
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