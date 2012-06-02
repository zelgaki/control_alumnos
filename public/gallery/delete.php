<? 
include("../config/config.php");
sleep(1);

$id=(int)$_REQUEST["id"];

if($_REQUEST[albumid]&&$id){
	 $sql= q("select  *  from ".$prefix_table."pics  WHERE  id = '$id' ");		 
	 $result=q("UPDATE ".$prefix_table."albums SET cnt=cnt-1 WHERE id=".$_REQUEST[albumid]);

}else{
	 $tb_name=$prefix_table."albums";
	 $f_name="id";
	 $result=q("delete from $tb_name  where $f_name='$id'");
	 $sql= q("select  *  from ".$prefix_table."pics  WHERE  albumid = '$id' ");	
}

if($result){
	while($row= mysql_fetch_array($sql)){
		$key_name=$row['filename'];
		 q("delete from ".$prefix_table."pics   WHERE  id = '$row[id]' ");							 
		@unlink($config['imageDir'].'/s/'.$key_name);
		@unlink($config['imageDir'].'/m/'.$key_name);
		@unlink($config['imageDir'].'/b/'.$key_name);		 
	}	
if($_REQUEST[albumid]&&$id){
	 $row= q_array("SELECT * FROM ".$prefix_table."albums WHERE id=".$_REQUEST[albumid]);
	 if($row[cnt]==0){
		 $thumb=1;
		q("UPDATE ".$prefix_table."albums SET thumb=0  WHERE id=".$_REQUEST[albumid]);
	 }elseif($row[thumb]==$id){
		 		q("UPDATE ".$prefix_table."albums SET thumb=
		  (
			SELECT id 
			FROM ".$prefix_table."pics
			WHERE albumid=".$_REQUEST[albumid]."
			ORDER BY id DESC
			LIMIT 1
		  )
		  WHERE id=".$_REQUEST[albumid]."");
		 
		 }
}
	$check="1";  
}else{
	$check="0";
}


$return_arr["check"] = $check;
$return_arr["thumb"] = $thumb;
echo json_encode($return_arr);
?>