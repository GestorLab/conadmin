<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"SELECT (MAX(id)+1) id FROM radius.nas";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);	
		if($lin[id]!=NULL){
			$local_id	=	$lin[id];
		}else{
			$local_id	=	1;
		}

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

		$sql	=	"INSERT INTO radius.nas SET
						id 			= $local_id,
						nasname 	= '$local_Nasname',
						shortname 	= '$local_Shortname',
						type 		= '$local_Type',
						ports 		= $local_Ports,
						secret 		= '$local_Secret',
						server 		= $local_Server,
						community 	= $local_Community,
						description = '$local_Description'";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 1;
		}
	}
?>
