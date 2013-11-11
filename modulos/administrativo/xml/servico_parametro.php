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
		$Login						= $_SESSION["Login"];
		$IdServico	 				= $_GET['IdServico'];
		$IdParametroServico	 		= $_GET['IdParametroServico'];
		$IdContrato			 		= $_GET['IdContrato'];
		$IdStatus			 		= $_GET['IdStatus'];
		$Visivel			 		= $_GET['Visivel'];
		$VisivelOS			 		= $_GET['VisivelOS'];
		$where			= "";
		
		if($Limit != ''){ $Limit = "limit 0,$Limit";	}
		
		if($IdServico != ''){
			$where .= " and ServicoParametro.IdServico=$IdServico";
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
		
		$sql	=	"select
						ServicoParametro.DescricaoParametroServico,
						ServicoParametro.ValorDefault,
						ServicoParametro.IdGrupoUsuario,
						ServicoParametro.IdParametroServico,
						ServicoParametro.IdTipoParametro,
						ServicoParametro.IdTipoAcesso,
						ServicoParametro.SalvarHistorico,
						ServicoParametro.IdMascaraCampo,
						ServicoParametro.OpcaoValor,
						ServicoParametro.Obrigatorio,
						ServicoParametro.Editavel,
						ServicoParametro.Obs,
						ServicoParametro.IdStatus,
						ServicoParametro.Calculavel,
						ServicoParametro.CalculavelOpcoes,
						ServicoParametro.RotinaCalculo,
						ServicoParametro.RotinaOpcoes,
						ServicoParametro.RotinaOpcoesContrato,
						ServicoParametro.ParametroDemonstrativo,
						ServicoParametro.Visivel,
						ServicoParametro.VisivelOS,
						ServicoParametro.VisivelCDA,
						ServicoParametro.AcessoCDA,
						ServicoParametro.BotaoAuxiliar,
						ServicoParametro.Unico,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha,
						ServicoParametro.TamMinimo,
						ServicoParametro.TamMaximo,
						ServicoParametro.DescricaoParametroServicoCDA,
						ServicoParametro.DataCriacao,
						ServicoParametro.LoginCriacao,
						ServicoParametro.DataAlteracao,
						ServicoParametro.LoginAlteracao
					from 
						Loja,
						Servico,
						ServicoParametro
					where
						Servico.IdLoja = $IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						ServicoParametro.IdLoja = Servico.IdLoja and
						Servico.IdLoja = Loja.IdLoja $where order by IdParametroServico ASC $Limit";
		$res	=	mysql_query($sql,$con) or die(mysql_error());
		if(mysql_num_rows($res) >=1){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	mysql_fetch_array($res)){
			$lin[Obs] = formTexto($lin[Obs]);
			
			$sql2	=	"select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=5 and IdCodigoInterno=$lin[Obrigatorio]";
			$res2	=	mysql_query($sql2,$con);
			$lin2	=	mysql_fetch_array($res2);
			
			$sql3	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=19 and IdParametroSistema=$lin[Editavel]";
			$res3	=   mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
			
			$sql6	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=79 and IdParametroSistema=$lin[IdStatus]";
			$res6	=	mysql_query($sql6,$con);
			$lin6	=	mysql_fetch_array($res6);
			
			if($lin[IdGrupoUsuario] != ''){
				$sql7	=	"select
								(COUNT(*)>0) Qtd
							from 
								UsuarioGrupoUsuario
							where 
								IdLoja = '$IdLoja' and 
								IdGrupoUsuario in ($lin[IdGrupoUsuario]) and 
								Login = '$Login';";
				$res7	=	mysql_query($sql7,$con);
				$lin7	=	mysql_fetch_array($res7);
			}else{
				$lin7[Qtd] = 1;
			}
			
			if($lin[CalculavelOpcoes] == 1){
				if($IdContrato != ''){
					$lin[RotinaOpcoesContrato] = str_replace('$IdContrato', $IdContrato, $lin[RotinaOpcoesContrato]);
				}
				$file = opcoesServicoParametro($lin[RotinaOpcoesContrato]);
				$count	=  	count($file);
				
				for($i=0; $i < $count; $i++){
					if($lin[OpcaoValor] != "") 	$lin[OpcaoValor] .= "\n";
					$lin[OpcaoValor]	.=	trim($file[$i]);	
				}
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
			}else{
				$lin[PermissaoInserir] = 2;
			}
			
			if($lin[PermissaoEditar]){
				$lin[PermissaoEditar] = 1;
			}else{
				$lin[PermissaoEditar] = 2;
			}
			
			if($lin[PermissaoVisualizar]){
				$lin[PermissaoVisualizar] = 1;
			}else{
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
			$dados	.=	"\n<DescObrigatorio><![CDATA[$lin2[ValorCodigoInterno]]]></DescObrigatorio>";
			$dados	.=	"\n<IdTipoParametro><![CDATA[$lin[IdTipoParametro]]]></IdTipoParametro>";
			$dados	.=	"\n<IdTipoAcesso><![CDATA[$lin[IdTipoAcesso]]]></IdTipoAcesso>";
			$dados	.=	"\n<SalvarHistorico><![CDATA[$lin[SalvarHistorico]]]></SalvarHistorico>";
			$dados	.=	"\n<IdMascaraCampo><![CDATA[$lin[IdMascaraCampo]]]></IdMascaraCampo>";
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<DescEditavel><![CDATA[$lin3[ValorParametroSistema]]]></DescEditavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<PermissaoInserir><![CDATA[$lin[PermissaoInserir]]]></PermissaoInserir>";
			$dados	.=	"\n<PermissaoEditar><![CDATA[$lin[PermissaoEditar]]]></PermissaoEditar>";
			$dados	.=	"\n<PermissaoVisualizar><![CDATA[$lin[PermissaoVisualizar]]]></PermissaoVisualizar>";
			$dados	.=	"\n<Visivel><![CDATA[$lin[Visivel]]]></Visivel>";
			$dados	.=	"\n<VisivelOS><![CDATA[$lin[VisivelOS]]]></VisivelOS>";
			$dados	.=	"\n<VisivelCDA><![CDATA[$lin[VisivelCDA]]]></VisivelCDA>";
			$dados	.=	"\n<AcessoCDA><![CDATA[$lin[AcessoCDA]]]></AcessoCDA>";
			$dados	.=	"\n<BotaoAuxiliar><![CDATA[$lin[BotaoAuxiliar]]]></BotaoAuxiliar>";
			$dados	.=	"\n<BotaoAuxiliarIMG><![CDATA[$lin[BotaoAuxiliarIMG]]]></BotaoAuxiliarIMG>";
			$dados	.=	"\n<Unico><![CDATA[$lin[Unico]]]></Unico>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";
			$dados	.=	"\n<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";
			$dados	.=	"\n<RestringirGrupoUsuario><![CDATA[$lin7[Qtd]]]></RestringirGrupoUsuario>";
			$dados	.=	"\n<Calculavel><![CDATA[$lin[Calculavel]]]></Calculavel>";
			$dados	.=	"\n<CalculavelOpcoes><![CDATA[$lin[CalculavelOpcoes]]]></CalculavelOpcoes>";
			$dados	.=	"\n<RotinaCalculo><![CDATA[$lin[RotinaCalculo]]]></RotinaCalculo>";
			$dados	.=	"\n<RotinaOpcoes><![CDATA[$lin[RotinaOpcoes]]]></RotinaOpcoes>";
			$dados	.=	"\n<RotinaOpcoesContrato><![CDATA[$lin[RotinaOpcoesContrato]]]></RotinaOpcoesContrato>";
			$dados	.=	"\n<IdStatusParametro>$lin[IdStatus]</IdStatusParametro>";
			$dados	.=	"\n<DescStatus><![CDATA[$lin6[ValorParametroSistema]]]></DescStatus>";
			$dados	.=	"\n<ParametroDemonstrativo><![CDATA[$lin[ParametroDemonstrativo]]]></ParametroDemonstrativo>";
			$dados	.=	"\n<IdTipoTexto><![CDATA[$lin[IdTipoTexto]]]></IdTipoTexto>";
			$dados	.=	"\n<ExibirSenha><![CDATA[$lin[ExibirSenha]]]></ExibirSenha>";
			$dados	.=	"\n<DescricaoParametroServicoCDA><![CDATA[$lin[DescricaoParametroServicoCDA]]]></DescricaoParametroServicoCDA>";
			$dados	.=	"\n<TamMinimo><![CDATA[$lin[TamMinimo]]]></TamMinimo>";
			$dados	.=	"\n<TamMaximo><![CDATA[$lin[TamMaximo]]]></TamMaximo>";
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