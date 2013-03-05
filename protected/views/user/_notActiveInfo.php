
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
