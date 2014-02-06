<?php
/**
 * Copyright (c) 2011 Amiado Group AG
 * Copyright (c) 2012-2014 Patrick Stadler & Michael Weibel

 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

/* @var $this SiteController */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>OCAx chat</title>
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/res/img/favicon.png" type="image/gif" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/res/default.css" />

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/libs/libs.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/candy.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			Candy.init('http://gatopelao.org/http-bind', {
				core: {
					debug: false,
					autojoin: ['ocax@rooms.gatopelao.org','ocm@rooms.gatopelao.org'],
				},
				view: {
					resources: '<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/res/',
					language: '<?php echo Yii::app()->language;?>'
				}
			});
			Candy.Core.connect('gatopelao.org', null, '<?php echo Yii::app()->user->getFullname();?>')
		});
	</script>
</head>
<body>
	<div id="candy"></div>
</body>
</html>


