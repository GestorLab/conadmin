<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de GrupoPermissao
		$sql	=	"select (max(IdGrupoPermissao)+1) IdGrupoPermissao from GrupoPermissao";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdGrupoPermissao]!=NULL){
			$local_IdGrupoPermissao	=	$lin[IdGrupoPermissao];
		}else{
			$local_IdGrupoPermissao	=	1;
		}
		
		if($local_LimiteVisualizacao == ""){
			$local_LimiteVisualizacao = 'NULL';	
		}
		
		$sql	=	"
				INSERT INTO GrupoPermissao SET
					IdGrupoPermissao			='$local_IdGrupoPermissao',
					DescricaoGrupoPermissao		='$local_DescricaoGrupoPermissao',
					LimiteVisualizacao			= $local_LimiteVisualizacao,
					IpAcesso					='$local_IpAcesso',
					LoginCriacao				='$local_Login', 
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
