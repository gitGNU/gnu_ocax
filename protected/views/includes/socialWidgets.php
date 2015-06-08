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

/* @var $this EnquiryController */
/* @var $model Enquiry */

?>

<style>
.socialWidgetBox {
	display:none;
	position:absolute;
	top: -7px;
	width:290px;
	z-index:99;
}
.socialWidgetBox > div.widget {
		margin: 4px 2px 8px 2px;
		padding: 0px;
		width: 110px;
		font-size: 16px;
		height: 22px;
}
</style>

<div class="alert socialWidgetBox">
<?php
	$fullurl = $this->createAbsoluteUrl('/enquiry/'.$model->id);
	$url = $this->createAbsoluteUrl('/e/'.$model->id);

	echo '<div style="float:right; margin:5px 5px 0 0; font-size:22px;"><i class="icon-cancel-circled" onclick="js:$(\'.alert\').hide();"></i></div>';

	echo '<div class="widget">
			<input type="text" style="width:232px; font-size:15px;" value='.$url.' />
		</div>';
	if(Config::model()->findByPk('socialActivateMeneame')->value){
		echo '<div	class="widget"
					style="cursor:pointer;"
					onclick="js:window.open(\'http://meneame.net/submit.php?url='.$url.'&title='.$model->title.'\',\'_blank\')"
				>
				<img style="float:left;" src="'.Yii::app()->request->baseUrl.'/images/meneame-icon.png" />
				<div style="float:left; margin:-2px 0 0 4px;">Meneame</div>
			</div><div class="clear" style="margin-bottom:8px;"></div>';
	}	
	if(Config::model()->findByPk('socialActivateNonFree')->value){	
		echo '<div class="widget">
			  <a	href="https://twitter.com/share"
					class="twitter-share-button"
					data-url="'.trim($url).'"
					data-counturl="'.trim($fullurl).'"
					data-text="'.trim($model->title).'"
					data-via="'.trim(Config::model()->findByPk('socialTwitterUsername')->value).'"
					data-lang="en"
					>
			</a>
			</div>';
		echo '<div class="widget">
			<div	class="fb-share-button" data-href="'.$fullurl.'"
					data-layout="button_count">
			</div>
			</div>';
	}
?>
</div>
