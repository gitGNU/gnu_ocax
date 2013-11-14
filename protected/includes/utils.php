<?php

/**
OCAX -- Citizen driven Municipal Observatory software
Copyright (C) 2013 OCAX Contributors. See AUTHORS.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

function percentage($val1, $val2){
	if($val2 == 0)
		return 100;
	$percent = ($val1 / $val2) * 100;
	//$decimals = strlen($percent) - strrpos($percent, '.') - 1;

	return number_format(round($percent,2), 2, ',', '.');
}

function format_number($number){
	return number_format(CHtml::encode($number), 2, ',', '.');
}

function getLanguagesArray($available=Null){
	if($available){
		// get array from reading translation directories
	}else// enabled
		$languages=explode(',', Config::model()->findByPk('languages')->value);
	$listData = array();
	if(isset($languages[1])){
		foreach($languages as $language){
			if($language == 'es')
				$listData['es'] = 'Castellano';
			if($language == 'ca')
				$listData['ca'] = 'Català';
			if($language == 'gl')
				$listData['gl'] = 'Gallego';
			if($language == 'en')
				$listData['en'] = 'English';
		}
	}
	return $listData;
}

function format_date($date, $hours=Null){
	$date = new DateTime($date);
	if($hours)
		return $date->format('d-m-Y H:i:s');
	else
		return $date->format('d-m-Y');
}

function getOCAXVersion(){
	$path = Yii::app()->basePath.'/data/ocax.version';
	$handle = @fopen($path, "r");
	$version = rtrim(fgets($handle),"\n");
	fclose($handle);
	return $version;
}

function getInlineHelpURL($path){
	return 'http://ocax.net/'.Yii::app()->user->getState('applicationLanguage').$path;	
	return 'http://ocax.net/'.Yii::app()->user->getState('applicationLanguage').$path.'&inlinehelp=1';
}

?>
