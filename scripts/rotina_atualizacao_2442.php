<?
	include("../files/conecta.php");

	$sql = "SELECT
					Contrato.IdLoja,
					Contrato.IdContrato,
					PessoaContaDebito.IdContaDebito
				FROM
					LocalCobranca,
					Contrato,
					PessoaContaDebito
				WHERE
					LocalCobranca.IdTipoLocalCobranca = 3 AND
					LocalCobranca.IdLoja = Contrato.IdLoja AND
					LocalCobranca.IdLocalCobranca = Contrato.IdLocalCobranca AND
					Contrato.IdPessoa = PessoaContaDebito.IdPessoa AND
					Contrato.IdLocalCobranca = PessoaContaDebito.IdLocalCobranca AND
					Contrato.IdContaDebito IS NULL";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql2 = "UPDATE Contrato SET 
					IdContaDebito = $lin[IdContaDebito] 
				WHERE 
					IdLoja = $lin[IdLoja] AND
					IdContrato = $lin[IdContrato] AND
					IdContaDebito IS NULL";
		mysql_query($sql2,$con);
	}
?>