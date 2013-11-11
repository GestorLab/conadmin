<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_LocalCobrancaParametroContrato(){
		
		global $con;
		global $_GET;
		
		$Limit 								= $_GET['Limit'];
		$IdLoja		 						= $_SESSION["IdLoja"];
		$IdLocalCobranca	 				= $_GET['IdLocalCobranca'];
		$IdLocalCobrancaParametroContrato	= $_GET['IdLocalCobrancaParametroContrato'];
		$IdStatus			 				= $_GET['IdStatus'];
		$Visivel			 				= $_GET['Visivel'];
		$VisivelOS			 				= $_GET['VisivelOS'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdLocalCobranca != ''){		
			$where .= " and LocalCobrancaParametroContrato.IdLocalCobranca=$IdLocalCobranca";
		}
		if($IdLocalCobrancaParametroContrato != ''){		
			$where .= " and LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato=$IdLocalCobrancaParametroContrato";
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
						LocalCobrancaParametroContrato.IdTipoParametro,
						LocalCobrancaParametroContrato.IdMascaraCampo,
						LocalCobrancaParametroContrato.OpcaoValor,
						LocalCobrancaParametroContrato.Obrigatorio,
						LocalCobrancaParametroContrato.Editavel,
						LocalCobrancaParametroContrato.Obs,
						LocalCobrancaParametroContrato.IdStatus,
						LocalCobrancaParametroContrato.Calculavel,
						LocalCobrancaParametroContrato.RotinaCalculo,
						LocalCobrancaParametroContrato.ParametroDemonstrativo,
						LocalCobrancaParametroContrato.Visivel,
						LocalCobrancaParametroContrato.VisivelOS,
						LocalCobrancaParametroContrato.DataCriacao,
						LocalCobrancaParametroContrato.LoginCriacao,
						LocalCobrancaParametroContrato.DataAlteracao,
						LocalCobrancaParametroContrato.LoginAlteracao
					from 
						LocalCobranca,
						LocalCobrancaParametroContrato
					where
						LocalCobranca.IdLoja = $IdLoja and
						LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca and
						LocalCobrancaParametroContrato.IdLoja = LocalCobranca.IdLoja $where 
					order by 
						LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Obs] = formTexto($lin[Obs]);
			
			$sql2	=	"select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=5 and IdCodigoInterno=$lin[Obrigatorio]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			$sql3	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 and IdParametroSistema=$lin[Editavel]";
			$res3	=	@mysql_query($sql3,$con);
			$lin3	=	@mysql_fetch_array($res3);
			
			$sql6	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=79 and IdParametroSistema=$lin[IdStatus]";
			$res6	=	@mysql_query($sql6,$con);
			$lin6	=	@mysql_fetch_array($res6);
						
			$dados	.=	"\n<IdLocalCobrancaParametroContrato>$lin[IdLocalCobrancaParametroContrato]</IdLocalCobrancaParametroContrato>";
			$dados	.=	"\n<DescricaoParametroContrato><![CDATA[$lin[DescricaoParametroContrato]]]></DescricaoParametroContrato>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			$dados	.=	"\n<DescObrigatorio><![CDATA[$lin2[ValorCodigoInterno]]]></DescObrigatorio>";
			$dados	.=	"\n<IdTipoParametro><![CDATA[$lin[IdTipoParametro]]]></IdTipoParametro>";
			$dados	.=	"\n<IdMascaraCampo><![CDATA[$lin[IdMascaraCampo]]]></IdMascaraCampo>";
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<DescEditavel><![CDATA[$lin3[ValorParametroSistema]]]></DescEditavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Visivel><![CDATA[$lin[Visivel]]]></Visivel>";
			$dados	.=	"\n<VisivelOS><![CDATA[$lin[VisivelOS]]]></VisivelOS>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
			$dados	.=	"\n<Calculavel><![CDATA[$lin[Calculavel]]]></Calculavel>";
			$dados	.=	"\n<RotinaCalculo><![CDATA[$lin[RotinaCalculo]]]></RotinaCalculo>";
			$dados	.=	"\n<IdStatusParametro>$lin[IdStatus]</IdStatusParametro>";
			$dados	.=	"\n<DescStatus><![CDATA[$lin6[ValorParametroSistema]]]></DescStatus>";
			$dados	.=	"\n<ParametroDemonstrativo><![CDATA[$lin[ParametroDemonstrativo]]]></ParametroDemonstrativo>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_LocalCobrancaParametroContrato();
?>
