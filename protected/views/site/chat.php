<?php
/**
 * OCAX -- Citizen driven Municipal Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

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
					// only set this to true if developing / debugging errors
					debug: true,
					// autojoin is a *required* parameter if you don't have a plugin (e.g. roomPanel) for it
					//   true
					//     -> fetch info from server (NOTE: does only work with openfire server)
					//   ['test@conference.example.com']
					//     -> array of rooms to join after connecting
					autojoin: ['csv@rooms.gatopelao.org','hangout@rooms.gatopelao.org'],
				},
				view: {
					resources: '<?php echo Yii::app()->request->baseUrl; ?>/candy-1.6.0/res/',
					language: '<?php echo Yii::app()->language;?>'
				}
			});

			//Candy.Core.connect();
			Candy.Core.connect('gatopelao.org', null, '<?php echo Yii::app()->user->id;?>')

			/**
			 * Thanks for trying Candy!
			 *
			 * If you need more information, please see here:
			 *   - Setup instructions & config params: http://candy-chat.github.io/candy/#setup
			 *   - FAQ & more: https://github.com/candy-chat/candy/wiki
			 *
			 * Mailinglist for questions:
			 *   - http://groups.google.com/group/candy-chat
			 *
			 * Github issues for bugs:
			 *   - https://github.com/candy-chat/candy/issues
			 */
		});
	</script>
</head>
<body>
	<div id="candy"></div>
</body>
</html>


