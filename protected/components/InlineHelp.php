<?php

/**
OCAX -- Citizen driven Observatory software
Copyright (C) 2013 OCAX Contributors. See AUTHORS.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class InlineHelp extends CWidget
{
	public $padParams='';
	
    public function run()
    {
		?>
<!-- help widget start -->
<style>iframe{min-height:500px;}</style>
<script src="<?php echo Yii::app()->request->baseUrl;?>/scripts/jquery.bpopup-0.9.4.min.js"></script>
<script>
function showHelp(url){
		$('#help').bPopup({
			modalClose: false,
			follow: ([false,false]),
			speed: 10,
			positionStyle: 'absolute',
			modelColor: '#ae34d5',
			content:'iframe',
			iframeAttr:'width=900px scrolling=auto',
			contentContainer:'#helpContent',
			loadUrl:url,
			/*loadCallback: function(){ $('.loading_help').hide(); },*/
		});
}
</script>
<div id="help" class="modalContextHelp" style="width:900px;scrolling:auto;padding:0px;">
	<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
	<div id="helpContent"></div>
</div>
<!-- help widget stop -->
<?php
    }
}
