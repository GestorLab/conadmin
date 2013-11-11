<?
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$DataNotaFiscalForca = $lin[DataEmissao];
	$IdNotaFiscalForca	 = $lin[IdNotaFiscal];

	$sql = "delete from NotaFiscalItem where IdNotaFiscalLayout = $lin[IdNotaFiscalLayout] and IdLoja = $IdLoja  and PeriodoApuracao = '$lin[PeriodoApuracao]' and IdNotaFiscal = $lin[IdNotaFiscal]";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	$sql = "delete from NotaFiscal where IdNotaFiscalLayout = $lin[IdNotaFiscalLayout] and IdLoja = $IdLoja  and PeriodoApuracao = '$lin[PeriodoApuracao]' and IdNotaFiscal = $lin[IdNotaFiscal]";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	$PatchLayout = getParametroSistema(6,1)."modulos/administrativo/nota_fiscal/".$lin[IdNotaFiscalLayout]."/gera_nf.php";

	include($PatchLayout);

	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true){
		$sql = "COMMIT;";
		$Return = true;
	}else{
		echo $sql = "ROLLBACK;";
		$Return = false;
	}
	mysql_query($sql,$con);
	
#	header("Location: nota_fiscal/".$lin[IdNotaFiscalLayout]."/nota_fiscal_html.php?IdLoja=$IdLoja&IdContaReceber=$IdContaReceber");
?>