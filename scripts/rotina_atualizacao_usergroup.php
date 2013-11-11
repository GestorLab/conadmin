<?
	include ("../files/conecta.php");

	$IdLicenca = "2007A002";

	$sql = "select
				Contrato.IdLoja,
				Contrato.IdContrato,
				radcheck.username,
				ServicoParametro.ValorDefault
			from
				Contrato,
				radius.radcheck,
				ServicoParametro
			where
				Contrato.IdLoja = ServicoParametro.IdLoja and
				Contrato.IdServico = ServicoParametro.IdServico and
				Contrato.IdServico in (40, 41, 42) and
				radcheck.IdLicenca = '$IdLicenca' and
				radcheck.IdLoja = Contrato.IdLoja and
				radcheck.id = Contrato.IdContrato and
				radcheck.Attribute = 'User-Password' and
				radcheck.value != '' and
				Contrato.IdStatus != 1 and
				ServicoParametro.IdParametroServico = 4";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlDeleta = "delete from radius.usergroup where IdLicenca='$IdLicenca' and IdLoja='$lin[IdLoja]' and Id='$lin[IdContrato]'";
		mysql_query($sqlDeleta,$con);

		$sqlInsere = "insert into radius.usergroup (IdLicenca, IdLoja, Id, UserName, GroupName) values ('$IdLicenca', '$lin[IdLoja]', '$lin[IdContrato]', '$lin[username]', '$lin[ValorDefault]')";
		mysql_query($sqlInsere,$con);
	}
?>