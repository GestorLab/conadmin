<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de Fabricante
		$sql	=	"select (max(IdFabricante)+1) IdFabricante from Fabricante where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdFabricante]!=NULL){
			$local_IdFabricante	=	$lin[IdFabricante];
		}else{
			$local_IdFabricante	=	1;
		}
		$sql	=	"
				INSERT INTO Fabricante SET
					IdLoja					='$local_IdLoja',
					IdFabricante			='$local_IdFabricante',
					DescricaoFabricante		='$local_DescricaoFabricante',
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginAlteracao			= NULL,
					DataAlteracao			= NULL";
					
		// Executa a Sql de Inser��o de Fabricante
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inser��o Positiva
		}else{
			// Muda a a��o para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inser��o Negativa
		}
	}
?>
