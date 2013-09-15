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
?>

<p>
<?php
	echo 'Para participar en la '.Config::model()->findByPk('siglas')->value.' ';
	echo '<span style="background-color:#FFEC8B">pedimos que nos confirmas tu dirección de correo electrónico</span></p><p>';
	echo '- Te hemos enviado un correo-e que contiene sencillas instrucciones.<br />';
	echo '- Si no has recibido el correo, comprueba que no te haya llegado como spam.<br />';
	echo '- Si quieres que te enviamos el correo de nuevo a '.$model->email.', '.CHtml::link('clicka aquí',array('site/sendActivationCode')).'<br />';
	echo '- Si tu dirección de correo no es '.$model->email.' la puedes '.CHtml::link('cambiar por otra',array('user/update'));
?>
</p>
