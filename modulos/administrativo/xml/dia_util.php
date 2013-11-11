<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_DiaUtil(){
		global $con;
		global $_GET;
		
		$Data = $_GET['Data'];
		$Data = dataConv($Data, 'd/m/Y', 'Y-m-d');
		$Data = dia_util($Data);
		
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		$dados	.=	"\n<Data><![CDATA[$Data]]></Data>";
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	
	echo get_DiaUtil();
?>