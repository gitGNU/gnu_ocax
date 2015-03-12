<?php

/**
OCAX -- Citizen driven Observatory software
Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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

?>

<script>
function acceptCookies(){
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/site/acceptCookies',
		type: 'GET',
		complete: function(){ $('.cookieAlert').hide(); },
	});
}
</script>
<div class="cookieAlert">
<?php
echo	Config::model()->getObservatoryName().' '.
		__('uses Twitter and Facebook cookies that collect statistics. By using this web site you accept this.').' ';
?>
<span class="link" style="color:yellow" onclick="js:acceptCookies()"><?php echo __('Ok'); ?></span>
</div>
