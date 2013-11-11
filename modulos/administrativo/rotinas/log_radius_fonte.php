<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');

	$IdLoja			= $_GET['IdLoja'];
	$Ano			= $_GET['Ano'];
	$Login			= strtolower($_GET['Login']);
	$Qtd			= $_GET['Qtd'];

	$sql = "select
				ValorCodigoInterno
			from
				CodigoInterno
			where
				IdGrupoCodigoInterno = 10000 and
				IdLoja = $IdLoja and
				IdCodigoInterno = 20";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$Patch = $lin[ValorCodigoInterno];

	if($Qtd  == ''){
		$Qtd = 25;
	}

	if($Ano != '' && $Ano != date("Y")){
		$Patch = explode(".",$Patch);
		$Patch = $Patch[0]."_$Ano.".$Patch[1];		
	}

	if($Login != ''){
		$comando   = "/usr/bin/grep $Login $Patch | /usr/bin/tail -n $Qtd";
    }else{
		$comando   = "/usr/bin/tail -n $Qtd $Patch";
    }

	system($comando);
?>