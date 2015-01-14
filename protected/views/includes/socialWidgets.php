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
	top: -5px;
	width:250px;
	z-index:99;
}
.socialWidgetBox > div.widget {
		margin: 4px 2px 8px 2px;
		padding: 0px;
		/*float:left;*/
		width: 110px;
		/*height: 21px;*/
		/*background-color:red;*/
		font-size: 16px;
}
</style>

<div class="alert socialWidgetBox">
<?php
	$url = $this->createAbsoluteUrl('/e/'.$model->id);

	echo '<div	class="widget">
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
					data-counturl="'.trim($url).'"
					data-text="'.trim($model->title).'"
					data-via="'.trim(Config::model()->findByPk('socialTwitterUsername')->value).'"
					data-lang="en"
					>
			</a>
			</div>';
		echo '<div class="widget">
			<div	class="fb-share-button" data-href="'.$url.'"
					data-layout="button_count">
			</div>
			</div>';
	}
?>
</div>
