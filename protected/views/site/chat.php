<?php
/* @var $this SiteController */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>

<style>
html, body { height: 100%; width: 100%; margin: 0; background-color:#575757; padding:0px}

@font-face {
	font-family: SansPro;
	src: url("<?php echo Yii::app()->request->baseUrl;?>/fonts/SansPro-Regular.woff");
	font-weight: 100;
}

#header { font-family: SansPro; color: #C4C4C4; font-size: 18pt; font-weight:100; margin: 0px}
#ocaxChat { height: 90%; width: 90%; margin: 0 auto; color: white; }
#candyFrame { height: 100%; width: 100%; border: 0px}
</style>

<body>
    
<div id="ocaxChat">
	<span id="header">OCAx Community support center (aka chat rooms)</span>
	<iframe id='candyFrame' src="candy" />
</div>

</body>
</html>



