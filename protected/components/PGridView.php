<?php
/**
 * PGridView class file.
 *
 * usage example in view

	$this->widget('PGridView', array(
		'dataProvider'=>$dataProvider,
		'onClick'=>array(
			'type'=>'javascript', 
			'call'=>'showProviderComment',
		),
		//'onClick'=>array(
		//	'type'=>'url', 
		//	'call'=>'serviceorder/view',
		//),
		'columns'=>array(
			array('class'=>'PHiddenColumn','value'=>'$data->id'),
		),
		.....

 * 
 * 
 */


Yii::import('zii.widgets.grid.CGridView');

class PGridView extends CGridView
{
	public $onClick=array();
	public $url=Null;


	public function init()
	{
		$this->htmlOptions['class']='pgrid-view pgrid-cursor-pointer';
		$this->cssFile=Yii::app()->theme->baseUrl.'/css/pgridview.css';

		$this->loadingCssClass='pgrid-view-loading';
		parent::init();
	}
	
	public function registerClientScript()
	{
			parent::registerClientScript();
			$id=$this->getId();
			$cs=Yii::app()->getClientScript();

			if($this->onClick['type']=='url'){
				$url=$this->onClick['call'];
				$this->url="window.location='$url/'+param";
				//$this->url="window.location=".Yii::app()->request->baseUrl."'$url/'+param";
			}
			$function_name=str_replace('-','',$id)."_pgridfunc";
			if($this->onClick['type']=='javascript'){
				$cs->registerScript($function_name,'function '.$function_name.'(paramstring,element) {
							parameters=paramstring.split(new RegExp("[,]", "g"));
							parameters.push(element);
							window["'.$this->onClick['call'].'"].apply(this,parameters);
						}
				');
			}
			$cs->registerScript(__CLASS__.'# '.$id,"jQuery('#$id tbody td:not([class=PButtonColumn])').live('click',
							function (){
								param=$(this).parent().find('.p_parameter').html();
								if(param){
									if('".$this->onClick['type']."'=='javascript')
										".$function_name."(param,this);
									else
										$this->url;
									return false;
								}
								return false;
							}
					);");
	}
}

Yii::import('zii.widgets.grid.CDataColumn');
class PHiddenColumn extends CDataColumn
{
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array('style'=>'display:none','class'=>'p_parameter');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array('style'=>'display:none');

    public function normalize($title) {
        return preg_replace(array('/[^a-zA-Z0-9\s]/', '/ /'), '_', $title);
    }


}


