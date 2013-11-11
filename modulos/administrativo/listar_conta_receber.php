<?
	set_time_limit(0);
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdPessoaLogin				= $_SESSION['IdPessoa'];
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_nome						= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_descrisao_servico			= url_string_xsl($_POST['filtro_descrisao_servico'],'url',false);
	$filtro_valor						= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_somar_desconto				= $_POST['filtro_somar_desconto'];
	$filtro_campo						= $_POST['filtro_campo'];
	$filtro_local_cobranca				= $_POST['filtro_local_cobranca'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_status						= $_POST['filtro_status'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_carne						= $_POST['filtro_carne'];
	$filtro_id_servico					= $_POST['filtro_id_servico'];
	$filtro_cpf_cnpj					= $_POST['filtro_cpf_cnpj'];
	$filtro_grupo_pessoa				= $_POST['filtro_grupo_pessoa'];
	$filtro_idPessoa					= $_GET['IdPessoa'];
	$filtro_conta_receber				= $_GET['IdContaReceber'];
	$filtro_ordem_servico				= $_GET['IdOrdemServico'];	
	$filtro_conta_eventual				= $_GET['IdContaEventual'];	
	$filtro_arquivo_retorno				= $_GET['IdArquivoRetorno'];	
	$filtro_arquivo_remessa				= $_GET['IdArquivoRemessa'];
	$filtro_processo_financeiro			= $_GET['IdProcessoFinanceiro'];
	$filtro_imprimir_conta_receber		= $_GET['ImprimirContaReceber'];
	$filtro_id_conta_cancelada			= $_GET['IdContaCancelada'];
	$filtro_id_conta_receber_agrupador	= $_GET['IdContaReceberAgrupador'];
	$filtro_id_caixa					= $_GET['IdCaixa'];
	$filtro_id_caixa_movimentacao		= $_GET['IdCaixaMovimentacao'];
	$filtro_tipo_relatorio				= 1;//$_SESSION["filtro_tipo_relatorio"];
	$ValorTotal 						= "0.00";
	$ValorRecebidoSoma 					= "0.00";
	
	$filtro_cancelado	=	$_SESSION["filtro_cancelado"];
	$filtro_juros		=	$_SESSION["filtro_juros"];
	$filtro_soma		=	$_SESSION["filtro_soma"];
	$filtro_nota_fiscal	=	$_SESSION["filtro_nota_fiscal"];
	$filtro_impressao	=	$_SESSION["filtro_impressao"];
	$filtro_conta_receber_nota_fiscal 			= $_SESSION["filtro_conta_receber_nota_fiscal"];
	$filtro_subtrair_desconto_conceber 			= $_SESSION["filtro_subtrair_desconto_conceber"];
	$filtro_contabiliza_recebimentos_vencimento = $_SESSION["filtro_contabiliza_recebimentos_vencimento"];
	
	
	if($filtro_ordem_servico == ''){
		$filtro_contrato = $_GET['IdContrato'];
	}
	
	if($filtro_local_cobranca == '' && $_GET['IdLocalCobranca'] != ''){
		$filtro_local_cobranca = $_GET['IdLocalCobranca'];
	} 
	
	if($filtro_tipo_pessoa == '' && $_GET['IdParametroSistema'] != ''){
		$filtro_tipo_pessoa 	= $_GET['IdParametroSistema'];
	}
	
	if($filtro_numero_documento 	== '' && $_GET['NumeroDocumento']!=''){
		$filtro_numero_documento	= $_GET['NumeroDocumento'];
	}
	
	if($filtro_carne == '' && $_GET['IdCarne']!=''){
		$filtro_carne	= $_GET['IdCarne'];
	}
	
	if($filtro_status == '' && $_GET['IdStatus']!=''){
		$filtro_status	= $_GET['IdStatus'];
	}
	
	if($filtro_limit == '' && $_GET['filtro_limit'] != ''){
		$filtro_limit	= $_GET['filtro_limit'];
	}
	
	$codigo_de_barras			= $_POST['codigo_de_barras'];
	
	if($codigo_de_barras !=''){
		switch(strlen($codigo_de_barras)){
			case '44':
				$filtro_nosso_numero = (int)(substr($codigo_de_barras,32,10));
				break;
		}
	}
	
	$filtro_url	 			= "";
	$filtro_sql  			= "";
	$filtro_from 			= "";
	$sqlAux		 			= "";
	$filtro_status_receb	= "";
	$filtro_sql_select		= "";
	
	LimitVisualizacao("listar");

	$sqlAux = ", ContaReceberDados";

	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_tipo_relatorio != ""){
		$filtro_url .= "&TipoRelatorio=".$filtro_tipo_relatorio;
	}
	
	if($filtro_imprimir_conta_receber != ''){
		if($filtro_status > 0 && $filtro_status < 3){
			switch($filtro_imprimir_conta_receber){
				case 1:
					if($filtro_status == 2){
						$filtro_sql .=	" and ContaReceberDados.IdStatus = 2";
					}else{
						$filtro_sql .=	" and ContaReceberDados.IdStatus = 1";
					}
					break;
				case 2:
					if($filtro_status == 1){
						$filtro_sql .=	" and ContaReceberDados.IdStatus = 1";
					}else{
						$filtro_sql .=	" and ContaReceberDados.IdStatus = 2";
					}
					break;
			}		
		}
	}
		
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_local_cobranca!=""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}

	if($filtro_tipo_pessoa != ""){
		$filtro_url	.= "&TipoPessoa=$filtro_tipo_pessoa";
		$filtro_sql .=	" and Pessoa.TipoPessoa = $filtro_tipo_pessoa";
	}
	
	if($filtro_arquivo_remessa!=""){
		$filtro_sql .= " and ContaReceberDados.IdArquivoRemessa = $filtro_arquivo_remessa";		
	}
	
	if($filtro_ordem_servico!=""){
		$filtro_url  .= "&IdOrdemServico=".$filtro_ordem_servico;
		$filtro_sql  .= " and LancamentoFinanceiro.IdOrdemServico= $filtro_ordem_servico";
		
		$filtro_idPessoa="";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_url  .= "&IdPessoa=".$filtro_idPessoa;
		$filtro_sql  .= " and Pessoa.IdPessoa = $filtro_idPessoa";
	}

	if($filtro_carne!=""){
		$filtro_url  .= "&IdCarne=".$filtro_carne;
		$filtro_sql .= " and ContaReceberDados.IdCarne = $filtro_carne";
	}
	
	if($filtro_conta_receber!=""){
		$filtro_url  .= "&IdContaReceber=".$filtro_conta_receber;
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = $filtro_conta_receber";
	}
	
	if($filtro_contrato!=""){
		$filtro_url  .= "&IdContrato=".$filtro_contrato;
		$filtro_sql .= " and LancamentoFinanceiro.IdContrato = $filtro_contrato";
	}
	
	if($filtro_conta_eventual!=""){
		$filtro_url  .= "&IdContaEventual=".$filtro_conta_eventual;
		$filtro_sql .= " and LancamentoFinanceiro.IdContaEventual = $filtro_conta_eventual";
	}
	
	if($filtro_processo_financeiro!=""){
		$filtro_url  .= "&IdProcessoFinanceiro=".$filtro_processo_financeiro;
		$filtro_sql .= " and LancamentoFinanceiro.IdProcessoFinanceiro = $filtro_processo_financeiro";
	}
	
	if($filtro_numero_documento!=""){	
		$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_numero_documento'";
	}
	if($filtro_conta_receber_nota_fiscal!=""){
		if($filtro_conta_receber_nota_fiscal == 1){	
			$filtro_sql .=	" and (ContaReceberDados.NumeroNF != '' and ContaReceberDados.NumeroNF is not null)";
		}
	}
	
	if($filtro_status!=""){
		$filtro_url  .= "&IdStatus=".$filtro_status;
		if($filtro_status == 200){
			$filtro_sql_status  .= " and ContaReceberDados.IdStatus = 1 and DataVencimento < curdate() and ContaReceberDados.IdStatus != 7";
		}else{
			if($filtro_status != 7){
				switch($filtro_imprimir_conta_receber){
					case 1:
						$filtro_sql_status  .= " and ContaReceberDados.IdStatus != 7";
						break;
					case 2:
						$filtro_sql_status  .= " and ContaReceberDados.IdStatus != 7";
						break;
					default:
						$filtro_sql_status  .= " and ContaReceberDados.IdStatus = ".$filtro_status." and ContaReceberDados.IdStatus != 7";
						break;
				}
			}else{
				$filtro_sql_status  .= " and ContaReceberDados.IdStatus = ".$filtro_status."";
			}
		}
	}else{
		$filtro_sql_status  .= " and ContaReceberDados.IdStatus != 7";		
	}
	
	if($filtro_arquivo_retorno!=""){
		$filtro_url .= "&Campo=IdArquivoRetorno";
		$filtro_url .= "&Valor=".$filtro_arquivo_retorno;
		
		$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdArquivoRetorno = $filtro_arquivo_retorno";
		
		if($_GET['IdLocalCobranca']!=""){
			$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalCobranca'];
		}
		if($_GET['IdLocalRecebimento']!=""){
			$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalRecebimento'];
		}
		$filtro_sql .= " and ContaReceberDados.IdLoja = ContaReceberRecebimentoAtivo.IdLoja";
		$filtro_sql .= " and ContaReceberDados.IdContaReceber = ContaReceberRecebimentoAtivo.IdContaReceber";
		
		$filtro_sql_select = ",
			LancamentoFinanceiro.IdProcessoFinanceiro,
			ContaReceberRecebimentoAtivo.DataRecebimento,
			ContaReceberRecebimentoAtivo.IdRecibo,
			ContaReceberRecebimentoAtivo.ValorRecebido,
			ContaReceberRecebimentoAtivo.IdLocalCobranca IdLocalCobrancaRecebimento";
		
		$sqlAux = ", ContaReceberDados, ContaReceberRecebimentoAtivo";		
	}else{
		$filtro_status_receb .=	" and ContaReceberRecebimento.IdStatus != 0";	
	}
	
	if($filtro_id_conta_cancelada != ""){
		$filtro_sql .= " and ContaReceberDados.IdContaReceber in ($filtro_id_conta_cancelada)";
	}
	
	if($filtro_id_conta_receber_agrupador != ""){
		$filtro_sql .= " and ContaReceberDados.IdContaReceber in (
			select 
				IdContaReceber
			from 
				ContaReceberAgrupadoParcela
			where
				IdLoja = $local_IdLoja and
				IdContaReceberAgrupador = $filtro_id_conta_receber_agrupador
		)";
	}
	
	if($filtro_id_caixa != "" || $filtro_id_caixa_movimentacao != ""){
		$filtro_sql .= " and ContaReceberDados.IdContaReceber in ( 
			select 
				IdContaReceber
			from 
				ContaReceberRecebimento
			where
				IdLoja = $local_IdLoja";
		
		if($filtro_id_caixa != ""){
			$filtro_sql .= " and IdCaixa = $filtro_id_caixa";
		}
		
		if($filtro_id_caixa_movimentacao != ""){
			$filtro_sql .= " and IdCaixaMovimentacao = $filtro_id_caixa_movimentacao";
		}
		
		$filtro_sql .= ")";
	}
	
	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=".$filtro_valor;
	
		switch($filtro_campo){
			case 'IdArquivoRetorno':
				$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdArquivoRetorno = $filtro_valor";
				
				if($_GET['IdLocalCobranca']!=""){
					$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalCobranca'];
				}
				
				if($_GET['IdLocalRecebimento']!=""){
					$filtro_sql .= " and ContaReceberRecebimentoAtivo.IdLocalCobranca = ".$_GET['IdLocalRecebimento'];
				}
				
				$filtro_sql .= " and ContaReceberDados.IdLoja = ContaReceberRecebimentoAtivo.IdLoja";
				$filtro_sql .= " and ContaReceberDados.IdContaReceber = ContaReceberRecebimentoAtivo.IdContaReceber";
				
				$filtro_sql_select = ",
					LancamentoFinanceiro.IdProcessoFinanceiro,
					ContaReceberRecebimentoAtivo.DataRecebimento,
					ContaReceberRecebimentoAtivo.IdRecibo,
					ContaReceberRecebimentoAtivo.ValorRecebido,
					ContaReceberRecebimentoAtivo.IdLocalCobranca IdLocalCobrancaRecebimento";
				
				$sqlAux = ", ContaReceberDados, ContaReceberRecebimentoAtivo";
				break;
			case 'IdArquivoRemessa':
				$filtro_sql .= " and ContaReceberDados.IdArquivoRemessa = $filtro_valor";
								
				$filtro_sql_select = ",
					ContaReceberDados.IdArquivoRemessa";
				
				$sqlAux = ", ContaReceberDados";
				break;
			case 'DataLancamento':
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataLancamento = '$filtro_valor'";
				break;
			case 'DataCancelamento'://LEONARDO/#/o filtro listar contas a receber cancelados tem que estar como "sim" para este funcionar
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataAlteracao like '$filtro_valor%' and ContaReceberDados.IdStatus = 0";
				break;
			case 'DataExclusao':
				$filtro_sql_status = "";
				$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
				$filtro_sql .= " and ContaReceberDados.DataAlteracao like '$filtro_valor%' and ContaReceberDados.IdStatus = 7";
				break;
			case 'DataVencimentoAtual':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberDados.DataVencimento = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
				}
				break;
			case 'DataVencimentoOriginal':
				$sqlAux .= ", (select 
								IdLoja,
								IdContaReceber, 
								min(DataVencimento) DataVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
				$filtro_sql .= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber";
				
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql .= " and ContaReceberVencimentoOriginal.DataVencimentoOriginal = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberVencimentoOriginal.DataVencimentoOriginal is NULL";
				}
				break;
			case 'MesLancamento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberDados.DataLancamento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataLancamento is NULL";
				}
				break;
			case 'MesCancelamento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and ContaReceberDados.DataAlteracao like '$filtro_valor%' and ContaReceberDados.IdStatus = 0";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataAlteracao is NULL and ContaReceberDados.IdStatus = 0";
				}
				break;
			case 'MesExclusao':
				if($filtro_valor!=""){
					$filtro_sql_status = "";	
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and ContaReceberDados.DataAlteracao like '$filtro_valor%' and ContaReceberDados.IdStatus = 7";
				}else{
					$filtro_sql_status = "";
					$filtro_sql .= " and ContaReceberDados.DataLancamento is NULL and ContaReceberDados.IdStatus = 7";
				}
				break;
			case 'MesVencimentoAtual':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and substring(ContaReceberDados.DataVencimento,1,7) = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberDados.DataVencimento is NULL";
				}
				break;
			case 'MesVencimentoOriginal':
				$sqlAux .= ", (select 
								IdLoja,
								IdContaReceber, 
								substring(min(DataVencimento),1,7) MesVencimentoOriginal 
							from 
								ContaReceberVencimento 
							group by IdLoja, IdContaReceber) ContaReceberVencimentoOriginal";
				$filtro_sql .= " and 
								ContaReceberDados.IdLoja = ContaReceberVencimentoOriginal.IdLoja and 
								ContaReceberDados.IdContaReceber = ContaReceberVencimentoOriginal.IdContaReceber";
				
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql .= " and ContaReceberVencimentoOriginal.MesVencimentoOriginal = '$filtro_valor'";
				}else{
					$filtro_sql .= " and ContaReceberVencimentoOriginal.MesVencimentoOriginal is NULL";
				}
				break;
			case 'DescricaoServico':
				$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
				$sqlAux = " left join Contrato on (
								LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
								LancamentoFinanceiro.IdContrato = Contrato.IdContrato
							) left join OrdemServico on (
								LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
								LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
							), 
							Servico".$sqlAux;
				$filtro_sql .= " and (
									Contrato.IdServico = Servico.IdServico OR 
									OrdemServico.IdServico = Servico.IdServico
								) and
								Servico.DescricaoServico like '%$filtro_descrisao_servico%'";
				break;
			case 'IdProcessoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiro.IdProcessoFinanceiro = '$filtro_valor'";
				break;
			case 'NumeroDocumento':
				$filtro_sql .=	" and ContaReceberDados.NumeroDocumento = '$filtro_valor'";
				break;
			case 'NossoNumero':
				$filtro_sql .=	" and ContaReceberDados.NossoNumero = '$filtro_valor'";
				break;
			case 'NumeroNF':
				$valor = str_pad($filtro_valor,9,"0",STR_PAD_LEFT);
				$filtro_sql .=	" and ContaReceberDados.NumeroNF = '$valor'";
				break;
			case 'IdReceibo':
				$sqlAux .= ", ContaReceberRecebimento";
				$filtro_sql .= " and 
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja and 
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
					ContaReceberRecebimento.IdRecibo = '$filtro_valor'";
				break;
			case 'IdContaReceber':
				$filtro_sql .=	" and ContaReceberDados.IdContaReceber = '$filtro_valor'";
				break;
			case 'IdLancamentoFinanceiro':
				$filtro_sql .=	" and LancamentoFinanceiro.IdLancamentoFinanceiro = '$filtro_valor'";
				break;
		}
		
	}else{
		$filtro_valor	=	"";
	}
		
	if($filtro_descrisao_servico!=''){
		$filtro_url .= "&DescricaoServico=".$filtro_descrisao_servico;
		$sqlAux = " left join Contrato on (
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
					), 
					Servico".$sqlAux;
		$filtro_sql .= " and (
							Contrato.IdServico = Servico.IdServico OR 
							OrdemServico.IdServico = Servico.IdServico
						) and
						Servico.DescricaoServico like '%$filtro_descrisao_servico%'";
		
		if($filtro_id_servico!=""){
			$filtro_url .= "&IdServico=".$filtro_id_servico;
			$filtro_sql .= " and (
								Contrato.IdServico = $filtro_id_servico or 
								OrdemServico.IdServico = $filtro_id_servico
							)";
		}
	} elseif($filtro_id_servico!=""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$sqlAux = " left join Contrato on (
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato
					) left join OrdemServico on (
						LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and 
						LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico
					)".$sqlAux;
		$filtro_sql .= " and (
							Contrato.IdServico = $filtro_id_servico or 
							OrdemServico.IdServico = $filtro_id_servico
						)";
	}
	
	if($filtro_grupo_pessoa != ""){
		$filtro_url .= "&GrupoPessoa=$filtro_grupo_pessoa";
		$filtro_sql .= " and PessoaGrupoPessoa.IdGrupoPessoa = $filtro_grupo_pessoa and
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa";
	}
	
	if($filtro_juros!=""){
		$filtro_url .= "&Juros=".$filtro_juros;
	}
	
	if($filtro_soma!=""){
		$filtro_url .= "&Soma=".$filtro_soma;
	}
	
	if($filtro_cancelado!=""){
		$filtro_url .= "&Cancelado=".$filtro_cancelado;
		if($filtro_cancelado == 2 && $filtro_status == ""){
			$filtro_sql  .= " and ContaReceberDados.IdStatus != 0";
		}
	}
	
	if($filtro_nota_fiscal!=""){
		$filtro_url .= "&NotaFiscal=".$filtro_nota_fiscal;
	}
	
	if($filtro_impressao!=""){
		$filtro_url .= "&Boleto=".$filtro_impressao;
	}
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							Carteira.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	if($filtro_tipo_relatorio == 1 || $_GET['NumeroDocumento']){
		$limitenome		= '20';
		$limitecidade	= '15';
		header ("content-type: text/xml");	
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_xsl.php$filtro_url\"?>";
		echo "<db>";
	}else{
		include "../../classes/fpdf/class.fpdf.php";
		$_SESSION["filtro_tipo_relatorio"] = $filtro_tipo_relatorio;
		$limitenome		= '50';
		$limitecidade	= '45';
		
		if($filtro_ordem != ""){
			$order_PDF = $filtro_ordem;
				
			if($filtro_ordem_direcao == "descending"){
				$order_PDF .= " DESC";
			}
		}
		
		$pdf = new FPDF('L','cm','A4');
		$pdf->SetMargins(0.3, 1.0, 1.64);
		$pdf->AddPage();
		$pdf->setFont('Arial','',11);
		$pdf->Ln();
		$pdf->Cell(10.6, 1.2, 'Relatório Conta Receber', 0,0, 'L',0);
		$pdf->setFont('Arial','',9);
		$pdf->Cell(6.0, 1.2, 'Relatório Emitido em: '.date("d/m/Y").' ás '.date("H:i:s"), 0,0, 'L',0);
		$pdf->Ln();
		$pdf->setFont('Arial','B',7);
		$pdf->Cell(0.9, 0.5, 'Id', 0,0, 'L',0);
		$pdf->Cell(4.5, 0.5, 'Nome Pessoa', 0,0, 'L',0);
		
		if($filtro_cpf_cnpj == 2){
			$pdf->Cell(2.5, 0.5, 'CPF/CNPJ', 0,0, 'L',0);
		}
		
		$pdf->Cell(1.9, 0.5, 'N° Doc', 0,0, 'L',0);
		
		if($filtro_nota_fiscal == 2){
			$pdf->Cell(2.5, 0.5, 'N° NF', 0,0, 'L',0);
		}
		
		$pdf->Cell(2.5, 0.5, 'Local Cobrança', 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, 'Data Lanc.', 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, "Valor(".getParametroSistema(5,1).")", 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, 'Vencimento', 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, "Receb(".getParametroSistema(5,1).")", 0,0, 'L',0);
		$pdf->Cell(2.0, 0.5, 'Pagamento', 0,0, 'L',0);
		$pdf->Cell(2.5, 0.5, 'Local Recebimento', 0,0, 'L',0);
		$pdf->Ln();
		$pdf->Cell(29.4, 0.2,'','T',0, 'L',0);
	}
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$cont	=	0;
	$sql	=	"select
					distinct
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.NumeroNF,
					ContaReceberDados.ModeloNF,
					ContaReceberDados.DataLancamento,
					(ContaReceberDados.ValorFinal) Valor,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.IdLocalCobranca,
					ContaReceberDados.MD5,
					ContaReceberDados.IdStatus,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,20) Nome,
					substr(Pessoa.RazaoSocial,1,20) RazaoSocial,
					Pessoa.CPF_CNPJ,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.PercentualMulta,
					LocalCobranca.PercentualJurosDiarios,
					LocalCobranca.IdLocalCobrancaLayout,
					LocalCobranca.IdDuplicataLayout,
					LancamentoFinanceiro.IdProcessoFinanceiro
					$filtro_sql_select
				from					
					LancamentoFinanceiroContaReceber,
					Pessoa left join (
						PessoaGrupoPessoa,
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					LocalCobranca,
					LancamentoFinanceiro
					$sqlAux
				where
					ContaReceberDados.IdLoja = $local_IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiro.IdLoja and
					ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa
					$filtro_sql $filtro_sql_status
					order by
						ContaReceberDados.IdContaReceber desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		
		if($filtro_sql_select == ''){
			$sql1	=	"select
							ContaReceberRecebimento.DataRecebimento,
							ContaReceberRecebimento.MD5,
							ContaReceberRecebimento.IdRecibo,
							ContaReceberRecebimento.ValorRecebido,
							ContaReceberRecebimento.IdLocalCobranca IdLocalCobrancaRecebimento
						from
							ContaReceberRecebimento
						where
							ContaReceberRecebimento.IdLoja = $lin[IdLoja] and 
							ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
							ContaReceberRecebimento.IdStatus = 1";
			$res1	=	mysql_query($sql1,$con);
			if($lin1 = mysql_fetch_array($res1)){
				if($filtro_contabiliza_recebimentos_vencimento == 1){
					$vencimento  = str_replace("-","",$lin[DataVencimento]);
					$recebimento = str_replace("-","",$lin1[DataRecebimento]);
					
					if($vencimento >= $recebimento){
						$lin[DataRecebimento]				= $lin1[DataRecebimento];
						$lin[Recibo]						= $lin1["MD5"];
						$lin[IdRecibo]						= $lin1[IdRecibo];
						$lin[ValorRecebido]					= $lin1[ValorRecebido];
						$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
					}else{
						$lin[DataRecebimento]				= $lin1[DataRecebimento];
						$lin[Recibo]						= $lin1["MD5"];
						$lin[IdRecibo]						= $lin1[IdRecibo];
						$lin[ValorRecebido]					= $lin1[ValorRecebido];
						$lin[ValorSomatorio]				= $lin1[ValorRecebido];
						$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
					}
					
				} else{
					$lin[DataRecebimento]				= $lin1[DataRecebimento];
					$lin[Recibo]						= $lin1["MD5"];
					$lin[IdRecibo]						= $lin1[IdRecibo];
					$lin[ValorRecebido]					= $lin1[ValorRecebido];
					$lin[IdLocalCobrancaRecebimento]	= $lin1[IdLocalCobrancaRecebimento];
				}
			}
		}
		
		$sqlLancamentoFinanceiroDados = "select
											LancamentoFinanceiroDados.IdContrato,
											LancamentoFinanceiroDados.IdContaEventual,
											LancamentoFinanceiroDados.IdOrdemServico
										from
											LancamentoFinanceiroDados
										where
											IdLoja = $local_IdLoja and
											IdContaReceber = $lin[IdContaReceber]";
		$resLancamentoFinanceiroDados = mysql_query($sqlLancamentoFinanceiroDados,$con);
		$linLancamentoFinanceiroDados = mysql_fetch_array($resLancamentoFinanceiroDados);

		$lin[IdContrato]		= $linLancamentoFinanceiroDados[IdContrato];
		$lin[IdContaEventual]	= $linLancamentoFinanceiroDados[IdContaEventual];
		$lin[IdOrdemServico]	= $linLancamentoFinanceiroDados[IdOrdemServico];

		$query = 'true';
		
		if($lin[IdContrato]!=''){
			if($_SESSION["RestringirCarteira"] == true){
				$sqlTemp =	"select 
								AgenteAutorizadoPessoa.IdContrato 
							from 
								AgenteAutorizadoPessoa,
								Carteira 
							where 
								AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
								AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
								AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
								Carteira.IdCarteira = $local_IdPessoaLogin and 
								Carteira.Restringir = 1 and 
								Carteira.IdStatus = 1 and
								AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
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
									AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
									AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
									AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
									AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
									AgenteAutorizado.Restringir = 1 and 
									AgenteAutorizado.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
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
											AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
											AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
											AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
											AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
											Carteira.IdCarteira = $local_IdPessoaLogin and 
											AgenteAutorizado.Restringir = 1 and 
											AgenteAutorizado.IdStatus = 1 and
											Carteira.IdStatus = 1 and
											AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
	
		if($query == 'true'){
		
			$lin[DataLancamentoTemp] 	= dataConv($lin[DataLancamento],"Y-m-d","d/m/Y");
			$lin[DataVencimentoTemp] 	= dataConv($lin[DataVencimento],"Y-m-d","d/m/Y");
			$lin[DataRecebimentoTemp] 	= dataConv($lin[DataRecebimento],"Y-m-d","d/m/Y");
			
			$lin[DataLancamento] 		= dataConv($lin[DataLancamento],"Y-m-d","Ymd");
			$lin[DataVencimento] 		= dataConv($lin[DataVencimento],"Y-m-d","Ymd");
			$lin[DataRecebimento] 		= dataConv($lin[DataRecebimento],"Y-m-d","Ymd");
			
			if($lin[Valor] == ""){				
				$lin[Valor]			=	0;		
			}
			
			if($lin[IdStatus] == 1 && $filtro_subtrair_desconto_conceber == 1){
				$lin[Valor]	= $lin[Valor] - $lin[ValorDescontoAConceber];
			}
			$lin3[AbreviacaoNomeLocalCobranca]	=	"";
			
			switch($lin[IdStatus]){
				case '0': 
					# Status = 0 (Cancelado)
					$Color	  =	getParametroSistema(15,2);
					$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Canc.";
					$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";	
					$Target			= "_self";	
					break;
				case '1':
					#Status = 1 (Aguardando Pagamento)
					if($filtro_impressao == 1){	//Html
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "html";
					} else{					//Pdf
						$lin[Link]		= "boleto.php";
						$lin[Tipo]		= "pdf";
					}
					
					if(file_exists($lin[Link])){
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "Boleto";
						$Target			= "_blank";	
						$lin[Link]		.= "?Tipo=$lin[Tipo]&ContaReceber=$lin[MD5]";
					} else{	
						$lin[Recebido]  = "N";
						$lin[MsgLink]	= "";
						$lin[Link]		= "";
						$Target			= "";	
					}
					
					$lin[DataRecebimento] 		= "";
					$lin[DataRecebimentoTemp] 	= "";	
					
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
					
					
					// Vencimento alterado
					$sql6 = "select
								count(*) VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado] > 1){
						$Color	 	=  	getCodigoInterno(45,1);
					}
					break;
				case '2':
					# Status = 2 (Quitado)
					$lin[Recebido] 	= "S";
					$lin[MsgLink]	= "Recibo";
					$lin[Link]		= "recibo.php?Recibo=$lin[Recibo]";
					$Color	  		= getParametroSistema(15,3);		
					$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
					$Target			= "_blank";	
					
					if($lin[ValorRecebido] != '' && $lin[ValorRecebido] < $lin[Valor]){
						$Color	  		= getParametroSistema(15,7);		
					} 
					
					$sql3 = "select 
								ContaReceberRecebimentoTemp.QtdReciboAtivo,
								ContaReceberRecebimento.IdCaixa,
								LocalCobranca.AbreviacaoNomeLocalCobranca 
							from
								(
									select 
										count(*) QtdReciboAtivo,
										ContaReceberRecebimentoTemp.IdLocalCobranca 
									from
										(
											select 
												ContaReceberRecebimento.IdLoja,
												ContaReceberRecebimento.IdContaReceber,
												ContaReceberRecebimento.IdLocalCobranca,
												ContaReceberRecebimento.IdStatus 
											from
												ContaReceberRecebimento 
											where 
												ContaReceberRecebimento.IdLoja = $local_IdLoja and 
												ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
												ContaReceberRecebimento.IdStatus = 1 
											order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
											limit 1
										) ContaReceberRecebimentoTemp,
										ContaReceberRecebimento 
									where ContaReceberRecebimentoTemp.IdLoja = ContaReceberRecebimento.IdLoja and
										ContaReceberRecebimentoTemp.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
										ContaReceberRecebimentoTemp.IdStatus = ContaReceberRecebimento.IdStatus
								) ContaReceberRecebimentoTemp,
								ContaReceberRecebimento,
								LocalCobranca 
							where 
								ContaReceberRecebimento.IdLoja = $local_IdLoja and 
								ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and 
								ContaReceberRecebimento.IdLoja = LocalCobranca.IdLoja and 
								case
									when ContaReceberRecebimento.IdLocalCobranca is null && ContaReceberRecebimentoTemp.IdLocalCobranca is null 
									then true
									when ContaReceberRecebimentoTemp.IdLocalCobranca is null 
									then ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
									else ContaReceberRecebimentoTemp.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
								end 
							order by ContaReceberRecebimento.IdContaReceberRecebimento desc 
							limit 1;";
					$res3 = @mysql_query($sql3,$con);
					$lin3 = @mysql_fetch_array($res3);
				
					if($lin3[IdCaixa] != ''){
						$lin3[AbreviacaoNomeLocalCobranca] = "Caixa ".$lin3[IdCaixa];
					} else {
						if($lin3[QtdReciboAtivo] > 1){
							$lin3[AbreviacaoNomeLocalCobranca]	= '***';
							$lin[ValorRecebido]					= '***';
							$lin[DataRecebimento] 				= '***';
							$lin[DataRecebimentoTemp] 			= '***';
							$lin[ValorRecebidoTemp]				= '***';
							
						}else{
							$lin3[AbreviacaoNomeLocalCobranca]	=	$lin3[AbreviacaoNomeLocalCobranca];
						}
					}
					
					// Pagamento em atraso
					$sql7 = "select
								ContaReceberRecebimento.DataRecebimento, 
								max(ContaReceberVencimento.DataVencimento) DataVencimento
							from
								ContaReceberVencimento,
								ContaReceberRecebimento 
							where
								ContaReceberRecebimento.IdLoja = $local_IdLoja and
								ContaReceberRecebimento.IdLoja = ContaReceberVencimento.IdLoja and
								ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber] and
								ContaReceberRecebimento.IdContaReceber = ContaReceberVencimento.IdContaReceber";
					$res7 = @mysql_query($sql7,$con);
					$lin7 = @mysql_fetch_array($res7);
					
					if ($lin7[DataRecebimento] > $lin7[DataVencimento]){
						$Color	 	=  getCodigoInterno(44,1);
					}
					break;
				case 3:
					#Status = 3 (Aguardando Envio)									
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "";
					$lin[Link]		= "";
					$Target			= "";
					
					$lin[DataRecebimento] 		= "";
					$lin[DataRecebimentoTemp] 	= "";	
					
					$Color	  = "";		
					$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";		
					
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 	= 	getCodigoInterno(45,1);
					}
					
					break;
				case 4:	
					#Status = 4 (Em Arquivo de Remessa)
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 =   getCodigoInterno(45,1);
					}
					break;
				case 5:	
					#Status = 5 (Compromisso Agendado)
					// Vencimento alterado
					$sql6 = "select
								count(*) > 0 VencimentoAlterado
								from ContaReceberVencimento
								where IdLoja = $local_IdLoja and  
								IdContaReceber = $lin[IdContaReceber]";
					$res6 = @mysql_query($sql6,$con);
					$lin6 = @mysql_fetch_array($res6);
					if ($lin6[VencimentoAlterado]){
						$Color	 	=  	getCodigoInterno(45,1);
					}
					
					break;	
				case 6:	
					#Status = 6 (Devolvido)
					$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Devolvido";
					$Color			= "";	
					$Target			= "";		
					$ImgExc			= "../../img/estrutura_sistema/ico_del.gif";					
					break;
				
				case '7':
					#Status = 7 (Excluído)
					$Color	  =	getParametroSistema(15,2);
					$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Exc.";
					$lin[Link]		= "";	
					$Target			= "";	
					break;
				
				case '8':
					#Status = 8 (Agrupado)
					$lin[Link]		= "cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]";
					$lin[Recebido]  = "N";
					$lin[MsgLink]	= "Agrupado";
					$Color			= "";	
					$Target			= "";		
					$ImgExc			= "../../img/estrutura_sistema/ico_del_c.gif";					
					break;
					
			}
			if($filtro_juros == 1){
				// Titulo não recebido
				if($lin[IdStatus] != 0 && $lin[IdStatus] != 2 && $lin[IdStatus] != 7 && $lin[IdStatus] != 6){
					$sql4	=	"select BaseVencimento from ContaReceberBaseVencimento where IdLoja= $local_IdLoja and IdContaReceber = $lin[IdContaReceber]";
					$res4	=	@mysql_query($sql4,$con);
					$lin4	=	@mysql_fetch_array($res4);
					
					if($lin4[BaseVencimento] > 0){
						$lin[ValorRecebido]			=	$lin[Valor] + (($lin[Valor] * $lin[PercentualMulta]) / 100) + ((($lin[Valor] * $lin[PercentualJurosDiarios]) / 100) * $lin4[BaseVencimento]);
						$lin[ValorRecebidoTemp]		=	'('.number_format($lin[ValorRecebido],2,",","").')';
					}else{
						$lin[ValorRecebido]			=	$lin[Valor];
						$lin[ValorRecebidoTemp]		=	number_format($lin[Valor],2,",","");
					}
				}else{
					if($lin[ValorRecebido] == ''){
						$lin[ValorRecebido]			=	0;	
					}
					
					if(is_numeric($lin[ValorRecebido]))
						$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
				}
			}else{
				// Titulo não recebido
				if($lin[IdStatus] != 0 && $lin[IdStatus] != 2){
					$lin[ValorRecebido]			=	$lin[Valor];
					$lin[ValorRecebidoTemp]		=	'('.number_format($lin[Valor],2,",","").')';
				}else{
					if($lin[ValorRecebido] == ''){		$lin[ValorRecebido]			=	0;	}
					
					if(is_numeric($lin[ValorRecebido]))					
						$lin[ValorRecebidoTemp]		=	str_replace('.',',',formata_double($lin[ValorRecebido]));
				}
			}
			
			$lin5[ValorRecebido] = $lin[ValorRecebido];
			if($lin[ValorRecebido] == "***"){
				$sql5	=	"select 
								ContaReceberRecebimento.IdLoja,
								ContaReceberRecebimento.IdContaReceber,
								count(0) AS QtdRecebimentosAtivo,
								ContaReceberRecebimento.DataRecebimento,
								ContaReceberRecebimento.IdRecibo,
								ContaReceberRecebimento.ValorRecebido,
								ContaReceberRecebimento.IdLocalCobranca,
								ContaReceberRecebimento.IdArquivoRetorno 
							from 
								ContaReceberRecebimento 
							where 
								(ContaReceberRecebimento.IdStatus <> 0) and
								(ContaReceberRecebimento.IdLoja = $lin[IdLoja]) and
								(ContaReceberRecebimento.IdContaReceber = $lin[IdContaReceber])
							group by 
								ContaReceberRecebimento.IdLoja,ContaReceberRecebimento.IdContaReceber";
				$res5	=	@mysql_query($sql5,$con);
				$lin5	=	@mysql_fetch_array($res5);
				
				$lin5[ValorRecebido] = $lin5[ValorRecebido]*2;
			}
			
			if($filtro_soma == 1){
				$lin[ValorSoma]			=	$lin[Valor];				
				$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];				
			}else{
				if($lin[IdStatus] == 2){
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];	
				}else{
					$lin[ValorSoma]			=	0;
					$lin[ValorRecebidoSoma]	=	0;
				}				
			}
			if($lin[ValorSomatorio] != ""){
				$lin[ValorRecebidoSoma] -= $lin[ValorSomatorio];
			}
			
		/*	if($filtro_soma == 1){
				if($lin[IdStatus] == 1){
					$lin[ValorSoma]			=	$lin[Valor];
					$lin[ValorRecebidoSoma]	=	0;
				}else{
					$lin[ValorSoma]			=	0;
					
					if($lin[IdStatus] == 2){
						$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];
					}else{
						$lin[ValorRecebidoSoma]	=	0;
					}
				}
			}else{
				$lin[ValorSoma]			=	$lin[Valor];
				$lin[ValorRecebidoSoma]	=	$lin5[ValorRecebido];
			}*/
			
			if($lin[TipoPessoa]=='1'){
				$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			}
			
			if($lin[NumeroNF] == null || $lin[ModeloNF] == null){
				$lin[ModeloNF] == '';
			} else{
				$lin[ModeloNF] = " Mod. ".$lin[ModeloNF];
			}
			
			if($filtro_tipo_relatorio == 1 || $_GET['NumeroDocumento']){
				echo "<reg>";	
				echo 	"<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
				echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
				echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
				echo 	"<NumeroDocumento>$lin[NumeroDocumento]</NumeroDocumento>";
				echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
				echo 	"<ModeloNF><![CDATA[$lin[ModeloNF]]]></ModeloNF>";
				echo 	"<NossoNumero>$lin[NossoNumero]</NossoNumero>";
				echo 	"<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";	
				echo 	"<DescricaoLocalRecebimento><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></DescricaoLocalRecebimento>";	
				
				echo 	"<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				echo 	"<DataLancamentoTemp><![CDATA[$lin[DataLancamentoTemp]]]></DataLancamentoTemp>";
				
				echo 	"<Valor>$lin[Valor]</Valor>";
				echo 	"<ValorRecebido>$lin[ValorRecebido]</ValorRecebido>";
				echo 	"<ValorRecebidoTemp><![CDATA[$lin[ValorRecebidoTemp]]]></ValorRecebidoTemp>";
				
				echo 	"<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				echo 	"<DataVencimentoTemp><![CDATA[$lin[DataVencimentoTemp]]]></DataVencimentoTemp>";
				
				echo 	"<Recebido><![CDATA[$lin[Recebido]]]></Recebido>";
				echo 	"<MsgLink><![CDATA[$lin[MsgLink]]]></MsgLink>";
				echo 	"<Link><![CDATA[$lin[Link]]]></Link>";
				echo 	"<Color><![CDATA[$Color]]></Color>";
				echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
				echo 	"<Target><![CDATA[$Target]]></Target>";
				
				echo 	"<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
				echo 	"<DataRecebimentoTemp><![CDATA[$lin[DataRecebimentoTemp]]]></DataRecebimentoTemp>";
				echo 	"<ValorSoma><![CDATA[$lin[ValorSoma]]]></ValorSoma>";
				echo 	"<ValorRecebidoSoma><![CDATA[$lin[ValorRecebidoSoma]]]></ValorRecebidoSoma>";
				echo 	"<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
				
				$ValorTotal 		= $lin[ValorSoma];
				$ValorRecebidoSoma 	= $lin[ValorRecebidoSoma];
				
				echo "</reg>";	
				
				$cont++;
				
				if($filtro_limit!= ""){
					if($cont >= $filtro_limit){
						break;
					}
				}
			} else{
					$pdf->SetFont('Arial','',6.5);
					$pdf->Ln();
					$linCR[IdContaReceber][$contador] 							= $lin[IdContaReceber];
					$linCR[Nome][$contador] 									= $lin[Nome];
					$linCR[CPF_CNPJ][$contador] 								= $lin[CPF_CNPJ];
					$linCR[NumeroDocumento][$contador] 							= $lin[NumeroDocumento];
					$linCR[NumeroNF][$contador] 								= $lin[NumeroNF];
					$linCR[ModeloNF][$contador] 								= $lin[ModeloNF];		
					$linCR[AbreviacaoNomeLocalCobranca][$contador] 				= $lin[AbreviacaoNomeLocalCobranca];
					$linCR[DataLancamento][$contador] 							= $lin[DataLancamentoTemp];
					$linCR[Valor][$contador] 									= $lin[Valor];
					$linCR[DataVencimento][$contador] 							= $lin[DataVencimentoTemp];
					$linCR[ValorRecebido][$contador] 							= $lin[ValorRecebidoTemp];
					$linCR[DataRecebimento][$contador] 							= $lin[DataRecebimentoTemp];
					$linCR[AbreviacaoNomeLocalCobrancaRecebimento][$contador] 	= $lin3[AbreviacaoNomeLocalCobranca];
					$linCR[DescricaoStatus][$contador] 							= $lin[DescricaoStatus];
					$linCR[MsgLink][$contador] 									= $lin[MsgLink];
					
					$contador++;
			}
		}
	}
	if($filtro_tipo_relatorio == 1 || $_GET['NumeroDocumento']){
		echo "</db>";
	} else{
	
		if(!empty($linCR)){
			if($filtro_ordem != ""){
				if($filtro_ordem_direcao == "descending"){
					rsort($linCR[$filtro_ordem]);
				} else{
					sort($linCR[$filtro_ordem]);
				}
			}
			for($i=0;$i<$contador;$i++){
				$tamanho = 1;
				$lancamento = "";
				$valorlancamento = "";
				if($i % 2 == 0){
					$pdf->SetFillColor(205, 201, 201);
				}else{
					$pdf->SetFillColor(238, 233, 233);
				}
				
				$totalvalor   += $lin[ValorSoma];
				$totalreceber += $lin[ValorRecebidoSoma];
				$pdf->Ln();
				$pdf->Cell(0.9,0.5, $linCR[IdContaReceber][$i], 0,0, 'L',true);
				$pdf->Cell(4.5,0.5,$linCR[Nome][$i], 0,0, 'L',true);
				
				$tamanho1 = 10.60;
				
				if($filtro_cpf_cnpj == 2){
					$pdf->Cell(2.5, 0.5, $linCR[CPF_CNPJ][$i], 0,0, 'L',true);
					$tamanho1 = $tamanho1+2.5;
				}
				
				$pdf->Cell(1.9,0.5,$linCR[NumeroDocumento][$i], 0,0, 'L',true);
				
				if($filtro_nota_fiscal == 2){
					$pdf->Cell(2.5,0.5,$linCR[NumeroNF][$i]. " ".$linCR[ModeloNF][$i], 0,0, 'L',true);
					
					$tamanho1 = $tamanho1+2.5;
				}
				
				$pdf->Cell(2.5,0.5,$linCR[AbreviacaoNomeLocalCobranca][$i], 0,0, 'L',true);
				$pdf->Cell(1.40,0.5,$linCR[DataLancamento][$i], 0,0, 'C',true);
				$pdf->Cell(1.90,0.5,number_format($linCR[Valor][$i],2,',',''),0,0, 'R',true);
				$pdf->Cell(2.15,0.5,$linCR[DataVencimento][$i], 0,0, 'R',true);
				$pdf->Cell(1.95,0.5,$linCR[ValorRecebido][$i], 0,0, 'R',true);
				$pdf->Cell(2.6,0.5,$linCR[DataRecebimento][$i], 0,0, 'C',true);
				$pdf->Cell(2.5,0.5,$linCR[AbreviacaoNomeLocalCobrancaRecebimento][$i], 0,0, 'L',true);
				$pdf->Cell(0.5,0.5,$linCR[DescricaoStatus][$i], 0,0, 'L',true);
				$pdf->Cell(6.5,0.5,$linCR[MsgLink][$i], 0,0, 'L',true);
				
				$cont++;
			}
		}	
		$pdf->Ln();
		$pdf->setFont('Arial','B',7);
		$pdf->Cell(29.4, 0.05,'','T',0, 'L',0);
		$pdf->Ln();
		$pdf->Cell(2.5,0.5,'Total: '.$cont, 0,0, 'L',0);
		$pdf->Cell($tamanho1,0.5,number_format($ValorTotal,2,',',''), 0,0, 'R',0);
		$pdf->Cell(4.1,0.5,number_format($ValorTotalRecebimento,2,',',''), 0,0, 'R',0);
		$pdf->Output("Relatório Contas Receber.pdf","D");
	}
?>
