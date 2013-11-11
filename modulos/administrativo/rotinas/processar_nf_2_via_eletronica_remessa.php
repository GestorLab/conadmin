<?
	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$local_PeriodoApuracao = dataConv($local_MesReferencia, 'm/Y','Y-m');

	// Dados do Processo
	$sqlProcesso = "select
						LogProcessamento
					from
						NotaFiscal2ViaEletronicaArquivo
					where
						IdLoja = $local_IdLoja  and
						IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
						MesReferencia = '$local_MesReferencia' and
						Status = '$local_IdStatusArquivoMestre'";
	$resProcesso = mysql_query($sqlProcesso,$con);
	$Processo = mysql_fetch_array($resProcesso);
	// FIM - Dados do Processo

	// Dados do Contribuinte
	$sqlContribuinte = "select
							Pessoa.RazaoSocial,
							Pessoa.RG_IE,
							Pessoa.CPF_CNPJ,
							Pessoa.IdEnderecoDefault,
							Pessoa.Telefone2 Telefone,
							PessoaEndereco.CEP,
							PessoaEndereco.Endereco,
							PessoaEndereco.Numero,
							PessoaEndereco.Complemento,
							PessoaEndereco.Bairro,
							Estado.SiglaEstado,
							Cidade.NomeCidade,
							NotaFiscalTipo.IdNotaFiscalTipo
						from
							NotaFiscalTipo,
							Pessoa,
							PessoaEndereco,
							Estado,
							Cidade
						where
							NotaFiscalTipo.IdLoja = $local_IdLoja and
							NotaFiscalTipo.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
							Pessoa.IdPessoa = NotaFiscalTipo.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
							PessoaEndereco.IdPais = Estado.IdPais and
							PessoaEndereco.IdPais = Cidade.IdPais and
							PessoaEndereco.IdEstado = Estado.IdEstado and
							PessoaEndereco.IdEstado = Cidade.IdEstado and
							PessoaEndereco.IdCidade = Cidade.IdCidade";
	$resContribuinte = mysql_query($sqlContribuinte,$con);
	$Contribuinte = mysql_fetch_array($resContribuinte);

	$Contribuinte[Endereco] .= ", ".$Contribuinte[Numero];

	if($Contribuinte[Complemento] != ''){
		$Contribuinte[Endereco] .= " - ".$Contribuinte[Complemento];
	}

	// FIM - Dados Contribuinte

	// Parтmetros da Nota Fiscal
	$sqlParametro = "select
						IdNotaFiscalLayoutParametro,
						Valor
					from
						NotaFiscalTipoParametro
					where
						IdLoja = $local_IdLoja and
						IdNotaFiscalTipo = $Contribuinte[IdNotaFiscalTipo] and
						IdNotaFiscalLayout = $local_IdNotaFiscalLayout";
	$resParametro = mysql_query($sqlParametro, $con);
	while($linParametro = mysql_fetch_array($resParametro)){
		$Parametro[$linParametro[IdNotaFiscalLayoutParametro]] = $linParametro[Valor];
	}

	// FIM - Parтmetros da Nota Fiscal

	// Dados da Apuraчуo
	$Apuracao[Serie] = "001";

	$Apuracao[y]					= substr($local_PeriodoApuracao,2,2);
	$Apuracao[M]					= substr($local_PeriodoApuracao,5,2);
	

	$Apuracao[StatusArquivoMestre]			= $local_IdStatusArquivoMestre;
	$Apuracao[StatusArquivoItem]			= $local_IdStatusArquivoMestre;
	$Apuracao[StatusArquivoDestinatario]	= $local_IdStatusArquivoMestre;
	$Apuracao[StatusArquivoControle]		= $local_IdStatusArquivoMestre;

	$Arquivo[NomeMestre]		= $Contribuinte[SiglaEstado].$Apuracao[Serie].$Apuracao[y].$Apuracao[M].$Apuracao[StatusArquivoMestre]."M.001";
	$Arquivo[NomeItem]			= $Contribuinte[SiglaEstado].$Apuracao[Serie].$Apuracao[y].$Apuracao[M].$Apuracao[StatusArquivoItem]."I.001";
	$Arquivo[NomeDestinatario]	= $Contribuinte[SiglaEstado].$Apuracao[Serie].$Apuracao[y].$Apuracao[M].$Apuracao[StatusArquivoDestinatario]."D.001";
	$Arquivo[NomeControle]		= $Contribuinte[SiglaEstado].$Apuracao[Serie].$Apuracao[y].$Apuracao[M].$Apuracao[StatusArquivoControle]."C.001";
	// FIM - Dados da Apuraчуo

	// Dados da NF
	$sqlNotaFiscal = "select
						count(*) QtdNF,
						min(NotaFiscal.DataEmissao) DataPrimeiraNF,
						max(NotaFiscal.DataEmissao) DataUltimaNF,
						min(NotaFiscal.IdNotaFiscal) NumeroPrimeiraNF,
						max(NotaFiscal.IdNotaFiscal) NumeroUltimaNF,
						sum(NotaFiscal.ValorTotal) ValorTotal,
						sum(NotaFiscal.ValorBaseCalculoICMS) ValorTotalBaseCalculoICMS,
						sum(NotaFiscal.ValorICMS) ValorTotalICMS,
						sum(NotaFiscal.ValorNaoTributado) ValorTotalNaoTributado,
						sum(NotaFiscal.ValorOutros) ValorTotalOutros
					from
						NotaFiscal,
						NotaFiscalTipo
					where
						NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
						NotaFiscal.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
						(
							NotaFiscalTipo.IdLoja = $local_IdLoja or
							NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja
						) AND
						NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja AND 
						NotaFiscal.PeriodoApuracao = '$local_PeriodoApuracao'
					group by
						NotaFiscal.IdNotaFiscalLayout,
						NotaFiscal.PeriodoApuracao";
	$resNotaFiscal = mysql_query($sqlNotaFiscal,$con);
	$NotaFiscal = mysql_fetch_array($resNotaFiscal);

	if($NotaFiscal[QtdNF] == ''){
		$NotaFiscal[QtdNF] = 0;
	}

	// Dados da NF Cancelada
	$sqlNotaFiscalCancelada = "select
									count(*) QtdNF,
									sum(NotaFiscal.ValorTotal) ValorTotalCancelado,
									sum(NotaFiscal.ValorBaseCalculoICMS) ValorBaseCalculoICMSCancelado
								from
									NotaFiscal,
									NotaFiscalTipo
								where
									NotaFiscal.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
									NotaFiscal.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
									(
										NotaFiscalTipo.IdLoja = $local_IdLoja or
										NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja
									) AND
									NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja AND 
									NotaFiscal.PeriodoApuracao = '$local_PeriodoApuracao' and
									NotaFiscal.IdStatus = 0
								group by
									NotaFiscal.IdNotaFiscalLayout,
									NotaFiscal.PeriodoApuracao";
	$resNotaFiscalCancelada = mysql_query($sqlNotaFiscalCancelada,$con);
	$NotaFiscalCancelada = mysql_fetch_array($resNotaFiscalCancelada);

	if($NotaFiscalCancelada[ValorTotalCancelado] == ""){
		$NotaFiscalCancelada[ValorTotalCancelado] = "0.00";
	}

	if($NotaFiscalCancelada[QtdNF] == ''){
		$NotaFiscalCancelada[QtdNF] = 0;
	}

	// Dados da NF Item
	$sqlNotaFiscalItem = "select
								count(*) QtdItem,
								sum(NotaFiscalItem.ValorTotal) ValorTotal,
								sum(NotaFiscalItem.ValorDesconto) ValorTotalDesconto,
								sum(NotaFiscalItem.ValorBaseCalculoICMS) ValorTotalBaseCalculoICMS,
								sum(NotaFiscalItem.ValorICMS) ValorTotalICMS,
								sum(NotaFiscalItem.ValorNaoTributado) ValorTotalNaoTributado,
								sum(NotaFiscalItem.ValorOutros) ValorTotalOutros
							from
								NotaFiscalItem,
								NotaFiscalTipo
							where
								NotaFiscalItem.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
								NotaFiscalItem.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
								(
									NotaFiscalTipo.IdLoja = $local_IdLoja or
									NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja
								) AND
								NotaFiscalItem.IdLoja = NotaFiscalTipo.IdLoja AND 
								NotaFiscalItem.PeriodoApuracao = '$local_PeriodoApuracao'
							group by	
								NotaFiscalItem.IdNotaFiscalLayout,
								NotaFiscalItem.PeriodoApuracao";
	$resNotaFiscalItem = mysql_query($sqlNotaFiscalItem,$con);
	$NotaFiscalItem = mysql_fetch_array($resNotaFiscalItem);

	// Dados da NF Item Cancelado
	$sqlNotaFiscalItemCancelado = "select
									count(*) QtdItem
								from
									NotaFiscalItem,
									NotaFiscalTipo
								where
									NotaFiscalItem.IdNotaFiscalLayout = $local_IdNotaFiscalLayout and
									NotaFiscalItem.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
									(
										NotaFiscalTipo.IdLoja = $local_IdLoja or
										NotaFiscalTipo.IdLojaCompartilhada = $local_IdLoja
									) AND
									NotaFiscalItem.IdLoja = NotaFiscalTipo.IdLoja AND 
									NotaFiscalItem.PeriodoApuracao = '$local_PeriodoApuracao' AND
									NotaFiscalItem.IdStatus = 0
								group by	
									NotaFiscalItem.IdNotaFiscalLayout,
									NotaFiscalItem.PeriodoApuracao";
	$resNotaFiscalItemCancelado = mysql_query($sqlNotaFiscalItemCancelado,$con);
	$NotaFiscalItemCancelado = mysql_fetch_array($resNotaFiscalItemCancelado);
	// Fim - Dados da NF

	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Processo executado.";

	$Contribuinte[Bairro] = str_replace("'","",$Contribuinte[Bairro]);

	$sql = "update NotaFiscal2ViaEletronicaArquivo set 
					NomeArquivoMestre='$Arquivo[NomeMestre]',
					NomeArquivoItem='$Arquivo[NomeItem]',
					NomeArquivoDestinatario='$Arquivo[NomeDestinatario]',
					NomeArquivoControle='$Arquivo[NomeControle]',
					IE='$Contribuinte[RG_IE]',
					CNPJ='$Contribuinte[CPF_CNPJ]',
					RazaoSocial='$Contribuinte[RazaoSocial]',
					Endereco='$Contribuinte[Endereco]',
					CEP='$Contribuinte[CEP]',
					Bairro='$Contribuinte[Bairro]',
					NomeCidade='$Contribuinte[NomeCidade]',
					SiglaEstado='$Contribuinte[SiglaEstado]',
					QtdNF=$NotaFiscal[QtdNF],
					QtdNFCancelado=$NotaFiscalCancelada[QtdNF],
					StatusArquivoMestre='$Apuracao[StatusArquivoMestre]',
					DataPrimeiraNF='$NotaFiscal[DataPrimeiraNF]',
					DataUltimaNF='$NotaFiscal[DataUltimaNF]',
					NumeroPrimeiraNF='$NotaFiscal[NumeroPrimeiraNF]',
					NumeroUltimaNF='$NotaFiscal[NumeroUltimaNF]',
					ValorTotal='$NotaFiscal[ValorTotal]',
					ValorTotalCancelado='$NotaFiscalCancelada[ValorTotalCancelado]',
					ValorTotalBaseCalculo='$NotaFiscal[ValorTotalBaseCalculoICMS]',
					ValorTotalBaseCalculoCancelado='$NotaFiscalCancelada[ValorBaseCalculoICMSCancelado]',
					ValorTotalICMS='$NotaFiscal[ValorTotalICMS]',
					ValorTotalIsentoNaoTributado='$NotaFiscal[ValorTotalNaoTributado]',
					ValorTotalOutros='$NotaFiscal[ValorTotalOutros]',
					CodigoAutenticacaoDigitalArquivoMestre=NULL,
					QtdItem='$NotaFiscalItem[QtdItem]',
					QtdItemCancelado='$NotaFiscalItemCancelado[QtdItem]',
					StatusArquivoItem='$Apuracao[StatusArquivoItem]',
					ValorTotalItem='$NotaFiscalItem[ValorTotal]',
					ValorTotalItemDesconto='$NotaFiscalItem[ValorTotalDesconto]',
					ValorTotalAcrecimoDespesas=0,
					ValorTotalItemBaseCalculo='$NotaFiscalItem[ValorTotalBaseCalculoICMS]',
					ValorTotalItemICMS='$NotaFiscalItem[ValorTotalICMS]',
					ValorTotalItemIsentoNaoTributado='$NotaFiscalItem[ValorTotalNaoTributado]',
					ValorTotalItemOutros='$NotaFiscalItem[ValorTotalOutros]',
					CodigoAutenticacaoDigitalArquivoItem=NULL,
					QtdRegistroDestinatario=$NotaFiscal[QtdNF],  
					StatusArquivoDestinatario='$Apuracao[StatusArquivoDestinatario]',
					CodigoAutenticacaoDigitalArquivoDestinatario=NULL,
					NomeResponsavel='$Parametro[4]',
					CargoResponsavel='$Parametro[5]',
					TelefoneResponsavel='$Contribuinte[Telefone]',
					EmailResponsavel='$Parametro[6]',
					LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento),
					IdStatus='2',
					LoginProcessamento='$local_login',
					DataProcessamento=concat(curdate(),' ',curtime()),
					LoginConfirmacao=NULL,
					DataConfirmacao=NULL 
				where 
					IdLoja='$local_IdLoja' and 
					IdNotaFiscalLayout='$local_IdNotaFiscalLayout' and 
					MesReferencia='$local_MesReferencia' and
					Status = '$local_IdStatusArquivoMestre'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
				
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true){
		$sql = "COMMIT;";
		$local_Erro = 147;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 135;
	}
	mysql_query($sql,$con);
?>