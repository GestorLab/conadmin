<?
	$localModulo	= 1;
	$localOperacao	= 162;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ProtocoloGeraCodigo(){
		global $localModulo;
		global $localOperacao;
		global $con;
		global $_GET;
		
		$local_Login				= $_SESSION["Login"];
		$local_IdLoja				= $_SESSION['IdLoja'];
		$local_IdLocalAbertura		= 2;
		$local_FormaGeracaoCodigo	= (int) getCodigoInterno(59,1);
		
		if($local_FormaGeracaoCodigo != 2){
			return "false";
		}
		
		include("../files/inserir/inserir_protocolo.php");
		
		header("content-type: text/xml");
		
		$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados .= "\n<reg>";
		$dados .= "\n<IdProtocolo><![CDATA[$local_IdProtocolo]]></IdProtocolo>";
		$dados .= "\n<Erro><![CDATA[$local_Erro]]></Erro>";
		$dados .= "\n</reg>";
		
		return $dados;
	}
	
	echo get_ProtocoloGeraCodigo();
?>