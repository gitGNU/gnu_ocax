<?php

/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2015 OCAX Contributors. See AUTHORS.

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

/* @var $this EnquiryController */
/* @var $model Enquiry */

$color = '#'.Config::model()->getSiteColor();

echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/fonts/fontello/css/fontello.css" />';
?>

<style type="text/css">
<!--

    table.page_header {width: 100%; border: none; border-bottom: solid <?php echo $color;?>; }
    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
    div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }
    ul.main { width: 95%; list-style-type: square; }
    ul.main li { padding-bottom: 2mm; }
    h1 { text-align: center; font-size: 20mm}
    h3 { text-align: center; font-size: 14mm}
    

page { font-size: 12pt; }

.sectionTitle {
	border-bottom: 2px #6E6E6E solid;
	margin: 10px 0 15px 0;
}
.sectionTitle span {
	font-size: 14pt;
	color: white;
	background-color: #6E6E6E;
}

.title {
	font-size: 18pt;
	color: <?php echo $color;?>;
	margin-bottom:15px;
}
.commentID {
	color: #5C5C5C;
}


-->
</style>

<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: left">
					<?php 
					//echo '<img id="logo" src="'.Yii::app()->request->baseUrl.'/files/logo.png" />';
					echo Config::model()->getObservatoryName();
					?>       
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 33%; text-align: left;">
					<?php
					$link = Yii::app()->getBaseUrl(true).'/enquiry/'.$model->id;
					echo "<a href='$link'>$link</a>";
					?>
                </td>
                <td style="width: 34%; text-align: center">
                    <?php echo __('page');?> [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 33%; text-align: right">
                    <?php echo Config::model()->getObservatoryName();?>
                </td>
            </tr>
        </table>
    </page_footer>

	<?php 
	echo '<div class="sectionTitle">';
	echo '<span>'.__('The enquiry').'</span>';
	echo '</div>';
	
	echo '<div class="title">';
	echo $model->title;
	echo '</div>';
	echo '<p>';
	$this->renderPartial('//enquiry/_detailsForCitizen', array('model'=>$model, 'hideBudgetDetails'=>1));
	echo '</p>';
	?>
	<p><?php echo $model->body; ?></p>

<?php
if ($model->budget){
	echo '<div class="sectionTitle">';
	echo '<span>'.__('Relative budgetary information').'</span>';
	echo '</div>';
	
	echo '<div class="title">';
	echo $model->budget0->getTitle();
	echo '</div>';


	echo $this->renderPartial('//enquiry/_budgetDetails', array('model'=>$model->budget0,'showMore'=>1));

	echo '<p>';
	echo $model->budget0->getExplication();
	echo '</p>';
		
}
?>

<?php
if ($comments = Comment::model()->findAllByAttributes(array('model'=>get_class($model), 'model_id'=>$model->id))){
	echo '<div class="sectionTitle">';
	echo '<span>'.__('Citizen comments').'</span>';
	echo '</div>';

	foreach($comments as $comment){
		echo '<p>';
		echo '<span class="commentID">- '.__('Comment').' #'.$comment->thread_position.' ('.format_date($comment->created,1).')</span><br />';
		echo $comment->body;
		echo '</p>';
	}
}
?>
</page>
<page pageset="old">
<?php
if ($model->replys){
	$reply = $model->replys[0];
	
	echo '<div class="sectionTitle">';
	echo '<span>'.__('Reply').'</span>';
	echo '</div>';	

	echo '<p>';
	echo $reply->body;
	echo '</p>';

	if ($comments = Comment::model()->findAllByAttributes(array('model'=>get_class($reply), 'model_id'=>$reply->id))){
		echo '<div class="sectionTitle">';
		echo '<span>'.__('Citizen comments').'</span>';
		echo '</div>';

		foreach($comments as $comment){
			echo '<p>';
			echo '<span class="commentID">- '.__('Comment').' #'.$comment->thread_position.' ('.format_date($comment->created,1).')</span><br />';
			echo $comment->body;
			echo '</p>';
		}
	}
}
?>

</page>






