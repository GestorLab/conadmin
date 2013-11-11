<?
	global $local_Erro;

	$sqlContaReceber = "select
							ContaReceberDados.IdPessoa,
							ContaReceberDados.ValorDespesas,
							ContaReceberDados.ValorTaxaReImpressaoBoleto,
							ContaReceberDados.ValorOutrasDespesas,
							ContaReceberDados.ValorLancamento ValorFinal,
							ContaReceberDados.ValorDesconto,
							ContaReceberDados.IdPessoaEndereco,
							ContaReceberDados.IdLocalCobranca,
							ContaReceberDados.DataVencimento,
							ContaReceberDados.LimiteDesconto,
							ContaReceberDados.IdStatus,
							LancamentoFinanceiro.IdProcessoFinanceiro
						from
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceberDados
						where
							LancamentoFinanceiro.IdLoja = $IdLoja and
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
							LancamentoFinanceiro.IdLoja = ContaReceberDados.IdLoja and
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
							LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber";
	$resContaReceber = mysql_query($sqlContaReceber,$con);
	$linContaReceber = mysql_fetch_array($resContaReceber);

	if($linContaReceber[IdProcessoFinanceiro] != ''){
		$sqlProcessoFinanceiro = "SELECT
										DataNotaFiscal
									FROM
										ProcessoFinanceiro
									WHERE
										IdLoja = $IdLoja AND
										IdProcessoFinanceiro = $linContaReceber[IdProcessoFinanceiro]";
		$resProcessoFinanceiro = mysql_query($sqlProcessoFinanceiro,$con);
		$linProcessoFinanceiro = mysql_fetch_array($resProcessoFinanceiro);

		$DataNotaFiscal = $linProcessoFinanceiro[DataNotaFiscal];
	}else{
		$DataNotaFiscal = date("Y-m-d");
	}

	$sqlValorRecebido = "select
							ContaReceberRecebimento.IdContaReceberRecebimento,
							ContaReceberRecebimento.ValorRecebido,
							ContaReceberRecebimento.DataRecebimento,
							ContaReceberRecebimento.ValorDesconto
						from
							ContaReceberRecebimento
						where
							ContaReceberRecebimento.IdLoja = $IdLoja and
							ContaReceberRecebimento.IdContaReceber = $IdContaReceber
						order by
							IdContaReceberRecebimento DESC
						limit 0,1";
	$resValorRecebido = mysql_query($sqlValorRecebido, $con);
	if($linValorRecebido = mysql_fetch_array($resValorRecebido)){
		$ValorRecebido			= $linValorRecebido[ValorRecebido];
		$ValorRecebidoDesconto	= $linValorRecebido[ValorDesconto];
		$DataRecebimento		= $linValorRecebido[DataRecebimento];
	}

	if($DataNotaFiscal == ''){
		$DataNotaFiscal = date('Y-m-d');
		$DataUltimaNF   = dataUltimaNF($IdLoja, $linContaReceber[IdLocalCobranca]);

		if(dataConv($DataNotaFiscal, 'Y-m-d','Ymd') < dataConv($DataUltimaNF, 'Y-m-d','Ymd')){
			$DataNotaFiscal = $DataUltimaNF;
		}
	}

	if($DataNotaFiscalForca != ''){
		$DataNotaFiscal = $DataNotaFiscalForca;
	}
	
	if($Modelo == '' or $Modelo == null){
		$Modelo	= 21;
	}

	$PeriodoApuracao		= substr($DataNotaFiscal,0,7);
	$IdNotaFiscalLayout		= $lin[IdNotaFiscalLayout];
	$IdNotaFiscalTipo		= $lin[IdNotaFiscalTipo];
	$IdPessoa				= $linContaReceber[IdPessoa];
	$ValorTotal				= 0; #$linContaReceber[ValorFinal];
	$GrupoTensao			= 0;
	$DataEmissao			= $DataNotaFiscal;
	$Serie					= "001";
	$ValorDesconto			= $linContaReceber[ValorDesconto];
	$ValorOutros			= $linContaReceber[ValorDespesas];
	$ValorOutros			+= $linContaReceber[ValorTaxaReImpressaoBoleto];
	$ValorOutros			+= $linContaReceber[ValorOutrasDespesas];	
	$NotaFiscalLayoutParametro = null;

	$sqlServicoNotaFiscalParametro = "select
										IdNotaFiscalLayoutParametro,
										Valor
									from
										NotaFiscalTipoParametro
									where
										IdLoja = $IdLoja and
										IdNotaFiscalTipo = $IdNotaFiscalTipo and
										IdNotaFiscalLayout = $IdNotaFiscalLayout";
	$resServicoNotaFiscalParametro = mysql_query($sqlServicoNotaFiscalParametro,$con);
	while($linServicoNotaFiscalParametro = mysql_fetch_array($resServicoNotaFiscalParametro)){
		$NotaFiscalLayoutParametro[$linServicoNotaFiscalParametro[IdNotaFiscalLayoutParametro]] = $linServicoNotaFiscalParametro[Valor];
	}

	$TipoUtilizacao = $NotaFiscalLayoutParametro[1];
	
