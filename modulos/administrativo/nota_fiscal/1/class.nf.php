<?
class NF extends FPDF
{
	function Cabecalho($IdLoja, $con){
		global $Path;
		include("../../local_cobranca/funcao_cabecalho_pdf.php");
	}
	
	function NotaFiscal($IdLoja, $IdContaReceber, $con){
		global $Path;
		include($Path."modulos/administrativo/nota_fiscal/1/funcao_demonstrativo_pdf.php");
	}
}
?>