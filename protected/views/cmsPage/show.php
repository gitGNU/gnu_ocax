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
#goBack{
	font-size:1.5em;
	margin:-25px;
	margin-bottom:30px;
	padding-left:20px;
}
</style>
<script>
$(function() {
	$('.language_link').hide();
});
</script>
<?php
echo '<div id="goBack">';
echo CHtml::link('<< '.__('CMS editor'),array(	'cmsPage/update',
												'id'=>$model->id,
												'lang'=>$content->language,
										));
echo '</div>';
} ?>


<!-- start page here -->

<style>           
	.outer{width:100%; padding: 0px;}
	.left{width: 73%; float: left;  margin: 0px;}
	.right{width: 25%; float: right; margin: 0px;}

	.cmsPageMenuItem { font-size:1.6em; }
	
</style>


<div class="outer">
	<div class="cms_titulo"><?php echo CHtml::encode($content->pageTitle); ?></div>

<div class="left">
	<div class="cms_content"><?php echo $content->body; ?></div>
</div>

<div id="cmsinfo"></div>
<div id="cmsPageMenu" class="right">
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

<div style="clear:both"></div>

