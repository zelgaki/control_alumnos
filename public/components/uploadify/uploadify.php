<?
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$name =  $_FILES['Filedata']['name'];
	$text= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	$rand=substr(str_shuffle($text),0,32);
	
$array_last=explode(".",$_FILES['Filedata']['name']);
$c=count($array_last)-1;
$ext=strtolower($array_last[$c]);
$fileupload_name=$rand.".".$ext;
$targetFile =  str_replace('//','/',$targetPath).$fileupload_name;
	
		
		move_uploaded_file($tempFile,$targetFile);
		chmod($targetFile, 0777);
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);

}
?>