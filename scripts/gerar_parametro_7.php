<?
	include("../files/conecta.php");

	$sql = "delete from ContratoParametro where  IdServico in (40,41) and IdParametroServico = 7";
	mysql_query($sql,$con);

	$sql = "select 
				IdLoja,
				id,
				UserName
			from 
				radius.usergroup 
			where
				IdLicenca = '2007A002' and
				IdLoja = 1 and
				UserName like '%@con%' and
				id < 4923";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$UserNameTemp = explode("@",$lin[UserName]);

		$UserNameTemp = $UserNameTemp[0];

		$sqlServico = "select 
							IdServico
						from
							Contrato
						where
							IdLoja = $lin[IdLoja] and 
							IdContrato = $lin[id]";
		$resServico = mysql_query($sqlServico,$con);
		if($linServico = mysql_fetch_array($resServico)){
			$sqlExec = "insert into ContratoParametro (IdLoja,IdContrato,IdParametroServico,IdServico,Valor) values ('$lin[IdLoja]','$lin[id]','7','$linServico[IdServico]','$UserNameTemp')";
			mysql_query($sqlExec,$con);
		}
	}
?>