<?php
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_pessoa					= url_string_xsl($_POST['filtro_pessoa'], "URL", false);
	$filtro_data					= $_POST['filtro_data'];
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_id_status				= $_POST['filtro_id_status'];
	$filtro_limit					= $_POST['filtro_limit'];
	$filtro_url						= "";
	$filtro_sql						= "";
	
	if($filtro_protocolo_tipo == ''){
		$filtro_protocolo_tipo = $_GET['IdProtocoloTipo'];
	}
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url .= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_pessoa != "") {
		$filtro_url .= "&Pessoa=".$filtro_pessoa;
		$filtro_sql .= " AND (
			Pessoa.Nome LIKE '%$filtro_pessoa%' OR 
			Pessoa.RazaoSocial LIKE '%$filtro_pessoa%'
		)";
	}
	
	if($filtro_data != "") {
		$filtro_url .= "&Data=".$filtro_data;
		
		switch($filtro_data) {
			case "DataAbertura":
				if($filtro_data_inicio != "") {
					$filtro_url .= "&DataInicio=".$filtro_data_inicio;
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y H:i:s","Y-m-d H:i:s");
					$filtro_sql .= " AND Caixa.DataAbertura >= '$filtro_data_inicio'";
				}
				
				if($filtro_data_fim != "") {
					$filtro_url .= "&DataFim=".$filtro_data_fim;
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y H:i:s","Y-m-d H:i:s");
					$filtro_sql .= " AND Caixa.DataAbertura <= '$filtro_data_fim'";
				}
				break;
				
			case "DataFechamento":
				if($filtro_data_inicio != "") {
					$filtro_url .= "&DataInicio=".$filtro_data_inicio;
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y H:i:s","Y-m-d H:i:s");
					$filtro_sql .= " AND Caixa.DataFechamento >= $filtro_data_inicio";
				}
				
				if($filtro_data_fim != "") {
					$filtro_url .= "&DataFim=".$filtro_data_fim;
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y H:i:s","Y-m-d H:i:s");
					$filtro_sql .= " AND Caixa.DataFechamento <= $filtro_data_fim";
				}
				break;
		}
	}
	
	if($filtro_id_status != "") {
		$filtro_url .= "&IdStatus=".$filtro_id_status;
		$filtro_sql .= " and Caixa.IdStatus = $filtro_id_status";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url, 'CONVERT', false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_caixa_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "SELECT 
				Caixa.IdLoja,
				Caixa.IdCaixa,
				Caixa.DataAbertura,
				Caixa.DataFechamento,
				Caixa.IdStatus,
				Caixa.LoginAbertura,
				Pessoa.Nome,
				Pessoa.RazaoSocial
			FROM 
				Caixa,
				Usuario,
				Pessoa
			WHERE 
				Caixa.IdLoja = '$local_IdLoja' AND 
				Caixa.LoginAbertura = Usuario.Login AND 
				Usuario.IdPessoa = Pessoa.IdPessoa
				$filtro_sql
			$Limit;";
	$res = @mysql_query($sql, $con);
	
	while($lin = @mysql_fetch_array($res)){
		$lin[DataAbertura]			= dataConv($lin[DataAbertura],"Y-m-d H:i:s","YmdHis");
		$lin[DataAberturaTemp]		= dataConv($lin[DataAbertura],"YmdHis","d/m/Y H:i:s");
		$lin[DataFechamento]		= dataConv($lin[DataFechamento],"Y-m-d H:i:s","YmdHis");
		$lin[DataFechamentoTemp]	= dataConv($lin[DataFechamento],"YmdHis","d/m/Y H:i:s");
		$lin[Status]				= getParametroSistema(243, $lin[IdStatus]);
		
		if(!empty($lin[RazaoSocial])) {
			$lin[Nome] = $lin[RazaoSocial];
		}
		
		$sql_rs = "SELECT 
						SUM(CaixaFormaPagamento.ValorAbertura) ValorAberturaTotal,
						SUM(SaldoAtual.ValorAtual) ValorAtualTotal
					FROM 
						CaixaFormaPagamento LEFT JOIN (
							SELECT 
								CaixaMovimentacao.IdLoja, 
								CaixaMovimentacao.IdCaixa,
								CaixaFormaPagamento.IdFormaPagamento,
								(SUM(CaixaMovimentacaoFormaPagamento.ValorTotal) + CaixaFormaPagamento.ValorAbertura) ValorAtual
							FROM
								CaixaMovimentacao,
								CaixaMovimentacaoFormaPagamento,
								CaixaFormaPagamento 
							WHERE 
								CaixaMovimentacao.IdStatus = '200' AND 
								CaixaMovimentacao.IdLoja = CaixaMovimentacaoFormaPagamento.IdLoja AND 
								CaixaMovimentacao.IdCaixa = CaixaMovimentacaoFormaPagamento.IdCaixa AND 
								CaixaMovimentacao.IdCaixaMovimentacao = CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao AND 
								CaixaMovimentacaoFormaPagamento.IdLoja = '$lin[IdLoja]' AND 
								CaixaMovimentacaoFormaPagamento.IdCaixa = '$lin[IdCaixa]' AND 
								CaixaMovimentacaoFormaPagamento.IdLoja = CaixaFormaPagamento.IdLoja AND 
								CaixaMovimentacaoFormaPagamento.IdCaixa = CaixaFormaPagamento.IdCaixa AND 
								CaixaMovimentacaoFormaPagamento.IdFormaPagamento = CaixaFormaPagamento.IdFormaPagamento
							GROUP BY 
								CaixaFormaPagamento.IdFormaPagamento
						) SaldoAtual ON (
							CaixaFormaPagamento.IdLoja = SaldoAtual.IdLoja AND
							CaixaFormaPagamento.IdCaixa = SaldoAtual.IdCaixa AND 
							CaixaFormaPagamento.IdFormaPagamento = SaldoAtual.IdFormaPagamento
						)
					WHERE
						CaixaFormaPagamento.IdLoja = '$lin[IdLoja]' AND 
						CaixaFormaPagamento.IdCaixa = '$lin[IdCaixa]'";
		$res_rs = @mysql_query($sql_rs, $con);
		$lin_rs = @mysql_fetch_array($res_rs);
		
		if(empty($lin_rs[ValorAberturaTotal])){
			$lin_rs[ValorAberturaTotal] = 0.00;
		}
		
		if(empty($lin_rs[ValorAtualTotal])){
			$lin_rs[ValorAtualTotal] = 0.00;
		}
	
		echo "<reg>";
		echo "<IdCaixa>$lin[IdCaixa]</IdCaixa>";
		echo "<NomeResponsavel><![CDATA[$lin[Nome]]]></NomeResponsavel>";
		echo "<ValorAbertura><![CDATA[$lin[ValorAbertura]]]></ValorAbertura>";
		echo "<ValorAberturaTotal><![CDATA[$lin_rs[ValorAberturaTotal]]]></ValorAberturaTotal>";
		echo "<ValorAtualTotal><![CDATA[$lin_rs[ValorAtualTotal]]]></ValorAtualTotal>";
		echo "<LoginAbertura><![CDATA[$lin[LoginAbertura]]]></LoginAbertura>";
		echo "<DataAbertura><![CDATA[$lin[DataAbertura]]]></DataAbertura>";
		echo "<DataAberturaTemp><![CDATA[$lin[DataAberturaTemp]]]></DataAberturaTemp>";
		echo "<DataFechamento><![CDATA[$lin[DataFechamento]]]></DataFechamento>";
		echo "<DataFechamentoTemp><![CDATA[$lin[DataFechamentoTemp]]]></DataFechamentoTemp>";
		echo "<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
		echo "<Status><![CDATA[$lin[Status]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>