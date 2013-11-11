<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
	
		if($local_IdGrupoPessoa == "") 		$local_IdGrupoPessoa	='NULL';
		if($local_IdLocalCobranca == "") 	$local_IdLocalCobranca	='NULL';
		
		$sql	=	"
				INSERT INTO AgenteAutorizado SET 
					IdLoja						= $local_IdLoja,
					IdAgenteAutorizado			= $local_IdAgenteAutorizado,
					IdGrupoPessoa				= $local_IdGrupoPessoa,
					Restringir					= '$local_Restringir',
					IdLocalCobranca				= $local_IdLocalCobranca,
					IdStatus					= '$local_IdStatus',
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login';";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>

