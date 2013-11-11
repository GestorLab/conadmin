<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_TipoEmail(){
		
		global $con;
		global $_GET;
		
		$Limit 				 = $_GET['Limit'];
		$IdLoja				 = $_SESSION["IdLoja"];
		$IdTipoEmail	     = $_GET['IdTipoEmail'];
		$DescricaoTipoEmail  = $_GET['DescricaoTipoEmail'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdTipoEmail != ''){	
			$where .= " and IdTipoEmail=$IdTipoEmail";	
		}
		if($DescricaoTipoEmail !=''){	
			$where .= " and DescricaoTipoEmail like '$DescricaoTipoEmail%'";	
		}
		
		$sql	=	"select
						IdLoja,
						IdTipoEmail, 
						DescricaoTipoEmail, 
						DiasParaEnvio,
						EstruturaEmail, 
						AssuntoEmail
					from 
						TipoEmail
					where
						IdLoja = $IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdTipoEmail>$lin[IdTipoEmail]</IdTipoEmail>";
			$dados	.=	"\n<DescricaoTipoEmail><![CDATA[$lin[DescricaoTipoEmail]]]></DescricaoTipoEmail>";
			$dados	.=	"\n<DiasParaEnvio><![CDATA[$lin[DiasParaEnvio]]]></DiasParaEnvio>";
			$dados	.=	"\n<EstruturaEmail><![CDATA[$lin[EstruturaEmail]]]></EstruturaEmail>";
			$dados	.=	"\n<AssuntoEmail><![CDATA[$lin[AssuntoEmail]]]></AssuntoEmail>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_TipoEmail();
?>
