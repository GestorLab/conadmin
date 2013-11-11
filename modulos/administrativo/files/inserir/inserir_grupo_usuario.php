<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de GrupoPermissao
		$sql	=	"select (max(IdGrupoUsuario)+1) IdGrupoUsuario from GrupoUsuario where IdLoja='$local_IdLoja'";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdGrupoUsuario]!=NULL){
			$local_IdGrupoUsuario	=	$lin[IdGrupoUsuario];
		}else{
			$local_IdGrupoUsuario	=	1;
		}
		$sql	=	"
				INSERT INTO GrupoUsuario SET
					IdLoja					='$local_IdLoja',
					IdGrupoUsuario			='$local_IdGrupoUsuario',
					DescricaoGrupoUsuario	='$local_DescricaoGrupoUsuario',
					OrdemServico			='$local_OrdemServico',
					LoginCriacao			='$local_Login', 
					LoginSupervisor			='$local_LoginSupervisor', 
					DataCriacao				=(concat(curdate(),' ',curtime()))";
					
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
