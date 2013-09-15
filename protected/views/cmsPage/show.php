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

<style>           
	.outer{width:100%; padding: 0px; float: left;}
	.left{width: 15%; float: left;  margin: 0px;}
	.right{width: 83%; float: left; margin: 0px;}
	.clear{clear:both;}
	.activeItem a{color: red;}
</style>

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

<div class="outer">

<div class="left">
	<?php
	$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block, 'published'=>1), array('order'=>'weight'));
	foreach ($items as $menu_item) {
		foreach($menu_item->cmsPageContents as $item){
			if($item->language == $content->language){
				break;	
			}
		}
		$itemclass='';
		if($content->pageURL == $item->pageURL)
			$itemclass='class="activeItem"';
		echo '<div '.$itemclass.'>';
		echo CHtml::link(CHtml::encode($item->pageTitle),array('p/'.$menu_item->id.'/'.$item->pageURL));
		echo '</div>';
		echo '<br />';
	}

?>
</div>

<div class="right">
	<div class="cms_titulo_j"><?php echo CHtml::encode($content->pageTitle); ?></div>
	<div class="cms_content_j"><?php echo $content->body; ?></div>
</div>
</div>

<div class="clear"></div>

