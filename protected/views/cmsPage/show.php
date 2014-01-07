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

$this->setPageTitle($content->pageTitle);
?>

<?php if(isset($noLanguageLinks)){ ?>
<style>
#cmsOptions{
	font-size:1.5em;
	margin: -35px -30px 0 -20px;
	padding: 10px 0 10px 0;
	background-color:white;
}
#cmsOptions a{
	padding: 12px 20px 12px 20px;
}
#cmsOptions a:hover{
	background-color:#f5f1ed;
}
</style>
<script>
$(function() {
	$('.language_link').hide();
});
</script>
<?php
echo '<div id="cmsOptions">';
	echo '<div style="width:50%; float: left; text-align: center;">';
	echo CHtml::link(__('Edit page'),array(	'cmsPage/update',
													'id'=>$model->id,
													'lang'=>$content->language,
											));
	echo '</div>';
	echo '<div style="width:50%; float: left; text-align: center;">';
	echo CHtml::link(__('Manage pages'),array('cmsPage/admin'));
	echo '</div>';
echo '<div style="clear:both;"></div>';
echo '</div>';

} ?>


<!-- start page here -->

<style>           
	.outer{width:100%; padding: 0px;}
	.left{width: 73%; float: left;  margin: 0px;}
	.right{width: 25%; float: right; margin: 0px;}
</style>

<div class="cms_titulo"><?php echo CHtml::encode($content->pageTitle); ?></div>
<div class="outer">
<div class="left">
	<div class="cms_content"><?php echo $content->body; ?></div>
</div>

<div class="right">
<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cmsinfo.png" style="margin-bottom:5px"/>
<div id="cmsPageMenu">

<?php
	$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block, 'published'=>1), array('order'=>'weight'));
	foreach ($items as $menu_item) {
		foreach($menu_item->cmsPageContents as $item){
			if($item->language == $content->language){
				break;	
			}
		}
		$itemclass='class="cmsPageMenuItem"';
		if($content->pageURL == $item->pageURL)
			$itemclass='class="cmsPageMenuItem activeMenuItem"';
		echo '<div '.$itemclass.'>';
		echo CHtml::link(CHtml::encode($item->pageTitle),array('p/'.$item->pageURL));
		echo '</div>';

	}
?>
</div>
</div>
</div>

<div style="clear:both"></div>

