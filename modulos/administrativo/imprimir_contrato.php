<?
	$local_CDA = $_GET['cda'];

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');

	if($local_CDA != 1){
		$localModulo		=	1;
		$localOperacao		=	2;
		$localSuboperacao	=	"V";	

		include ('../../rotinas/verifica.php');

		$local_Login			= $_SESSION["Login"];
		$local_IdLoja			= $_SESSION["IdLoja"];
	}else{
		include ('../cda/rotinas/verifica.php');

		$local_Login			= $_SESSION["LoginCDA"];
		$local_IdLoja			= $_SESSION["IdLojaCDA"];
	}
	
	$local_IdContrato		= $_GET['IdContrato'];

	$sql = "select
				*
			from
				Contrato
			where
				IdLoja = $local_IdLoja and
				IdContrato = $local_IdContrato";
	$res = mysql_query($sql,$con);
	$Contrato = mysql_fetch_array($res);

	$Campos = array_keys($Contrato);

	for($i = 0; $i < count($Campos); $i++){
		if($i%2 != 0){
			$Var[$Campos[$i]] = $Contrato[$Campos[$i]];
		}
	}

	$sql = "select
				*
			from
				Servico
			where
				IdLoja = $local_IdLoja and
				IdServico = $Contrato[IdServico]";
	$res = mysql_query($sql,$con);
	$Servico = mysql_fetch_array($res);

	$Campos = array_keys($Servico);

	for($i = 0; $i < count($Campos); $i++){
		if($i%2 != 0){
			$Var[$Campos[$i]] = $Servico[$Campos[$i]];
		}
	}

	$sql = "select
				IdParametroServico,
				Valor
			from
				ContratoParametro
			where
				IdLoja = $local_IdLoja and
				IdContrato = $local_IdContrato";
	$res = mysql_query($sql,$con);
	while($Parametro = mysql_fetch_array($res)){
		$Var["Parametro".$Parametro[IdParametroServico]] = $Parametro[Valor];
	}


	// Resultado Final
	$Campos = array_keys($Var);
	for($i = 0; $i < count($Campos); $i++){
		$Campos[$i]." = ".$Var[$Campos[$i]]."<br>";
	}

	// Distrato do contrato
	$file	= file($Var[UrlContratoImpresso]);	
	
	$Campos = array_keys($Var);

	for($i = 0; $i < count($file); $i++){
		// Resultado Final
		for($ii = 0; $ii < count($Campos); $ii++){
			$file[$i] = str_replace('$'.$Campos[$ii], $Var[$Campos[$ii]], $file[$i]);
			$file[$i] = str_replace('$cda', $local_CDA, $file[$i]);
		}
		echo $file[$i]."\n";
	}
?>