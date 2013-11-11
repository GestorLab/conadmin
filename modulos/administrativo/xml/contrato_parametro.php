<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');

	function get_Parametro(){
	
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja						= $_SESSION["IdLoja"];
		$Login						= $_SESSION["Login"];
		$IdServico	 				= $_GET['IdServico'];
		$IdContrato	 				= $_GET['IdContrato'];
		$IdParametroServico	 		= $_GET['IdParametroServico'];
		$IdStatus			 		= $_GET['IdStatus'];
		$Visivel			 		= $_GET['Visivel'];
		$VisivelOS			 		= $_GET['VisivelOS'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdContrato != '' && $IdServico == ''){		
			$sql2	=	"select IdServico from Contrato where IdLoja = $IdLoja and IdContrato = $IdContrato";
			$res2	=	mysql_query($sql2,$con);
			$lin2	=	mysql_fetch_array($res2);
			
			$IdServico	=	$lin2[IdServico];
		}
		if($IdParametroServico != ''){		$where .= " and ServicoParametro.IdParametroServico=$IdParametroServico";	}
		if($IdStatus != ''){				$where .= " and ServicoParametro.IdStatus=$IdStatus";						}
		if($Visivel != ''){					$where .= " and ServicoParametro.Visivel=$Visivel";							}
		if($VisivelOS != ''){				$where .= " and ServicoParametro.VisivelOS=$VisivelOS";						}
		
		$sql	=	"select
						ServicoParametro.IdServico,
						ServicoParametro.IdParametroServico,
						ServicoParametro.DescricaoParametroServico,
						ContratoParametro.Valor,
						ServicoParametro.ValorDefault,
						ServicoParametro.IdGrupoUsuario,
						ServicoParametro.Obrigatorio,
						ServicoParametro.Obs,
						ServicoParametro.RotinaCalculo,
						ServicoParametro.RotinaOpcoes,
						ServicoParametro.RotinaOpcoesContrato,
						ServicoParametro.Calculavel,
						ServicoParametro.RotinaOpcoes,
						ServicoParametro.RotinaOpcoesContrato,
						ServicoParametro.CalculavelOpcoes,
						ServicoParametro.BotaoAuxiliar,
						ServicoParametro.Editavel,
						ServicoParametro.IdTipoParametro,
						ServicoParametro.IdMascaraCampo,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha,
						ServicoParametro.TamMinimo,
						ServicoParametro.TamMaximo,
						ServicoParametro.OpcaoValor,
						ServicoParametro.Visivel,
						ServicoParametro.IdTipoAcesso,
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
						ServicoParametro.IdParametroServico ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){

			$lin[RotinaCalculo]			= str_replace('$IdContrato',$IdContrato,$lin[RotinaCalculo]);
			$lin[RotinaOpcoesContrato]	= str_replace('$IdContrato',$IdContrato,$lin[RotinaOpcoesContrato]);

			if($lin[Calculavel] == 1){
				$lin[Valor] = "";

				$file = opcoesServicoParametro($lin[RotinaCalculo]);
				$count	=  	count($file);
				
				for($i=0; $i < $count; $i++){
					if($lin[Valor] != "") 	$lin[Valor] .= "\n";
					$lin[Valor]	.=	trim($file[$i]);	
				}
			}
			
			if($lin[CalculavelOpcoes] == 1){
				$lin[OpcaoValor] = "";

				$file = opcoesServicoParametro($lin[RotinaOpcoesContrato]);
				$count	=  	count($file);
				
				for($i=0; $i < $count; $i++){
					if($lin[OpcaoValor] != "") 	$lin[OpcaoValor] .= "\n";
					$lin[OpcaoValor]	.=	trim($file[$i]);	
				}
			}
			
			if($lin[IdGrupoUsuario] != '') {
				$sql1	=	"select
								(COUNT(*)>0) Qtd
							from 
								UsuarioGrupoUsuario
							where 
								IdLoja = '$IdLoja' and 
								IdGrupoUsuario in ($lin[IdGrupoUsuario]) and 
								Login = '$Login';";
				$res1	=	@mysql_query($sql1,$con);
				$lin1	=	@mysql_fetch_array($res1);
			} else {
				$lin1[Qtd] = 1;
			}
			
			switch($lin[IdTipoAcesso]){
				case "1": # Administrativo
					$lin[PermissaoInserir] = permissaoSubOperacao(1, 198, "I");
					$lin[PermissaoEditar] = permissaoSubOperacao(1, 198, "U");
					$lin[PermissaoVisualizar] = permissaoSubOperacao(1, 198, "V");
					break;
					
				case "2": # Técnico
					$lin[PermissaoInserir] = permissaoSubOperacao(1, 199, "I");
					$lin[PermissaoEditar] = permissaoSubOperacao(1, 199, "U");
					$lin[PermissaoVisualizar] = permissaoSubOperacao(1, 199, "V");
					break;
					
				default:
					$lin[PermissaoInserir] = false;
					$lin[PermissaoEditar] = false;
					$lin[PermissaoVisualizar] = false;
			}
			
			if($lin[PermissaoInserir]){
				$lin[PermissaoInserir] = 1;
			} else {
				$lin[PermissaoInserir] = 2;
			}
			
			if($lin[PermissaoEditar]){
				$lin[PermissaoEditar] = 1;
			} else {
				$lin[PermissaoEditar] = 2;
			}
			
			if($lin[PermissaoVisualizar]){
				$lin[PermissaoVisualizar] = 1;
			} else {
				$lin[PermissaoVisualizar] = 2;
			}
			
			switch($lin[BotaoAuxiliar]){
				case 1:
					$lin[BotaoAuxiliarIMG] = "../../img/estrutura_sistema/ico_ie.gif";
					break;
				case 2:
					$lin[BotaoAuxiliarIMG] = "../../img/estrutura_sistema/processar.gif";
					break;
				case 3:
					$lin[BotaoAuxiliarIMG] = "../../img/estrutura_sistema/ico_date.gif";
					break;
				default: 
					$lin[BotaoAuxiliarIMG] = "";
			}
			
			$dados	.=	"\n<IdParametroServico><![CDATA[$lin[IdParametroServico]]]></IdParametroServico>";
			$dados	.=	"\n<DescricaoParametroServico><![CDATA[$lin[DescricaoParametroServico]]]></DescricaoParametroServico>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
			$dados	.=	"\n<BotaoAuxiliar><![CDATA[$lin[BotaoAuxiliar]]]></BotaoAuxiliar>";
			$dados	.=	"\n<BotaoAuxiliarIMG><![CDATA[$lin[BotaoAuxiliarIMG]]]></BotaoAuxiliarIMG>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdTipoParametro><![CDATA[$lin[IdTipoParametro]]]></IdTipoParametro>";
			$dados	.=	"\n<IdMascaraCampo><![CDATA[$lin[IdMascaraCampo]]]></IdMascaraCampo>";
			$dados	.=	"\n<RestringirGrupoUsuario><![CDATA[$lin1[Qtd]]]></RestringirGrupoUsuario>";
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
			$dados	.=	"\n<PermissaoInserir><![CDATA[$lin[PermissaoInserir]]]></PermissaoInserir>";
			$dados	.=	"\n<PermissaoEditar><![CDATA[$lin[PermissaoEditar]]]></PermissaoEditar>";
			$dados	.=	"\n<PermissaoVisualizar><![CDATA[$lin[PermissaoVisualizar]]]></PermissaoVisualizar>";
			$dados	.=	"\n<Visivel><![CDATA[$lin[Visivel]]]></Visivel>";
			$dados	.=	"\n<VisivelOS><![CDATA[$lin[VisivelOS]]]></VisivelOS>";
			$dados	.=	"\n<IdTipoTexto><![CDATA[$lin[IdTipoTexto]]]></IdTipoTexto>";
			$dados	.=	"\n<TamMinimo><![CDATA[$lin[TamMinimo]]]></TamMinimo>";
			$dados	.=	"\n<TamMaximo><![CDATA[$lin[TamMaximo]]]></TamMaximo>";
			$dados	.=	"\n<ExibirSenha><![CDATA[$lin[ExibirSenha]]]></ExibirSenha>";
			$dados	.=	"\n<CalculavelOpcoes><![CDATA[$lin[CalculavelOpcoes]]]></CalculavelOpcoes>";
			$dados	.=	"\n<RotinaOpcoes><![CDATA[$lin[RotinaOpcoes]]]></RotinaOpcoes>";
			$dados	.=	"\n<RotinaOpcoesContrato><![CDATA[$lin[RotinaOpcoesContrato]]]></RotinaOpcoesContrato>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Parametro();
?>