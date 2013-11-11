<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				ContaReceber.IdLoja,
				ContaReceber.IdContaReceber,
				Demonstrativo.IdPessoa,
				Pessoa.IdEnderecoDefault,
				Pessoa.IdEnderecoCobrancaDefault
			from
				ContaReceber,
				Demonstrativo,
				Pessoa
			where
				ContaReceber.IdLoja = Demonstrativo.IdLoja and
				ContaReceber.IdContaReceber = Demonstrativo.IdContaReceber and
				Demonstrativo.IdPessoa = Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$IdEndereco = $lin[IdEnderecoCobrancaDefault];

		if($IdEndereco == ''){
			$IdEndereco = $lin[IdEnderecoDefault];
		}
		
		$sql = "update ContaReceber set IdPessoa = '$lin[IdPessoa]',  IdPessoaEndereco = '$IdEndereco' where IdLoja='$lin[IdLoja]' and IdContaReceber='$lin[IdContaReceber]'";
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