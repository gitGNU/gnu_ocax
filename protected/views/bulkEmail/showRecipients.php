<?php

?>

<div class="form">

<div class="title">
<?php 
	if($draft)
		echo __('Will send to all users at').' '.Config::model()->findByPk('siglas')->value;
	else
		echo __('Was sent to these users');
?>
</div>

<?php echo $recipients;?>

</div>
