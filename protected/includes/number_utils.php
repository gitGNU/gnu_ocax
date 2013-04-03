<?php

function percentage($val1, $val2){
	$percent = ($val1 / $val2) * 100;
	//$decimals = strlen($percent) - strrpos($percent, '.') - 1;

	return number_format(round($percent,2), 2, ',', '.');
}

function format_number($number){
	return number_format(CHtml::encode($number), 2, ',', '.');
}

?>
