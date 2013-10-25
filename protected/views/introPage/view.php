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
 * along w

/* @var $this IntroPageController */
/* @var $model IntroPage */


// get wallpaper photo
$files=array();
$images=array();
$dir = Yii::app()->theme->basePath.'/wallpaper/';
$files = glob($dir.'*.jpg',GLOB_BRACE);

foreach($files as $image)
	$images[] = basename($image);
shuffle($images);

?>

<style>
#goBack{
	font-size:1.5em;
	margin:-25px;
	margin-bottom:50px;
	padding-left:20px;
}
#wallpaper {
	position:relative;
	margin-left:-20px;	
	margin-top:-35px;
	margin-bottom:-10px;
	height:728px;
	width:980px;
	background: url("<?php echo Yii::app()->theme->baseUrl;?>/wallpaper/<?php echo $images[0];?>") 0 0 no-repeat;
}
</style>

<script>
$(function() {
	$('.language_link').hide();
});
function nextPage(id){
	alert('<?php echo __('You are in edit mode');?>');	
}
</script>
<?php
echo '<div id="goBack">';
echo CHtml::link('<< '.__('CMS editor'),array(	'introPage/update',
												'id'=>$model->id,
												'lang'=>$content->language,
										));
echo '</div>';
?>

<div id="wallpaper">
	<?php echo $this->renderPartial('show', array('model'=>$model,'content'=>$content));?>
</div>



