<?
include("../config/config.php");
sleep(1);

$lastalbumid	= (int)$_REQUEST['lastalbumid'];
$newalbumid	= (int)$_REQUEST['newalbumid'];
$picid		= (int)$_REQUEST['picid'];

if($newalbumid  and $picid and $lastalbumid) {

	q("UPDATE ".$prefix_table."pics SET albumid = ".$newalbumid."  WHERE id=".$picid);
	$newalbum=q("UPDATE ".$prefix_table."albums SET cnt=cnt+1 WHERE id=".$newalbumid);
	$lastalbum=q("UPDATE ".$prefix_table."albums SET cnt=cnt-1 WHERE id=".$lastalbumid);
	


	$row = q_array("SELECT * FROM ".$prefix_table."albums WHERE id=".$lastalbumid);
	if($row[cnt]==0){
		q("UPDATE ".$prefix_table."albums SET thumb=0  WHERE id=".$lastalbumid);
	}else if($row[thumb]==$picid){
		q("UPDATE ".$prefix_table."albums SET thumb=
		  (
			SELECT id 
			FROM ".$prefix_table."pics
			WHERE albumid=".$lastalbumid."
			ORDER BY id DESC
			LIMIT 1
		  )
		  WHERE id=".$lastalbumid."");
	}
		
if($newalbum){
	q("UPDATE  ".$prefix_table."albums SET thumb = IF(thumb<>0,thumb,".$picid.")  WHERE  id =".$newalbumid);
}		 
		
		
	$check=1;	

}else{
	$check=0;
}
$return_arr["check"] = $check;
echo json_encode($return_arr);

?>