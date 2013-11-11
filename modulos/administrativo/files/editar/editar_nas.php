<?
 	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_Ports == ''){
			$local_Ports = "NULL";
		}
		if($local_Server == ''){
			$local_Server = "NULL";
		}else{
			$local_Server = "'$local_Server'";
		}
		if($local_Community == ''){
			$local_Community = "NULL";
		}else{
			$local_Community = "'$local_Community'";
		}
		$sql	=	"UPDATE radius.nas
						SET 
							id 			= $local_id,
							nasname 	= '$local_Nasname',
							shortname 	= '$local_Shortname',
							type 		= '$local_Type',
							ports 		= $local_Ports,
							secret 		= '$local_Secret',
							server 		= $local_Server,
							community 	= $local_Community,
							description = '$local_Description'
						WHERE id = $local_id";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
