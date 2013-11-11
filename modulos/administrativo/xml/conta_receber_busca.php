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
		$where							= "";
		$sqlAux							= "";
		
		//if($Limit != ''){				$Limit = "limit 0,$Limit";	}
		
		if($IdContaReceberRecebimento != ''){	$where .= " and ContaReceberRecebimento.IdContaReceberRecebimento=$IdContaReceberRecebimento";	}
		if($IdContaReceber != ''){				$where .= " and ContaReceberDados.IdContaReceber=$IdContaReceber";	}
		if($Nome != ''){						$where .= " and (Pessoa.Nome like '$Nome%' or Pessoa.RazaoSocial like '$Nome%')";	}
		if($NumeroDocumento != ''){				$where .= " and ContaReceberDados.NumeroDocumento=$NumeroDocumento";	}
		if($DataVencimento != ''){				$where .= " and ContaReceberDados.DataVencimento='".dataConv($DataVencimento,'d/m/Y','Y-m-d')."'";	}
		if($DataLancamento != ''){				$where .= " and ContaReceberDados.DataLancamento='".dataConv($DataLancamento,'d/m/Y','Y-m-d')."'";	}
		if($DataPagamento != ''){				$where .= " and ContaReceberRecebimento.DataRecebimento='".dataConv($DataPagamento,'d/m/Y','Y-m-d')."'";	}
		if($IdLocalCobranca != ''){				$where .= " and ContaReceberDados.IdLocalCobranca=$IdLocalCobranca";	}
		if($IdStatus != ''){					$where .= " and ContaReceberDados.IdStatus=$IdStatus";	}
		
		if($IdContrato != '' || $IdContaEventual != '' || $IdOrdemServico != ''){
			$where .= " and (";
			$temp = "";
			
			if($IdContrato != ''){
				$temp .= " LancamentoFinanceiro.IdContrato=$IdContrato";
			}
			
			if($IdContaEventual != ''){
				if($temp != ''){
					$temp .= " or";
				}
				
				$temp .= " LancamentoFinanceiro.IdContaEventual=$IdContaEventual";
			}
			
			if($IdOrdemServico != ''){
				if($temp != ''){
					$temp .= " or";
				}
				
				$temp .= " LancamentoFinanceiro.IdOrdemServico=$IdOrdemServico";
			}
			
			$where .= $temp.")";
		}
		
		if($IdStatusValido != ''){					
			$where .= " and ContaReceberDados.IdStatus != 0 and ContaReceberDados.IdStatus != 7";	
		}
		if($IdStatusAtivacaoContaReceber != ''){					
			$where .= " and (ContaReceberDados.IdStatus = 0 or ContaReceberDados.IdStatus = 7)";	
		}
		if($IdPessoa != ''){
			$where .= " and Pessoa.IdPessoa = '$IdPessoa'";	
		}
		if($IdStatusAberto != ''){					
			$where .= " and ContaReceberDados.IdStatus != 0 and ContaReceberDados.IdStatus != 2";	
		}
		if($IdContaReceber == '' && $IdStatusAtivacaoContaReceber == ''){
			$where .= " and ContaReceberDados.IdStatus != 7";
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
						ContaReceberDados.IdLoja,
						ContaReceberDados.IdContaReceber,
						ContaReceberDados.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						ContaReceberDados.IdLocalCobranca,
						LocalCobranca.AbreviacaoNomeLocalCobranca,
						LocalCobranca.DescricaoLocalCobranca,
						ContaReceberDados.NumeroDocumento,
						ContaReceberDados.DataLancamento,
						ContaReceberDados.DataVencimento,
						ContaReceberDados.ValorLancamento,
						ContaReceberDados.ValorDespesas,
						ContaReceberDados.ValorDesconto,
						ContaReceberDados.IdStatus,
						ContaReceberDados.ValorJuros,
						ContaReceberDados.ValorMulta,
						ContaReceberDados.ValorFinal,
						ContaReceberDados.ValorTaxaReImpressaoBoleto,
						(ContaReceberDados.ValorLancamento + ContaReceberDados.ValorDespesas) Valor
					from
						Pessoa left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						),
						LocalCobranca,
						ContaReceberDados LEFT JOIN ContaReceberRecebimento ON (
							ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
							ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber
						),
						LancamentoFinanceiroContaReceber,
						LancamentoFinanceiro
					where
						ContaReceberDados.IdLoja = $IdLoja and
						ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						ContaReceberDados.IdLocalCobranca =	LocalCobranca.IdLocalCobranca and
						ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
						LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
						LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
						$where
                	group by
                    	ContaReceberDados.IdContaReceber  	
					order by
						ContaReceberDados.IdPessoa";
		$res	=	@mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$query = 'true';
		
			$sql3	=	"select IdContrato from LancamentoFinanceiroDados where IdLoja=$IdLoja and IdContaReceber = $lin[IdContaReceber]";
			$res3	=	@mysql_query($sql3,$con);
			$lin3	=	@mysql_fetch_array($res3);
		
			if($lin3[IdContrato]!=""){
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
									AgenteAutorizadoPessoa.IdContrato = $lin3[IdContrato]";
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
										AgenteAutorizadoPessoa.IdContrato = $lin3[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp		.=	"select 
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
												AgenteAutorizadoPessoa.IdContrato = $lin3[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
			
			if($query == 'true'){
			
				if($cont == 0){
					header ("content-type: text/xml");
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";
				}
				
				if($lin[IdStatus] != ""){
					$sql8 	= "SELECT ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 35 AND IdParametroSistema = $lin[IdStatus]";
					$res8 	= mysql_query($sql8,$con);
					$Status = mysql_fetch_array($res8);
				}
			
				if($lin[ValorDesconto]	==	'')				$lin[ValorDesconto] = 0; 
				if($lin[ValorDespesas]	==	'')				$lin[ValorDespesas] = 0; 
				if($lin[ValorMoraMulta]	==	'')				$lin[ValorMoraMulta] = 0; 
				if($lin[ValorOutrasDespesas]	==	'')		$lin[ValorOutrasDespesas] = 0; 
				if($lin[ValorDescontoRecebimento]	==	'')	$lin[ValorDescontoRecebimento] = 0; 
				
				$dados	.=	"\n<IdLoja>$IdLoja</IdLoja>";
				$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
				$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
				$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados	.=	"\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
				$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
				$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
				$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
				$dados	.=	"\n<ValorJuros><![CDATA[$lin[ValorJuros]]]></ValorJuros>";
				$dados	.=	"\n<Status><![CDATA[$Status[ValorParametroSistema]]]></Status>";
				$dados	.=	"\n<ValorMulta><![CDATA[$lin[ValorMulta]]]></ValorMulta>";
				$dados  .=  "\n<Valor><![CDATA[".number_format($lin[ValorFinal],2,',','.')."]]></Valor>";
				$dados	.=	"\n<ValorMoraMulta><![CDATA[$lin[ValorMoraMulta]]]></ValorMoraMulta>";
				$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
				$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
				$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
				
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
