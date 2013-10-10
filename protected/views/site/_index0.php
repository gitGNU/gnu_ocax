<?php
/* @var $this SiteController */
?>

<div class="block" style="position:absolute; top:70px; left:70px ">
<div class="title">
<?php
$text = array(
		'en'=>'A title. page 0',
		'es'=>'Un título. página 0',		
		'ca'=>'Un titol. pàgina 0'
	);
echo $text[$lang];
?>
</div>
<div class="text" style="width:500px">
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut
laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation
ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
</div>

<span class="nextPage" onClick="js:nextPage()">
<?php
$text = array(
		'en'=>'Next',
		'es'=>'Siguiente',		
		'ca'=>'Següent'
	);
echo $text[$lang];
?>
</span>
</div>



