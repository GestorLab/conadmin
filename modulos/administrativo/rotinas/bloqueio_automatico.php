<?	
	// Negativa o cliente sobre a confirmação de pagamento falsa
	$sql = "select
				ContaReceber.IdLoja,
				ContaReceber.IdContaReceber
			from
				ContaReceber,
				LocalCobranca
			where
				ContaReceber.IdStatus = 1 and
				ContaReceber.IdStatusConfirmacaoPagamento = 1 and
				ContaReceber.IdLoja = LocalCobranca.IdLoja and
				ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				ADDDATE(ContaReceber.DataSolicitacaoConfirmacaoPagamento, INTERVAL LocalCobranca.DiasCompensacao DAY) < CURDATE()";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update ContaReceber set IdStatusConfirmacaoPagamento = 2 where IdLoja=$lin[IdLoja] and IdContaReceber=$lin[IdContaReceber]";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;

	$UrlContrato	= getParametroSistema(6,3)."/modulos/administrativo/cadastro_contrato.php?IdContrato=";
	$Moeda			= getParametroSistema(5,1);
	$LogEmail		= null;

	// Bloqueio Automático
	if(getParametroSistema(136,date('w')+1) == 1){
		$sql = "select
					Data
				from
					DatasEspeciais
				where
					Data = curdate()";
		$res = mysql_query($sql,$con);
		if(mysql_num_rows($res) == 0){

			$ContratosExec	= false;
			$count = 0;

			// Localizo todos os contas a receber vencidos
			$sqlBaseVencimento = "select 
									ContaReceberBaseVencimentoBloqueio.IdLoja,
									ContaReceberBaseVencimentoBloqueio.IdContaReceber,
									ContaReceberBaseVencimentoBloqueio.BaseVencimento,
									ContaReceberDados.DataVencimento,
									(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal
								from 
									ContaReceberBaseVencimentoBloqueio,
									ContaReceberDados
								where
									ContaReceberBaseVencimentoBloqueio.IdStatus in (1,3,4,5,6) and
									ContaReceberBaseVencimentoBloqueio.BaseVencimento > 0 and
									ContaReceberDados.IdLoja = ContaReceberBaseVencimentoBloqueio.IdLoja and
									ContaReceberDados.IdContaReceber = ContaReceberBaseVencimentoBloqueio.IdContaReceber";
			$resBaseVencimento = @mysql_query($sqlBaseVencimento,$con);
			while($linBaseVencimento = @mysql_fetch_array($resBaseVencimento)){
				
				if(getParametroSistema(136,8) == 1){
					$sql = "select
								min(DataVencimento) DataVencimento
							from
								ContaReceberVencimento
							where
								IdLoja = $linBaseVencimento[IdLoja] and
								IdContaReceber=$linBaseVencimento[IdContaReceber]";
					$res = mysql_query($sql,$con);
					$lin = mysql_fetch_array($res);
				
					$linBaseVencimento[DataVencimento] = $lin[DataVencimento];
				}

				$linBaseVencimento[DataVencimento] = dataConv($linBaseVencimento[DataVencimento],'Y-m-d','d/m/Y');

				// Filtro todos que tenham contratos vinculados ao Conta a Receber em aberto
				$sqlContrato = "select
									Contrato.IdLoja,
									Contrato.IdContrato,
									Contrato.IdServico,
									Servico.DiasLimiteBloqueio,
									Contrato.IdStatus,
									Contrato.VarStatus,
									Servico.UrlRotinaBloqueio,
									Pessoa.IdPessoa,
									Pessoa.Nome,
									Servico.DescricaoServico,
									Contrato.Obs
								from
									Servico,
									Pessoa,
									Contrato left join (select 
															LancamentoFinanceiro.IdLoja,
															LancamentoFinanceiro.IdContrato,
															Contrato.IdContratoAgrupador
														from
															ContaReceber,
															LancamentoFinanceiroContaReceber,
															LancamentoFinanceiro,
															Contrato,
															Servico,
															Pessoa
														where
															ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
															ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
															ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
															LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
															ContaReceber.IdLoja = Contrato.IdLoja and
															Contrato.IdLoja = Servico.IdLoja and
															ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
															LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
															LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
															Contrato.IdServico = Servico.IdServico and
															Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
															Contrato.IdPessoa = Pessoa.IdPessoa) ContratoBloquear on 
																(Contrato.IdLoja = ContratoBloquear.IdLoja and (Contrato.IdContrato = ContratoBloquear.IdContrato or Contrato.IdContrato = ContratoBloquear.IdContratoAgrupador))
												left join (select
																LancamentoFinanceiro.IdLoja,
																ContaEventual.IdContrato
															from
																ContaReceber,
																LancamentoFinanceiroContaReceber,
																LancamentoFinanceiro,
																ContaEventual,
																Contrato,
																Servico,
																Pessoa
															where
																ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
																ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
																ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																ContaReceber.IdLoja = Contrato.IdLoja and
																ContaEventual.IdLoja = Servico.IdLoja and
																Contrato.IdLoja = Servico.IdLoja and
																ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
																LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
																LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and
																ContaEventual.IdContrato = Contrato.IdContrato and
																Contrato.IdServico = Servico.IdServico and
																Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
																Contrato.IdPessoa = Pessoa.IdPessoa) ContaEventualBloquear on (Contrato.IdLoja = ContaEventualBloquear.IdLoja and Contrato.IdContrato = ContaEventualBloquear.IdContrato)
												left join (select
																LancamentoFinanceiro.IdLoja,
																if(OrdemServico.IdContratoFaturamento > 0, OrdemServico.IdContratoFaturamento, OrdemServico.IdContrato) IdContrato
															from
																ContaReceber,
																LancamentoFinanceiroContaReceber,
																LancamentoFinanceiro,
																OrdemServico,
																Contrato,
																Servico,
																Pessoa
															where
																ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
																ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
																ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
																ContaReceber.IdLoja = Contrato.IdLoja and
																OrdemServico.IdLoja = Servico.IdLoja and
																Contrato.IdLoja = Servico.IdLoja and
																ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
																LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
																LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico and
																(OrdemServico.IdContratoFaturamento = Contrato.IdContrato or OrdemServico.IdContrato = Contrato.IdContrato) and
																Contrato.IdServico = Servico.IdServico and
																Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
																Contrato.IdPessoa = Pessoa.IdPessoa) OrdemServicoBloquear on (Contrato.IdLoja = OrdemServicoBloquear.IdLoja and Contrato.IdContrato = OrdemServicoBloquear.IdContrato)
								where
									Contrato.IdLoja = Servico.IdLoja and
									Contrato.IdStatus >= 200 and Contrato.IdStatus <= 299 and Contrato.IdStatus != 202 and
									Contrato.IdServico = Servico.IdServico and
									Contrato.IdPessoa = Pessoa.IdPessoa and
									Servico.ExecutarRotinas = 1 and
									(
										(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContrato != '') or
										(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContratoAgrupador != '') or
										(ContaEventualBloquear.IdLoja != '' and ContaEventualBloquear.IdContrato != '') or
										(OrdemServicoBloquear.IdLoja != '' and OrdemServicoBloquear.IdContrato != '')
									)
								group by
									Contrato.IdContrato";
				$resContrato = mysql_query($sqlContrato,$con);
				while($linContrato = mysql_fetch_array($resContrato)){

					$linContrato[Bloqueia] = true;

					$linContrato[Obs] = str_replace('"',"'",$linContrato[Obs]);

					if($ContratosExec[$linContrato[IdContrato]] == ''){
						$ContratosExec[$linContrato[IdContrato]] = true;
					
						// Liberação para o caso "Ativo Temporariamente"
						if($linContrato[IdStatus] == 201 && $linContrato[VarStatus] != ''){
							
							$linContrato[VarStatus] = dataConv($linContrato[VarStatus],"d/m/Y","Y-m-d");
							$linContrato[VarStatus] = dataConv($linContrato[VarStatus],"Y-m-d","Ymd");

							if($linContrato[VarStatus] >= date("Ymd")){
								$linContrato[Bloqueia] = false;
							}
						}

						if($linContrato[Bloqueia] == true){
							
							if($linContrato[UrlRotinaBloqueio] != ''){
								$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Contrato Bloqueado (Financeiro) devido Contas a Receber N° $linBaseVencimento[IdContaReceber].\n";

								$local_Obs	.=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Bloqueado (Financeiro).\n$linContrato[Obs]";								

								$sqlBloqueio = "update Contrato set 
													IdStatus='303', 
													DataUltimoBloqueio=curdate(), 
													Obs = \"$local_Obs\" 
												where 
													IdLoja=$linContrato[IdLoja] and 
													(IdContrato = $linContrato[IdContrato] or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $linContrato[IdLoja] and IdContrato = $linContrato[IdContrato]))";
								$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
								$tr_i++;

								$local_IdLoja		= $linContrato[IdLoja];
								$local_IdContrato	= $linContrato[IdContrato];

								include($Path."modulos/administrativo/".$linContrato[UrlRotinaBloqueio]);
							}else{
								$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Contrato Bloqueado (Financeiro) devido Contas a Receber N° $linBaseVencimento[IdContaReceber].\n";

								$local_Obs	.=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Bloqueado (Financeiro).\n$linContrato[Obs]";								

								$sqlBloqueio = "update Contrato set 
													IdStatus='303', 
													DataUltimoBloqueio=curdate(), 
													Obs = \"$local_Obs\" 
												where 
													IdLoja=$linContrato[IdLoja] and 
													(IdContrato = $linContrato[IdContrato] or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $linContrato[IdLoja] and IdContrato = $linContrato[IdContrato]))";				
								$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
								$tr_i++;
							}

							derrubaConexaoRadius($linContrato[IdLoja], $linContrato[IdContrato]);
							
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdLoja]			= $linContrato[IdLoja];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdContrato]		= $linContrato[IdContrato];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdPessoa]			= $linContrato[IdPessoa];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][Nome]				= $linContrato[Nome];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][UrlContrato]		= $UrlContrato;
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][DescricaoServico]	= $linContrato[DescricaoServico];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][DataVencimento]	= $linBaseVencimento[DataVencimento];
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][Moeda]			= $Moeda;
							$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][ValorTotal]		= $linBaseVencimento[ValorTotal];					

							$LogEmailServico[$linContrato[IdLoja]][$linContrato[IdServico]][$linContrato[IdContrato]] = $LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]];
						}
					}
				}
			}
		}
	}

	// Bloqueio Agendado
	$sql = "select 
				Contrato.IdLoja,
				Contrato.IdContrato,
				Contrato.IdServico,
				Contrato.IdPessoa,
				Pessoa.Nome,
				Contrato.VarStatus,
				Servico.UrlRotinaBloqueio,
				Servico.DescricaoServico,
				Contrato.Obs
			from
				Contrato,
				Servico,
				Pessoa
			where
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdServico = Servico.IdServico and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Contrato.IdStatus = 306  and
				concat(substring(VarStatus,7,4),'-',substring(VarStatus,4,2),'-',substring(VarStatus,1,2)) <= curdate() and
				Servico.ExecutarRotinas = 1";
	$res = mysql_query($sql,$con);
	while($linContrato = mysql_fetch_array($res)){

		$linContrato[Obs] = str_replace('"',"'",$linContrato[Obs]);

		if($linContrato[UrlRotinaBloqueio] != ''){

			$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Bloqueado (Administrativo).\n$linContrato[Obs]";

			$sqlBloqueio = "update Contrato set 
								IdStatus='302', 
								DataUltimoBloqueio=curdate(), 
								Obs = \"$local_Obs\" 
							where 
								IdLoja=$linContrato[IdLoja] and 
								(IdContrato = $linContrato[IdContrato] or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $linContrato[IdLoja] and IdContrato = $linContrato[IdContrato]))";
			$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
			$tr_i++;

			$local_IdLoja		= $linContrato[IdLoja];
			$local_IdContrato	= $linContrato[IdContrato];

			include($Path."modulos/administrativo/".$linContrato[UrlRotinaBloqueio]);
		}else{
			$local_Obs	=	date("d/m/Y H:i:s")." [automatico] - Mudou status para Bloqueado (Administrativo).\n$linContrato[Obs]";

			$sqlBloqueio = "update Contrato set 
								IdStatus='302', 
								DataUltimoBloqueio=curdate(), 
								Obs = \"$local_Obs\" 
							where 
								IdLoja=$linContrato[IdLoja] and 
								(IdContrato = $linContrato[IdContrato] or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $linContrato[IdLoja] and IdContrato = $linContrato[IdContrato]))";				
			$local_transaction[$tr_i]	=	mysql_query($sqlBloqueio,$con);
			$tr_i++;
		}

		derrubaConexaoRadius($linContrato[IdLoja], $linContrato[IdContrato]);
		
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdLoja]			= $linContrato[IdLoja];	
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdContrato]		= $linContrato[IdContrato];
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][IdPessoa]			= $linContrato[IdPessoa];
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][Nome]				= $linContrato[Nome];
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][UrlContrato]		= $UrlContrato;
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][DescricaoServico]	= $linContrato[DescricaoServico];
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][DataVencimento]	= '';
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][Moeda]			= '';
		$LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]][ValorTotal]		= '';

		$LogEmailServico[$linContrato[IdLoja]][$linContrato[IdServico]][$linContrato[IdContrato]] = $LogEmail[$linContrato[IdLoja]][$linContrato[IdContrato]];
	}

	/*#Localizo todos os contas a receber com o vencimento para amanhã	
	$sql = "select
				IdLoja
			from
				Loja
			where
				IdStatus = 1";
	$resLoja = mysql_query($sql,$con);
	while($linLoja = mysql_fetch_array($resLoja)){
		
		$BaseVencimento = BaseVencimento(date("Y-m-d"));

		$ListaPrevisaoBloqueio = null;

		echo "<br><br>".$sql = "select 
					ContaReceberBaseVencimentoBloqueio.IdLoja,
					ContaReceberBaseVencimentoBloqueio.IdContaReceber,
					ContaReceberBaseVencimentoBloqueio.BaseVencimento,
					ContaReceberDados.DataVencimento,
					(ContaReceberDados.ValorLancamento - ContaReceberDados.ValorDesconto + ContaReceberDados.ValorDespesas) ValorTotal
				from 
					ContaReceberBaseVencimentoBloqueio,
					ContaReceberDados
				where
					ContaReceberDados.IdLoja = $linLoja[IdLoja] and
					ContaReceberBaseVencimentoBloqueio.IdStatus = 1 and
					ContaReceberBaseVencimentoBloqueio.BaseVencimento = $BaseVencimento and
					ContaReceberDados.IdLoja = ContaReceberBaseVencimentoBloqueio.IdLoja and
					ContaReceberDados.IdContaReceber = ContaReceberBaseVencimentoBloqueio.IdContaReceber";
		$resBaseVencimento = mysql_query($sql,$con);
		while($linBaseVencimento = mysql_fetch_array($resBaseVencimento)){
			#Posição do Vencimento para bloqueio
			if(getParametroSistema(136,8) == 1){ 
				$sql = "select
							min(DataVencimento) DataVencimento
						from
							ContaReceberVencimento
						where
							IdLoja = $linBaseVencimento[IdLoja] and
							IdContaReceber=$linBaseVencimento[IdContaReceber]";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
			
				$linBaseVencimento[DataVencimento] = $lin[DataVencimento];
			}

			$linBaseVencimento[DataVencimento] = dataConv($linBaseVencimento[DataVencimento],'Y-m-d','d/m/Y');

			$UrlContrato	= getParametroSistema(6,3)."/modulos/administrativo/cadastro_contrato.php?IdContrato=";
			$Moeda			= getParametroSistema(5,1);

			// Filtro todos que tenham contratos vinculados ao Conta a Receber em aberto
			echo "<br><br>".$sqlContrato = "select
								Contrato.IdLoja,
								Contrato.IdContrato,
								Servico.DiasLimiteBloqueio,
								Contrato.IdStatus,
								Contrato.VarStatus,
								Servico.UrlRotinaBloqueio,
								Pessoa.IdPessoa,
								substr(Pessoa.Nome,1,20) Nome,
								substr(Servico.DescricaoServico,1,20) DescricaoServico,
								Contrato.Obs
							from
								Servico,
								Pessoa,
								Contrato left join (select 
														LancamentoFinanceiro.IdLoja,
														LancamentoFinanceiro.IdContrato,
														Contrato.IdContratoAgrupador
													from
														ContaReceber,
														LancamentoFinanceiroContaReceber,
														LancamentoFinanceiro,
														Contrato,
														Servico,
														Pessoa
													where
														ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
														ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
														ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
														LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
														ContaReceber.IdLoja = Contrato.IdLoja and
														Contrato.IdLoja = Servico.IdLoja and
														ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
														LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
														LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
														Contrato.IdServico = Servico.IdServico and
														Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
														Contrato.IdPessoa = Pessoa.IdPessoa) ContratoBloquear on 
															(Contrato.IdLoja = ContratoBloquear.IdLoja and (Contrato.IdContrato = ContratoBloquear.IdContrato or Contrato.IdContrato = ContratoBloquear.IdContratoAgrupador))
											left join (select
															LancamentoFinanceiro.IdLoja,
															ContaEventual.IdContrato
														from
															ContaReceber,
															LancamentoFinanceiroContaReceber,
															LancamentoFinanceiro,
															ContaEventual,
															Contrato,
															Servico,
															Pessoa
														where
															ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
															ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
															ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
															LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
															ContaReceber.IdLoja = Contrato.IdLoja and
															ContaEventual.IdLoja = Servico.IdLoja and
															Contrato.IdLoja = Servico.IdLoja and
															ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
															LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
															LancamentoFinanceiro.IdContaEventual = ContaEventual.IdContaEventual and
															ContaEventual.IdContrato = Contrato.IdContrato and
															Contrato.IdServico = Servico.IdServico and
															Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
															Contrato.IdPessoa = Pessoa.IdPessoa) ContaEventualBloquear on (Contrato.IdLoja = ContaEventualBloquear.IdLoja and Contrato.IdContrato = ContaEventualBloquear.IdContrato)
											left join (select
															LancamentoFinanceiro.IdLoja,
															OrdemServico.IdContratoFaturamento
														from
															ContaReceber,
															LancamentoFinanceiroContaReceber,
															LancamentoFinanceiro,
															OrdemServico,
															Contrato,
															Servico,
															Pessoa
														where
															ContaReceber.IdLoja = $linBaseVencimento[IdLoja] and
															ContaReceber.IdContaReceber = $linBaseVencimento[IdContaReceber] and
															ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
															LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
															ContaReceber.IdLoja = Contrato.IdLoja and
															OrdemServico.IdLoja = Servico.IdLoja and
															Contrato.IdLoja = Servico.IdLoja and
															ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
															LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
															LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico and
															OrdemServico.IdContratoFaturamento = Contrato.IdContrato and
															Contrato.IdServico = Servico.IdServico and
															Servico.DiasLimiteBloqueio < $linBaseVencimento[BaseVencimento] and
															Contrato.IdPessoa = Pessoa.IdPessoa) OrdemServicoBloquear on (Contrato.IdLoja = OrdemServicoBloquear.IdLoja and Contrato.IdContrato = OrdemServicoBloquear.IdContratoFaturamento)
							where
								Contrato.IdLoja = Servico.IdLoja and
								Contrato.IdStatus >= 200 and Contrato.IdStatus <= 299 and Contrato.IdStatus != 202 and
								Contrato.IdServico = Servico.IdServico and
								Contrato.IdPessoa = Pessoa.IdPessoa and
								Servico.ExecutarRotinas = 1 and
								(
									(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContrato != '') or
									(ContratoBloquear.IdLoja != '' and ContratoBloquear.IdContratoAgrupador != '') or
									(ContaEventualBloquear.IdLoja != '' and ContaEventualBloquear.IdContrato != '') or
									(OrdemServicoBloquear.IdLoja != '' and OrdemServicoBloquear.IdContratoFaturamento != '')
								)";
			$resContrato = mysql_query($sqlContrato,$con);
			while($linContrato = mysql_fetch_array($resContrato)){					
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][IdLoja]			= $linContrato[IdLoja];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][IdContrato]		= $linContrato[IdContrato];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][IdPessoa]		= $linContrato[IdPessoa];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][Nome]			= $linContrato[Nome];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][UrlContrato]		= $UrlContrato;
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][DescricaoServico]= $linContrato[DescricaoServico];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][DataVencimento]	= $linBaseVencimento[DataVencimento];
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][Moeda]			= $Moeda;
				$ListaPrevisaoBloqueio[$linContrato[IdLoja]][$linContrato[IdContrato]][ValorTotal]		= $linBaseVencimento[ValorTotal];
			}
		}	
	}*/

	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;				
		}
	}
		
	if($local_transaction == true){

		$sql = "COMMIT;";
		mysql_query($sql,$con);

		if(count($LogEmail) > 0){

			$keys = array_keys($LogEmail);

			for($i=0; $i<count($keys); $i++){

				$IdLoja = $keys[$i];
				enviaClientesBloqueados($IdLoja, $LogEmail[$IdLoja]);

				if(count($LogEmailServico[$IdLoja]) > 0){
				
					$keysServico = array_keys($LogEmailServico[$IdLoja]);
					
					for($ii=0; $ii<count($keysServico); $ii++){
					
						$IdServico = $keysServico[$i];
						enviaClientesBloqueadosServico($IdLoja, $IdServico, $LogEmailServico[$IdLoja][$IdServico]);
					}
				}
			}
		}

#		if(count($ListaPrevisaoBloqueio) > 0){
#			$keys = array_keys($ListaPrevisaoBloqueio);
#
#			for($i=0; $i<count($keys); $i++){
#				$IdLoja = $keys[$i];
#				//enviaListaPrevisaoBloqueio($IdLoja, $ListaPrevisaoBloqueio[$IdLoja]);
#			}
#		}
	}else{
		$sql = "ROLLBACK;";
		mysql_query($sql,$con);
	}
?>