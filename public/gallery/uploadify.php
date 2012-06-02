<?
require_once("../config/config.php");

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
		$imageInfo = getimagesize($_FILES['Filedata']['tmp_name']);
		
	imageResize(array(
		'sourceFile'		=> $_FILES['Filedata']['tmp_name'],
		'imageInfo'			=> $imageInfo,
		'destinationFile'	=> 's/'.$fileupload_name,
		'width'				=> $config['smallImageX'],
		'height'			=> $config['smallImageY'],
		'method'			=> 'crop'
	));


}
?>