#	varifica se tem EV em caso positivo, return false;
	if($NotaFiscalLayoutParametro[7] != 1){
		$sqlVerificarEV = "select 
								count(*) QTD
							from
								LancamentoFinanceiroContaReceber,
								LancamentoFinanceiro 
							where 
								LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and 
								LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and 
								LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
								LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
								LancamentoFinanceiro.IdContaEventual is not null";
		$resVerificarEV = mysql_query($sqlVerificarEV,$con);
		$linVerificarEV = mysql_fetch_array($resVerificarEV);
		
		if($linVerificarEV[QTD] > 0){
			return true;
		}
	}
	
#	varifica se tem OS em caso positivo, return false;
	if($NotaFiscalLayoutParametro[8] != 1){
		$sqlVerificarOS = "select 
								count(*) QTD
							from
								LancamentoFinanceiroContaReceber,
								LancamentoFinanceiro 
							where 
								LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and 
								LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and 
								LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
								LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
								LancamentoFinanceiro.IdOrdemServico is not null";
		$resVerificarOS = mysql_query($sqlVerificarOS,$con);
		$linVerificarOS = mysql_fetch_array($resVerificarOS);
		
		if($linVerificarOS[QTD] > 0){
			return true;
		}
	}
	
	$sqlPessoaContaReceber = "select
								Pessoa.CPF_CNPJ,
								Pessoa.TipoPessoa,
								PessoaEndereco.IdPais,
								PessoaEndereco.IdEstado
							from
								Pessoa,
								PessoaEndereco
							where
								Pessoa.IdPessoa = $IdPessoa and
								Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
								PessoaEndereco.IdPessoaEndereco = $linContaReceber[IdPessoaEndereco]";
	$resPessoaContaReceber = mysql_query($sqlPessoaContaReceber,$con);
	$linPessoaContaReceber = mysql_fetch_array($resPessoaContaReceber);

	$CPF_CNPJ = $linPessoaContaReceber[CPF_CNPJ];

	switch($linPessoaContaReceber[TipoPessoa]){
		case 1:
			$TipoAssinante = 1;
			break;
		case 2:
			$TipoAssinante = 3;
			break;
	}

	if($NotaFiscalLayoutParametro[10] != 2){
		$where = "and PeriodoApuracao = '$PeriodoApuracao'";
	}else{
		$where = "";
	}

	$sqlNotaFiscal = "select
						max(IdNotaFiscal) IdNotaFiscal
					from
						NotaFiscal,
						NotaFiscalTipo
					where
						(
							NotaFiscalTipo.IdLoja = $IdLoja OR
							NotaFiscalTipo.IdLojaCompartilhada = $IdLoja
						) and						
						(
							NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja OR
							NotaFiscal.IdLoja = NotaFiscalTipo.IdLojaCompartilhada
						) AND
						NotaFiscal.IdNotaFiscalLayout = $IdNotaFiscalLayout
						$where";
	$resNotaFiscal = mysql_query($sqlNotaFiscal,$con);
	$linNotaFiscal = mysql_fetch_array($resNotaFiscal);

	$IdNotaFiscal = $linNotaFiscal[IdNotaFiscal] + 1;

	if($IdNotaFiscalForca != ''){
		$IdNotaFiscal = $IdNotaFiscalForca;
	}

