<?
	include("../rotinas/verifica.php");
	include("../files/conecta.php");

	// Captura das variáveis
	////////////////////////////////////////////////////////////////////////////////////////
	$IdLicenca		= $_SESSION['IdLicenca'];			// Para identificar o cliente [45]
	////////////////////////////////////////////////////////////////////////////////////////	
	$IdPessoaLoja	= $_SESSION['IdPessoaLoja'];		// Para pegar o nome do cliente [100]
	$IdPessoaLoja	= explode(",",$IdPessoaLoja);
	$IdPessoaLoja	= $IdPessoaLoja[0];
	$sql			= "select Nome from Pessoa where IdPessoa = $IdPessoaLoja";
	$res			= mysql_query($sql,$con);
	$lin			= mysql_fetch_array($res);
	$NomePessoa		= $lin[Nome];
	////////////////////////////////////////////////////////////////////////////////////////
	$Dominio		= $_SERVER['SERVER_NAME'];			// Pega o domínio do sistema [255]
	////////////////////////////////////////////////////////////////////////////////////////

	// Criptografia da IdLicença
	////////////////////////////////////////////////////////////////////////////////////////
	// 0 [2]
	$IdLicenca_0 = $IdLicenca[0] - 10;
	if($IdLicenca_0 < 0){ $IdLicenca_0 = $IdLicenca_0 * -1; }
	$IdLicenca_0 = $IdLicenca_0 * 3;
	$IdLicenca_0 = $IdLicenca_0 + 8;

	// 1 [2]
	$IdLicenca_1 = $IdLicenca[1] + $IdLicenca[0] + $IdLicenca_0;

	// 2 [2]
	$IdLicenca_2 = $IdLicenca[2] - $IdLicenca_1;
	$IdLicenca_2 = $IdLicenca_2 - $IdLicenca_0;
	if($IdLicenca_2 < 0){ $IdLicenca_2 = $IdLicenca_2 * -1; }

	// 3 [2]
	$IdLicenca_3 = $IdLicenca[3] * 8 - $IdLicenca_1;

	// 4 [3]
	$IdLicenca_4 = hexdec($IdLicenca[4]);
	$IdLicenca_4 = $IdLicenca_4 + $IdLicenca_3 + $IdLicenca_2 + $IdLicenca_1 + $IdLicenca_0;

	// 5 [2]
	$IdLicenca_5 = ($IdLicenca[5] * 2 + $IdLicenca_3)/2;

	// 6 [4]
	$IdLicenca_6 = (($IdLicenca[6] + $IdLicenca_4) * 8)-5;

	// 7 [2]
	$IdLicenca_7 = ($IdLicenca[7] * $IdLicenca[7]) - 7;
	if($IdLicenca_7 < 0){ $IdLicenca_7 = $IdLicenca_7 * -1; }

	// Digito [1]
	$Digito = $IdLicenca_0+$IdLicenca_1+$IdLicenca_2+$IdLicenca_3+$IdLicenca_4+$IdLicenca_5+$IdLicenca_6+$IdLicenca_7;
	$DigitoSum = 0;
	$DigitoTam = strlen($Digito);
	for($i=0; $i<$DigitoTam; $i++){
		$DigitoSum += substr($Digito,$i,1);
	}
	$Digito = $DigitoSum;

	$DigitoSum = 0;
	$DigitoTam = strlen($Digito);
	for($i=0; $i<$DigitoTam; $i++){
		$DigitoSum += substr($Digito,$i,1);
	}
	$Digito = $DigitoSum;

	$Digito = substr($DigitoSum,strlen($DigitoSum)-1,1);

	// Junção
	$IdLicenca = $IdLicenca_0.$IdLicenca_1.$IdLicenca_2.$IdLicenca_3.$IdLicenca_4.$IdLicenca_5.$IdLicenca_6.$IdLicenca_7.$Digito;
	$IdLicenca = str_pad($IdLicenca, 45, "0", STR_PAD_LEFT);

	// Criptografia do NomePessoa
	////////////////////////////////////////////////////////////////////////////////////////
	// 0 [3]
	$NomePessoa_0 = strlen($NomePessoa);
	$NomePessoa_0 = ($NomePessoa_0 * $NomePessoa_0) - $NomePessoa_0;
	$NomePessoa_0 = str_pad($NomePessoa_0, 3, "0", STR_PAD_LEFT);

	// 1[?]
	$NomePessoa_1 = '';
	$NomePessoaTam = strlen($NomePessoa);
	for($i=0; $i<$NomePessoaTam; $i++){
		$NomePessoa_1 .= str_pad(ord(substr($NomePessoa,$i,1)), 3, "0", STR_PAD_LEFT);
	}

	// 2[?]
	$NomePessoa = $NomePessoa_0.$NomePessoa_1;

	// Digito
	$Digito = str_pad(strlen($NomePessoa), 3, "0", STR_PAD_LEFT);

	// Junção
	$NomePessoa .= $Digito;
	$NomePessoa  = str_pad($NomePessoa, 100, "0", STR_PAD_LEFT);

	// Criptografia do Dominio
	////////////////////////////////////////////////////////////////////////////////////////
	// 0 [3]
	$Dominio_0 = strlen($Dominio);
	$Dominio_0 = ($Dominio_0 * $Dominio_0) - $Dominio_0;
	$Dominio_0 = str_pad($Dominio_0, 3, "0", STR_PAD_LEFT);

	// 1[?]
	$Dominio_1 = '';
	$DominioTam = strlen($Dominio);
	for($i=0; $i<$DominioTam; $i++){
		$Dominio_1 .= str_pad(ord(substr($Dominio,$i,1)), 3, "0", STR_PAD_LEFT);
	}

	// 2[?]
	$Dominio = $Dominio_0.$Dominio_1;

	// Digito
	$Digito = str_pad(strlen($Dominio), 3, "0", STR_PAD_LEFT);

	// Junção
	$Dominio .= $Digito;
	$Dominio  = str_pad($Dominio, 255, "0", STR_PAD_LEFT);

	// Resultado
	////////////////////////////////////////////////////////////////////////////////////////
 	$Serial = $IdLicenca.$NomePessoa.$Dominio;

	$QtdChars	= 40;
	$SerialTam	= strlen($Serial);
	$i			= 0;
	while($SerialTam > $QtdChars){
		echo $SerialTemp[$i] = substr($Serial,count($SerialTemp)*$QtdChars,$QtdChars);
		echo "<br>";
		$SerialTam -= $QtdChars;
		$i++;
	}
	if($SerialTam > 0){
		echo $SerialTemp[$i] = substr($Serial,count($SerialTemp)*$QtdChars,$QtdChars);
	}
?>