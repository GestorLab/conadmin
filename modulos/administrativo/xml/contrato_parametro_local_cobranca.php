<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Contrato_Parametro_LocalCobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 								= $_GET['Limit'];
		$IdLoja								= $_SESSION["IdLoja"];
		$IdLocalCobranca					= $_GET['IdLocalCobranca'];
		$IdContrato	 						= $_GET['IdContrato'];
		$IdLocalCobrancaParametroContrato 	= $_GET['IdLocalCobrancaParametroContrato'];
		$IdStatus			 				= $_GET['IdStatus'];
		$Visivel			 				= $_GET['Visivel'];
		$VisivelOS			 				= $_GET['VisivelOS'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
	/*	if($IdServico != ''){		
			$where .= " and ContratoParametro.IdServico=$IdServico";
		}
	*/	if($IdContrato != '' && $IdLocalCobranca == ''){		
			$sql2	=	"select IdLocalCobranca from Contrato where IdLoja = $IdLoja and IdLocalCobranca = $IdLocalCobranca";
			$res2	=	mysql_query($sql2,$con);
			$lin2	=	mysql_fetch_array($res2);
			
			$IdLocalCobranca	=	$lin2[IdLocalCobranca];
		}
		if($IdLocalCobrancaParametroContrato != ''){		
			$where .= " and ContratoParametroLocalCobranca.IdLocalCobrancaParametroContrato=$IdLocalCobrancaParametroContrato";
		}
		if($IdContrato != ''){		
		//	$where .= " and ContratoParametroLocalCobranca.IdContrato=$IdContrato";
		}
		if($IdStatus != ''){		
			$where .= " and LocalCobrancaParametroContrato.IdStatus=$IdStatus";
		}
		if($Visivel != ''){		
			$where .= " and LocalCobrancaParametroContrato.Visivel=$Visivel";
		}
		if($VisivelOS != ''){		
			$where .= " and LocalCobrancaParametroContrato.VisivelOS=$VisivelOS";
		}
		
		$sql	=	"select
					    LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato,
					    LocalCobrancaParametroContrato.DescricaoParametroContrato,
					    LocalCobrancaParametroContrato.ValorDefault,
					    ContratoParametroLocalCobranca.Valor,
					    LocalCobrancaParametroContrato.Obrigatorio,
					    LocalCobrancaParametroContrato.Obs,
					    LocalCobrancaParametroContrato.RotinaCalculo,
					    LocalCobrancaParametroContrato.Calculavel,
					    LocalCobrancaParametroContrato.Editavel,
					    LocalCobrancaParametroContrato.IdTipoParametro,
					    LocalCobrancaParametroContrato.IdMascaraCampo,
					    LocalCobrancaParametroContrato.OpcaoValor,
					    LocalCobrancaParametroContrato.Visivel,
					    LocalCobrancaParametroContrato.VisivelOS
					from
					    LocalCobrancaParametroContrato left join (select * from ContratoParametroLocalCobranca where ContratoParametroLocalCobranca.IdLoja = $IdLoja and ContratoParametroLocalCobranca.IdContrato=$IdContrato and ContratoParametroLocalCobranca.IdLocalCobranca=$IdLocalCobranca) ContratoParametroLocalCobranca on (LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato = ContratoParametroLocalCobranca.IdLocalCobrancaParametroContrato)
					where
						LocalCobrancaParametroContrato.IdLoja = $IdLoja and
					    LocalCobrancaParametroContrato.IdLocalCobranca = $IdLocalCobranca $where
                    order by IdLocalCobrancaParametroContrato ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[Calculavel] == 1){
				if($lin[RotinaCalculo] != ''){
					$lin[RotinaCalculo] = str_replace('$IdContrato',$IdContrato,$lin[RotinaCalculo]);
					$lin[Valor] 		= end(file($lin[RotinaCalculo]));
				}
			}
			
			$dados	.=	"\n<IdLocalCobrancaParametroContrato>$lin[IdLocalCobrancaParametroContrato]</IdLocalCobrancaParametroContrato>";
			$dados	.=	"\n<DescricaoParametroContrato><![CDATA[$lin[DescricaoParametroContrato]]]></DescricaoParametroContrato>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdTipoParametro><![CDATA[$lin[IdTipoParametro]]]></IdTipoParametro>";
			$dados	.=	"\n<IdMascaraCampo><![CDATA[$lin[IdMascaraCampo]]]></IdMascaraCampo>";
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
			$dados	.=	"\n<Visivel><![CDATA[$lin[Visivel]]]></Visivel>";
			$dados	.=	"\n<VisivelOS><![CDATA[$lin[VisivelOS]]]></VisivelOS>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Contrato_Parametro_LocalCobranca();
?>