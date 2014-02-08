<?php
/* @var $this SiteController */
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>OCAx chat</title>
	
<style>
iframe {
    display: block;
    border: 0;
}
.fluidMedia {
    position: relative;
    padding-bottom: 56.25%; /* proportion value to aspect ratio 16:9 (9 / 16 = 0.5625 or 56.25%) */
    padding-top: 30px;
    height: 0;
    overflow: hidden;
}
.fluidMedia iframe {
    position: absolute;
    top: 0; 
    left: 0;
    width: 80%;
    height: 60%;
    margin: 0 auto;
}
</style>



</head>
<body style="background-color:darkgrey">

<div class="fluidMedia">
	<iframe src="candy" frameborder="0" scrolling="no" />
</div>


</body>
</html>




