<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Aliquota(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdServico	 			= $_GET['IdServico'];
		
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdServico != ''){		
			$where .= " and ServicoAliquota.IdServico=$IdServico";
		}
		
		
		$sql	=	"select		
						distinct		
						ServicoAliquota.IdPais,
						ServicoAliquota.IdEstado,
						ServicoAliquota.IdAliquotaTipo,
						ServicoAliquota.Aliquota,
						ServicoAliquota.FatorBaseCalculoAliquota
					from 
						Loja, 
						ServicoAliquota						
					where
						ServicoAliquota.IdLoja = $IdLoja 				
						$where order by ServicoAliquota.IdEstado $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){		
			$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
			$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
			$dados	.=	"\n<IdAliquotaTipo><![CDATA[$lin[IdAliquotaTipo]]]></IdAliquotaTipo>";
			$dados	.=	"\n<AliquotaICMS><![CDATA[$lin[Aliquota]]]></AliquotaICMS>";
			$dados	.=	"\n<FatorBaseCalculoAliquotaICMS><![CDATA[$lin[FatorBaseCalculoAliquota]]]></FatorBaseCalculoAliquotaICMS>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Aliquota();
?>
