<?php

function percentage($val1, $val2){
	$percent = ($val1 / $val2) * 100;
	//$decimals = strlen($percent) - strrpos($percent, '.') - 1;
	if($percent > 0)
		return round($percent,2);
	else
		return round($percent,3);
}


?>
