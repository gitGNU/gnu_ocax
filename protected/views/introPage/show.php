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
?>

<?php
//get next introPage
$nextPage=null;

$criteria=new CDbCriteria;
$criteria->addCondition('weight > '.$model->weight.' and published = 1');
$criteria->order = 'weight ASC';

$pages = $model->findAll($criteria);
if($pages)
	$nextPage=$pages[0];
?>

<style>
.block {
	opacity: 0.5;
	font-size:1.3em;
	padding:10px;
	background-color:white;
	position:absolute;
	top:<?php echo $model->toppos;?>px;
	left:<?php echo $model->leftpos;?>px;
	width:<?php echo $model->width;?>px;
}
.block .title {
	margin-bottom:15px;
	line-height: 100%;
	font-size: 28pt;
	letter-spacing:-0.5pt;	font-weight:200;	
}
.nextIntroPage {
	width:100%;
	text-align:right;
}
</style>

<div class="block">
	<div class="title"><?php echo $content->title; ?></div>
	<div class="sub_title"><?php echo $content->subtitle ?></div>
	<p class="text"><?php echo $content->body; ?></p>
	<?php
	if($nextPage){
		echo '<div class="nextIntroPage" onClick="js:nextPage('.$nextPage->id.')">';
		echo '<span style="cursor:pointer">'.$model->getTitleForModel($nextPage->id,$content->language).'</span>';
		echo '</div>';
	}
	?>
</div>

