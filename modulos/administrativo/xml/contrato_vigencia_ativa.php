<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContratoVigenciaAtiva(){
		global $con;
		global $_GET;
	
		$IdLoja					= $_SESSION["IdLoja"];
		$IdContrato	 			= $_GET['IdContrato'];
		
		$sql = "select						
						ContratoVigenciaAtiva.Valor,
						ContratoVigenciaAtiva.ValorDesconto,
						ContratoVigenciaAtiva.ValorRepasseTerceiro,
						ContratoVigenciaAtiva.IdTipoDesconto,
						ContratoVigenciaAtiva.LimiteDesconto								
					from 
						ContratoVigenciaAtiva
					where
						ContratoVigenciaAtiva.IdLoja = $IdLoja and 
						ContratoVigenciaAtiva.IdContrato = $IdContrato					
					";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[Valor]!= ''){
					if($lin[Valor]!= '' && $lin[ValorDesconto]!=''){
						$valorTotal = $lin[Valor] - $lin[ValorDesconto];
					}
				
					$TipoDesconto = getParametroSistema(73,$lin[IdTipoDesconto]);
			
					$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
					$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
					$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
					$dados	.=	"\n<ValorTotal><![CDATA[$valorTotal]]></ValorTotal>";
					$dados	.=	"\n<IdTipoDesconto><![CDATA[$lin[IdTipoDesconto]]]></IdTipoDesconto>";
					$dados	.=	"\n<TipoDesconto><![CDATA[$TipoDesconto]]></TipoDesconto>";
					$dados	.=	"\n<LimiteDesconto><![CDATA[$lin[LimiteDesconto]]]></LimiteDesconto>";		
				}else{
					return "false";
				}
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	
	echo get_ContratoVigenciaAtiva();
?>