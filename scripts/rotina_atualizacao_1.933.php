<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				LocalCobranca.IdLoja,
				LocalCobrancaParametro.IdLocalCobranca,
				LocalCobranca.IdLocalCobrancaLayout
			from
				LocalCobrancaParametro,
				LocalCobranca
			where
				LocalCobranca.IdLoja = LocalCobrancaParametro.IdLoja and
				LocalCobranca.IdLocalCobranca = LocalCobrancaParametro.IdLocalCobranca
			group by
				LocalCobranca.IdLoja,
				LocalCobrancaParametro.IdLocalCobranca,
				LocalCobranca.IdLocalCobrancaLayout";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		
		$sql = "update LocalCobrancaParametro set  IdLocalCobrancaLayout='$lin[IdLocalCobrancaLayout]' where IdLoja=$lin[IdLoja] and IdLocalCobranca=$lin[IdLocalCobranca]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

	}
		
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true || $tr_i==0){
		$sql = "COMMIT;";
		$local_Erro = 51;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 50;
	}

	echo $sql;
	mysql_query($sql,$con);
?>