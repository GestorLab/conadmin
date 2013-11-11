<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_cfop_servico(){
		global $con;
		global $_GET;
		
		$IdLoja 	= $_SESSION["IdLoja"];
		$IdServico	= $_GET['IdServico'];
		
		$sql = "
			SELECT 
				CFOP.CFOP,
				CFOP.NaturezaOperacao
			FROM
				CFOP,
				ServicoCFOP
			WHERE
				CFOP.CFOP = ServicoCFOP.CFOP AND
				ServicoCFOP.IdLoja = $IdLoja AND
				ServicoCFOP.IdServico = $IdServico;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
				$dados	.=	"\n<NaturezaOperacao><![CDATA[$lin[NaturezaOperacao]]]></NaturezaOperacao>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_cfop_servico();
?>