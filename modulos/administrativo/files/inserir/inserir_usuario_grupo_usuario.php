<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"
				INSERT INTO UsuarioGrupoUsuario SET
					IdLoja						='$local_IdLoja',
					IdGrupoUsuario				='$local_IdGrupoUsuario',
					Login						='$local_Login',
					LoginCriacao				='$local_Login_Sistema', 
					DataCriacao					=(concat(curdate(),' ',curtime()))";
					
			// Executa a Sql de Inserção de GrupoPermissao
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
	}
?>
