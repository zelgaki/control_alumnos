<? 
//  connect database .
$hostname = "localhost";
$username = "root";
$password = "ict";
$dbname = "ziceadmin";
$prefix_table="01_";
$link = mysql_pconnect($hostname,$username, $password);
mysql_query("SET NAMES 'utf8'");
mysql_select_db($dbname);
//------------------------------------------------------------ 

// gallery settings. 
$config = array();
$config['bigImageX']			= 1680;
$config['bigImageY']			= 1260;

$config['midImageX']			= 640;
$config['midImageY']			= 480;

$config['smallImageX']			= 160;
$config['smallImageY']			= 120;

$config['imageDir']				= 'gallery';
$config['max_upload_size']		 =200;

// add ,upadate database, resize images function------------------------------------------- 

//query MYSQL
function q($str)
{
	global $link;
	return mysql_query($str,$link);
}
//query array MYSQL
function q_array($str){
	return mysql_fetch_assoc(q($str));
}
// add  MYSQL
function add_db($column_ , $values_ ,$tb_)
{
	$result = mysql_query('select * from '. $tb_ );
		for ($i=0;$i < count($column_);$i++) {
		$meta = mysql_fetch_field($result, $column_[$i]);
			if($i==(count($column_)-1))
					$comma='';
			else
					$comma=',';				   
			$column_data[]=$meta->name.$comma ;
		}
		for($i=0;$i<count($values_);$i++){
			if($i==(count($values_)-1))
				$comma='';
			else
				  $comma=',';			
			 $values_data[]= "'".$values_[$i]."'".$comma;			  
		}		
		$sql="insert  into  $tb_ (";
			foreach ($column_data as $key) {
					$sql.= $key;
		} 				  
		$sql.=") values (";
			foreach ($values_data as $key) {
					$sql.= $key;
		} 
		$sql.=")";		
		return "$sql";
}
// update  MYSQL
 function update_db($column_ , $values_ ,$tb_,$where_){
		$result = mysql_query('select * from '. $tb_ );
		for ($i=0;$i < count($column_);$i++) {
		$meta = mysql_fetch_field($result, $column_[$i]);
			if($i==(count($column_)-1))
					$comma='';
			else
					$comma=',';						
				$set[]=$meta->name."='".$values_[$i]."'".$comma; 				
			}	
		  $sql="update   $tb_  set  ";
			  foreach ($set as $key) {
					  $sql.= $key;
		  } 	
		  $sql.=" where $where_ ";
		  return "$sql";  
}

function imageResize($parameters)
{
	$fileTmp	= $parameters['sourceFile'];	//$_FILES['Filedata']['tmp_name'];
	$imageInfo	= $parameters['imageInfo'];
	$outputFile	= $parameters['destinationFile'];
	
	$newWidth	= $resizeWidth	= $parameters['width'];	//120;
	$newHeight	= $resizeHeight	= $parameters['height'];	//90;	

	$origRatio	= $imageInfo[0]/$imageInfo[1];
	
	$offsetX	= $offsetY = 0;
	
	$resizeRatio= $resizeWidth/$resizeHeight;

	if($imageInfo[0]<$resizeWidth && $imageInfo[1]<$resizeHeight)
	{
		@copy($fileTmp,$outputFile);
		return;
	}

	switch($imageInfo[2])
	{
		case 1:
			$origImg = imagecreatefromgif($fileTmp);
			break;
			
		case 2:
			$origImg = imagecreatefromjpeg($fileTmp);
			break;
			
		default:
			$origImg = imagecreatefrompng($fileTmp);
			break;
	}
	
	$method = 'scale';
	if($parameters['method']) $method = $parameters['method'];
	
	if($method == 'crop' && ($imageInfo[0]<$resizeWidth || $imageInfo[1]<$resizeHeight)) $method = 'scale';
	
	if($method == 'scale')
	{
		if($origRatio > $resizeRatio)
		{
			$quot = $resizeWidth / $imageInfo[0];
			$resizeHeight = $newHeight = round($imageInfo[1]*$quot);
			$newWidth = $resizeWidth;
		}
		
		else if( $origRatio < $resizeRatio)
		{
			$quot = $resizeHeight / $imageInfo[1];
			$resizeWidth = $newWidth = round($imageInfo[0]*$quot);
			$newHeight = $resizeHeight;
		}
	}
	else if($method=='crop')
	{
		if($origRatio > $resizeRatio)
		{
			$quot = $resizeHeight / $imageInfo[1];
			$newHeight = $resizeHeight;
			$newWidth = round($imageInfo[0]*$quot);
			$offsetX = round( (($newWidth-$resizeWidth)/2)/$quot );
		}
		
		else if( $origRatio < $resizeRatio)
		{
			$quot = $resizeWidth / $imageInfo[0];
			$newWidth = $resizeWidth;
			$newHeight = round($imageInfo[1]*$quot);
			$offsetY = round( (($newHeight-$resizeHeight)/2)/$quot );
		}
	}
	
	$newImg = imagecreatetruecolor($resizeWidth,$resizeHeight);
	
	imagecopyresampled($newImg, $origImg, 0, 0,$offsetX,$offsetY, $newWidth, $newHeight, $imageInfo[0], $imageInfo[1]); 
	
	switch($imageInfo[2])
	{
		case 1:
			imagegif($newImg, $outputFile);
			break;
			
		case 2:
			imagejpeg($newImg, $outputFile,100);
			break;
			
		default:
			imagepng($newImg, $outputFile);
			break;
	}
	
	imagedestroy($newImg);
	imagedestroy($origImg);
}
function let_to_num($v)
{
	$l = substr($v, -1);
	$ret = substr($v, 0, -1);
	switch(strtoupper($l))
	{
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
		break;
	}
	return $ret;
}


?>