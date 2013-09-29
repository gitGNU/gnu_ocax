<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
//$this->layout='//column1';

$files=array();
$images=array();
$dir = Yii::app()->theme->basePath.'/wallpaper/';
$files = glob($dir.'*.jpg',GLOB_BRACE);

foreach($files as $image)
	$images[] = basename($image);
shuffle($images);

?>

<style>
#wallpaper {
	position:relative;
	margin-left:-20px;	
	margin-top:-35px;
	margin-bottom:-10px;
	height:728px;
	background: url("<?php echo Yii::app()->theme->baseUrl;?>/wallpaper/<?php echo $images[0];?>") 0 0 no-repeat;
}
</style>

<script>
var pageNumber = 0;
var wallpapers = <?php echo json_encode($images); ?>

function nextPage(){
	pageNumber = pageNumber +1;
	if(pageNumber == wallpapers.length)
		pageNumber = 0;
	$('#wallpaper').hide();
	$('#wallpaper').css('background-image', 'url("<?php echo Yii::app()->theme->baseUrl;?>/wallpaper/'+wallpapers[pageNumber]+'")');
	$('#wallpaper').fadeIn('fast');
	
}

</script>

<div id="wallpaper">

<div style="position:absolute; top:150px; left:150px ">
<span style="cursor:pointer" onClick="js:nextPage()">next</span>
</div>

</div>

