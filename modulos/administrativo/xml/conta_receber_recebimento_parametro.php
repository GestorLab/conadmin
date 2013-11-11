<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_conta_receber_recebimento_parametro(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdLocalRecebimento 	= $_GET['IdLocalRecebimento'];
		$IdContaReceber		 	= $_GET['IdContaReceber'];
		
		$sql	=	"select
					     ContaReceberRecebimentoParametro.IdParametroRecebimento,
					     ContaReceberRecebimentoParametro.ValorParametro
					from 
					     ContaReceberRecebimentoParametro
                    where
					     ContaReceberRecebimentoParametro.IdLoja=$IdLoja and
					     ContaReceberRecebimentoParametro.IdLocalCobranca = $IdLocalRecebimento and
					     ContaReceberRecebimentoParametro.IdContaReceber = $IdContaReceber";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdParametroRecebimento><![CDATA[$lin[IdParametroRecebimento]]]></IdParametroRecebimento>";
			$dados	.=	"\n<ValorParametro><![CDATA[$lin[ValorParametro]]]></ValorParametro>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_conta_receber_recebimento_parametro();
?>
