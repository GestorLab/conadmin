<?
	include("../files/conecta.php");
	include("../files/funcoes.php");

	$Vars = Vars();
 	$Vars[DataLicenca] = dataConv($Vars[DataLicenca], 'Ymd', 'Y-m-d');
	$Vars[DataHoje] = date("Y-m-d");

	if($Vars[DataLicenca] != $Vars[DataHoje]){
		$nDiasIntervalo = nDiasIntervalo($Vars[DataLicenca],$Vars[DataHoje]);
		$nDiasIntervalo--;
		if($nDiasIntervalo < 0){
			$nDiasIntervalo = $nDiasIntervalo * (-1);
		}
	 	$Vars[DiasLicenca] -= $nDiasIntervalo;
		AtualizaConfig($Vars[IdLicenca], $Vars[TipoLicenca], $Vars[DiasLicenca]);

		if($Vars[DiasLicenca] <= 8){
			$KeyCode	= KeyCode($Vars[IdLicenca],1);

			$File		= @file("http://intranet.cntsistemas.com.br/licenca/licenca.php?KeyCode=$KeyCode");
			$KeyLicenca = end($File);
			KeyProcess($KeyCode, $KeyLicenca);
		}
	}
?>