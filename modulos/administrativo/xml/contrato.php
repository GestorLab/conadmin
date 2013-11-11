<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Contrato(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$Nome 					= $_GET['Nome'];
		$CPF_CNPJ				= $_GET['CPF_CNPJ'];
		$DescricaoServico	  	= $_GET['DescricaoServico'];
		$Periodicidade		  	= $_GET['Periodicidade'];
		$IdPeriodicidade		= $_GET['IdPeriodicidade'];
		$QtdParcela			  	= $_GET['QtdParcela'];
		$TipoContrato		  	= $_GET['TipoContrato'];
		$IdLocalCobranca	  	= $_GET['IdLocalCobranca'];
		$MesFechado			  	= $_GET['MesFechado'];
		$IdStatus			  	= $_GET['IdStatus'];
		$IdStatusExc		  	= $_GET['IdStatusExc'];
		$IdPessoa			  	= $_GET['IdPessoa'];
		$IdOrdemServico		  	= $_GET['IdOrdemServico'];
		$IdContaEventual	  	= $_GET['IdContaEventual'];
		$IdContaReceber	  		= $_GET['IdContaReceber'];
		$DataCancelamento		= dataConv($_GET['DataCancelamento'], 'd/m/Y', 'Y-m-d');
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdContrato'] != ''){		$where .= " and Contrato.IdContrato in (".$_GET['IdContrato'].")";	}
		
		if($Nome !=''){					$where .= " and (Pessoa.Nome like '$Nome%' or Pessoa.RazaoSocial like '$Nome%')";	}
		if($CPF_CNPJ != ''){			$where .= " and Pessoa.CPF_CNPJ like '$CPF_CNPJ%'";	}
		if($DescricaoServico !=''){		$where .= " and Servico.DescricaoServico like '$DescricaoServico%'"; }
		if($IdPeriodicidade != ""){		$where .= " and Contrato.IdPeriodicidade = $IdPeriodicidade";		}
		if($QtdParcela != ""){			$where .= " and Contrato.QtdParcela = $QtdParcela";		}
		if($TipoContrato != ""){		$where .= " and Contrato.TipoContrato IN ($TipoContrato)";		}
		if($IdLocalCobranca != ""){		$where .= " and Contrato.IdLocalCobranca = $IdLocalCobranca";		}
		if($MesFechado != ""){			$where .= " and Contrato.MesFechado = $MesFechado";		}
		if($IdStatus != ""){			$where .= " and Contrato.IdStatus = $IdStatus";		}
		if($IdStatusExc != ""){			$where .= " and Contrato.IdStatus != $IdStatusExc";		}
		if($IdPessoa != ""){			$where .= " and Contrato.IdPessoa = $IdPessoa";		}
		
		if($IdOrdemServico != ""){
			$IdContratoAux	= 0;
			$i = 0;
			
			$sql = "select 
						OrdemServico.IdContrato
					from
						OrdemServico
					where
						OrdemServico.IdLoja = $IdLoja and
						OrdemServico.IdOrdemServico = $IdOrdemServico";
			$resOrdemServico	=	@mysql_query($sql,$con);
			while($linOrdemServico	=	@mysql_fetch_array($resOrdemServico)){				
				if($i == 0){
					$IdContratoAux = $linOrdemServico[IdContrato];
					$i = 1;
				}else{
					$IdContratoAux .= ",".$linOrdemServico[IdContrato];
				}
			}
			$where .= " and Contrato.IdContrato in ($IdContratoAux)";
		}
		
		if($IdContaEventual != ""){
			$IdContratoAux	= 0;
			$i = 0;
			
			$sql = "select 
						ContaEventual.IdContrato
					from
						ContaEventual
					where
						ContaEventual.IdLoja = $IdLoja and
						ContaEventual.IdContaEventual = $IdContaEventual";
			
			$resContaEventual	=	@mysql_query($sql,$con);
			while($linContaEventual	=	@mysql_fetch_array($resContaEventual)){				
				if($i == 0){
					$IdContratoAux = $linContaEventual[IdContrato];
					$i = 1;
				}else{
					$IdContratoAux .= ",".$linContaEventual[IdContrato];
				}
			}
			if($IdContratoAux != ""){
				$where .= " and Contrato.IdContrato in ($IdContratoAux)";
			}
		}
		
		if($IdContaReceber != ""){
			$IdContratoAux	= 0;
			$i = 0;
			$sql = "select 
						LancamentoFinanceiro.IdContrato
					from
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber
					where
						LancamentoFinanceiro.IdLoja = $IdLoja and
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
						LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber";
			$resLancamentoFinanceiro	=	@mysql_query($sql,$con);
			while($linLancamentoFinanceiro	=	@mysql_fetch_array($resLancamentoFinanceiro)){				
				if($i == 0){
					$IdContratoAux = $linLancamentoFinanceiro[IdContrato];
					$i = 1;
				}else{
					$IdContratoAux .= ",".$linLancamentoFinanceiro[IdContrato];
				}
			}
			if($IdContratoAux != ""){
				$where .= " and Contrato.IdContrato in ($IdContratoAux)";
			}
		}
		/*
		if($_SESSION["RestringirCarteira"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato 
							   from 
									AgenteAutorizadoPessoa,
									Carteira 
							   where 
									AgenteAutorizadoPessoa.IdLoja = $IdLoja and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
									AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
									Carteira.IdCarteira = $IdPessoaLogin and 
									Carteira.Restringir = 1 and 
									Carteira.IdStatus = 1
								) ContratoCarteira";
			$where .=  " and  Contrato.IdContrato = ContratoCarteira.IdContrato";
		}else{
			if($_SESSION["RestringirAgenteAutorizado"] == true){
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
										AgenteAutorizado.Restringir = 1 and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteAutorizado";
				$where .=  " and  Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
			}
			if($_SESSION["RestringirAgenteCarteira"] == true){
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado,
										Carteira
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
										AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
										Carteira.IdCarteira = $IdPessoaLogin and 
										AgenteAutorizado.Restringir = 1 and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteCarteira";
				$where .=  " and  Contrato.IdContrato = ContratoAgenteCarteira.IdContrato";
			}
		}
		*/
		$sqlAgente="select 
						Restringir
					from
						AgenteAutorizado 
					where 
						AgenteAutorizado.IdLoja = $IdLoja and
						AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and
						AgenteAutorizado.IdStatus = 1 ";
		$resAgente = mysql_query($sqlAgente,$con);
		if($linAgente = mysql_fetch_array($resAgente)){
			
			$sqlCarteira="	select 
								Restringir 
							from
								Carteira 
							where
								Carteira.IdLoja = $IdLoja and
								Carteira.IdCarteira = $IdPessoaLogin and
								Carteira.IdStatus = 1";
			$resCarteira = mysql_query($sqlCarteira,$con);
			$linCarteira = mysql_fetch_array($resCarteira);
			
			if($linAgente["Restringir"] == '1'){
				$restringirAgente = "AgenteAutorizado.Restringir = '$linAgente[Restringir]' and";
				if($linCarteira["Restringir"] == '1'){
					$restringirCarteira = "AgenteAutorizadoPessoa.IdCarteira = $IdPessoaLogin and";
				}else{
					$restringirCarteira = "";
				}
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado,
										Carteira
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
										$restringirAgente
										$restringirCarteira
										AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteAutorizado";
				$where .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
				//$filtro_cancelado = '';
			}
		}
		
		$sql	=	"select
						Contrato.IdLoja,
						Contrato.IdContrato,
						Contrato.IdPessoa,
						Pessoa.TipoPessoa,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						Pessoa.NomeRepresentante,
						Pessoa.CPF_CNPJ,
						Pessoa.RG_IE,
						Pessoa.Email,
						Pessoa.DataNascimento,
						Pessoa.IdEnderecoDefault,
					    Pessoa.InscricaoMunicipal,
					    Pessoa.Telefone1,
					    Pessoa.Telefone2,
					    Pessoa.Telefone3,					
					    Pessoa.Celular,
 				        Pessoa.Fax,	
				        Pessoa.ComplementoTelefone,
						Contrato.IdServico, 
						Servico.DescricaoServico,
						Servico.UrlContratoImpresso,
						Servico.UrlDistratoImpresso,
						Servico.AtivacaoAutomatica,
						Servico.IdNotaFiscalTipo,
						ServicoGrupo.DescricaoServicoGrupo,
						Contrato.DataInicio,
						Contrato.DataTermino,
						Contrato.DataBaseCalculo,
						Contrato.DataPrimeiraCobranca,
						Contrato.DataUltimaCobranca,
						Contrato.IdContaDebito,
						Contrato.IdCartao,
						Contrato.AssinaturaContrato,
						Contrato.IdAgenteAutorizado,
						Contrato.IdCarteira,
						Contrato.IdPeriodicidade,
						Contrato.QtdParcela,
						Contrato.IdLocalCobranca,
						Contrato.CFOP,
						Contrato.DiaCobranca,
						LocalCobranca.DescricaoLocalCobranca,
						LocalCobranca.IdTipoLocalCobranca,
						LocalCobranca.AbreviacaoNomeLocalCobranca,
						Contrato.IdContratoAgrupador,
						Contrato.AdequarLeisOrgaoPublico,
						Contrato.NotaFiscalCDA,
						Contrato.DataCriacao, 
						Contrato.LoginCriacao, 
						Contrato.DataAlteracao, 
						Contrato.LoginAlteracao,
						Contrato.TipoContrato, 
						Contrato.IdStatus,
						Contrato.VarStatus,
						Contrato.IdTerceiro,
						Contrato.Obs,
						Periodicidade.DescricaoPeriodicidade,
						Contrato.MesFechado,
						Contrato.QtdMesesFidelidade,
						Contrato.MultaFidelidade,
						Contrato.IdPessoaEndereco,
						Contrato.IdPessoaEnderecoCobranca
					from 
						Loja,
						Contrato,
						Pessoa,
						Servico,
						Periodicidade,
						ServicoGrupo,
						LocalCobranca $sqlAux 
					where 
						Contrato.IdLoja = $IdLoja and
						Contrato.IdLoja = Loja.IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdLoja = Periodicidade.IdLoja and
						Contrato.IdLoja = LocalCobranca.IdLoja and
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Contrato.IdServico = Servico.IdServico and
						ServicoGrupo.IdLoja = Servico.IdLoja and
						ServicoGrupo.IdServicoGrupo = Servico.IdServicoGrupo and
						Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade and
						Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca
						$where 
					order by
						Contrato.IdContrato DESC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header("content-type: text/xml");
			
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin	=	@mysql_fetch_array($res)){
				if($lin[IdAgenteAutorizado] != ""){
					$sql2 = "select Pessoa.RazaoSocial,Pessoa.Nome,Pessoa.TipoPessoa from Loja,AgenteAutorizado,Pessoa where AgenteAutorizado.IdLoja = $IdLoja and AgenteAutorizado.IdLoja = Loja.IdLoja and AgenteAutorizado.IdAgenteAutorizado = $lin[IdAgenteAutorizado] and AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa";
					$res2 = mysql_query($sql2,$con);
					$lin2 = mysql_fetch_array($res2);
					$lin2[Nome]	=	$lin2[getCodigoInterno(3,24)];
				}
				
				$sql3 = "select 
							Valor,
							ValorRepasseTerceiro,
							ValorDesconto,
							IdTipoDesconto 
						from 
							ContratoVigencia 
						where 
							DataInicio <= curdate() and 
							(
								DataTermino is Null or 
								DataTermino >= curdate()
							) and 
							IdLoja = $IdLoja and 
							IdContrato = $lin[IdContrato] 
						order BY 
							DataInicio DESC LIMIT 0,1";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				if($lin3[IdTipoDesconto] == 1){
					$lin3[ValorFinal] = $lin3[Valor] - $lin3[ValorDesconto];
				} else{
					$lin3[ValorFinal] = $lin3[Valor];
				}
				
				$sql5	="	select 
								count(*) QtdLancamentos
							from
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber,
								ContaReceber,
								Demonstrativo
							where
								LancamentoFinanceiro.IdLoja = $IdLoja and
								LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and
								Demonstrativo.IdLoja = ContaReceber.IdLoja and
								LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
								LancamentoFinanceiro.IdContrato=$lin[IdContrato] and
								LancamentoFinanceiro.IdStatus != 0 and
								LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
								Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber and
								ContaReceber.IdStatus != 0 and
								Demonstrativo.Tipo = 'CO'"; 
				$res5	=	@mysql_query($sql5,$con);
				$lin5	=	@mysql_fetch_array($res5);
				
				$sql12	=	"select count(*) QtdLancamentosCancelado from LancamentoFinanceiro,LancamentoFinanceiroContaReceber,ContaReceber where LancamentoFinanceiro.IdLoja = $IdLoja and LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and  LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and LancamentoFinanceiro.IdContrato=$lin[IdContrato] and LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and ContaReceber.IdStatus = 0"; 
				$res12	=	@mysql_query($sql12,$con);
				$lin12	=	@mysql_fetch_array($res12);
				
				// Calcula dataReferenciaFinal
				if($lin[AtivacaoAutomatica] == 2 and $lin12[QtdLancamentosCancelado] == 0){
					$DataPrimeiraCobranca		= $lin[DataPrimeiraCobranca];
					$DataPrimeiraCobrancaAno	= substr($DataPrimeiraCobranca,0,4);
					$DataPrimeiraCobrancaMes	= substr($DataPrimeiraCobranca,5,2);
					$DataPrimeiraCobrancaDia	= substr($DataPrimeiraCobranca,8,2);
					$MesReferenciaInicial		= $DataPrimeiraCobrancaMes."/".$DataPrimeiraCobrancaAno;
					$MesReferenciaFinal			= incrementaMesReferencia($MesReferenciaInicial, $lin[QtdParcela]);
					$MesReferenciaFinalAno		= substr($MesReferenciaFinal,3,4);
					$MesReferenciaFinalMes		= substr($MesReferenciaFinal,0,2);
					$MesReferenciaFinalDia		= ultimoDiaMes($MesReferenciaFinalMes, $MesReferenciaFinalAno);

					if($DataPrimeiraCobrancaDia < $MesReferenciaFinalDia){
						$MesReferenciaFinalDia = $DataPrimeiraCobrancaDia;
					}
					$DataReferenciaFinal = $MesReferenciaFinalAno."-".$MesReferenciaFinalMes."-".$MesReferenciaFinalDia;
					$DataReferenciaFinal = incrementaData($DataReferenciaFinal,-1);
				}
				
				$sql6 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema = $lin[TipoContrato]";
				$res6 = @mysql_query($sql6,$con);
				$lin6 = @mysql_fetch_array($res6);
				
				$sql7 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=70 and IdParametroSistema = $lin[MesFechado]";
				$res7 = @mysql_query($sql7,$con);
				$lin7 = @mysql_fetch_array($res7);
				
				$sql8 = "select ValorParametroSistema Status  from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema=$lin[IdStatus]";
				$res8 = @mysql_query($sql8,$con);
				$lin8 = @mysql_fetch_array($res8);
				
				
				if($lin[VarStatus] != ''){
					switch($lin[IdStatus]){
						case '201':
							$lin8[Status]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin8[Status]);	
							break;
					}					
				}
				
				$IdStatus	=	substr($lin[IdStatus],0,1);
				
				switch($IdStatus){
					case '2':
						$Color	  = getParametroSistema(15,3);
						break;
					case '3':
						$Color	  = getParametroSistema(15,2);
						break;
					default:
						$Color	  = "";
						break;
				}	
				
				$lin[DescricaoContratoAgrupador]	=	$lin[DescricaoServico];
				
				if($lin[TipoPessoa]=='1'){
					$lin[NomePessoa]	=	$lin[getCodigoInterno(3,24)];	
				}else{
					$lin[NomePessoa]	=	$lin[Nome];
				}
				
				$ServicoAutomatico	=	"";
				
				$sql9	=	"select 
								ContratoAutomatico.IdContratoAutomatico,
								Contrato.IdServico,
								Contrato.IdAgenteAutorizado,
								Contrato.IdCarteira 
							from 
								(select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from 	ContratoAutomatico where ContratoAutomatico.IdLoja = $IdLoja and ContratoAutomatico.IdContrato = $lin[IdContrato])
								ContratoAutomatico, Contrato 
							where 
								Contrato.IdLoja = $IdLoja and 
								Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
				$res9 	= 	@mysql_query($sql9,$con);
				while($lin9 = @mysql_fetch_array($res9)){
					if($ServicoAutomatico != "")	$ServicoAutomatico	.=	"#";
					$ServicoAutomatico	  .=	$lin9[IdServico]."¬".$lin9[IdContratoAutomatico];
				}
				
				$sql10	=	"select IdContrato IdContratoPai from ContratoAutomatico where IdLoja = $IdLoja and IdContratoAutomatico = $lin[IdContrato] group by	IdContrato";
				$res10 	= 	@mysql_query($sql10,$con);
				$lin10  = 	@mysql_fetch_array($res10);
				
				$sql11	=	"select							
								sum(LancamentoFinanceiro.Valor) ValorCredito
							from
								ContaReceberRecebimento,
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber
							where
								LancamentoFinanceiro.IdContrato = '$lin[IdContrato]' and 
								LancamentoFinanceiro.IdLoja = $IdLoja and
								LancamentoFinanceiro.IdLoja = ContaReceberRecebimento.IdLoja and
								LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
								LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
								ContaReceberRecebimento.IdStatus='1' and
								LancamentoFinanceiro.DataReferenciaInicial >= '$DataCancelamento'";
				$res11 	= 	@mysql_query($sql11,$con);
				$lin11  = 	@mysql_fetch_array($res11);
				
				$sql12	=	"select 
								Cidade.NomeCidade 
							from 
								PessoaEndereco,
								Pais,
								Estado,
								Cidade 
							where
								PessoaEndereco.IdPessoa = $lin[IdPessoa] and
								PessoaEndereco.IdPessoaEndereco = $lin[IdEnderecoDefault] and
								PessoaEndereco.IdPais = Pais.IdPais and
								PessoaEndereco.IdEstado = Estado.IdEstado and	
								Pais.IdPais = Estado.IdPais and
								PessoaEndereco.IdCidade = Cidade.IdCidade and
								Estado.IdEstado = Cidade.IdEstado";
				$res12 	= 	@mysql_query($sql12,$con);
				$lin12  = 	@mysql_fetch_array($res12);
		
				$sql14 = "select
							  max(DataAlteracao) DataAlteracao
						  from
							  ContratoStatus
						  where
							  IdLoja = $IdLoja and
							  IdContrato = $lin[IdContrato]";
							  
				$res14	=	mysql_query($sql14,$con);
				$lin14	=	mysql_fetch_array($res14);
				
				if($lin14[DataAlteracao] != ""){
					$local_StatusTempoAlteracao = ' ('.diferencaData($lin14[DataAlteracao], date("Y-m-d H:i:s")).')';
				}else{
					$local_StatusTempoAlteracao = "";
				}
				
				$sql15 = "select 
							ValorParametroSistema 
						from 
							ParametroSistema 
						where 
							IdGrupoParametroSistema = 73 and 
							IdParametroSistema = $lin3[IdTipoDesconto]";
				$res15 = @mysql_query($sql15,$con);
				$lin15 = @mysql_fetch_array($res15);
				
				$sql16 = "select IdOrdemServico from OrdemServico where IdLoja = $IdLoja and IdContrato = '$lin[IdContrato]' and OrdemServico.IdStatus > 99 LIMIT 0,1";
				$res16 = mysql_query($sql16,$con);
				$lin16 = mysql_fetch_array($res16);
				
				$sql17 = "select count(*) Qtd from MonitorContrato where IdLoja = '$IdLoja' and IdContrato = '$lin[IdContrato]';";
				$res17 = mysql_query($sql17,$con);
				$lin17 = mysql_fetch_array($res17);
				
				// Primeira referência ultilazada na OS e EV
				if($lin[DataBaseCalculo] == ''){
					$lin[DataPrimeiraReferencia] = dataConv($lin[DataPrimeiraCobranca],"Y-m-d","m/Y");
				}else{			
					$lin[DataPrimeiraReferencia] = incrementaData($lin[DataBaseCalculo],1);
					$lin[DataPrimeiraReferencia] = dataConv($lin[DataPrimeiraReferencia],"Y-m-d","m/Y");
				}		
				
				$LancamentoFinanceiroTipoContrato = "";
				$sql19 = "select 
								IdLancamentoFinanceiro
							from
								LancamentoFinanceiro
							where 
								IdLoja = '$IdLoja' and 	 
								IdContrato = '$lin[IdContrato]' and 
								IdOrdemServico is NULL and
								IdContaEventual is NULL and
								IdEncargoFinanceiro is NULL and
								IdStatus != 0;";
				$res19 = mysql_query($sql19,$con);				
				while($lin19 = mysql_fetch_array($res19)){
					if($LancamentoFinanceiroTipoContrato != ''){
						$LancamentoFinanceiroTipoContrato .= ',';
					}					
					$LancamentoFinanceiroTipoContrato .= $lin19[IdLancamentoFinanceiro];
				}
				
				$dados	.=	"\n<IdContrato>$lin[IdContrato]</IdContrato>";
				$dados	.=	"\n<IdContratoPai><![CDATA[$lin10[IdContratoPai]]]></IdContratoPai>";
				$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
				$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados	.=	"\n<NomePessoa><![CDATA[$lin[NomePessoa]]]></NomePessoa>";
				$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
				$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
				$dados	.=	"\n<NomeRepresentante><![CDATA[$lin[NomeRepresentante]]]></NomeRepresentante>";
				$dados	.=	"\n<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
				$dados	.=	"\n<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
				$dados	.=	"\n<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
				$dados	.=	"\n<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
				$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
				$dados	.=	"\n<DataNascimento><![CDATA[$lin[DataNascimento]]]></DataNascimento>";
				$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
				$dados	.=	"\n<Fax><![CDATA[$lin[Fax]]]></Fax>";
				$dados	.=	"\n<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";		
				$dados	.=	"\n<NomeCidade><![CDATA[$lin12[NomeCidade]]]></NomeCidade>";
				$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
				$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				$dados	.=	"\n<DescricaoContratoAgrupador><![CDATA[$lin[DescricaoContratoAgrupador]]]></DescricaoContratoAgrupador>";
				$dados	.=	"\n<Periodicidade><![CDATA[$lin[Periodicidade]]]></Periodicidade>";
				$dados	.=	"\n<DescPeriodicidade><![CDATA[$lin[DescricaoPeriodicidade]]]></DescPeriodicidade>";
				$dados	.=	"\n<DescricaoContrato><![CDATA[$lin[DescricaoContrato]]]></DescricaoContrato>";
				$dados	.=	"\n<ServicoAutomatico><![CDATA[$ServicoAutomatico]]></ServicoAutomatico>";
				$dados	.=	"\n<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
				$dados	.=	"\n<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
				$dados	.=	"\n<DataBaseCalculo><![CDATA[$lin[DataBaseCalculo]]]></DataBaseCalculo>";
				$dados	.=	"\n<DataPrimeiraCobranca><![CDATA[$lin[DataPrimeiraCobranca]]]></DataPrimeiraCobranca>";
				$dados	.=	"\n<DataUltimaCobranca><![CDATA[$lin[DataUltimaCobranca]]]></DataUltimaCobranca>";
				$dados	.=	"\n<AssinaturaContrato>$lin[AssinaturaContrato]</AssinaturaContrato>";
				$dados	.=	"\n<IdAgenteAutorizado><![CDATA[$lin[IdAgenteAutorizado]]]></IdAgenteAutorizado>";
				$dados	.=	"\n<NomeAgenteAutorizado><![CDATA[$lin2[Nome]]]></NomeAgenteAutorizado>";
				$dados	.=	"\n<IdCarteira><![CDATA[$lin[IdCarteira]]]></IdCarteira>";
				$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
				$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
				$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
				$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
				$dados	.=	"\n<DiaCobranca><![CDATA[$lin[DiaCobranca]]]></DiaCobranca>";
				$dados	.=	"\n<AdequarLeisOrgaoPublico><![CDATA[$lin[AdequarLeisOrgaoPublico]]]></AdequarLeisOrgaoPublico>";
				$dados	.=	"\n<NotaFiscalCDA><![CDATA[$lin[NotaFiscalCDA]]]></NotaFiscalCDA>";
				$dados	.=	"\n<TipoContrato><![CDATA[$lin[TipoContrato]]]></TipoContrato>";
				$dados	.=	"\n<DescTipoContrato><![CDATA[$lin6[ValorParametroSistema]]]></DescTipoContrato>";
				$dados	.=	"\n<IdContratoAgrupador><![CDATA[$lin[IdContratoAgrupador]]]></IdContratoAgrupador>";
				$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
				$dados	.=	"\n<UrlContratoImpresso><![CDATA[$lin[UrlContratoImpresso]]]></UrlContratoImpresso>";
				$dados	.=	"\n<UrlDistratoImpresso><![CDATA[$lin[UrlDistratoImpresso]]]></UrlDistratoImpresso>";
				$dados	.=	"\n<IdPeriodicidade><![CDATA[$lin[IdPeriodicidade]]]></IdPeriodicidade>";
				$dados	.=	"\n<IdPessoaEndereco><![CDATA[$lin[IdPessoaEndereco]]]></IdPessoaEndereco>";
				$dados	.=	"\n<IdPessoaEnderecoCobranca><![CDATA[$lin[IdPessoaEnderecoCobranca]]]></IdPessoaEnderecoCobranca>";
				$dados	.=	"\n<IdEnderecoDefault><![CDATA[$lin[IdEnderecoDefault]]]></IdEnderecoDefault>";
				$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<QtdLancamentos><![CDATA[$lin5[QtdLancamentos]]]></QtdLancamentos>";
				$dados	.=	"\n<QtdLancamentosCancelado><![CDATA[$lin12[QtdLancamentosCancelado]]]></QtdLancamentosCancelado>";
				$dados	.=	"\n<Valor><![CDATA[$lin3[Valor]]]></Valor>";
				$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin3[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
				$dados	.=	"\n<ValorDesconto><![CDATA[$lin3[ValorDesconto]]]></ValorDesconto>";
				$dados	.=	"\n<ValorFinal><![CDATA[$lin3[ValorFinal]]]></ValorFinal>";
				$dados	.=	"\n<AtivacaoAutomatica>$lin[AtivacaoAutomatica]</AtivacaoAutomatica>";	
				$dados	.=	"\n<DataReferenciaFinal><![CDATA[$DataReferenciaFinal]]></DataReferenciaFinal>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.=	"\n<VarStatus><![CDATA[$lin[VarStatus]]]></VarStatus>";	
				$dados	.=	"\n<MesFechado><![CDATA[$lin[MesFechado]]]></MesFechado>";	
				$dados	.=	"\n<DescMesFechado><![CDATA[$lin7[ValorParametroSistema]]]></DescMesFechado>";
				$dados	.=	"\n<QtdMesesFidelidade><![CDATA[$lin[QtdMesesFidelidade]]]></QtdMesesFidelidade>";	
				$dados	.=	"\n<Status><![CDATA[$lin8[Status]]]></Status>";	
				$dados	.=	"\n<MultaFidelidade><![CDATA[$lin[MultaFidelidade]]]></MultaFidelidade>";
				$dados	.=	"\n<ValorCredito><![CDATA[$lin11[ValorCredito]]]></ValorCredito>";		
				$dados	.=	"\n<TipoDesconto><![CDATA[$lin15[ValorParametroSistema]]]></TipoDesconto>";	
				$dados	.=	"\n<IdNotaFiscalTipo><![CDATA[$lin[IdNotaFiscalTipo]]]></IdNotaFiscalTipo>";	
				$dados	.=	"\n<StatusTempoAlteracao><![CDATA[$local_StatusTempoAlteracao]]></StatusTempoAlteracao>";
				$dados	.=	"\n<OrdemServico><![CDATA[$lin16[IdOrdemServico]]]></OrdemServico>";
				$dados	.=	"\n<MonitorSignalStrength><![CDATA[$lin17[Qtd]]]></MonitorSignalStrength>";
				$dados	.=	"\n<IdTipoDesconto><![CDATA[$lin3[IdTipoDesconto]]]></IdTipoDesconto>";
				$dados	.=	"\n<DataPrimeiraReferencia><![CDATA[$lin[DataPrimeiraReferencia]]]></DataPrimeiraReferencia>";
				$dados	.=	"\n<IdTerceiro><![CDATA[$lin[IdTerceiro]]]></IdTerceiro>";
				$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
				$dados	.=	"\n<LancamentoFinanceiroTipoContrato><![CDATA[$LancamentoFinanceiroTipoContrato]]></LancamentoFinanceiroTipoContrato>";
				$dados	.=	"\n<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
				$dados	.=	"\n<IdContaDebito><![CDATA[$lin[IdContaDebito]]]></IdContaDebito>";
				$dados	.=	"\n<IdCartao><![CDATA[$lin[IdCartao]]]></IdCartao>";
				
				$sql13 = "select
							  IdParametroSistema,					     
							  ValorParametroSistema						
						  from 
							  ParametroSistema					     
						  where
							  IdGrupoParametroSistema = 133
						  order by
							  ValorParametroSistema";
				$res13 	= @mysql_query($sql13,$con);
				while($lin13  =  @mysql_fetch_array($res13)){
					$dados	.=	"\n<NotaFiscalCDAOpc>";	
					$dados	.=	"\n <IdNotaFiscalCDAOpc>$lin13[IdParametroSistema]</IdNotaFiscalCDAOpc>";
					$dados	.=	"\n <DescricaoNotaFiscalCDAOpc><![CDATA[$lin13[ValorParametroSistema]]]></DescricaoNotaFiscalCDAOpc>";
					$dados	.=	"\n</NotaFiscalCDAOpc>";	
				}
				
				$sql18 = "select 
								ServicoMonitor.TipoMonitor,
								ServicoMonitor.IdSNMP
							from
								Contrato,
								Servico,
								ServicoMonitor 
							where
								Contrato.IdContrato = $lin[IdContrato]
								and Contrato.IdLoja = $IdLoja
								and Contrato.IdLoja = Servico.IdLoja 
								and Servico.IdLoja = ServicoMonitor.IdLoja 
								and Contrato.IdServico = Servico.IdServico 
								and ServicoMonitor.IdServico = Servico.IdServico";
				$res18 	= @mysql_query($sql18,$con);
				$num = mysql_num_rows($res18);
				
				if ($num >= 1) {
					while($lin18  =  @mysql_fetch_array($res18)){
						$dados	.=	"\n<ServicoMonitor>";	
						$dados	.=	"\n<TipoMonitor><![CDATA[$lin18[TipoMonitor]]]></TipoMonitor>";
						$dados	.=	"\n<IdSNMP><![CDATA[$lin18[IdSNMP]]]></IdSNMP>";
						$dados	.=	"\n</ServicoMonitor>";
					}
				}else{
					if ($num == 0){
						$dados	.=	"\n<ServicoMonitor>";	
						$dados	.=	"\n<TipoMonitor><![CDATA[$lin18[TipoMonitor]]]></TipoMonitor>";
						$dados	.=	"\n<IdSNMP><![CDATA[$lin18[IdSNMP]]]></IdSNMP>";
						$dados	.=	"\n</ServicoMonitor>";
					}	
				}
			}
			
			$dados	.=	"\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	echo get_Contrato();
?>