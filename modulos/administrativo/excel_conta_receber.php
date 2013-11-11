<?php
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"V";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	// include the php-excel class
	require ("../../classes/php-excel/class-excel-xml.inc.php");

	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_tipo					= $_POST['filtro_tipo'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_data_lanc_ini			= $_POST['filtro_data_lanc_ini'];
	$filtro_data_lanc_fim			= $_POST['filtro_data_lanc_fim'];
	$filtro_data_venc_ini			= $_POST['filtro_data_venc_ini'];
	$filtro_data_venc_fim			= $_POST['filtro_data_venc_fim'];
	$filtro_servico					= $_POST['filtro_servico'];
	$filtro_valor					= $_POST['filtro_valor'];
	$filtro_data_paga_ini			= $_POST['filtro_data_paga_ini'];
	$filtro_data_paga_fim			= $_POST['filtro_data_paga_fim'];
	$filtro_numero_doc				= $_POST['filtro_numero_doc'];
	$filtro_processo				= $_POST['filtro_processo'];
	$filtro_idpais					= $_POST['filtro_idpais'];
	$filtro_estado					= $_POST['filtro_estado'];
	$filtro_cidade					= $_POST['filtro_cidade'];
	$filtro_tipo_pessoa				= $_POST['filtro_tipo_pessoa'];
	$filtro_id_servico				= $_POST['filtro_id_servico'];
	$filtro_numero_nf				= $_POST['filtro_numero_nf'];
	
	$filtro_chNome					= $_POST['chNome'];
	$filtro_chRazao					= $_POST['chRazao'];
	$filtro_chFone1					= $_POST['chFone1'];
	$filtro_chFone2					= $_POST['chFone2'];
	$filtro_chFone3					= $_POST['chFone3'];
	$filtro_chCel					= $_POST['chCel'];
	$filtro_chFax					= $_POST['chFax'];
	$filtro_chCompF					= $_POST['chCompF'];
	$filtro_chEmail					= $_POST['chEmail'];
	$filtro_chNumD					= $_POST['chNumD'];
	$filtro_chNumNF					= $_POST['chNumNF'];
	$filtro_chDataF					= $_POST['chDataF'];
	$filtro_chFax					= $_POST['chFax'];
	$filtro_chLCob					= $_POST['chLCob'];
	$filtro_chDataL					= $_POST['chDataL'];
	$filtro_chDataP					= $_POST['chDataP'];
	$filtro_chDataV					= $_POST['chDataV'];
	$filtro_chValor					= $_POST['chValor'];
	$filtro_chValDe					= $_POST['chValDe'];
	$filtro_chValDeC				= $_POST['chValDeC'];
	$filtro_chPDesc					= $_POST['chPDesc'];
	$filtro_chValDp					= $_POST['chValDp'];
	$filtro_chValF					= $_POST['chValF'];
	$filtro_chLRec					= $_POST['chLRec'];
	$filtro_chStat					= $_POST['chStat'];
	$filtro_chObs					= $_POST['chObs'];
	$filtro_chLink					= $_POST['chLink'];
	
	$filtro_idPessoa				= $_GET['IdPessoa'];
	$filtro_contrato				= $_GET['IdContrato'];
	
	if($filtro_processo == '' && $_GET['IdProcessoFinanceiro']!= ''){
		$filtro_processo	= $_GET['IdProcessoFinanceiro'];
	}
	
	$filtro_sql = "";
	$filtro_sql_receb = "";//Leonardo -> 22-06-13 -> esta variável influencia diretamente na verificação da data de pagamento;
	$filtro_url = "";
	
	if($filtro_nome!=''){
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_tipo!=""){
		switch($filtro_tipo){
			case 'CO':
				$filtro_sql  .= " and LancamentoFinanceiroDados.IdContrato != ''";
				break;
			case 'EV':
				$filtro_sql  .= " and LancamentoFinanceiroDados.IdContaEventual != ''";
				break;
			case 'OS':
				$filtro_sql  .= " and LancamentoFinanceiroDados.IdOrdemServico != ''";
				break;
		}
	}
	
	if($filtro_status!=""){
		$filtro_sql  .= " and ContaReceberDados.IdStatus = ".$filtro_status;
	}	
	
	if($filtro_local_cobranca!=""){
		$filtro_sql .= " and ContaReceberDados.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_idPessoa!=""){
		$filtro_sql .= " and ContaReceberDados.IdPessoa = $filtro_idPessoa";
	}
	
	if($filtro_contrato!=""){
		$filtro_sql .= " and LancamentoFinanceiroDados.IdContrato = $filtro_contrato";
	}
	
	if($filtro_data_lanc_ini!=""){
		$filtro_data_lanc_ini = dataConv($filtro_data_lanc_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataLancamento >= '$filtro_data_lanc_ini'";
	}
	
	if($filtro_data_lanc_fim!=""){
		$filtro_data_lanc_fim = dataConv($filtro_data_lanc_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataLancamento <= '$filtro_data_lanc_fim'";
	}
	
	if($filtro_data_venc_ini!=""){
		$filtro_data_venc_ini = dataConv($filtro_data_venc_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataVencimento >= '$filtro_data_venc_ini'";
	}
	
	if($filtro_data_venc_fim!=""){
		$filtro_data_venc_fim = dataConv($filtro_data_venc_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberDados.DataVencimento <= '$filtro_data_venc_fim'";
	}
	
	if($filtro_data_paga_ini!=""){
		$filtro_data_paga_ini = dataConv($filtro_data_paga_ini,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento >= '$filtro_data_paga_ini'";
		$filtro_sql_receb .= " and ContaReceberRecebimento.DataRecebimento >= '$filtro_data_paga_ini'";
	}
	
	if($filtro_data_paga_fim!=""){
		$filtro_data_paga_fim = dataConv($filtro_data_paga_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ContaReceberRecebimento.DataRecebimento <= '$filtro_data_paga_fim'";
		$filtro_sql_receb .= " and ContaReceberRecebimento.DataRecebimento <= '$filtro_data_paga_fim'";
	}

	if($filtro_valor!=""){
		$filtro_valor	=	str_replace(".", "", $filtro_valor);	
		$filtro_valor	= 	str_replace(",", ".", $filtro_valor);
		$filtro_sql .= " and (ContaReceberDados.ValorContaReceber  = '$filtro_valor')";
	}
	
	if($filtro_servico!=""){
		$filtro_sql .= " and LancamentoFinanceiroDados.IdServico in (select IdServico from Servico where IdLoja = $local_IdLoja and DescricaoServico like '%$filtro_servico%')";
	}
	
	if($filtro_numero_doc!=""){
		$filtro_sql  .= " and ContaReceberDados.NumeroDocumento = ".$filtro_numero_doc;
	}
	
	if($filtro_idpais!=''){
		$filtro_sql .=	" and PessoaEndereco.IdPais = $filtro_idpais";
	}
	
	if($filtro_idpais!='' && $filtro_estado!='0' && $filtro_estado!=''){
		$filtro_sql .= " and PessoaEndereco.IdEstado=".$filtro_estado;
	}
	
	if($filtro_idpais!='' && $filtro_estado!='0' && $filtro_estado!='' && $filtro_cidade!=''){
		$filtro_sql .= " and Cidade.NomeCidade like '%$filtro_cidade%'";
	}
	
	if($filtro_numero_nf!=""){
		$filtro_sql  .= " and ContaReceberDados.NumeroNF = ".$filtro_numero_nf;
	}
	
	if($filtro_tipo_pessoa != ""){
		$filtro_sql .= " and Pessoa.TipoPessoa = ".$filtro_tipo_pessoa;
	}
	
	if($filtro_id_servico !=""){
		$filtro_sql .= " and LancamentoFinanceiroDados.IdServico  = ".$filtro_id_servico;
	}
	
	if($filtro_processo!=""){
		$filtro_sql  .= " and LancamentoFinanceiroDados.IdProcessoFinanceiro = ".$filtro_processo;
	}
	
	if($filtro_chNome !="")		$filtro_url  .= ",Pessoa.Nome";
	if($filtro_chRazao !="")	$filtro_url  .= ",Pessoa.RazaoSocial 'Razão Social'";
	if($filtro_chFone1 !="")	$filtro_url  .= ",Pessoa.Telefone1 'Fone Residencial'";
	if($filtro_chFone2 !="")	$filtro_url  .= ",Pessoa.Telefone2 'Fone Comercial (1)'";
	if($filtro_chFone3 !="")	$filtro_url  .= ",Pessoa.Telefone3 'Fone Comercial (2)'";
	if($filtro_chCel !="")		$filtro_url  .= ",Pessoa.Celular";
	if($filtro_chFax !="")		$filtro_url  .= ",Pessoa.Fax";
	if($filtro_chCompF !="")	$filtro_url  .= ",Pessoa.ComplementoTelefone 'Complemento Fone'";
	if($filtro_chEmail !="")	$filtro_url  .= ",Pessoa.Email 'E-mail'";
	if($filtro_chNumD !="")		$filtro_url  .= ",NumeroDocumento 'Número Documento'";
	if($filtro_chNumNF !="")	$filtro_url  .= ",NumeroNF 'Número Nota Fiscal'";
	if($filtro_chDataF !="")	$filtro_url  .= ",date_format(DataNF,'%d/%m/%Y') 'Data Nota Fiscal'";
	if($filtro_chLCob !="")		$filtro_url  .= ",DescricaoLocalCobranca 'Local de Cobrança'";
	if($filtro_chDataL !="")	$filtro_url  .= ",date_format(ContaReceberDados.DataLancamento,'%d/%m/%Y') 'Data Lançamento'";
	if($filtro_chDataV !="")	$filtro_url  .= ",date_format(ContaReceberDados.DataVencimento,'%d/%m/%Y') 'Data Vencimento'";
	if($filtro_chDataP !="")	$filtro_url  .= ",date_format(DataRecebimento,'%d/%m/%Y') 'Data Pagamento'";
	if($filtro_chValor !="")	$filtro_url  .= ",REPLACE(ValorLancamento, '.', ',') 'Valor'";
	if($filtro_chValDe !="")	$filtro_url  .= ",REPLACE(ValorDesconto, '.', ',') 'Valor Desconto Concebido'";
	if($filtro_chValDeC !="")	$filtro_url  .= ",REPLACE(ValorDescontoAConceber, '.', ',') 'Valor Desc. A Conceber'";
	if($filtro_chValDp !="")	$filtro_url  .= ",REPLACE(ContaReceberDados.ValorDespesas, '.', ',') 'Valor Despesas'";
	$filtro_url  .= ",REPLACE(LancamentoFinanceiroDados.Valor,'.',',') 'Valor Lançamento Financeiro'";
	if($filtro_chValF !="")		$filtro_url  .= ",REPLACE(ValorFinal,'.',',') 'Valor Final'";
	if($filtro_chLRec !="")		$filtro_url  .= ",LocalRecebimento 'Local de Recebimento'";
	if($filtro_chStat !="")		$filtro_url  .= ",ContaReceberDados.IdStatus 'Status'";
	if($filtro_chObs !="")		$filtro_url  .= ",ContaReceberDados.Obs 'Observações'";
	
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
			$filtro_sql    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
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
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	$tam	=	0;
	$cont	=	0;
	$i		=	0;
	
	$sql	=	"select
						distinct ContaReceberDados.IdContaReceber 'Conta Receber',
						LancamentoFinanceiroDados.IdLancamentoFinanceiro 'Lançamento Financeiro',
						LancamentoFinanceiroDados.IdContrato,
						LancamentoFinanceiroDados.IdContaEventual,
						LancamentoFinanceiroDados.IdOrdemServico
						$filtro_url
					from
						LancamentoFinanceiroDados, 
						ContaReceberDados LEFT JOIN (select ContaReceberRecebimento.IdContaReceber, ContaReceberRecebimento.DataRecebimento, ContaReceberRecebimento.IdRecibo, ContaReceberRecebimento.ValorRecebido, ContaReceberRecebimento.IdLocalCobranca, 	ContaReceberRecebimento.IdStatus, ContaReceberRecebimento.IdArquivoRetorno, 	DescricaoLocalCobranca LocalRecebimento from ContaReceberRecebimento, LocalCobranca, (select IdLoja, IdContaReceber, max(IdContaReceberRecebimento) IdContaReceberRecebimento from ContaReceberRecebimento where IdLoja = $local_IdLoja and IdStatus != 0 group by IdLoja, IdContaReceber) ContaReceberRecebimentoUltimo where ContaReceberRecebimento.IdLoja = $local_IdLoja and ContaReceberRecebimento.IdLoja = ContaReceberRecebimentoUltimo.IdLoja and ContaReceberRecebimento.IdContaReceber = ContaReceberRecebimentoUltimo.IdContaReceber and ContaReceberRecebimento.IdContaReceberRecebimento = ContaReceberRecebimentoUltimo.IdContaReceberRecebimento and ContaReceberRecebimento.IdLoja = LocalCobranca.IdLoja and ContaReceberRecebimento.IdLocalCobranca = LocalCobranca.IdLocalCobranca $filtro_sql_receb) ContaReceberRecebimento On (ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber), 
						Pessoa,
						PessoaEndereco, 
						Pais,
			    		Estado,
			    		Cidade, 
						LocalCobranca
					where
						ContaReceberDados.IdLoja = $local_IdLoja and
						ContaReceberDados.IdLoja = LancamentoFinanceiroDados.IdLoja and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						LancamentoFinanceiroDados.IdContaReceber = ContaReceberDados.IdContaReceber and
						LancamentoFinanceiroDados.IdPessoa = Pessoa.IdPessoa and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						PessoaEndereco.IdPessoaEndereco = ContaReceberDados.IdPessoaEndereco and
						Pais.IdPais = PessoaEndereco.IdPais and
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdCidade = PessoaEndereco.IdCidade and
						Pais.IdPais = Estado.IdPais and
			       	 	Pais.IdPais = Cidade.IdPais and
			       	 	Estado.IdEstado = Cidade.IdEstado and
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca  $filtro_sql";
	$res = @mysql_query($sql,$con);
	$qtd = @mysql_num_fields($res);
	
	
	$j = 0;
	for($i = 0; $i < $qtd; $i++){
		if($i != 2 && $i != 3 && $i != 4){
			$doc[0][$j] = @mysql_field_name($res,$i);
			$j++;
		}
	}
		
	$ii = 1;		
	while($lin = @mysql_fetch_array($res)){
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
					$sqlTemp	=	"select 
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
								AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				}
			}
		}
		
		if($lin[IdContaEventual]!=''){
			$sql2	=	"select IdContrato from ContaEventual where IdLoja = $local_IdLoja and IdContaEventual = $lin[IdContaEventual]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[IdContrato]!=""){
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
									AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
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
										AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp	=	"select 
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
									AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
		}
		
		if($lin[IdOrdemServico]!=''){
			$sql2	=	"select IdContrato,IdContratoFaturamento from OrdemServico where IdLoja = $local_IdLoja and IdOrdemServico = $lin[IdOrdemServico]";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[IdContrato]!="" ||  $lin2[IdContratoFaturamento]!=""){
			
				if($lin2[IdContrato]!=""){
					$aux	.=	" and AgenteAutorizadoPessoa.IdContrato = $lin2[IdContrato]";
				}
				
				if($lin2[IdContrato]!="" && $lin2[IdContratoFaturamento]!=""){
					$aux	.=	" or";
				}else{
					$aux	.=	" and";
				}
				
				if($lin2[IdContratoFaturamento]!=""){
					$aux	.=	" AgenteAutorizadoPessoa.IdContrato = $lin2[IdContratoFaturamento]";
				}
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
									Carteira.IdStatus = 1 $aux";
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
										AgenteAutorizado.IdStatus = 1 $aux";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp	=	"select 
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
												AgenteAutorizado.IdStatus = 1 $aux";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
				}
			}
		}
		
		if($query == 'true'){
			$j	=	0;
			for($i = 0; $i < $qtd; $i++){
				if($i!=2 && $i!=3 && $i!=4){
					if(@mysql_field_name($res,$i) == "Status"){
						$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema = ".$lin[@mysql_field_name($res,$i)];
						$res2 = @mysql_query($sql2,$con);
						$lin2 = @mysql_fetch_array($res2);
				
						$doc[$ii][$j] = $lin2[ValorParametroSistema];
						
						$j++;
					}else{
						$doc[$ii][$j] = $lin[@mysql_field_name($res,$i)];					
						$j++;
					}
				}
			}	
		}
				
		$ii++;
	}
					
	// generate excel file
	$xls = new Excel_XML;
	$xls->addArray ($doc);
	$xls->generateXML ("contas_receber");
?>
