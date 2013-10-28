<?php

/**
OCAX -- Citizen driven Municipal Observatory software
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
	public $padParams='?showControls=false&showChat=false&showLineNumbers=false&useMonospaceFont=false';
	
    public function run()
    {
		?>
<!-- help widget start -->
<style>iframe{min-width:880px; min-height:500px;}</style>
<script src="<?php echo Yii::app()->request->baseUrl;?>/scripts/jquery.bpopup-0.8.0.min.js"></script>
<script>
function showHelp(url){
		urlParams="<?php echo $this->padParams;?>";
		$('#help').bPopup({
			modalClose: false,
			follow: ([false,false]),
			fadeSpeed: 10,
			positionStyle: 'absolute',
			modelColor: '#ae34d5',
			content:'iframe',
			iframeAttr:'width:1500px',
			contentContainer:'#helpContent',
			loadUrl:url+urlParams
		});
}
</script>
<div id="help" class="modal" style="width:870px;">
<img class="bClose" src="<?php echo Yii::app()->request->baseUrl; ?>/images/close_button.png" />
<div id="helpContent"></div>
</div>
<!-- help widget stop -->
	<?php

    }
}
