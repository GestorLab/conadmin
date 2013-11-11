<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				ContaReceberDataVencimento.IdLoja,
				ContaReceberDataVencimento.IdContaReceber,
				ContaReceberDataVencimento.DataVencimento,
				ContaReceberVencimento.ValorContaReceber ValorErrado,
				(ContaReceber.ValorLancamento + ContaReceber.ValorDespesas) ValorCorreto,
				(ContaReceberVencimento.ValorContaReceber + ContaReceber.ValorDespesas) ValorAtualizar
			from
				ContaReceberDataVencimento,
				ContaReceberVencimento,
				ContaReceber
			where
				ContaReceberDataVencimento.IdLoja = ContaReceberVencimento.IdLoja and
				ContaReceberDataVencimento.IdLoja = ContaReceber.IdLoja and
				ContaReceberDataVencimento.IdContaReceber = ContaReceberVencimento.IdContaReceber and
				ContaReceberDataVencimento.DataVencimento = ContaReceberVencimento.DataVencimento and
				ContaReceberVencimento.IdContaReceber = ContaReceber.IdContaReceber and
				(ContaReceber.ValorLancamento + ContaReceber.ValorDespesas) > ContaReceberVencimento.ValorContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update ContaReceberVencimento set ValorContaReceber='$lin[ValorAtualizar]' where IdLoja='$lin[IdLoja]' and IdContaReceber='$lin[IdContaReceber]' and DataVencimento='$lin[DataVencimento]'";
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