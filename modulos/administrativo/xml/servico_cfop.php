<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servico_cfop(){
		global $con;
		global $_GET;
		
		$IdLoja	= $_SESSION['IdLoja'];
		$CFOP	= $_GET['CFOP'];
		$where	= '';
		
		if($CFOP != ''){
			$where .= " AND CFOP.CFOP = '$CFOP'";
		}
		
		$sql = "
			SELECT
				CFOP.CFOP,
				CFOP.NaturezaOperacao
			FROM
				CFOP
			WHERE
				1
				$where;";
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
	
	echo get_servico_cfop();
?>