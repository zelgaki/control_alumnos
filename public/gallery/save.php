<? 
include("../config/config.php");
sleep(1);
$tb_=$prefix_table."albums"; 
$column_=array(1); 
$values_=
array(
	  $_REQUEST['name']
	  ); 
$where_=" id='$_REQUEST[id_edit]' ";

$result=mysql_query(update_db($column_,$values_,$tb_,$where_));
			if($result){ 			 
				if($_REQUEST['thumbPreview']){
					mysql_query(update_db(array(2),array($_REQUEST['thumbPreview']),$tb_,$where_));
					}
				$check="1";  
			}else{
				$check="0";
			}
$return_arr["check"] = $check;
echo json_encode($return_arr);
?>