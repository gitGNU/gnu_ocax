<?php

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
?>
