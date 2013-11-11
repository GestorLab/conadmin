<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContratoVigencia(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdContrato	 			= $_GET['IdContrato'];
		$DataInicio	 			= $_GET['DataInicioVigencia'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$contrato	=	"";
		$sqlTemp	=	"select IdContrato from ContratoAutomatico where IdLoja = $IdLoja and (IdContratoAutomatico = $IdContrato or IdContrato = $IdContrato) group by IdContrato";
		$resTemp	=	mysql_query($sqlTemp,$con);
		$linTemp	=	mysql_fetch_array($resTemp);
		if($linTemp[IdContrato] != "" && $DataInicio == ''){
			if($linTemp[IdContrato] == $IdContrato){
				$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $IdLoja and IdContrato = $IdContrato";
			}else{
				$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $IdLoja and IdContrato = $linTemp[IdContrato]";
			}
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
				if($contrato != ""){
					 $contrato .= ",".$lin2[IdContratoAutomatico];
				}else{
					$contrato .= $linTemp[IdContrato].",".$lin2[IdContratoAutomatico];
				}		
			}
			$where .= " and Contrato.IdContrato in ($contrato)";
		}else{
			$where .= " and Contrato.IdContrato = '$IdContrato'";
		}
		
		if($DataInicio != ''){
			$where .= " and ContratoVigencia.DataInicio = '$DataInicio'";
		}
		
		$sql	=	"select
						ContratoVigencia.IdContrato IdContratoVigencia,
						ContratoVigencia.DataInicio,
						ContratoVigencia.DataTermino,
						ContratoVigencia.Valor,
						ContratoVigencia.ValorDesconto,
						ContratoVigencia.ValorRepasseTerceiro,
						ContratoVigencia.IdContratoTipoVigencia,
						ContratoTipoVigencia.DescricaoContratoTipoVigencia,
						ContratoVigencia.IdTipoDesconto,
						ContratoVigencia.LimiteDesconto,
						ContratoVigencia.Obs,
						ContratoVigencia.DataCriacao,
						ContratoVigencia.LoginCriacao,
						ContratoVigencia.DataAlteracao,
						ContratoVigencia.LoginAlteracao
					from 
						Loja,
						Contrato,
						ContratoVigencia LEFT JOIN ContratoTipoVigencia ON (ContratoVigencia.IdLoja = ContratoTipoVigencia.IdLoja and ContratoVigencia.IdContratoTipoVigencia = ContratoTipoVigencia.IdContratoTipoVigencia)
					where
						Contrato.IdLoja = $IdLoja and
						Contrato.IdLoja = Loja.IdLoja and
						Contrato.IdLoja = ContratoVigencia.IdLoja and
						Contrato.IdContrato = ContratoVigencia.IdContrato $where 
					order by DataInicio ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[Valor]!= '' && $lin[ValorDesconto]!=''){
				$valorTotal = $lin[Valor] - $lin[ValorDesconto];
			}
			
			$TipoDesconto = getParametroSistema(73,$lin[IdTipoDesconto]);
			
			$dados	.=	"\n<IdContratoVigencia><![CDATA[$lin[IdContratoVigencia]]]></IdContratoVigencia>";
			$dados	.=	"\n<DataInicioVigencia><![CDATA[$lin[DataInicio]]]></DataInicioVigencia>";
			$dados	.=	"\n<DataTerminoVigencia><![CDATA[$lin[DataTermino]]]></DataTerminoVigencia>";
			$dados	.=	"\n<IdContratoTipoVigencia><![CDATA[$lin[IdContratoTipoVigencia]]]></IdContratoTipoVigencia>";
			$dados	.=	"\n<DescricaoContratoTipoVigencia><![CDATA[$lin[DescricaoContratoTipoVigencia]]]></DescricaoContratoTipoVigencia>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
			$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
			$dados	.=	"\n<ValorTotal><![CDATA[$valorTotal]]></ValorTotal>";
			$dados	.=	"\n<IdTipoDesconto><![CDATA[$lin[IdTipoDesconto]]]></IdTipoDesconto>";
			$dados	.=	"\n<TipoDesconto><![CDATA[$TipoDesconto]]></TipoDesconto>";
			$dados	.=	"\n<LimiteDesconto><![CDATA[$lin[LimiteDesconto]]]></LimiteDesconto>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ContratoVigencia();
?>
