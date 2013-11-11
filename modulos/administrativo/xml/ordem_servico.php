<?

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Ordem_Servico(){
		$localModulo	=	1;
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$Limit 					= $_GET['Limit'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$IdOrdemServico			= $_GET['IdOrdemServico'];
		$DescricaoOS			= $_GET['DescricaoOS'];
		$DescricaoCDA			= $_GET['DescricaoCDA'];
		$IdTipoOrdemServico		= $_GET['IdTipoOrdemServico'];
		$IdStatus				= $_GET['IdStatus'];
		$IdContrato				= $_GET['IdContrato'];
		$IdContaReceber			= $_GET['IdContaReceber'];
		$where					= "";
		$where2					= "";	
		
		if($Limit != ''){		$Limit = " limit 0,$Limit";	}
		
		if($IdOrdemServico != ''){
			$where	=	" and OrdemServico.IdOrdemServico = $IdOrdemServico";
			$where2	=	" and OrdemServico.IdOrdemServico = $IdOrdemServico";
		}
		
		if($IdPessoa != ''){
			$where	.=	" and OrdemServico.IdPessoa = $IdPessoa";
		}
		
		if($IdContrato != ''){
			$where	.=	" and OrdemServico.IdContrato = $IdContrato";
		}
		
		if($DescricaoOS != ''){
			$where	.=	" and OrdemServico.DescricaoOS like '$DescricaoOS%'";
		}
		
		if($DescricaoCDA != ''){
			$where	.=	" and OrdemServico.DescricaoCDA like '$DescricaoCDA%'";
		}
		
		if($IdTipoOrdemServico != ''){
			$where	.=	" and OrdemServico.IdTipoOrdemServico = $IdTipoOrdemServico";
		}
		
		if($IdStatus != ''){
			$where	.=	" and OrdemServico.IdStatus = $IdStatus";
		}
		
		if($IdContaReceber !=''){	 
			$where .= " and OrdemServico.IdOrdemServico in (
				select 
					LancamentoFinanceiro.IdOrdemServico
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber
			)";
		}
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		
		$cont	=	0;	
		$sql	=	"select
						OrdemServico.IdLoja,
						OrdemServico.IdOrdemServico,
						OrdemServico.IdTipoOrdemServico,
						OrdemServico.IdSubTipoOrdemServico,
						OrdemServico.IdPessoa,
						OrdemServico.IdLocalCobranca,
						OrdemServico.IdServico,
						OrdemServico.IdContrato,
						OrdemServico.DataAgendamentoAtendimento,
						OrdemServico.IdStatus,
						OrdemServico.IdGrupoUsuarioAtendimento,
						OrdemServico.LoginAtendimento,
						OrdemServico.IdTerceiro,
						OrdemServico.ValorOutros,
						OrdemServico.ValorTotal,
						OrdemServico.Valor,
						OrdemServico.IdCartao,
						OrdemServico.IdContaDebito,
						OrdemServico.ValorDespesaLocalCobranca,
				     	OrdemServicoParcela.Vencimento,
					 	OrdemServicoParcela.MesReferencia,
						OrdemServico.DescricaoOS,
						OrdemServico.DescricaoOutros,
						OrdemServico.FormaCobranca,
						OrdemServico.QtdParcela,
						OrdemServico.Obs,
						OrdemServico.Carne,
						OrdemServico.DataConclusao,
						OrdemServico.LoginConclusao,
						OrdemServico.DataFaturamento,
						OrdemServico.LoginFaturamento,
						OrdemServico.MD5,
						OrdemServico.Faturado,
						OrdemServico.IdContratoFaturamento,
						OrdemServico.IdPessoaEndereco,
						OrdemServico.IdPessoaEnderecoCobranca,
						OrdemServico.IdMarcador,
						OrdemServico.IdAgenteAutorizado,
						OrdemServico.IdCarteira,
						OrdemServico.DataCriacao,
						OrdemServico.LoginCriacao,
						OrdemServico.DataAlteracao,
						OrdemServico.LoginAlteracao,
						TipoOrdemServico.DescricaoTipoOrdemServico,
						Pessoa.Nome,
						Pessoa.RazaoSocial
					from
					    OrdemServico LEFT JOIN (
							select 
								OrdemServicoParcela.IdOrdemServico, 
								OrdemServicoParcela.MesReferencia, 
								OrdemServicoParcela.Vencimento 
							from 
								OrdemServicoParcela 
							where 
								OrdemServicoParcela.IdLoja = $IdLoja 
							group by 
								OrdemServicoParcela.IdOrdemServico 
							order by 
								OrdemServicoParcela.Vencimento Asc 
						) OrdemServicoParcela ON (
							OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico
						) LEFT JOIN Pessoa ON (
							OrdemServico.IdPessoa = Pessoa.IdPessoa
						) left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						),
						TipoOrdemServico
					where
						OrdemServico.IdLoja = $IdLoja and
						OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico $where
					group by
						OrdemServico.IdOrdemServico$Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){	
			if($lin[IdGrupoAtendimento] == ''){
				$sql2	=	"SELECT IdGrupoUsuarioAtendimento, LoginAtendimento from OrdemServico,OrdemServicoHistorico where OrdemServico.IdOrdemServico = OrdemServicoHistorico.IdOrdemServico and OrdemServicoHistorico.IdLoja = $IdLoja and OrdemServicoHistorico.IdLoja = OrdemServico.IdLoja $where and OrdemServicoHistorico.IdGrupoUsuarioAtendimento != '' order by DataHora DESC limit 0,1";
				$res2	=	@mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);
				
				if($lin[IdGrupoUsuarioAtendimento] ==	''){	
					$lin[IdGrupoUsuarioAtendimento]	=	$lin2[IdGrupoUsuarioAtendimento];	
					$lin[LoginAtendimento]			=	$lin2[LoginAtendimento];						
				}
			}
			
			if($lin[IdAgenteAutorizado] != ""){
				$sql2 = "select 
							Pessoa.RazaoSocial,
							Pessoa.Nome,
							Pessoa.TipoPessoa 
						from 
							Loja,
							AgenteAutorizado,
							Pessoa 
						where 
							AgenteAutorizado.IdLoja = $IdLoja and 
							AgenteAutorizado.IdLoja = Loja.IdLoja and 
							AgenteAutorizado.IdAgenteAutorizado = $lin[IdAgenteAutorizado] and 
							AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa";
				$res2 = mysql_query($sql2,$con);
				$lin2 = mysql_fetch_array($res2);
				$lin[NomeAgenteAutorizado] = $lin2[getCodigoInterno(3,24)];	
			}
			
			if($lin[FormaCobranca] == 1){
				$lin[Vencimento]	=	$lin[MesReferencia];
			}

			$sql4 = "select IdServico IdServicoContrato from Contrato where IdLoja=$local_IdLoja and IdContrato = $lin[IdContrato]";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);
			
			$sql5 = "select 
						DescricaoServico,
						DetalheDemonstrativoTerceiro
					from 
						Servico 
					where 
						IdLoja=$IdLoja and 
						IdServico = $lin[IdServico]";
			$res5 = @mysql_query($sql5,$con);
			$lin5 = @mysql_fetch_array($res5);
			
			$EditarTerceiro = 0;
			if($lin[IdTerceiro] == ''){
				$EditarTerceiro		= 1;
			}
			if($lin[IdTerceiro] != ''){
				$lin[IdTerceiro]	= $lin[IdTerceiro];
			}
			
			if($lin[FormaCobranca] == 1){
				$lin[Vencimento]	=	$lin[MesReferencia];
			}
			
			######## verifica permissao fatura ########################
			if(permissaoSubOperacao($localModulo, 57, 'U') == false){
				$local_PermissaoFatura		= "flase";	
			}
			
			######################################################
			$PermissaoGeralOsTipoAtendimento 	= getCodigoInterno(3,103);
			$PermissaoGeralOsTipoInterno 		= getCodigoInterno(3,109);
			
			if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
				$local_TempoAbertura = "";			
			}else{
				$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
			}
			
			$sql6	=	"select 
							OrdemServico.IdTipoOrdemServico,
							Servico.IdOrdemServicoLayout 
						from 
							OrdemServico left Join Servico ON (Servico.IdLoja = OrdemServico.IdLoja and	Servico.IdServico = OrdemServico.IdServico)
						where
							OrdemServico.IdLoja = $IdLoja $where2";
			$res6	=	mysql_query($sql6,$con);
			$lin6	=	mysql_fetch_array($res6);
			
			if($lin6[IdTipoOrdemServico]!="2"){
				$local_IdOrdemServicoLayoutImprimir = $lin6[IdOrdemServicoLayout];
			}else{
				$local_IdOrdemServicoLayoutImprimir	=	getCodigoInterno(3,104);
			}
			
			$sql7 = "select
						IdLancamentoFinanceiro, 
						sum(ValorRepasseTerceiro) ValorRepasseTerceiro 
					from
						LancamentoFinanceiro
					where
						IdLoja = $lin[IdLoja] and
						IdOrdemServico = $lin[IdOrdemServico];";
			$res7 = @mysql_query($sql7,$con);
			$lin7 = @mysql_fetch_array($res7);
			
			$sql8 = "select Nome from Pessoa where IdPessoa = '$lin[IdTerceiro]';";
			$res8 = @mysql_query($sql8,$con);
			$lin8 = @mysql_fetch_array($res8);
			
			switch($lin[IdMarcador]){
				case 1:
					$CorMarcador1 = getParametroSistema(155, 1);
					$CorMarcador2 = getParametroSistema(156, 2);
					$CorMarcador3 = getParametroSistema(156, 3);
					break;
				case 2:
					$CorMarcador1 = getParametroSistema(156, 1);
					$CorMarcador2 = getParametroSistema(155, 2);
					$CorMarcador3 = getParametroSistema(156, 3);
					break;
				case 3:
					$CorMarcador1 = getParametroSistema(156, 1);
					$CorMarcador2 = getParametroSistema(156, 2);
					$CorMarcador3 = getParametroSistema(155, 3);
					break;
				default:
					$CorMarcador1 = getParametroSistema(156, 1);
					$CorMarcador2 = getParametroSistema(156, 2);
					$CorMarcador3 = getParametroSistema(156, 3);
					break;
			}
			
			$sql = "select
						count(*) QtdContaReceberAguardandoPagamento
					from
						LancamentoFinanceiroDados
					where
						IdLoja = $IdLoja and
						IdOrdemServico = $lin[IdOrdemServico] and
						IdStatusContaReceber = 1";
			$res9 =	@mysql_query($sql,$con);
			$lin9 =	@mysql_fetch_array($res9);
			
			$sql10="select 
						OrdemServico.IdLocalCobranca
					from
						OrdemServico,
						LocalCobranca 
					where
						OrdemServico.IdLoja = $IdLoja and
						OrdemServico.IdLoja = LocalCobranca.IdLoja and
						IdOrdemServico = $lin[IdOrdemServico] and
						OrdemServico.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
			$res10 =	@mysql_query($sql10,$con);
			$lin10 =	@mysql_fetch_array($res10);
			
			/*if($lin[IdStatus] >= "500"){
				$lin[Status] = getParametroSistema(40,$lin[IdStatus]);
			}*/
			
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<IdTipoOrdemServico><![CDATA[$lin[IdTipoOrdemServico]]]></IdTipoOrdemServico>";
			$dados	.=	"\n<IdSubTipoOrdemServico><![CDATA[$lin[IdSubTipoOrdemServico]]]></IdSubTipoOrdemServico>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
			$dados	.=	"\n<IdServicoContrato><![CDATA[$lin4[IdServicoContrato]]]></IdServicoContrato>";
			$dados	.=	"\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
			$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
			$dados	.=	"\n<IdLocalCobrancaF><![CDATA[$lin10[IdLocalCobranca]]]></IdLocalCobrancaF>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<IdGrupoUsuarioAtendimento><![CDATA[$lin[IdGrupoUsuarioAtendimento]]]></IdGrupoUsuarioAtendimento>";
			$dados	.=	"\n<LoginAtendimento><![CDATA[$lin[LoginAtendimento]]]></LoginAtendimento>";
			$dados	.=	"\n<ValorOutros><![CDATA[$lin[ValorOutros]]]></ValorOutros>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
			$dados	.=	"\n<DataAgendamentoAtendimento><![CDATA[$lin[DataAgendamentoAtendimento]]]></DataAgendamentoAtendimento>";
			$dados	.=	"\n<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
			$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Carne><![CDATA[$lin[Carne]]]></Carne>";
			$dados	.=	"\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
			$dados	.=	"\n<Faturado><![CDATA[$lin[Faturado]]]></Faturado>";
			$dados	.=	"\n<FormaCobranca><![CDATA[$lin[FormaCobranca]]]></FormaCobranca>";
			$dados	.=	"\n<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
			$dados	.=	"\n<DescricaoCDA><![CDATA[$lin[DescricaoCDA]]]></DescricaoCDA>";
			$dados	.=	"\n<IdContratoFaturamento><![CDATA[$lin[IdContratoFaturamento]]]></IdContratoFaturamento>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin5[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<DataPrimeiroVencimento><![CDATA[$lin[Vencimento]]]></DataPrimeiroVencimento>";
			$dados	.=	"\n<DescTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescTipoOrdemServico>";
			$dados	.=	"\n<IdPessoaEndereco><![CDATA[$lin[IdPessoaEndereco]]]></IdPessoaEndereco>";
			$dados	.=	"\n<IdPessoaEnderecoCobranca><![CDATA[$lin[IdPessoaEnderecoCobranca]]]></IdPessoaEnderecoCobranca>";
			$dados	.=	"\n<IdMarcador><![CDATA[$lin[IdMarcador]]]></IdMarcador>";
			$dados	.=	"\n<PermissaoFatura><![CDATA[$local_PermissaoFatura]]></PermissaoFatura>";
			$dados	.=	"\n<PermissaoGeralOsTipoAtendimento><![CDATA[$PermissaoGeralOsTipoAtendimento]]></PermissaoGeralOsTipoAtendimento>";
			$dados	.=	"\n<PermissaoGeralOsTipoInterno><![CDATA[$PermissaoGeralOsTipoInterno]]></PermissaoGeralOsTipoInterno>";
			$dados	.=	"\n<TempoAbertura><![CDATA[$local_TempoAbertura]]></TempoAbertura>";
			$dados	.=	"\n<Justificativa><![CDATA[$lin[DescricaoOutros]]]></Justificativa>";
			$dados	.=	"\n<IdTerceiro><![CDATA[$lin[IdTerceiro]]]></IdTerceiro>";
			$dados	.=	"\n<EditarTerceiro><![CDATA[$EditarTerceiro]]></EditarTerceiro>";
			$dados	.=	"\n<NomeTerceiro><![CDATA[$lin8[Nome]]]></NomeTerceiro>";
			$dados	.=	"\n<DetalheDemonstrativoTerceiro><![CDATA[$lin5[DetalheDemonstrativoTerceiro]]]></DetalheDemonstrativoTerceiro>";
			$dados	.=	"\n<IdLancamentoFinanceiro><![CDATA[$lin7[IdLancamentoFinanceiro]]]></IdLancamentoFinanceiro>";
			$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin7[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
			$dados	.=	"\n<IdOrdemServicoLayoutImprimir><![CDATA[$local_IdOrdemServicoLayoutImprimir]]></IdOrdemServicoLayoutImprimir>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataConclusao><![CDATA[$lin[DataConclusao]]]></DataConclusao>";
			$dados	.=	"\n<LoginConclusao><![CDATA[$lin[LoginConclusao]]]></LoginConclusao>";
			$dados	.=	"\n<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
			$dados	.=	"\n<LoginFaturamento><![CDATA[$lin[LoginFaturamento]]]></LoginFaturamento>";
			$dados	.=	"\n<CorMarcador1><![CDATA[$CorMarcador1]]></CorMarcador1>";
			$dados	.=	"\n<CorMarcador2><![CDATA[$CorMarcador2]]></CorMarcador2>";
			$dados	.=	"\n<CorMarcador3><![CDATA[$CorMarcador3]]></CorMarcador3>";
			$dados	.=	"\n<IdAgenteAutorizado><![CDATA[$lin[IdAgenteAutorizado]]]></IdAgenteAutorizado>";
			$dados	.=	"\n<NomeAgenteAutorizado><![CDATA[$lin[NomeAgenteAutorizado]]]></NomeAgenteAutorizado>";
			$dados	.=	"\n<IdCarteira><![CDATA[$lin[IdCarteira]]]></IdCarteira>";
			$dados	.=	"\n<QtdContaReceberAguardandoPagamento>$lin9[QtdContaReceberAguardandoPagamento]</QtdContaReceberAguardandoPagamento>";
			$dados	.=	"\n<IdContaDebito><![CDATA[$lin[IdContaDebito]]]></IdContaDebito>";
			$dados	.=	"\n<IdCartao><![CDATA[$lin[IdCartao]]]></IdCartao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Ordem_Servico();
?>