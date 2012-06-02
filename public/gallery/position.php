<? 
include("../config/config.php");

		  $tb_name=$prefix_table."pics";
		  $f_name="id";

$posi=$_POST['posi'];
for($i=0;$i<=count($posi);$i++){
	$position=$i+1;
	$result= q("UPDATE $tb_name SET key_position = '$position'  WHERE  $f_name = '$posi[$i]' ");
}
if($result){
	$check=1;
	}

$return_arr["check"] = $check;
$return_arr["id"] = $posi;
echo json_encode($return_arr);
?>
