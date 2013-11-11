<?
	switch(getCodigoInterno(57,1)){
		case 1:
			include($Path."modulos/administrativo/local_cobranca/funcao_demonstrativo_carne_pdf.php");
			break;
		case 2:
			include($Path."modulos/administrativo/local_cobranca/funcao_demonstrativo_carne_pdf_personalizado.php");
			break;
	}
?>