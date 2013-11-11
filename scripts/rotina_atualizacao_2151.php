<?php
	set_time_limit(0);	
	include ('../files/conecta.php');

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	// Loja
	$sqlLoja = "select
					IdLoja
				from
					Loja";
	$resLoja = mysql_query($sqlLoja,$con);
	while($linLoja = mysql_fetch_array($resLoja)){

		$sql = "select
					count(*) Qtd
				from
					LocalCobrancaParametro
				where
					IdLoja = $linLoja[IdLoja] and
					IdLocalCobrancaLayout = 16 and
					IdLocalCobrancaParametro = 'LocalImpressaoBoleto'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		if($lin[Qtd] == 0){
			
			$sql = "select
						IdLoja,
						IdLocalCobranca
					from
						LocalCobrancaParametro
					where
						IdLoja = $linLoja[IdLoja] and
						IdLocalCobrancaLayout = 16 and
						IdLocalCobrancaParametro = 'Aceite'";
			$res2 = mysql_query($sql,$con);
			while($lin2 = mysql_fetch_array($res2)){				
				$sql = "insert into LocalCobrancaParametro set
							IdLoja							= $lin2[IdLoja],
							IdLocalCobranca					= $lin2[IdLocalCobranca],
							IdLocalCobrancaLayout			= 16,
							IdLocalCobrancaParametro		= 'LocalImpressaoBoleto',
							ValorLocalCobrancaParametro		= '1',
							LogParametro					= NULL";
				$transaction[$tr_i]	=	mysql_query($sql,$con);	
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
echo		$sql = "ROLLBACK;";
	}
	mysql_query($sql,$con);
?>