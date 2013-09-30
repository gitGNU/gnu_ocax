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
	width:980px;
	background: url("<?php echo Yii::app()->theme->baseUrl;?>/wallpaper/<?php echo $images[0];?>") 0 0 no-repeat;
}

.block {
	opacity: 0.5;
	font-size:1.3em;
	padding:10px;
	background-color:white;
}
.block .title {
	margin-bottom:15px;
	line-height: 100%;
	font-size: 28pt;
	letter-spacing:-0.5pt;	font-weight:200;	
}
.nextPage {
	font-size: 1.7em;
	cursor:pointer;
}

</style>

<script>
var TOTAL_PAGES = 3;

var pageCnt = 0;
var wallpaperCnt = 0;
var wallpapers = <?php echo json_encode($images); ?>;
var pageCache=new Array();

function nextPage(){
	wallpaperCnt = wallpaperCnt +1;
	if(wallpaperCnt == wallpapers.length)
		wallpaperCnt = 0;
		
	pageCnt = pageCnt +1;
	if(pageCnt == TOTAL_PAGES)
		pageCnt = 0;
		
	if(pageCache[pageCnt]){
		showPage();
		return;	
	}
	$.ajax({
		url: '<?php echo Yii::app()->request->baseUrl; ?>/site/getIndexContent/'+pageCnt,
		type: 'GET',
		async: false,
		//beforeSend: function(){	$('.loading_gif').remove();	},
		//complete: function(){ $('.loading_gif').remove(); },
		success: function(data){
			if(data != 0){
				pageCache[pageCnt]=data;
				showPage();
			}
		},
		error: function() {
			alert("Error on get page content");
		}
	});
}

function showPage(){
	$('#wallpaper').hide();
	$('#wallpaper').css('background-image', 'url("<?php echo Yii::app()->theme->baseUrl;?>/wallpaper/'+wallpapers[wallpaperCnt]+'")');
	$('#wallpaper').html(pageCache[pageCnt]);
	$('#wallpaper').fadeIn('fast');
}

</script>

<div id="wallpaper">

<?php echo $this->renderPartial('_index0'); ?>

</div>

