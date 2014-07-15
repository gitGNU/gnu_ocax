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
