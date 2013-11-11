<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"
				INSERT INTO Carteira SET 
					IdLoja						= $local_IdLoja,
					IdAgenteAutorizado			= $local_IdAgenteAutorizado,
					IdCarteira					= $local_IdCarteira,
					IdStatus					= $local_IdStatus,
					Restringir					= $local_Restringir,
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
