<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"
				INSERT INTO Terceiro SET 
					IdLoja						= $local_IdLoja,
					IdPessoa					= $local_IdPessoa,
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login';";
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inser��o Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inser��o Negativa
		}
	}
?>
