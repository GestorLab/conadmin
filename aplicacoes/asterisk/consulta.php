<?
	include("../../files/conecta.php");

	$CPF_CNPJ = $_GET[CPF_CNPJ];

	$sql = "SELECT
				Pessoa.IdPessoa
			FROM
				Pessoa,
				Contrato
			WHERE
				(
					REPLACE(REPLACE(REPLACE(Pessoa.CPF_CNPJ,'.',''),'/',''),'-','') = '$CPF_CNPJ' OR
					Pessoa.CPF_CNPJ = '$CPF_CNPJ'
				) AND
				Pessoa.IdPessoa = Contrato.IdPessoa AND
				Contrato.IdStatus >= 200";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		echo $lin[IdPessoa];
	}
?>