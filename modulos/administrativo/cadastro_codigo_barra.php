<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja = $_SESSION["IdLoja"];
	$local_IdLicenca = $_SESSION["IdLicenca"];
	$codigo_barra = trim(strtoupper($_POST['codigo_barra']));

	switch(preg_replace(array("/(CI|PS)+(( |)+(\d)+([,\.])+(\d|))/", "/( |)+(\d)/"), array('$1', ''), $codigo_barra)){
		case 'AR':
			$IdArquivoRetorno = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: listar_arquivo_retorno.php?IdArquivoRetorno=$IdArquivoRetorno");
			break;
		case 'CC':
			$IdCentroCusto = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_centro_custo.php?IdCentroCusto=$IdCentroCusto");
			break;
		case 'CO':
			$IdContrato = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_contrato.php?IdContrato=$IdContrato");
			break;
		case 'CR':
			$IdContaReceber = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_conta_receber.php?IdContaReceber=$IdContaReceber");
			break;
		case 'ND':
			$NumeroDocumento = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: listar_conta_receber.php?NumeroDocumento=$NumeroDocumento");
			break;
		case 'EE':
			$IdEmail = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_reenvio_email.php?IdEmail=$IdEmail");
			break;
		case 'EV':
			$IdContaEventual = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_conta_eventual.php?IdContaEventual=$IdContaEventual");
			break;
		case 'LF':
			$IdLancamentoFinanceiro = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro=$IdLancamentoFinanceiro");
			break;
		case 'LC':
			$IdLocalCobranca = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_local_cobranca.php?IdLocalCobranca=$IdLocalCobranca");
			break;
		case 'LR':
			$IdLoteRepasse = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_lote_repasse.php?IdLoteRepasse=$IdLoteRepasse");
			break;
		case 'OS':
			$IdOrdemServico = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_ordem_servico.php?IdOrdemServico=$IdOrdemServico");
			break;
		case 'PC':
			$IdPlanoConta = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_plano_conta.php?IdPlanoConta=$IdPlanoConta");
			break;
		case 'PE':
			$IdPessoa = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_pessoa.php?IdPessoa=$IdPessoa");
			break;
		case 'PF':
			$IdProcessoFinanceiro = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_processo_financeiro.php?IdProcessoFinanceiro=$IdProcessoFinanceiro");
			break;
		case 'PR':
			$IdProduto = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_produto.php?IdProduto=$IdProduto");
			break;
		case 'PT':
			$IdProtocolo = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_protocolo.php?IdProtocolo=$IdProtocolo");
			break;
		case 'SE':
			$IdServico = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_servico.php?IdServico=$IdServico");
			break;
		case 'TV':
			$IdTipoVigencia = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_tipo_vigencia.php?IdTipoVigencia=$IdTipoVigencia");
			break;
		case 'TK':
			if($local_IdLicenca == '2007A000' || $local_IdLicenca == '2007A001'){
				$IdTicket = substr($codigo_barra,2,strlen($codigo_barra)-2);
				header("Location: cadastro_help_desk.php?IdTicket=$IdTicket");
			}else{
				header("Location: informacao_atalho.php");
			}
			break;
		case 'NF':
			$IdNotaFiscal = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: listar_nota_fiscal.php?IdNotaFiscal=$IdNotaFiscal");
			break;
		case 'ME':
			$IdHistoricoMensagem = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_reenvio_mensagem.php?IdHistoricoMensagem=$IdHistoricoMensagem");
			break;
		case 'RE':
			$IdArquivoRemessa = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: listar_arquivo_remessa.php?IdArquivoRemessa=$IdArquivoRemessa");
			break;
		case 'CI':
			$CodigoInterno = preg_split("/[,\.]/", substr($codigo_barra,2,strlen($codigo_barra)-2));
			header("Location: cadastro_codigo_interno.php?IdGrupoCodigoInterno=$CodigoInterno[0]&IdCodigoInterno=$CodigoInterno[1]");
			break;
		case 'PS':
			$ParametroSistema = preg_split("/[,\.]/", substr($codigo_barra,2,strlen($codigo_barra)-2));
			header("Location: cadastro_parametro_sistema.php?IdGrupoParametroSistema=$ParametroSistema[0]&IdParametroSistema=$ParametroSistema[1]");
			break;
		case 'RC':
			$IdRecibo = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_conta_receber.php?IdRecibo=$IdRecibo");
			break;
		case 'MD':
			$IdMalaDireta = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_mala_direta.php?IdMalaDireta=$IdMalaDireta");
			break;
		case 'CX':
			$IdCaixa = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_caixa.php?IdCaixa=$IdCaixa");
			break;
		case 'NN':
			$NossoNumero = substr($codigo_barra,2,strlen($codigo_barra)-2);
			header("Location: cadastro_conta_receber.php?NossoNumero=$NossoNumero");
			break;
		default:
			header("Location: informacao_atalho.php");
			break;
	}
	
	switch(strlen($codigo_barra)){
		case 44:
			// Conta a Receber
			$numero_documento = (int)substr($codigo_barra,33,strlen($codigo_barra)-33-2);

			$sql = "select IdContaReceber from ContaReceber where IdLoja = $local_IdLoja and NumeroDocumento = $numero_documento";
			$res = mysql_query($sql,$con);
			if(mysql_num_rows($res) == 0){
				$numero_documento = (int)substr($codigo_barra,24,5);
			}
			break;
		case 47:
			// Conta a Receber
			$numero_documento = (int)(substr($codigo_barra,15,5).substr($codigo_barra,21,2));
			break;
		case 92:
			// Conta a Receber
			$codigo_barra = str_replace(" ", "", $codigo_barra);
			$numero_documento = (int)(substr($codigo_barra,15,5).substr($codigo_barra,21,2));
			break;
	}

	if($numero_documento != ''){
		$sql = "select IdContaReceber from ContaReceber where IdLoja = $local_IdLoja and NumeroDocumento = $numero_documento";
		$res = mysql_query($sql,$con);

		if(mysql_num_rows($res) == 1){
			$lin = mysql_fetch_array($res);
			header("Location: cadastro_conta_receber.php?IdContaReceber=$lin[IdContaReceber]");
		}else{
			header("Location: listar_conta_receber.php?NumeroDocumento=$numero_documento");
		}
	}
?>