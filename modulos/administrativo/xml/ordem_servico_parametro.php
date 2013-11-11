<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Ordem_Servico_Parametro(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja						= $_SESSION["IdLoja"];
		$Login						= $_SESSION["Login"];
		$IdServico	 				= $_GET['IdServico'];
		$IdOrdemServico				= $_GET['IdOrdemServico'];
		$IdOrdemServicoParametro	= $_GET['IdOrdemServicoParametro'];
		$VisivelOS			 		= $_GET['VisivelOS'];
		$IdStatus			 		= $_GET['IdStatus'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdServico != ''){		
			$where .= " and ServicoParametro.IdServico=$IdServico";
		}
		if($IdOrdemServicoParametro != ''){		
			$where .= " and OrdemServicoParametro.IdOrdemServicoParametro=$IdOrdemServicoParametro";
		}
		if($VisivelOS != ''){		
			$where .= " and ServicoParametro.VisivelOS=$VisivelOS";
		}
		if($IdStatus != ''){		
			$where .= " and ServicoParametro.IdStatus=$IdStatus";
		}
		
		$sql	=	"select
					    ServicoParametro.IdParametroServico,
					    ServicoParametro.DescricaoParametroServico,
					    ServicoParametro.ValorDefault,
						ServicoParametro.IdGrupoUsuario,
					    OrdemServicoParametro.Valor,
					    ServicoParametro.Obrigatorio,
					    ServicoParametro.Obs,
					    ServicoParametro.RotinaCalculo,
					    ServicoParametro.Calculavel, 
					    ServicoParametro.OpcaoValor,
					    ServicoParametro.IdMascaraCampo,
						ServicoParametro.IdTipoParametro,						
						ServicoParametro.Visivel,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.TamMinimo,
						ServicoParametro.TamMaximo,
						ServicoParametro.Editavel,
						ServicoParametro.IdTipoAcesso,
						ServicoParametro.ExibirSenha
					from
					    ServicoParametro 
							left join 
								(select 
									* 
								from 
									OrdemServicoParametro 
								where 
									OrdemServicoParametro.IdOrdemServico=$IdOrdemServico and 
									OrdemServicoParametro.IdServico=$IdServico) 
									OrdemServicoParametro on (ServicoParametro.IdParametroServico = OrdemServicoParametro.IdParametroServico)
					where
						ServicoParametro.IdLoja = $IdLoja $where
                    order by 
						ServicoParametro.IdParametroServico ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$sql1	=	"select
							COUNT(*) Qtd
						from 
							UsuarioGrupoUsuario
						where 
							IdLoja = '$IdLoja' and 
							IdGrupoUsuario = '$lin[IdGrupoUsuario]' and 
							Login = '$Login';";
			$res1	=	@mysql_query($sql1,$con);
			$lin1	=	@mysql_fetch_array($res1);
			
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
			
			$dados	.=	"\n<DescricaoOrdemServicoParametro><![CDATA[$lin[DescricaoParametroServico]]]></DescricaoOrdemServicoParametro>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			$dados	.=	"\n<Valor><![CDATA[".trim($lin[Valor])."]]></Valor>";
			$dados	.=	"\n<RestringirGrupoUsuario><![CDATA[$lin1[Qtd]]]></RestringirGrupoUsuario>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdParametroServico>$lin[IdParametroServico]</IdParametroServico>";
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
			$dados	.=	"\n<IdMascaraCampo><![CDATA[$lin[IdMascaraCampo]]]></IdMascaraCampo>";
			$dados	.=	"\n<IdTipoParametro><![CDATA[$lin[IdTipoParametro]]]></IdTipoParametro>";
			$dados	.=	"\n<PermissaoInserir><![CDATA[$lin[PermissaoInserir]]]></PermissaoInserir>";
			$dados	.=	"\n<PermissaoEditar><![CDATA[$lin[PermissaoEditar]]]></PermissaoEditar>";
			$dados	.=	"\n<PermissaoVisualizar><![CDATA[$lin[PermissaoVisualizar]]]></PermissaoVisualizar>";
			$dados	.=	"\n<VisivelOS><![CDATA[$lin[Visivel]]]></VisivelOS>";
			$dados	.=	"\n<IdTipoTexto><![CDATA[$lin[IdTipoTexto]]]></IdTipoTexto>";
			$dados	.=	"\n<TamMinimo><![CDATA[$lin[TamMinimo]]]></TamMinimo>";
			$dados	.=	"\n<TamMaximo><![CDATA[$lin[TamMaximo]]]></TamMaximo>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<ExibirSenha><![CDATA[$lin[ExibirSenha]]]></ExibirSenha>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Ordem_Servico_Parametro();
?>
