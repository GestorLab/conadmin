<?
	$IdLocalCobrancaLayout = 31;
	$EndFile 	= "modulos/administrativo/local_cobranca/31/pdf_all.php";
	$Vars 		= $_SERVER['argv'];
	$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile)-1);

	$Background = $Vars[3];

	if($Background == 's'){
		set_time_limit(0);

		require($Path."classes/fpdf/class.fpdf.php");
		require($Path."modulos/administrativo/local_cobranca/31/include/class.boleto.php");
		require($Path."modulos/administrativo/local_cobranca/31/include/funcoes-itau.php");

		include($Path."modulos/administrativo/local_cobranca/pdf_all_query.php");
	}else{
		
		$Path = "../../../../";

		require("../../../../classes/fpdf/class.fpdf.php");
		require("include/class.boleto.php");
		require("include/funcoes-itau.php");	
	
		include("../pdf_all_query.php");
	}
?>
