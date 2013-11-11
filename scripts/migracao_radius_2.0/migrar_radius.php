<?
	set_time_limit(0);
	ini_set("memory_limit","2048M");
	
	echo date("Y-m-d H:i:s")."\n";	

	include ('../../files/conecta.php');
	
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;
	
	$IdLoja = 1;

	include("radius_nas.php");
	include("radius_radacct.php");
	include("radius_radcheck.php");
	include("radius_radgroupcheck.php");
	include("radius_radgroupreply.php");
	include("radius_radippool.php");
	include("radius_radpostauth.php");
	include("radius_radusergroup.php");		
	include("radius_radreply.php");		

	for($i=0; $i<$tr_i; $i++){
		if($transaction[$i] == false){
			$transaction = false;
		}
	}
	
	if($transaction == true){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}

	echo $sql;
	mysql_query($sql,$con);
	echo "\n".date("Y-m-d H:i:s")."\n";	
?>