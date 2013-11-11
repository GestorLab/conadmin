<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sql = "select
				IdPessoa,
				IdPais,
				IdEstado,
				IdCidade,
				CEP,
				Endereco,
				Complemento,
				Numero,
				Bairro,
				Cob_NomeResponsavel,
				Cob_Endereco,
				Cob_Telefone1,
				Cob_ComplementoTelefone,
				Cob_CEP,
				Cob_Complemento,
				Cob_Numero,
				Cob_Bairro,
				Cob_IdPais,
				Cob_IdEstado,
				Cob_IdCidade,
				Cob_Email	
			from
				Pessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		if($lin[Cob_IdPais] == ''){		$lin[Cob_IdPais] = "NULL"; }
		if($lin[Cob_IdEstado] == ''){	$lin[Cob_IdEstado] = "NULL"; }
		if($lin[Cob_IdCidade] == ''){	$lin[Cob_IdCidade] = "NULL"; }

		if($lin[IdPais] == ''){		$lin[IdPais] = "NULL"; }
		if($lin[IdEstado] == ''){	$lin[IdEstado] = "NULL"; }
		if($lin[IdCidade] == ''){	$lin[IdCidade] = "NULL"; }

		$lin[Bairro] = str_replace('"', "'", $lin[Bairro]);
		$lin[Complemento] = str_replace('"', "'", $lin[Complemento]);
		$lin[Endereco] = str_replace('"', "'", $lin[Endereco]);

		$IdEnderecoDefault = 1;
		$IdEnderecoCobranca = 1;

 		$sql = "insert into PessoaEndereco (IdPessoa, IdPessoaEndereco, DescricaoEndereco, NomeResponsavelEndereco, CEP, IdPais, IdEstado, IdCidade, Endereco, Numero, Complemento, Bairro, Telefone, Celular, ComplementoTelefone, Fax, EmailEndereco ) values ( '$lin[IdPessoa]',  '1',  'Endereço Principal',  '$lin[NomeResponsavel]',  '$lin[CEP]',  $lin[IdPais],  $lin[IdEstado],  $lin[IdCidade],  \"$lin[Endereco]\",  '$lin[Numero]',  \"$lin[Complemento]\",  \"$lin[Bairro]\",  '$lin[Telefone]',  '$lin[Celular]',  '$lin[ComplementoTelefone]',  '$lin[Fax]',  '$lin[Email]')";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);

		if($local_transaction[$tr_i] == false){
			echo $sql;
			break;
		}
		$tr_i++;

		if($lin[Cob_Endereco] != ''){
			$sql = "insert into PessoaEndereco (IdPessoa, IdPessoaEndereco, DescricaoEndereco, NomeResponsavelEndereco, CEP, IdPais, IdEstado, IdCidade, Endereco, Numero, Complemento, Bairro, Telefone, Celular, ComplementoTelefone, Fax, EmailEndereco ) values ( '$lin[IdPessoa]',  '2',  'Endereço de Cobrança',  '$lin[Cob_NomeResponsavel]',  '$lin[Cob_CEP]',  $lin[Cob_IdPais], $lin[Cob_IdEstado], $lin[Cob_IdCidade],  '$lin[Cob_Endereco]',  '$lin[Cob_Numero]',  \"$lin[Cob_Complemento]\", \"$lin[Cob_Bairro]\",  '$lin[Cob_Telefone1]',  '$lin[Celular]',  '$lin[Cob_ComplementoTelefone]',  '$lin[Fax]',  '$lin[Cob_Email]')";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$IdEnderecoCobranca = 2;
		}

		$sql = "update Pessoa set IdEnderecoDefault='$IdEnderecoDefault', IdEnderecoCobrancaDefault='$IdEnderecoCobranca' where IdPessoa='$lin[IdPessoa]'";
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
	mysql_query($sql,$con);
?>