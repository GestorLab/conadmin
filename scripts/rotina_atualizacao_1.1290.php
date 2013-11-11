<?
	include ("../files/conecta.php");
	
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$sql = "select IdLoja from Loja";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql2 = "select
					IdContrato,
					DataTermino,
					DataCriacao,
					DataAlteracao,
					IdStatus
				from
					Contrato
				where
					IdLoja = $lin[IdLoja] and
					IdContrato not in (select
					IdContrato
				from
					ContratoStatus
				where
					IdLoja = $lin[IdLoja]
				group by
					IdContrato)";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){

			if($lin2[DataTermino] != ''){
				$DataAlteracao = $lin2[DataTermino];
			}else{
				if($lin2[DataAlteracao] != ''){
					$DataAlteracao = $lin2[DataAlteracao];
				}else{
					$DataAlteracao = $lin2[DataCriacao];
				}
			}

			$sql = "insert into ContratoStatus set 
						IdLoja = $lin[IdLoja],
						IdContrato = $lin2[IdContrato],
						IdMudancaStatus = 1,
						IdStatusAntigo = $lin2[IdStatus],
						IdStatus = $lin2[IdStatus],
						DataAlteracao = '$DataAlteracao'";
			$transaction[$tr_i]	=	mysql_query($sql,$con);	
			$tr_i++;	
		}
	}

	for($i=0; $i<$tr_i; $i++){
		if($transaction[$i] == false){
			$transaction = false;
		}
	}
	
	if($transaction == true || $tr_i == 0){
		$sql = "COMMIT;";
	}else{
		echo $sql = "ROLLBACK;";
	}
	mysql_query($sql,$con);
?>