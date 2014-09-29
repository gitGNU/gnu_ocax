
<div class="sub_title"><?php echo __('Check server requirements');?>
</div>

<p>
<?php
$cnt =1;
$errors = 0;
$config = Config::model();

// Check direcotry permisions
$err_msg = '';
if(!is_writable(Yii::app()->basePath.'/runtime/tmp')){
	$err_msg = $err_msg.$cnt.'. '.'<span style="color:red">'.__('Error: protected/runtime/tmp ').'</span><br />';
	$cnt +=1;
}
if(!is_writable(Yii::app()->basePath.'/runtime/html')){
	$err_msg = $err_msg.$cnt.'. '.'<span style="color:red">'.__('Error: protected/runtime/html ').'</span><br />';
	$cnt +=1;
}
if($err_msg){
	echo $err_msg;
	$errors +=1;
}else{
	echo $cnt.'. <span style="color:green">'.__('Directory permissions seem Ok').'</span><br />';
	$cnt +=1;
}


echo $cnt.'. <span style="color:green">';
if(isExecAvailable() === false){
	$dumpMethod = $config->findByPk('databaseDumpMethod');
	$dumpMethod->value = 'alternative';
	$dumpMethod->save();
	echo __('Info: Native mysqldump not available. Using PHP alternative for backups. Ok');
}else{
	// need to add test for mysqldump
	echo __('Using native mysqldump for backups. Ok');
}
echo '</span><br />';
$cnt +=1;


echo $cnt.'. ';
if(class_exists('ZipArchive'))
	echo '<span style="color:green">'.__('ZipArchive is installed. Ok').'</span>';
else{
	echo '<span style="color:red">'.__('Error: ZipArchive is not installed').'</span>';
	$errors +=1;
}
echo '<br />';
$cnt +=1;


$requirementsCheck = $config->findByPk('siteConfigStatusRequirementsCheck');
if($errors){
	$requirementsCheck->value = 0;
	echo '<span style="background-color:red; color:white; font-size: 1.2em">'.__('Please fix these errors').'</span>';
	
}
else{
	$requirementsCheck->value = 1;
	echo '<span style="background-color:green; color:white; font-size: 1.2em">'.__('The system seems ok').'</span>';	
}
$requirementsCheck->save();
	
?>
</p>
