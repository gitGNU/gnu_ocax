<?php
/**
 * OCAX -- Citizen driven Observatory software
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
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/jquery.hoverIntent.min.js"></script>


	<!-- <div id="mainmenu"> -->
	<div id="mainMbMenu">
		<?php
			$items=array(
				array('label'=>__('Budgets'), 'url'=>array('/budget'),'active'=> (strcasecmp(Yii::app()->controller->id, 'budget') === 0)  ? true : false),
				array('label'=>__('Enquiries'), 'url'=>array('/enquiry'),'active'=> (strcasecmp(Yii::app()->controller->id, 'enquiry') === 0)  ? true : false),
			);
			$criteria=new CDbCriteria;
			$criteria->condition = 'weight = 0 AND published = 1';
			$criteria->order = 'block DESC';
			$cms_pages=CmsPage::model()->findAll($criteria);
			foreach($cms_pages as $page){
				$page_content = $page->getContentForModel(Yii::app()->language);
				$item = array(	'label'=>CHtml::encode($page_content->pageTitle),
										'url'=>array('/p/'.$page_content->pageURL),
										'active'=> ($page->isMenuItemHighlighted()) ? true : false,
								);
				//add sub menu
				$criteria=new CDbCriteria;
				$criteria->condition = 'block = '.$page->block.' AND weight != 0 AND published = 1 AND weight IS NOT NULL';
				$criteria->order = 'weight ASC';				
				$subpages = $page->findAll($criteria);
				
				if($subpages){
					$subItems=array();
					foreach($subpages as $subpage){
						$subpage_content = $subpage->getContentForModel(Yii::app()->language);
						$subitems[] = array('label'=>CHtml::encode($subpage_content->pageTitle),
												'url'=>array('/p/'.$subpage_content->pageURL),
										);
					}
					$item['items'] = $subitems;
				}
				array_splice( $items, 0, 0, array($item) );
			}
			if(!Yii::app()->user->isGuest){
				$item = array(	'label'=>__('My page'),
								'url'=>array('/user/panel'),
						);
				array_splice( $items, 0, 0, array($item) );
			}
			$this->widget('application.extensions.mbmenu.MbMenu',array(
				'items'=>$items,
			));
		?>
	</div><!-- mainmenu -->
