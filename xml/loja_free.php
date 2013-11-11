<?
	include ('../files/conecta.php');
	include ('../files/funcoes.php');
	
	function get_Loja($IdLoja){
		
		global $con;
		
		$sql	=	"select DescricaoLoja from Loja where IdLoja=$IdLoja and IdStatus=1";
		$res	=	mysql_query($sql,$con);
		if($lin	=	mysql_fetch_array($res)){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$dados	.=	"\n<DescricaoLoja><![CDATA[$lin[DescricaoLoja]]]></DescricaoLoja>";
			$dados	.=	"\n</reg>";
			
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_Loja($_GET['IdLoja']);
?>
