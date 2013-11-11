<?
	$localModulo		=	1;
	$localOperacao		=	25;
	
	$local_IdLoja 		= 	$_SESSION["IdLoja"];
	$local_IdServico	=	$_GET['IdServico'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql2	=	"select UrlContratoImpresso from Servico where IdLoja = $local_IdLoja and IdServico = $local_IdServico;";
		$res2	=	mysql_query($sql2,$con);
		$lin2	=	mysql_fetch_array($res2);
		if($lin2[UrlContratoImpresso]!=''){
			$local_UrlContratoImpresso	=	$lin2[UrlContratoImpresso];
		}

		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
		
		$sql	=	"DELETE FROM ServicoNotaFiscalLayoutParametro WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoAliquota WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoAgendamento WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoPeriodicidade WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoAgrupado WHERE IdLoja = $local_IdLoja and (IdServico=$local_IdServico or IdServicoAgrupador=$local_IdServico);";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoParametro WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
	
		$sql	=	"DELETE FROM ServicoValor WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
	
		$sql	=	"DELETE FROM ServicoMascaraVigencia WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
	
		$sql	=	"DELETE FROM ServicoCFOP WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
	
		$sql	=	"DELETE FROM ServicoTerceiro WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		$sql	=	"DELETE FROM ServicoMonitor WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		/*
		 * Deleta os grupos device da tabela ServicoGrupoDevice referente ao servico excluido
		 */
		$sql	=	"DELETE FROM ServicoGrupoDevice WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql	=	"DELETE FROM Servico WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;	
			}
		}
		
		if($local_transaction == true){
			if($local_UrlContratoImpresso!=''){
				@unlink("../../contrato/$local_UrlContratoImpresso");
			}
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
