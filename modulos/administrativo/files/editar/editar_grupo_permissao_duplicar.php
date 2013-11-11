<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql2	=	"select IdModulo,IdOperacao,IdSubOperacao from GrupoPermissaoSubOperacao where IdLoja = $local_IdLoja and IdGrupoPermissao = $local_IdGrupoPermissao;\n";
		$res2	=	mysql_query($sql2,$con);
		while($lin2	=	mysql_fetch_array($res2)){
			$sql3	=	"select * from GrupoPermissaoSubOperacao where IdLoja=$local_IdLojaDestino and IdGrupoPermissao = $local_IdGrupoPermissao and IdModulo = $lin2[IdModulo] and IdOperacao = $lin2[IdOperacao] and IdSubOperacao = '$lin2[IdSubOperacao]'\n";
			$res3	=	mysql_query($sql3,$con);
			
			if(mysql_num_rows($res3) < 1){
				$sql	=	"INSERT INTO 
								GrupoPermissaoSubOperacao
									(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, LoginCriacao, DataCriacao) VALUES
									($local_IdGrupoPermissao, $local_IdLojaDestino, $lin2[IdModulo], $lin2[IdOperacao], '$lin2[IdSubOperacao]', '$local_Login', concat(curdate(),' ', curtime()));\n\n";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
		
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;			
			}
		}
		
		if($local_transaction == true || $tr_i == 0){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Alteração Positiva
			mysql_query($sql,$con);
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteração Negativa
			mysql_query($sql,$con);
		}
	}
?>
