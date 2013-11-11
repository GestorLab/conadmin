<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				IdLoja,
				IdContrato,
				IdPessoa
			from
				Contrato";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "select
					IdEnderecoDefault,
					IdEnderecoCobrancaDefault
				from
					Pessoa
				where
					IdPessoa = $lin[IdPessoa]";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);

		$sql = "update Contrato set IdPessoaEndereco='$lin2[IdEnderecoDefault]', IdPessoaEnderecoCobranca='$lin2[IdEnderecoCobrancaDefault]' where IdLoja='$lin[IdLoja]' and IdContrato='$lin[IdContrato]'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

	}

	$sql = "select
				IdLoja,
				IdContaEventual,
				IdPessoa
			from
				ContaEventual";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "select
					IdEnderecoCobrancaDefault
				from
					Pessoa
				where
					IdPessoa = $lin[IdPessoa]";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);

		$sql = "update ContaEventual set IdPessoaEnderecoCobranca='$lin2[IdEnderecoCobrancaDefault]' where IdLoja='$lin[IdLoja]' and IdContaEventual='$lin[IdContaEventual]'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

	}

	$sql = "select
				IdLoja,
				IdOrdemServico,
				IdPessoa
			from
				OrdemServico
			where
				IdPessoa != ''";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "select
					IdEnderecoDefault,
					IdEnderecoCobrancaDefault
				from
					Pessoa
				where
					IdPessoa = $lin[IdPessoa]";
		$res2 = mysql_query($sql,$con);
		$lin2 = mysql_fetch_array($res2);

		$sql = "update OrdemServico set IdPessoaEndereco='$lin2[IdEnderecoDefault]', IdPessoaEnderecoCobranca='$lin2[IdEnderecoCobrancaDefault]' where IdLoja='$lin[IdLoja]' and IdOrdemServico='$lin[IdOrdemServico]'";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

	}
		
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true){
		$sql = "COMMIT;";
		$local_Erro = 51;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 50;
	}

	echo $sql;
	mysql_query($sql,$con);
?>