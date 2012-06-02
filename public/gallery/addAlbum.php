<? 
include("../config/config.php");
$tb_=$prefix_table."albums";
$datenow=date('Y-m-d H:i:s');
$column_=array(1,4);
$values_=
array(
	  $_REQUEST['name'],
	 $datenow
	  ); 
$result=q(add_db($column_,$values_,$tb_));
			if($result){ 			 
				$check="1";  
			}else{
				$check="0";
			}
$return_arr["check"] = $check;
echo json_encode($return_arr);
?>