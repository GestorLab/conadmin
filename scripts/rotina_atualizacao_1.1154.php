<?
	include ('../files/conecta.php');

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$sql = "select
				IdLoja,
				IdContaEventual,
				IdLancamentoFinanceiro
			from
				LancamentoFinanceiro
			where
				IdContrato is NULL and
				IdContaEventual is not NULL";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "select				
					IdContrato,
					IdContaEventual
				from
					ContaEventual
				where
					IdLoja = $lin[IdLoja] and
					IdContaEventual = $lin[IdContaEventual]";
		$res2 = mysql_query($sql,$con);
		if($lin2 = mysql_fetch_array($res2)){
			if($lin2[IdContrato] != ''){
				$sql	=	"update LancamentoFinanceiro set 
								 IdContrato = $lin2[IdContrato]
							 where
								 IdLoja = $lin[IdLoja] and
								 IdContaEventual = $lin2[IdContaEventual]";
				$transaction[$tr_i]	=	mysql_query($sql,$con);	
				if($transaction[$tr_i] == false){
					echo $sql;
					break;
				}
				$tr_i++;				
			}
		}			
	}

	///////////////////////////////Corrige os Lançamentos do Tipo OS ///////////////////////////////////////////////
	$sql = "select
				IdLoja,
				IdOrdemServico,
				IdLancamentoFinanceiro
			from
				LancamentoFinanceiro
			where			
				IdContrato is NULL and
				IdOrdemServico is not NULL";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "select				
					IdContrato,
					IdOrdemServico
				from
					OrdemServico
				where
					IdLoja = $lin[IdLoja] and
					IdOrdemServico = $lin[IdOrdemServico]";
		$res2 = mysql_query($sql,$con);
		if($lin2 = mysql_fetch_array($res2)){
			if($lin2[IdContrato] != ''){
				$sql	=	"update LancamentoFinanceiro set 
								 IdContrato = $lin2[IdContrato]
							 where
								 IdLoja = $lin[IdLoja] and
								 IdOrdemServico = $lin2[IdOrdemServico]";
				$transaction[$tr_i]	=	mysql_query($sql,$con);	
				if($transaction[$tr_i] == false){
					echo $sql;
					break;
				}
				$tr_i++;				
			}
		}			
	}

	for($i=0; $i<$tr_i; $i++){
		if($transaction[$i] == false){
			$transaction = false;
		}
	}
	
	if($transaction == true){
		$sql = "COMMIT;";
	}else{
		$sql = "ROLLBACK;";
	}

	echo $sql;
//	$sql = "ROLLBACK;";
	mysql_query($sql,$con);
?>