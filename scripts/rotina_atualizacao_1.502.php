<?
	include ("../files/conecta.php");

	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				Pessoa.DiaCobranca
			from
				Contrato,
				Pessoa
			where
				Contrato.IdPessoa = Pessoa.IdPessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update Contrato set DiaCobranca = '$lin[DiaCobranca]' where IdLoja='$lin[IdLoja]' and IdContrato='$lin[IdContrato]'";
		mysql_query($sql,$con);
	}
?>