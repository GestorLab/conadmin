<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Conta_Receber(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja		 					= $_SESSION["IdLoja"];
		$IdPessoaLogin					= $_SESSION['IdPessoa'];
		$IdContaReceber					= $_GET['IdContaReceber'];
		$Nome							= $_GET['Nome'];
		$IdCarne						= $_GET['IdCarne'];
		$NumeroDocumento				= $_GET['NumeroDocumento'];
		$DataVencimento					= $_GET['DataVencimento'];
		$DataLancamento					= $_GET['DataLancamento'];
		$DataPagamento					= $_GET['DataPagamento'];
		$IdLocalCobranca				= $_GET['IdLocalCobranca'];
		$IdContaReceberRecebimento		= $_GET['IdContaReceberRecebimento'];
		$IdArquivoRemessa				= $_GET['IdArquivoRemessa'];
		$IdStatusAberto					= $_GET['IdStatusAberto'];
		$IdPessoa						= $_GET['IdPessoa'];
		$IdContrato						= $_GET['IdContrato'];
		$IdOrdemServico					= $_GET['IdOrdemServico'];
		$IdContaEventual				= $_GET['IdContaEventual'];
		$IdStatus						= $_GET['IdStatus'];
		$IdStatusValido					= $_GET['IdStatusValido'];
		$IdStatusAtivacaoContaReceber	= $_GET['IdStatusAtivacaoContaReceber'];
		$Local							= $_GET['Local'];
		
		$where							= "";
		$sqlAux							= "";
		
		
		$sql = "select
					IdContaReceberAgrupador
				from 
					ContaReceberAgrupado
				where 
					IdLoja = '$IdLoja' and 
					IdContaReceberAgrupador = '$IdContaReceber'";
		$res = mysql_query($sql, $con);
		$lin = mysql_fetch_array($res);
		$IdContaReceberAgrupador = $lin[IdContaReceberAgrupador];
		
		if($IdContaReceberAgrupador != '' && $Local == "AgruparContaReceber"){
			return $IdContaReceberAgrupador;
		}
		
		//if($Limit != ''){				$Limit = "limit 0,$Limit";	}
		
		if($IdContaReceberRecebimento != ''){	$where .= " and ContaReceberRecebimento.IdContaReceberRecebimento=$IdContaReceberRecebimento";	}
		if($Nome != ''){						$where .= " and Pessoa.Nome like '$Nome%'";	}
		if($Nome != ''){						$where .= " and Pessoa.Nome like '$Nome%'"; }	
		if($NumeroDocumento != ''){				$where .= " and ContaReceberDados.NumeroDocumento=$NumeroDocumento";	}
		if($IdCarne != ''){						$where .= " and ContaReceberDados.IdCarne=$IdCarne";	}
		if($DataVencimento != ''){				$where .= " and ContaReceberDados.DataVencimento='".dataConv($DataVencimento,'d/m/Y','Y-m-d')."'";	}
		if($DataLancamento != ''){				$where .= " and ContaReceberDados.DataLancamento='".dataConv($DataLancamento,'d/m/Y','Y-m-d')."'";	}
		if($DataPagamento != ''){				$where .= " and ContaReceberRecebimento.DataRecebimento='".dataConv($DataPagamento,'d/m/Y','Y-m-d')."'";	}
		if($IdLocalCobranca != ''){				$where .= " and ContaReceberDados.IdLocalCobranca=$IdLocalCobranca";	}
		if($IdArquivoRemessa != ''){			$where .= " and ContaReceberDados.IdArquivoRemessa=$IdArquivoRemessa";	}
		if($IdStatus != ''){					$where .= " and ContaReceberDados.IdStatus=$IdStatus";	}
		
		if($IdStatusValido != '' || $IdStatusAberto != ''){
			$having = " having ";
		}
		
		
		if($IdContaReceber != ''){
			$where .= " and ContaReceberDados.IdContaReceber in ($IdContaReceber)";	
		}
		
		if($IdStatusValido != ''){					
			if($Local == "Carne"){
				$having .= " ContaReceberDados.IdStatus != 0";	
				
			}else{
				$having .= " ContaReceberDados.IdStatus != 0 and ContaReceberDados.IdStatus != 7";
			}
		}
		
		if($IdStatusAtivacaoContaReceber != ''){					
			$where .= " and (ContaReceberDados.IdStatus = 0 or ContaReceberDados.IdStatus = 7)";	
		}
			
		if($IdStatusAberto != ''){					
			$having .= " ContaReceberDados.IdStatus != 0 and ContaReceberDados.IdStatus != 2";	
		}
		if($IdPessoa != ''){
			$where .= " and Pessoa.IdPessoa = '$IdPessoa'";	
		}
		if($IdContaReceber == ''){
			if($Local == "Carne"){
				$where .= "";
			}else{
				$where .= " and (ContaReceberDados.IdStatus = 1 or ContaReceberDados.IdStatus = 3)";
			}
		}
		
		if($Local == "Carne" || $Local == "ContratoStatus" || $Local == "ContratoServico"){
			$orderBy = "order by ContaReceberDados.IdContaReceber asc";
		}else{
			$orderBy = "order by Demonstrativo.IdPessoa,Tipo,Codigo,IdLancamentoFinanceiro";
		}
		
		if($IdContrato !=''){	 
			$IdContaReceberAux = "";
			$i = 0;

			$sql = "
				select 
					LancamentoFinanceiroContaReceber.IdContaReceber
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdContrato = $IdContrato and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro";
			$resLancamentoFinanceiroContaReceber	=	@mysql_query($sql,$con);
			while($linLancamentoFinanceiroContaReceber	=	@mysql_fetch_array($resLancamentoFinanceiroContaReceber)){				
				if($i == 0){
					$IdContaReceberAux = $linLancamentoFinanceiroContaReceber[IdContaReceber];
					$i = 1;
				}else{
					$IdContaReceberAux .= ",".$linLancamentoFinanceiroContaReceber[IdContaReceber];
				}
			}
			$where .= " and ContaReceberDados.IdContaReceber in ($IdContaReceberAux)";
		}
		if($IdOrdemServico !=''){	 
			$IdContaReceberAux = "";
			$i = 0;

			$sql ="
				select 
					LancamentoFinanceiroContaReceber.IdContaReceber
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdOrdemServico = $IdOrdemServico and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro"; 
			$resLancamentoFinanceiroContaReceber	=	@mysql_query($sql,$con);
			while($linLancamentoFinanceiroContaReceber	=	@mysql_fetch_array($resLancamentoFinanceiroContaReceber)){				
				if($i == 0){
					$IdContaReceberAux = $linLancamentoFinanceiroContaReceber[IdContaReceber];
					$i = 1;
				}else{
					$IdContaReceberAux .= ",".$linLancamentoFinanceiroContaReceber[IdContaReceber];
				}
			}
			$where .= " and ContaReceberDados.IdContaReceber in ($IdContaReceberAux)";
		}
		if($IdContaEventual !=''){	 
			$IdContaReceberAux = "";
			$i = 0;

			$sql ="
				select 
					LancamentoFinanceiroContaReceber.IdContaReceber
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdContaEventual = $IdContaEventual and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro";
			$resLancamentoFinanceiroContaReceber	=	@mysql_query($sql,$con);
			while($linLancamentoFinanceiroContaReceber	=	@mysql_fetch_array($resLancamentoFinanceiroContaReceber)){				
				if($i == 0){
					$IdContaReceberAux = $linLancamentoFinanceiroContaReceber[IdContaReceber];
					$i = 1;
				}else{
					$IdContaReceberAux .= ",".$linLancamentoFinanceiroContaReceber[IdContaReceber];
				}
			}
			$where .= " and ContaReceberDados.IdContaReceber in ($IdContaReceberAux)";
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
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro,
						Demonstrativo.IdProcessoFinanceiro,
						ContaReceberDados.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.Descricao,
						Demonstrativo.Referencia,
						Demonstrativo.IdNotaFiscalTipo,
						ContaReceberDados.IdLocalCobranca,
						LocalCobranca.IdLocalCobrancaLayout,
						LocalCobranca.AbreviacaoNomeLocalCobranca,
						LocalCobranca.IdArquivoRemessaTipo,
						LocalCobranca.IdTipoLocalCobranca,
						LocalCobranca.PercentualMulta,
						LocalCobranca.PercentualJurosDiarios,
						LocalCobranca.ValorTaxaReImpressaoBoleto ValorTaxaReImpressaoBoletoLocalCobranca,
						ContaReceberDados.NumeroDocumento,
						ContaReceberDados.DataLancamento,
						ContaReceberDados.DataVencimento,
						ContaReceberDados.ValorLancamento,
						ContaReceberDados.ValorDespesas,
						ContaReceberDados.ValorDesconto,
						ContaReceberDados.ValorJuros,
						ContaReceberDados.ValorMulta,
						ContaReceberDados.ValorFinal,
						ContaReceberDados.ValorTaxaReImpressaoBoleto,
						(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor,
						ContaReceberDados.IdStatus,
						ContaReceberDados.Obs,
						ContaReceberDados.DataNF,
						ContaReceberDados.ModeloNF,
						ContaReceberDados.NumeroNF,
						ContaReceberDados.IdArquivoRemessa,
						ContaReceberDados.IdPosicaoCobranca,
						ContaReceberDados.DataCriacao,
						ContaReceberDados.LoginCriacao,
						ContaReceberDados.DataAlteracao,
						ContaReceberDados.LoginAlteracao,
						ContaReceberDados.IdPessoaEndereco,
						ContaReceberDados.ValorContaReceber,
						ContaReceberDados.ValorOutrasDespesas ValorOutrasDespesasVencimento,
						ContaReceberRecebimento.IdContaReceberRecebimento,
						ContaReceberRecebimento.DataRecebimento,
						ContaReceberRecebimento.ValorDesconto ValorDescontoRecebimento,
						ContaReceberRecebimento.ValorOutrasDespesas,
						ContaReceberRecebimento.ValorMoraMulta,
						ContaReceberRecebimento.ValorRecebido,
						ContaReceberRecebimento.IdLocalCobranca IdLocalRecebimento,
						ContaReceberRecebimento.IdCaixa,
						ContaReceberRecebimento.IdCaixaMovimentacao,
						ContaReceberRecebimento.IdCaixaItem,
						ContaReceberRecebimento.IdArquivoRetorno,
						ContaReceberRecebimento.IdRecibo,
						ContaReceberRecebimento.Obs ObsCancelamento,
						ContaReceberRecebimento.IdStatus IdStatusRecebimento				    
					from
						Demonstrativo,
						Pessoa join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						) ,
						LocalCobranca,
						ContaReceberDados LEFT JOIN ContaReceberRecebimento ON (
							ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
							ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
							ContaReceberRecebimento.IdStatus = 1
						)
					where
						Demonstrativo.IdLoja = $IdLoja and
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						Demonstrativo.IdLoja = ContaReceberDados.IdLoja and
						ContaReceberDados.IdLocalCobranca =	LocalCobranca.IdLocalCobranca and
						Demonstrativo.IdContaReceber = ContaReceberDados.IdContaReceber $where
                	group by
                    	ContaReceberDados.IdContaReceber
                    	$having
					$orderBy";
		//echo $sql;die;
		$res	=	@mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
		
			if($lin[Tipo]=='CO' && $lin[Codigo]!=""){
				if($_SESSION["RestringirCarteira"] == true){
					$sqlTemp =	"select 
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
									Carteira.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}else{
					if($_SESSION["RestringirAgenteAutorizado"] == true){
						$sqlTemp =	"select 
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
										AgenteAutorizado.IdStatus = 1 and
										AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp		=	"select 
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
												AgenteAutorizado.IdStatus = 1 and
												AgenteAutorizadoPessoa.IdContrato = $lin[Codigo]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}

			if($lin[IdPosicaoCobranca] != ''){
				$lin[PosicaoCobrancaDescricao] = getParametroSistema(81,$lin[IdPosicaoCobranca]);
			}
			
			if($query == 'true'){
			
				if($cont == 0){
					header ("content-type: text/xml");
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";
				}
			
			
				if($lin[ValorDesconto]	==	'')				$lin[ValorDesconto] = 0; 
				if($lin[ValorDespesas]	==	'')				$lin[ValorDespesas] = 0; 
				if($lin[ValorMoraMulta]	==	'')				$lin[ValorMoraMulta] = 0; 
				if($lin[ValorOutrasDespesas]	==	'')		$lin[ValorOutrasDespesas] = 0; 
				if($lin[ValorDescontoRecebimento]	==	'')	$lin[ValorDescontoRecebimento] = 0; 
				
				$lin[ValorReceber]	=	$lin[ValorFinal] + $lin[ValorOutrasDespesas] + $lin[ValorMoraMulta] - $lin[ValorDescontoRecebimento];			
				
				$lin[Link]		= "../local_cobranca/$lin[IdLocalCobrancaLayout]/index.php";
				
				if(file_exists($lin[Link])){
					$lin[Boleto]	=	'true';
				}else{
					$lin[Boleto]	=	'false';
				}
				
				if($lin[IdLocalCobranca]!= ''){	
					$sql2	=	"select DescricaoLocalCobranca from LocalCobranca where IdLocalCobranca='$lin[IdLocalCobranca]'";
					$res2	=	@mysql_query($sql2,$con);
					$lin2	=	@mysql_fetch_array($res2);
				}
				
				$sql3 = "select 
							AbreviacaoNomeLocalCobranca 
						from 
							ContaReceberRecebimento,
							LocalCobranca 
						where 
							ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
							ContaReceberRecebimento.IdStatus = 1 and
							ContaReceberRecebimento.IdLoja = $IdLoja and 
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber];";
				$res3 = @mysql_query($sql3,$con);
				$qtd3 = @mysql_num_rows($res3);
		
				if($qtd3 > 1){
					$lin[DescricaoLocalRecebimento]	=	'***';
				} else{
					$lin3 = @mysql_fetch_array($res3);
					$lin[DescricaoLocalRecebimento]	=	$lin3[AbreviacaoNomeLocalCobranca];
				}
				
				$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema=$lin[IdStatus]";
				$res4 = @mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
				
				if($lin[IdStatus]!=""){
					$Color	  = getCodigoInterno(22,$lin[IdStatus]);
				}else{
					$Color	  =	"";
				}
				
				/*if($lin[Tipo] == 'CO'){
					$sql5	=	"select
								    if(count(IdContaReceber) > 0,'false','true') Voltar
								from
								    Demonstrativo
								where
								    IdLoja = $IdLoja and
								    IdContaReceber > '$lin[IdContaReceber]' and
								    Tipo = 'CO' and
								    Codigo = '$lin[Codigo]' and
								    IdStatus != 0";
		 			$res5	=	@mysql_query($sql5,$con);
					$lin5	=	@mysql_fetch_array($res5);
				}*/

				if($lin[Tipo] == 'CO'){ 
					/*$sql5 = "select
								max(IdContaReceber) MaxIdContaReceber
							from
							    LancamentoFinanceiroDados
							where
							    IdLoja = $IdLoja and
							    Tipo = 'CO' and
							    IdContrato = '$lin[Codigo]';";
		 			$res5 = @mysql_query($sql5,$con);
					$lin5 = @mysql_fetch_array($res5);

					if((int)$lin5[MaxIdContaReceber] <= (int)$lin[IdContaReceber]){
						$lin5[Voltar] = "true";
					} else{
						$lin5[Voltar] = "false";
					}*/
				} else{
					$lin5[Voltar] = "false";
				}
				
				$sql6	=	"select BaseVencimento from ContaReceberBaseVencimento where IdLoja= $IdLoja and IdContaReceber = $lin[IdContaReceber]";
				$res6	=	@mysql_query($sql6,$con);
				$lin6	=	@mysql_fetch_array($res6);
				
				$sql7	=	"select 
									sum(ValorDescontoAConceber) ValorDescontoAConceber,
									min(LimiteDesconto) LimiteDesconto
							from 
									LancamentoFinanceiro,
									LancamentoFinanceiroContaReceber 
							where 
									LancamentoFinanceiro.IdLoja = $IdLoja and 
									LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
									LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and 
									LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber]";
				$res7	=	@mysql_query($sql7,$con);
				$lin7	=	@mysql_fetch_array($res7);
	
				$DataLimiteDesconto = incrementaData($lin[DataVencimento],$lin7[LimiteDesconto]);
				
				$sql	="
						 select
							ValorContaReceber ValorPrimeiroVencimento,
							min(DataVencimento) DataPrimeiroVencimento
						 from
							ContaReceberVencimento
						 where
							IdLoja = $IdLoja and
							IdContaReceber = $lin[IdContaReceber]
						group by
							IdLoja,
							IdContaReceber
							having min(DataVencimento) is not null"; 
				$res8	=	mysql_query($sql,$con);
				$lin8	=	mysql_fetch_array($res8);
				
				$sql9 = "
						select
							ContaReceber.IdContaReceber
						from
							ContaReceber,
							LocalCobranca
						where
							ContaReceber.IdLoja = '$IdLoja' and
							ContaReceber.IdContaReceber = '$lin[IdContaReceber]' and
							ContaReceber.IdLoja = LocalCobranca.IdLoja and
							ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
							ContaReceber.IdStatus != '2' and
							LocalCobranca.IdTipoLocalCobranca IN (3, 4);"; 
				$res9	=	mysql_query($sql9, $con);
				$lin9	=	mysql_fetch_array($res9);
				
				if($lin9[IdContaReceber] != ''){
					$lin9[PosicaoCobranca] = 1;
				} else{
					$lin9[PosicaoCobranca] = 0;
				}
				/* Ver se ouver confirmação de pagamento para o CR */
				$lin[Status] = $lin4[ValorParametroSistema];
				$sql10 = "select 
							ContaReceber.IdStatusConfirmacaoPagamento,
							ParametroSistema.ValorParametroSistema StatusPagamento
						from 
							ContaReceber,
							ParametroSistema
						where
							ContaReceber.IdLoja = '$IdLoja' and
							ContaReceber.IdContaReceber = '$lin[IdContaReceber]' and
							ContaReceber.IdStatusConfirmacaoPagamento = ParametroSistema.IdParametroSistema and
							ParametroSistema.IdGrupoParametroSistema = '174' and
							ParametroSistema.IdParametroSistema = '1' and
							ContaReceber.IdStatus not in(0, 2);";
				$res10 = @mysql_query($sql10, $con);
				$lin10 = @mysql_fetch_array($res10);
				
				if($lin10[StatusPagamento] != ''){
					$lin[IdStatusConfirmacaoPagamento] = $lin10[IdStatusConfirmacaoPagamento];
					$lin[StatusPagamento] = "Pagamento " . $lin10[StatusPagamento];
				} else{
					$lin[IdStatusConfirmacaoPagamento] = '';
					$lin[StatusPagamento] = '';
				}
				
				$TipoLancamento = '';
				$sql11	= "select
								distinct
								Demonstrativo.Tipo
							from
								Demonstrativo
							where
								Demonstrativo.IdLoja = $IdLoja and
								Demonstrativo.IdContaReceber = $lin[IdContaReceber];";
				$res11	= mysql_query($sql11, $con);
				while($lin11 = mysql_fetch_array($res11)){
					if($TipoLancamento != ''){
						$TipoLancamento .= "/$lin11[Tipo]";
					} else{
						$TipoLancamento = $lin11[Tipo];
					}
				}
				
				$sql12 = "select 
							ObsVisivel
						from
							NotaFiscal 
						where 
							IdLoja = '$IdLoja' and 
							IdContaReceber = '$lin[IdContaReceber]';";
				$res12 = @mysql_query($sql12, $con);
				$lin12 = @mysql_fetch_array($res12);
				
				$sql13 = "SELECT 
							COUNT(NotaFiscal.IdNotaFiscal) CancelarNotaFiscalRecebimento
						FROM 
							NotaFiscal,LancamentoFinanceiroDados,
							Contrato
						WHERE 
							NotaFiscal.IdLoja = '$IdLoja' AND 
							NotaFiscal.IdContaReceber = '$lin[IdContaReceber]' AND 
							NotaFiscal.IdStatus = 1 AND 
							NotaFiscal.IdLoja = LancamentoFinanceiroDados.IdLoja AND 
							NotaFiscal.IdContaReceber = LancamentoFinanceiroDados.IdContaReceber AND 
							LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja AND 
							LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato AND 
							Contrato.NotaFiscalCDA = 1
							having COUNT(NotaFiscal.IdNotaFiscal) > 0";
				$res13 = @mysql_query($sql13, $con);
				$lin13 = @mysql_fetch_array($res13);
				
				$sql14 = "SELECT 
							COUNT(NotaFiscal.IdNotaFiscal) CancelarNotaFiscal
						FROM 
							NotaFiscal
						WHERE 
							NotaFiscal.IdLoja = '$IdLoja' AND 
							NotaFiscal.IdContaReceber = '$lin[IdContaReceber]' AND 
							NotaFiscal.IdStatus = 1
							having COUNT(NotaFiscal.IdNotaFiscal) > 0";
				$res14 = @mysql_query($sql14, $con);
				$lin14 = @mysql_fetch_array($res14);
				
				if($lin[IdLocalRecebimento] != ""){
					$sql10 = "
							select
								IdTipoLocalCobranca IdTipoLocalCobrancaRecebimento
							from
								LocalCobranca
							where
								IdLoja = '$IdLoja' and						
								IdLocalCobranca = $lin[IdLocalRecebimento];"; 
					$res10	=	mysql_query($sql10, $con);
					$lin10	=	mysql_fetch_array($res10);

					$IdTipoLocalCobrancaRecebimento = $lin10[IdTipoLocalCobrancaRecebimento];
				}else{
					$IdTipoLocalCobrancaRecebimento = "";
				}
				
				$sql15 = "select 
								count(*) OcultarBlocoNotafiscal
							from
								NotaFiscal 
							where 
								IdLoja = '$IdLoja' and 
								IdContaReceber = '$lin[IdContaReceber]';";
				$res15 = mysql_query($sql15, $con);
				$lin15 = mysql_fetch_array($res15);
				
				$sql16 = "SELECT
								PessoaCartao.IdCartao,
								PessoaCartao.NumeroCartao
							FROM
								ContaReceberPosicaoCobranca,
								PessoaCartao
							WHERE
								ContaReceberPosicaoCobranca.IdLoja = '$IdLoja' AND
								PessoaCartao.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
								ContaReceberPosicaoCobranca.IdContaReceber = '$lin[IdContaReceber]' AND
								PessoaCartao.IdCartao = ContaReceberPosicaoCobranca.IdCartao AND
								PessoaCartao.IdPessoa = ContaReceberPosicaoCobranca.IdPessoa";
				$res16 = mysql_query($sql16, $con);
				$lin16 = mysql_fetch_array($res16);				
				
				$dados	.=	"\n<IdLoja>$IdLoja</IdLoja>";
				$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
				$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados	.=	"\n<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";
				$dados	.=	"\n<Codigo><![CDATA[$lin[Codigo]]]></Codigo>";
				$dados	.=	"\n<ObsNotaFiscal><![CDATA[$lin12[ObsVisivel]]]></ObsNotaFiscal>";
				$dados	.=	"\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
				$dados	.=	"\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
				$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
				$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
				$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
				$dados	.=	"\n<ValorJuros><![CDATA[$lin[ValorJuros]]]></ValorJuros>";
				$dados	.=	"\n<ValorMulta><![CDATA[$lin[ValorMulta]]]></ValorMulta>";
				$dados	.=	"\n<ValorMoraMulta><![CDATA[$lin[ValorMoraMulta]]]></ValorMoraMulta>";
				$dados	.=	"\n<IdPosicaoCobranca><![CDATA[$lin[IdPosicaoCobranca]]]></IdPosicaoCobranca>";
				$dados	.=	"\n<PosicaoCobrancaDescricao><![CDATA[$lin[PosicaoCobrancaDescricao]]]></PosicaoCobrancaDescricao>";
				$dados	.=	"\n<PosicaoCobranca><![CDATA[$lin9[PosicaoCobranca]]]></PosicaoCobranca>";
				$dados	.=	"\n<LimiteDesconto><![CDATA[$lin7[LimiteDesconto]]]></LimiteDesconto>";
				$dados	.=	"\n<DataLimiteDesconto><![CDATA[$DataLimiteDesconto]]></DataLimiteDesconto>";
				$dados	.=	"\n<ValorDescontoAConceber><![CDATA[$lin7[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
				$dados	.=	"\n<DataNF><![CDATA[$lin[DataNF]]]></DataNF>";
				$dados	.=	"\n<ModeloNF><![CDATA[$lin[ModeloNF]]]></ModeloNF>";
				$dados	.=	"\n<ValorTaxaReImpressaoBoleto><![CDATA[$lin[ValorTaxaReImpressaoBoleto]]]></ValorTaxaReImpressaoBoleto>";
				$dados	.=	"\n<ValorTaxaReImpressaoBoletoLocalCobranca><![CDATA[$lin[ValorTaxaReImpressaoBoletoLocalCobranca]]]></ValorTaxaReImpressaoBoletoLocalCobranca>";
				$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
				$dados	.=	"\n<ValorOutrasDespesasVencimento><![CDATA[$lin[ValorOutrasDespesasVencimento]]]></ValorOutrasDespesasVencimento>";
				$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados	.=	"\n<IdStatusConfirmacaoPagamento><![CDATA[$lin[IdStatusConfirmacaoPagamento]]]></IdStatusConfirmacaoPagamento>";
				$dados	.=	"\n<StatusPagamento><![CDATA[$lin[StatusPagamento]]]></StatusPagamento>";
				$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
				$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin2[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
				$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
				$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
				$dados	.=	"\n<IdRecibo><![CDATA[$lin[IdRecibo]]]></IdRecibo>";
				$dados	.=	"\n<IdContaReceberRecebimento><![CDATA[$lin[IdContaReceberRecebimento]]]></IdContaReceberRecebimento>";
				$dados	.=	"\n<IdLocalRecebimento><![CDATA[$lin[IdLocalRecebimento]]]></IdLocalRecebimento>";
				$dados	.=	"\n<IdCaixa><![CDATA[$lin[IdCaixa]]]></IdCaixa>";
				$dados	.=	"\n<IdCaixaMovimentacao><![CDATA[$lin[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
				$dados	.=	"\n<IdCaixaItem><![CDATA[$lin[IdCaixaItem]]]></IdCaixaItem>";
				$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
				$dados	.=	"\n<ObsCancelamento><![CDATA[$lin[ObsCancelamento]]]></ObsCancelamento>";
				$dados	.=	"\n<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
				$dados	.=	"\n<ValorFinal><![CDATA[$lin[ValorFinal]]]></ValorFinal>";
				$dados	.=	"\n<IdArquivoRetorno><![CDATA[$lin[IdArquivoRetorno]]]></IdArquivoRetorno>";
				$dados	.=	"\n<IdArquivoRemessaTipo><![CDATA[$lin[IdArquivoRemessaTipo]]]></IdArquivoRemessaTipo>";
				$dados	.=	"\n<ValorReceber><![CDATA[$lin[ValorReceber]]]></ValorReceber>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<ValorDescontoRecebimento><![CDATA[$lin[ValorDescontoRecebimento]]]></ValorDescontoRecebimento>";
				$dados	.=	"\n<ValorMoraMulta><![CDATA[$lin[ValorMoraMulta]]]></ValorMoraMulta>";
				$dados	.=	"\n<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
				$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
				$dados	.=	"\n<IdStatusRecebimento><![CDATA[$lin[IdStatusRecebimento]]]></IdStatusRecebimento>";
				$dados	.=	"\n<Boleto><![CDATA[$lin[Boleto]]]></Boleto>";
				$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.=	"\n<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
				$dados	.=	"\n<DescricaoLocalRecebimento><![CDATA[$lin[DescricaoLocalRecebimento]]]></DescricaoLocalRecebimento>";
				$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
				$dados	.=	"\n<Voltar><![CDATA[$lin5[Voltar]]]></Voltar>";
				$dados	.=	"\n<TipoLancamentoFinanceiro><![CDATA[$TipoLancamento]]></TipoLancamentoFinanceiro>";
				$dados	.=	"\n<BaseVencimento><![CDATA[$lin6[BaseVencimento]]]></BaseVencimento>";
				$dados	.=	"\n<PercentualMulta><![CDATA[$lin[PercentualMulta]]]></PercentualMulta>";
				$dados	.=	"\n<PercentualJurosDiarios><![CDATA[$lin[PercentualJurosDiarios]]]></PercentualJurosDiarios>";
				$dados	.=	"\n<IdPessoaEndereco><![CDATA[$lin[IdPessoaEndereco]]]></IdPessoaEndereco>";
				$dados	.=	"\n<ValorLancamento><![CDATA[$lin[ValorLancamento]]]></ValorLancamento>";
				$dados	.=	"\n<IdNotaFiscalTipo><![CDATA[$lin[IdNotaFiscalTipo]]]></IdNotaFiscalTipo>";
				$dados	.=	"\n<ValorPrimeiroVencimento><![CDATA[$lin8[ValorPrimeiroVencimento]]]></ValorPrimeiroVencimento>";
				$dados	.=	"\n<DataPrimeiroVencimento><![CDATA[$lin8[DataPrimeiroVencimento]]]></DataPrimeiroVencimento>";
				$dados	.=	"\n<IdContaReceberAgrupador><![CDATA[$IdContaReceberAgrupador]]></IdContaReceberAgrupador>";
				$dados	.=	"\n<CancelarNotaFiscalRecebimento><![CDATA[$lin13[CancelarNotaFiscalRecebimento]]]></CancelarNotaFiscalRecebimento>";
				$dados	.=	"\n<CancelarNotaFiscal><![CDATA[$lin14[CancelarNotaFiscal]]]></CancelarNotaFiscal>";
				$dados	.=	"\n<IdTipoLocalCobrancaRecebimento><![CDATA[$IdTipoLocalCobrancaRecebimento]]></IdTipoLocalCobrancaRecebimento>";
				$dados	.=	"\n<OcultarBlocoNotafiscal>$lin15[OcultarBlocoNotafiscal]</OcultarBlocoNotafiscal>";
				$dados	.=	"\n<NumeroCartaoCredito><![CDATA[$lin16[NumeroCartaoCredito]]]></NumeroCartaoCredito>";
				
				$cont++;
				
				if($Limit!=""){
					if($cont > $Limit){
						break;
					}
				}
			}
		}
		if($cont >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_Conta_Receber();
?>
