<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				IdLoja,
				IdServico,
				IdTerceiro
			from
				Servico
			where
				IdTerceiro != ''";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "update Contrato set IdTerceiro = $lin[IdTerceiro] where IdLoja = $lin[IdLoja] and IdLoja = $lin[IdServico]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

		$sql = "insert into ServicoTerceiro set IdLoja = $lin[IdLoja], IdServico = $lin[IdServico], IdPessoa = $lin[IdTerceiro]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}

	$sql = "select
				IdLoja,
				IdServico,
				ValorRepasseTerceiro,
				PercentualRepasseTerceiro,
				PercentualRepasseTerceiroOutros
			from
				ServicoValor";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "update ServicoTerceiro set 
					ValorRepasseTerceiro = '$lin[ValorRepasseTerceiro]',
					PercentualRepasseTerceiro = '$lin[PercentualRepasseTerceiro]',
					PercentualRepasseTerceiroOutros = '$lin[PercentualRepasseTerceiroOutros]'
				where 
					IdLoja = $lin[IdLoja] and 
					IdLoja = $lin[IdServico]";
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
	}else{
		$sql = "ROLLBACK;";
	}
	echo $sql;
	mysql_query($sql,$con);
?>