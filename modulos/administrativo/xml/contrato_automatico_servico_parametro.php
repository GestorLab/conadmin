<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoParametro(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdServico	 				= $_GET['IdServico'];
		$IdParametroServico	 		= $_GET['IdParametroServico'];
		$IdStatus			 		= $_GET['IdStatus'];
		$Visivel			 		= $_GET['Visivel'];
		$VisivelOS			 		= $_GET['VisivelOS'];
		$IdContrato			 		= $_GET['IdContrato'];
		$where						= "";
		$union						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdServico != ''){		
		//	$where .= " and ServicoParametro.IdServico=$IdServico";
		}
		if($IdParametroServico != ''){		
			$where .= " and ServicoParametro.IdParametroServico=$IdParametroServico";
		}
		if($IdStatus != ''){		
			$where .= " and ServicoParametro.IdStatus=$IdStatus";
		}
		if($Visivel != ''){		
			$where .= " and ServicoParametro.Visivel=$Visivel";
		}
		if($VisivelOS != ''){		
			$where .= " and ServicoParametro.VisivelOS=$VisivelOS";
		}
		if($IdContrato != ''){		
			$sql2	=	"select ContratoAutomatico.IdContratoAutomatico,Contrato.IdServico from (select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from 	ContratoAutomatico where ContratoAutomatico.IdLoja = $IdLoja and ContratoAutomatico.IdContrato = $IdContrato) ContratoAutomatico, Contrato where Contrato.IdLoja = $IdLoja and Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
				$union .= " UNION
	
						(select
							ServicoParametro.IdServico,
							ServicoParametro.IdParametroServico,
							ServicoParametro.DescricaoParametroServico,
							ContratoParametro.Valor,
							ServicoParametro.ValorDefault,
							ServicoParametro.Obrigatorio,
							ServicoParametro.Obs,
							ServicoParametro.RotinaCalculo,
							ServicoParametro.RotinaOpcoes,
							ServicoParametro.RotinaOpcoesContrato,
							ServicoParametro.Calculavel,
							ServicoParametro.RotinaOpcoes,
							ServicoParametro.RotinaOpcoesContrato,
							ServicoParametro.CalculavelOpcoes,
							ServicoParametro.Editavel,
							ServicoParametro.IdTipoParametro,
							ServicoParametro.IdMascaraCampo,
							ServicoParametro.OpcaoValor,
							ServicoParametro.Visivel,
							ServicoParametro.VisivelOS
						from 
							Loja,
							Servico,
							ServicoParametro LEFT JOIN 
									ContratoParametro ON (
										ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
										ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
										ServicoParametro.IdServico = ContratoParametro.IdServico and
										ContratoParametro.IdContrato = $lin2[IdContratoAutomatico]),
							Contrato
						where
							Servico.IdLoja = $IdLoja and
							Servico.IdServico = ServicoParametro.IdServico and
							ServicoParametro.IdLoja = Servico.IdLoja and
							Servico.IdLoja = Loja.IdLoja and
							ServicoParametro.IdServico = $lin2[IdServico] 
						order by 
							ServicoParametro.DescricaoParametroServico ASC) 
					";
			}
			
			if($union!="")	$union .= " order by DescricaoParametroServico ASC";
		}
		
		$sql	=	"(select
						ServicoParametro.IdServico,
						ServicoParametro.IdParametroServico,
						ServicoParametro.DescricaoParametroServico,
						ContratoParametro.Valor,
						ServicoParametro.ValorDefault,
						ServicoParametro.Obrigatorio,
						ServicoParametro.Obs,
						ServicoParametro.RotinaCalculo,
						ServicoParametro.RotinaOpcoes,
						ServicoParametro.RotinaOpcoesContrato,
						ServicoParametro.Calculavel,
						ServicoParametro.RotinaOpcoes,
						ServicoParametro.RotinaOpcoesContrato,
						ServicoParametro.CalculavelOpcoes,
						ServicoParametro.Editavel,
						ServicoParametro.IdTipoParametro,
						ServicoParametro.IdMascaraCampo,
						ServicoParametro.OpcaoValor,
						ServicoParametro.Visivel,
						ServicoParametro.VisivelOS
					from 
						Loja,
						Servico,
						ServicoParametro LEFT JOIN 
								ContratoParametro ON (
									ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
									ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
									ServicoParametro.IdServico = ContratoParametro.IdServico and
									ContratoParametro.IdContrato = $IdContrato)
					where
						Servico.IdLoja = $IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						ServicoParametro.IdLoja = Servico.IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						ServicoParametro.IdServico = $IdServico $where
					order by 
						ServicoParametro.DescricaoParametroServico ASC) $union";
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
			
			if($lin[Calculavel] == 1){
				if($lin[RotinaCalculo] != ''){
					$lin[RotinaCalculo] = str_replace('$IdContrato',$IdContrato,$lin[RotinaCalculo]);
					$lin[Valor] 		= @end(file($lin[RotinaCalculo]));
					$lin[Valor]			= trim($lin[Valor]);
				}
			}
			
			if($lin[CalculavelOpcoes] == 1){
				$lin[RotinaOpcoesContrato] = str_replace('$IdContrato',$IdContrato,$lin[RotinaOpcoesContrato]);
				$file 	= 	@file($lin[RotinaOpcoesContrato]);
				$count	=  	count($file);
				
				for($i=0; $i < $count; $i++){
					if($lin[OpcaoValor] != "") 	$lin[OpcaoValor] .= "\n";
					
					$lin[OpcaoValor]	.=	trim($file[$i]);	
				}
			}
						
			$dados	.=	"\n<IdParametroServico>$lin[IdParametroServico]</IdParametroServico>";
			$dados	.=	"\n<DescricaoParametroServico><![CDATA[$lin[DescricaoParametroServico]]]></DescricaoParametroServico>";
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
			$dados	.=	"\n<CalculavelOpcoes><![CDATA[$lin[CalculavelOpcoes]]]></CalculavelOpcoes>";
			$dados	.=	"\n<RotinaCalculo><![CDATA[$lin[RotinaCalculo]]]></RotinaCalculo>";
			$dados	.=	"\n<RotinaOpcoes><![CDATA[$lin[RotinaOpcoes]]]></RotinaOpcoes>";
			$dados	.=	"\n<RotinaOpcoesContrato><![CDATA[$lin[RotinaOpcoesContrato]]]></RotinaOpcoesContrato>";
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
	echo get_ServicoParametro();
?>
