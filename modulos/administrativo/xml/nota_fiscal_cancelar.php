<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_cancelar(){
		
		global $con;
		global $_GET;
		
		$IdLoja = $_SESSION["IdLoja"];	
		$IdContaReceber = $_GET['IdContaReceber'];
		$IdNotaFiscal	= $_GET['IdNotaFiscal'];
		
		return cancela_nf($IdLoja, $IdContaReceber, $IdNotaFiscal);
	}
	echo get_nota_fiscal_cancelar();
?>