////// Preparando as Variáveis

	# CPF_CNPJ
	$CPF_CNPJ = str_replace(".","",$CPF_CNPJ);
	$CPF_CNPJ = str_replace("/","",$CPF_CNPJ);
	$CPF_CNPJ = str_replace("-","",$CPF_CNPJ);
	$CPF_CNPJ = str_pad($CPF_CNPJ, 14, "0", STR_PAD_LEFT); 

	# Numero documento fiscal	
	$Campo[IdNotaFiscal] = str_pad($IdNotaFiscal, 9, "0", STR_PAD_LEFT); 

////// FIM - Preparando as Variáveis

	// Salva a NF
	$sql = "insert into NotaFiscal set 
				IdNotaFiscalLayout = $IdNotaFiscalLayout,
				IdLoja = $IdLoja,
				PeriodoApuracao = '$PeriodoApuracao',
				IdNotaFiscal = $IdNotaFiscal,
				IdContaReceber = $IdContaReceber,
				IdPais = $linPessoaContaReceber[IdPais],
				IdEstado = $linPessoaContaReceber[IdEstado],
				TipoAssinante = $TipoAssinante,
				TipoUtilizacao = $TipoUtilizacao,
				DataEmissao = '$DataNotaFiscal',
				Modelo = '$Modelo',
				Serie = '$Serie',
				ValorTotal = 0,
				ValorNaoTributado = '0',
				ValorOutros = '$ValorOutros',
				MD5 = md5(concat($IdLoja,$IdNotaFiscalLayout,'$PeriodoApuracao',$IdNotaFiscal)),
				IdStatus = 1,
				LoginCriacao = '$local_Login',
				DataCriacao = (concat(curdate(),' ',curtime()))";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	// Informa qual nota fiscal para o contas a receber
	$sql = "update ContaReceber set NumeroNF='$Campo[IdNotaFiscal]',  DataNF='$DataNotaFiscal', ModeloNF='$Modelo' where IdLoja='$IdLoja' and IdContaReceber='$IdContaReceber'";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;

	$NotaFiscalLayoutParametro	= null;
	$IdNotaFiscalItem			= 0;
	$ValorTotalBaseCalculoICMS	= 0;
	$ValorTotalICMS				= 0;
	$ValorTotalDesconto			= 0;

	$sqlLancamentoFinanceiro = "select
									LancamentoFinanceiroAliquota.IdLancamentoFinanceiro,
									LancamentoFinanceiroAliquota.IdServico,
									LancamentoFinanceiroAliquota.CFOP,
									LancamentoFinanceiroAliquota.Valor,
									LancamentoFinanceiroAliquota.ValorDescontoAConceber,
									LancamentoFinanceiroAliquota.ValorBaseCalculoICMS,
									LancamentoFinanceiroAliquota.ValorICMS,
									LancamentoFinanceiroAliquota.AliquotaICMS,
									LancamentoFinanceiroAliquota.IdCategoriaTributaria
								from
									LancamentoFinanceiroAliquota
								where
									LancamentoFinanceiroAliquota.IdLoja = $IdLoja and
									LancamentoFinanceiroAliquota.IdContaReceber = $IdContaReceber
								order by
									LancamentoFinanceiroAliquota.IdLancamentoFinanceiro";
	$resLancamentoFinanceiro = mysql_query($sqlLancamentoFinanceiro,$con);
	while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){

		$CFOP					= $linLancamentoFinanceiro[CFOP];
		$ValorTotalItem			= $linLancamentoFinanceiro[Valor];
		$ValorBaseCalculoICMS	= $linLancamentoFinanceiro[ValorBaseCalculoICMS];
		$ValorICMS				= $linLancamentoFinanceiro[ValorICMS];
		$AliquotaICMS			= $linLancamentoFinanceiro[AliquotaICMS];
		$IdLancamentoFinanceiro	= $linLancamentoFinanceiro[IdLancamentoFinanceiro];
		$IdCategoriaTributaria	= $linLancamentoFinanceiro[IdCategoriaTributaria];
		$Situacao				= 'S';		

		if($ValorTotalItem > 0 && $linContaReceber[IdStatus] == 2){
			if($ValorRecebidoDesconto > $ValorTotalItem){
				$ValorDesconto = $ValorTotalItem;
				$ValorRecebidoDesconto -= $ValorTotalItem;
			}else{
				$ValorDesconto = $ValorRecebidoDesconto;
				$ValorRecebidoDesconto = 0;
			}
		}else{
			$ValorDesconto = $linLancamentoFinanceiro[ValorDescontoAConceber];
		}

		$ValorBaseCalculoICMS		= str_replace(",","",$ValorBaseCalculoICMS);
		$ValorBaseCalculoICMS		-= $ValorDesconto;

		$ValorTotalBaseCalculoICMS	+= $ValorBaseCalculoICMS;
		$ValorTotalDesconto			+= $ValorDesconto;

		if($ValorICMS != 0){
			$ValorTotalICMS	+= $ValorICMS;
		}

		$IdNotaFiscalItem++;

 		$sqlServicoNotaFiscalParametro = "select
											IdNotaFiscalLayoutParametro,
											Valor
										from
											ServicoNotaFiscalLayoutParametro
										where
											IdLoja = $IdLoja and
											IdServico = $linLancamentoFinanceiro[IdServico] and
											IdNotaFiscalLayout = $IdNotaFiscalLayout";
		$resServicoNotaFiscalParametro = mysql_query($sqlServicoNotaFiscalParametro,$con);
		while($linServicoNotaFiscalParametro = mysql_fetch_array($resServicoNotaFiscalParametro)){
			$NotaFiscalLayoutParametro[$linServicoNotaFiscalParametro[IdNotaFiscalLayoutParametro]] = $linServicoNotaFiscalParametro[Valor];
		}

		$ClassificacaoItem = $NotaFiscalLayoutParametro[2];

		if($AliquotaICMS == '' || $AliquotaICMS == NULL || $IdCategoriaTributaria == 1){
			$ValorICMStemp		= 'NULL';
			$AliquotaICMStemp	= 'NULL';
		}else{
			$ValorICMStemp		= "'$ValorICMS'";
			$AliquotaICMStemp	= "'$AliquotaICMS'";
		}

		if($ValorTotalItem < 0){
			$ValorDesconto = 0;
		}

		$ValorTotal += $ValorTotalItem;

		$sql = "insert into NotaFiscalItem set
							IdNotaFiscalLayout = $IdNotaFiscalLayout,
							IdLoja = $IdLoja,
							PeriodoApuracao = '$PeriodoApuracao',
							IdNotaFiscal = $IdNotaFiscal,
							IdNotaFiscalItem = $IdNotaFiscalItem,
							IdContaReceber = $IdContaReceber,
							IdLancamentoFinanceiro = $IdLancamentoFinanceiro,
							CFOP = '$CFOP',
							IdClassificacaoItem = '$ClassificacaoItem',
							ValorTotal = '$ValorTotalItem',
							ValorDesconto = '$ValorDesconto',
							ValorBaseCalculoICMS = '$ValorBaseCalculoICMS',
							ValorICMS = $ValorICMStemp,
							ValorNaoTributado = 0,
							ValorOutros = 0,
							AliquotaICMS = $AliquotaICMStemp,
							IdStatus = 1";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;

		if($CFOP == '' || $ClassificacaoItem == ''){
			$local_Erro = 160;
			$erro = true;
		}
	}

	if($IdNotaFiscalItem == 0){
		echo "NOTA FISCAL SEM ITEM CONFIGURADO PARA GERAÇÃO: CR $IdContaReceber<br>";
		$erro = true;
	}
	if($ValorOutros > 0){

		$IdNotaFiscalItem++;

		$ClassificacaoItem	= '0899';
		$ValorTotalItem		= $ValorOutros;
		$ValorTotal			+= $ValorTotalItem;
		
		$sql = "insert into NotaFiscalItem set
							IdNotaFiscalLayout = $IdNotaFiscalLayout,
							IdLoja = $IdLoja,
							PeriodoApuracao = '$PeriodoApuracao',
							IdNotaFiscal = $IdNotaFiscal,
							IdNotaFiscalItem = $IdNotaFiscalItem,
							IdContaReceber = $IdContaReceber,
							IdLancamentoFinanceiro = NULL,
							CFOP = NULL,
							IdClassificacaoItem = '$ClassificacaoItem',
							ValorTotal = '$ValorTotalItem',
							ValorDesconto = 0,
							ValorBaseCalculoICMS = 0,
							ValorICMS = 0,
							ValorNaoTributado = 0,
							ValorOutros = $ValorOutros,
							AliquotaICMS = 0,
							IdStatus = 1";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	}	
	
	# Valor Total
	$Campo[ValorTotal] = number_format(($ValorTotal-$ValorTotalDesconto), 2, '.', '');
	$Campo[ValorTotal] = str_replace(".","",$Campo[ValorTotal]);
	$Campo[ValorTotal] = str_pad($Campo[ValorTotal], 12, "0", STR_PAD_LEFT); 

	# Valor Base Calculo do ICMS
	$Campo[ValorBaseCalculoICMS] = number_format($ValorTotalBaseCalculoICMS, 2, '.', '');
	$Campo[ValorBaseCalculoICMS] = str_replace(".","",$Campo[ValorBaseCalculoICMS]);
	$Campo[ValorBaseCalculoICMS] = str_pad($Campo[ValorBaseCalculoICMS], 12, "0", STR_PAD_LEFT); 

	# Valor ICMS
	$Campo[ValorICMS] = number_format($ValorTotalICMS, 2, '.', '');
	$Campo[ValorICMS] = str_replace(".","",$Campo[ValorICMS]);
	$Campo[ValorICMS] = str_pad($Campo[ValorICMS], 12, "0", STR_PAD_LEFT); 

	# Codigo de Autenticacao Digital do Documento
	$CodigoAutenticacaoDocumento = $CPF_CNPJ.$Campo[IdNotaFiscal].$Campo[ValorTotal].$Campo[ValorBaseCalculoICMS].$Campo[ValorICMS];
	$CodigoAutenticacaoDocumento = md5($CodigoAutenticacaoDocumento);

	if($IdCategoriaTributaria == 1){
		$ValorTotalICMStemp = 'NULL';
	}else{
		$ValorTotalICMStemp = "$ValorTotalICMS";
	}

	$sql = "update NotaFiscal set 
				CodigoAutenticacaoDocumento = '$CodigoAutenticacaoDocumento',  
				ValorBaseCalculoICMS = '$ValorTotalBaseCalculoICMS',
				ValorICMS = $ValorTotalICMStemp,				
				ValorDesconto = '$ValorTotalDesconto',
				ValorTotal = ($ValorTotal - ValorDesconto)
			where 
				IdNotaFiscalLayout = $IdNotaFiscalLayout and
				IdLoja = $IdLoja and
				PeriodoApuracao = '$PeriodoApuracao' and
				IdNotaFiscal = $IdNotaFiscal";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
?>