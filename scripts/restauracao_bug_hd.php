<?
	include ('../files/conecta.php');

	$IdLicenca	= "2007A002";
	$IdLoja		= 1;

	$sql = "select 
				Contrato.IdContrato 
			from 
				Contrato, 
				ContratoParametro 
			where 
				Contrato.IdLoja = 1 and 
				Contrato.IdLoja = ContratoParametro.IdLoja and 
				Contrato.IdContrato = ContratoParametro.IdContrato and 
				Contrato.IdServico in (20, 22, 26) and 
				ContratoParametro.IdParametroServico = 3 and 
				ContratoParametro.Valor != ''";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		
		$IdContrato = $lin[IdContrato];

		$sql2 = "select
					Contrato.IdContrato,
					ContratoParametro.IdParametroServico,
					ContratoParametro.Valor
				from
					Contrato,
					ContratoParametro
				where
					Contrato.IdContrato = $IdContrato and
					Contrato.IdContrato = ContratoParametro.IdContrato
				order by
					IdParametroServico";
		$res2 = mysql_query($sql2,$con);

		$lin2= mysql_fetch_array($res2); // 1
		$LoginRadius = $lin2[Valor];

		$lin2= mysql_fetch_array($res2); // 2
		$SenhaRadius = $lin2[Valor];

		$lin2= mysql_fetch_array($res2); // 3 
		$AtivoRadius = $lin2[Valor];

		$lin2= mysql_fetch_array($res2); // 4
		$UserGroup = $lin2[Valor];

		$sqlInsert = "insert into radius.radcheck set 
									IdLicenca = '$IdLicenca',
									IdLoja = '$IdLoja',
									id = '$IdContrato',
									UserName = '$LoginRadius',
									Attribute = 'User-Password',
									Value = '$SenhaRadius',
									op = '==',
									Status = '$AtivoRadius'";
		mysql_query($sqlInsert,$con);
			
		$sqlInsert = "insert into radius.usergroup set
									IdLicenca = '$IdLicenca',
									IdLoja = '$IdLoja',
									id = '$IdContrato',
									UserName = '$LoginRadius',
									GroupName = '$UserGroup'";
		mysql_query($sqlInsert,$con);
	}
?>