<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servico_cfop_opcoes(){
		global $con;
		global $_GET;
		
		$IdLoja		= $_SESSION['IdLoja'];
		$IdServico	= $_GET['IdServico'];
		$Limit 		= $_GET['Limit'];
		
		if($Limit != ''){
			$Limit = "limit 0,$Limit";
		}
		
		$sql = "
			SELECT
				CFOP.CFOP,
				CFOP.NaturezaOperacao
			FROM
				CFOP,
				ServicoCFOP
			WHERE
				CFOP.CFOP = ServicoCFOP.CFOP AND
				ServicoCFOP.IdLoja = '$IdLoja' AND 
				ServicoCFOP.IdServico = '$IdServico'
			GROUP BY
				CFOP.CFOP;";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
			$dados	.=	"\n<NaturezaOperacao><![CDATA[$lin[NaturezaOperacao]]]></NaturezaOperacao>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_servico_cfop_opcoes();
?>