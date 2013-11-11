<?
	$sql = "select
				Contrato.TipoContrato,
				Contrato.IdPessoa,
				Contrato.IdLocalCobranca,
				Contrato.MesFechado,
				Contrato.IdPeriodicidade
			from 
				Contrato
			where 
				Contrato.IdLoja = $local_IdLoja and
				Contrato.IdContrato = $local_IdContrato";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	
	$sqlLC="select 
				IdNotaFiscalTipo 
			from
				LocalCobranca 
			where 
				IdLoja = $local_IdLoja and
				IdLocalCobranca = $lin[IdLocalCobranca] and
				IdNotaFiscalTipo is not null";
	$resLC = @mysql_query($sqlLC,$con);
	$linLC = @mysql_fetch_array($resLC);

	// Gerando o Mъs de Vencimento
	$local_DiaPrimeiroVencimento	= substr($local_DataPrimeiroVenc,0,2);
	$local_MesPrimeiroVencimento	= substr($local_DataPrimeiroVenc,3,2);
	$local_AnoPrimeiroVencimento	= substr($local_DataPrimeiroVenc,6,4);
	$local_MesVencimento			= $local_MesPrimeiroVencimento."/".$local_AnoPrimeiroVencimento;

	// Gerando o menor vencimento
	$local_MenorVencimento = (int)$local_DiaPrimeiroVencimento;

	// Gerando o mъs de referencia
	$local_MesDataInicio	= substr($local_DataAtivacaoInicio,3,2);
	$local_AnoDataInicio	= substr($local_DataAtivacaoInicio,6,4);
	
	$local_MesReferencia = $local_MesDataInicio."/".$local_AnoDataInicio;
	
	if($lin[TipoContrato] == 2){
		$local_MesReferencia = incrementaMesReferencia($local_MesReferencia, 1);
	}
	
	// Gerando o filtro (pessoa/contrato)
	if($local_AgruparContrato == 2){
		$local_Filtro_IdContrato = $local_IdContrato;
	}else{
		$local_Filtro_IdPessoa = $lin[IdPessoa];
	}

	// Gerando o local de cobranчa
 	$local_Filtro_IdLocalCobranca = $lin[IdLocalCobranca];

	// Gerando o tipo de lanчamento
	$local_Filtro_TipoLancamento = 1;
	
	// Gerando a data da NF
	if($linLC[IdNotaFiscalTipo] != ""){
		$local_DataNotaFiscal = date("d/m/Y");
	}

	// Gerando o tipo da cobranчa
	if($lin[IdPeriodicidade] != 8){
		$local_Filtro_TipoCobranca = 1;
	}else{
		$local_Filtro_TipoCobranca = 2;
	}

	// Log do Processo Financeiro
	$local_LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Processo Financeiro gerado automaticamente no ato de cadastro do contrato [$local_IdContrato].";

	// Executando a rotina de inserчуo do processo financeiro
	include('files/inserir/inserir_processo_financeiro.php');

	// Processando o processo financeiro
	include('rotinas/processar_processo_financeiro.php');

	// Confirmando o processo financeiro
	include('rotinas/confirmar_processo_financeiro.php');

	// Desabilita o processamento de ativaчуo
	$sql = "update Contrato set DadosAtivacao=2 where IdLoja=$local_IdLoja and IdContrato=$local_IdContrato";
	@mysql_query($sql,$con);

	header("Location: listar_conta_receber.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");
?>