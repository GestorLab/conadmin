<?
	if($Reaviso != 2){			$Reaviso		= true;		}else{			$Reaviso			= false;	}
	if($Destinatario != 2){		$Destinatario	= true;		}else{			$Destinatario		= false;	}

	$sql = "select
				IdNotaFiscalLayout
			from
				NotaFiscal
			where
				Idloja = $IdLoja and
				IdContaReceber = $IdContaReceber and
				IdStatus = 1";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){

		if($Path != ''){
			include($Path."modulos/administrativo/nota_fiscal/$lin[IdNotaFiscalLayout]/funcao_demonstrativo_pdf.php");
		}else{
			include("../../nota_fiscal/$lin[IdNotaFiscalLayout]/funcao_demonstrativo_pdf.php");
		}
	}else{
		if($Path != ''){
			include($Path."modulos/administrativo/local_cobranca/47/funcao_demonstrativo_pdf_simples.php");
		}else{
			include("funcao_demonstrativo_pdf_simples.php");
		}
	}
?>