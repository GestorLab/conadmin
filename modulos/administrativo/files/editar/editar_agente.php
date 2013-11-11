<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		if($local_IdGrupoPessoa == "") 		$local_IdGrupoPessoa	='NULL';
		if($local_IdLocalCobranca == "") 	$local_IdLocalCobranca	='NULL';
		
		$sql	=	"UPDATE AgenteAutorizado SET 
							IdStatus				='$local_IdStatus',
							IdGrupoPessoa			= $local_IdGrupoPessoa,
							Restringir				= '$local_Restringir',
						IdLocalCobranca				= $local_IdLocalCobranca,
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdAgenteAutorizado		= '$local_IdAgenteAutorizado'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
