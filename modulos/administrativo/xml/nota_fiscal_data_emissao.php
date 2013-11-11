<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_NotaFiscalDataEmissao(){
		
		global $con;
		global $_GET;
		
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdLocalCobranca	 	= $_GET['IdLocalCobranca'];

		$DataNotaFiscal = dataUltimaNF($local_IdLoja, $IdLocalCobranca);
		
		if($DataNotaFiscal == ""){
			return "false";
		}
		
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";		
		$dados	.=	"\n<DataNotaFiscal><![CDATA[$DataNotaFiscal]]></DataNotaFiscal>";		
		$dados	.=	"\n</reg>";
		return $dados;
	}
	echo get_NotaFiscalDataEmissao();
?>