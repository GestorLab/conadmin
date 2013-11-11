<?php 
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"]; 
	$IdPessoaLogin					= $_SESSION['IdPessoa'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_pessoa					= url_string_xsl($_POST['filtro_pessoa'], "URL", false);
	$filtro_data					= $_POST['filtro_data'];	
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];	
	$filtro_data_fim				= $_POST['filtro_data_fim'];	
	$filtro_id_status_caixa			= $_POST['filtro_id_status_caixa'];	
	$filtro_id_tipo_movimentacao	= $_POST['filtro_id_tipo_movimentacao'];	
	$filtro_id_status_movimentacao	= $_POST['filtro_id_status_movimentacao'];	
	$filtro_limit					= $_POST['filtro_limit'];	
	$filtro_id_caixa				= $_GET['IdCaixa'];
	$filtro_url						= "";
	$filtro_sql						= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
	
	if($filtro_ordem != "")
		$filtro_url .= "&Ordem=$filtro_ordem";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
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
		$filtro_data_inicio_temp = explode(" ",$filtro_data_inicio);
		$filtro_data_fim_temp = explode(" ",$filtro_data_fim);
		$filtro_url .= "&Data=".$filtro_data;
		
		switch($filtro_data) {
			case "DataAbertura":
				if($filtro_data_inicio != "") {
					$filtro_url .= "&DataInicio=".$filtro_data_inicio;
					if($filtro_data_inicio_temp[1] !=""){
						$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y H:i:s","Y-m-d H:i:s");
						$filtro_sql .= " AND Caixa.DataAbertura >= '$filtro_data_inicio'";
					}else{
						$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
						$filtro_sql .= " AND Caixa.DataAbertura like '$filtro_data_inicio%'";
					}
				}
				
				if($filtro_data_fim != "") {
					$filtro_url .= "&DataFim=".$filtro_data_fim;
					if($filtro_data_fim_temp[1] !=""){
						$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y H:i:s","Y-m-d H:i:s");
						$filtro_sql .= " AND Caixa.DataAbertura <= '$filtro_data_fim'";
					}else{
						$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
						$filtro_sql .= " AND Caixa.DataAbertura like '$filtro_data_inicio%'";
					}
				}
				break;
				
			case "DataFechamento":
				if($filtro_data_inicio != "") {
					$filtro_url .= "&DataInicio=".$filtro_data_inicio;
					if($filtro_data_inicio_temp[1] !=""){
						$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y H:i:s","Y-m-d H:i:s");
						$filtro_sql .= " AND Caixa.DataFechamento >= $filtro_data_inicio";
					}else{
						$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
						$filtro_sql .= " AND Caixa.DataFechamento like '$filtro_data_inicio%'";
					}
				}
				
				if($filtro_data_fim != "") {
					$filtro_url .= "&DataFim=".$filtro_data_fim;
					if($filtro_data_fim_temp[1] !=""){
						$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y H:i:s","Y-m-d H:i:s");
						$filtro_sql .= " AND Caixa.DataFechamento <= $filtro_data_fim";
					}else{
						$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
						$filtro_sql .= " AND Caixa.DataFechamento like '$filtro_data_fim%'";
					}
				}
				break;
		}
	}
	
	if($filtro_id_status_caixa != "") {
		$filtro_url .= "&IdStatusCaixa=".$filtro_id_status_caixa;
		$filtro_sql .= " AND Caixa.IdStatus = $filtro_id_status_caixa";
	}
	
	if($filtro_id_tipo_movimentacao != "" || $filtro_id_status_movimentacao != ""){
		$filtro_sql .= " AND Caixa.IdCaixa IN (SELECT CaixaMovimentacao.IdCaixa FROM CaixaMovimentacao WHERE CaixaMovimentacao.IdLoja = '$local_IdLoja'";
		
		if($filtro_id_tipo_movimentacao != "") {
			$filtro_url .= "&IdTipoMovimentacao=".$filtro_id_tipo_movimentacao;
			$filtro_sql .= " AND CaixaMovimentacao.TipoMovimentacao = $filtro_id_tipo_movimentacao";
		}
		
		if($filtro_id_status_movimentacao != "") {
			$filtro_url .= "&IdStatusMovimentacao=".$filtro_id_status_movimentacao;
			$filtro_sql .= " AND CaixaMovimentacao.IdStatus = $filtro_id_status_movimentacao";
		}
		
		$filtro_sql .= ")";
	}
	
	if($filtro_id_caixa != "")
		$filtro_sql .= " AND Caixa.IdCaixa = $filtro_id_caixa";
	
	if($filtro_limit == "" && $_GET["filtro_limit"] != "")
		$filtro_limit = $_GET["filtro_limit"];
	
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url = "?f=t".$filtro_url;
		$filtro_url = url_string_xsl($filtro_url,'CONVERT',false);
	}
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit = " LIMIT $filtro_limit";
		}
	} else{
		if($filtro_limit == ""){
			$Limit = " LIMIT 0,".getCodigoInterno(7,5);
		} else{
			$Limit = " LIMIT 0,".$filtro_limit;
		}
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_caixa_movimentacao_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	$sql_cx = "SELECT 
					Caixa.IdLoja, 
					Caixa.IdCaixa, 
					Caixa.IdStatus, 
					Caixa.DataAbertura, 
					Caixa.LoginAbertura, 
					Caixa.DataFechamento, 
					Caixa.LoginFechamento
				FROM 
					Caixa,
					Usuario,
					Pessoa
				WHERE
					Caixa.IdLoja = $local_IdLoja AND 
					Caixa.LoginAbertura = Usuario.Login AND 
					Usuario.IdPessoa = Pessoa.IdPessoa
					$filtro_sql
				ORDER BY 
					Caixa.IdCaixa DESC;";
	$res_cx = @mysql_query($sql_cx, $con);
	
	while($lin_cx = @mysql_fetch_array($res_cx)){
		ob_start("out_buffer");
		
		$lin_cx[DataAberturaTemp] 	= dataConv($lin_cx[DataAbertura], "Y-m-d H:i:s", "d/m/Y H:i:s");
		$lin_cx[DataAbertura] 		= dataConv($lin_cx[DataAbertura], "Y-m-d H:i:s", "Ymd H:i:s");
		$lin_cx[DataFechamentoTemp] = dataConv($lin_cx[DataFechamento], "Y-m-d H:i:s", "d/m/Y H:i:s");
		$lin_cx[DataFechamento] 	= dataConv($lin_cx[DataFechamento], "Y-m-d H:i:s", "Ymd H:i:s");
		$lin_cx[Status]				= getParametroSistema(243, $lin_cx[IdStatus]);
		
		$sql_rp = "SELECT 
						Pessoa.Nome,
						Pessoa.RazaoSocial
					FROM 
						Usuario,
						Pessoa
					WHERE
						Usuario.Login = '".$lin_cx[LoginAbertura]."' AND
						Usuario.IdPessoa = Pessoa.IdPessoa;";
		$res_rp = @mysql_query($sql_rp, $con);
		$lin_rp = @mysql_fetch_array($res_rp);
		
		if(!empty($lin_rp[RazaoSocial])) {
			$lin_rp[Nome] = $lin_rp[RazaoSocial];
		}
		
		echo "<reg>";
		echo "<IdCaixa><![CDATA[$lin_cx[IdCaixa]]]></IdCaixa>";
		echo "<DataAberturaTemp><![CDATA[$lin_cx[DataAberturaTemp]]]></DataAberturaTemp>";
		echo "<DataAbertura><![CDATA[$lin_cx[DataAbertura]]]></DataAbertura>";
		echo "<DataFechamentoTemp><![CDATA[$lin_cx[DataFechamentoTemp]]]></DataFechamentoTemp>";
		echo "<DataFechamento><![CDATA[$lin_cx[DataFechamento]]]></DataFechamento>";
		echo "<NomeResponsavel><![CDATA[$lin_rp[Nome]]]></NomeResponsavel>";
		echo "<LoginAbertura><![CDATA[$lin_cx[LoginAbertura]]]></LoginAbertura>";
		echo "<IdStatus><![CDATA[$lin_cx[IdStatus]]]></IdStatus>";
		echo "<Status><![CDATA[$lin_cx[Status]]]></Status>";
		
		$sql_mv = "SELECT
						CaixaMovimentacao.IdLoja,
						CaixaMovimentacao.IdCaixa,
						CaixaMovimentacao.IdCaixaMovimentacao,
						CaixaMovimentacao.TipoMovimentacao,
						CaixaMovimentacao.ValorTotal,
						CaixaMovimentacao.Obs,
						CaixaMovimentacao.IdStatus
					FROM
						CaixaMovimentacao
					WHERE
						CaixaMovimentacao.IdLoja = $lin_cx[IdLoja] AND
						CaixaMovimentacao.IdCaixa = $lin_cx[IdCaixa]
					$Limit";
		$res_mv = @mysql_query($sql_mv, $con);
		
		while($lin_mv = @mysql_fetch_array($res_mv)){
			$lin_mv[IdTipoMovimentacao]	= $lin_mv[TipoMovimentacao];
			$lin_mv[TipoMovimentacao]	= getParametroSistema(244, $lin_mv[IdTipoMovimentacao]);
			$lin_mv[Status]				= getParametroSistema(259, $lin_mv[IdStatus]);
			
			echo "<Movimentacao>";
			echo "<IdCaixa><![CDATA[$lin_mv[IdCaixa]]]></IdCaixa>";
			echo "<IdCaixaMovimentacao><![CDATA[$lin_mv[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
			echo "<IdTipoMovimentacao><![CDATA[$lin_mv[IdTipoMovimentacao]]]></IdTipoMovimentacao>";
			echo "<TipoMovimentacao><![CDATA[$lin_mv[TipoMovimentacao]]]></TipoMovimentacao>";
			echo "<IdStatus><![CDATA[$lin_mv[IdStatus]]]></IdStatus>";
			echo "<Status><![CDATA[$lin_mv[Status]]]></Status>";
			
			$sql_it = "SELECT 
							CaixaMovimentacaoItem.IdCaixa,
							CaixaMovimentacaoItem.IdCaixaMovimentacao,
							CaixaMovimentacaoItem.IdCaixaItem,
							CaixaMovimentacaoItem.ValorItem,
							CaixaMovimentacaoItem.ValorMulta,
							CaixaMovimentacaoItem.ValorJuros,
							CaixaMovimentacaoItem.ValorDesconto,
							CaixaMovimentacaoItem.ValorFinal,
							ContaReceberDados.IdContaReceber,
							ContaReceberDados.NumeroDocumento,
							ContaReceberDados.DataVencimento,
							Pessoa.Nome,
							Pessoa.RazaoSocial
						FROM
							CaixaMovimentacaoItem,
							ContaReceberDados,
							Pessoa
						WHERE
							CaixaMovimentacaoItem.IdLoja = '$lin_mv[IdLoja]' AND 
							CaixaMovimentacaoItem.IdCaixa = '$lin_mv[IdCaixa]' AND 
							CaixaMovimentacaoItem.IdCaixaMovimentacao = '$lin_mv[IdCaixaMovimentacao]' AND 
							CaixaMovimentacaoItem.IdLoja = ContaReceberDados.IdLoja AND 
							CaixaMovimentacaoItem.IdContaReceber = ContaReceberDados.IdContaReceber AND 
							ContaReceberDados.IdPessoa = Pessoa.IdPessoa 
						ORDER BY 
							CaixaMovimentacaoItem.IdCaixaItem";
			$res_it = @mysql_query($sql_it, $con);
			
			while($lin_it = @mysql_fetch_array($res_it)){
				$lin_it[DataVencimentoTemp] = dataConv($lin_it[DataVencimento], "Y-m-d", "d/m/Y");
				
				if($lin_it[RazaoSocial] != null) {
					$lin_it[Nome] = $lin_it[RazaoSocial];
				}
				
				echo "<Item>";
				echo "<IdCaixa><![CDATA[$lin_it[IdCaixa]]]></IdCaixa>";
				echo "<IdCaixaMovimentacao><![CDATA[$lin_it[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
				echo "<IdCaixaItem><![CDATA[$lin_it[IdCaixaItem]]]></IdCaixaItem>";
				echo "<ValorItem><![CDATA[$lin_it[ValorItem]]]></ValorItem>";
				echo "<ValorMulta><![CDATA[$lin_it[ValorMulta]]]></ValorMulta>";
				echo "<ValorJuros><![CDATA[$lin_it[ValorJuros]]]></ValorJuros>";
				echo "<ValorDesconto><![CDATA[$lin_it[ValorDesconto]]]></ValorDesconto>";
				echo "<ValorFinal><![CDATA[$lin_it[ValorFinal]]]></ValorFinal>";
				echo "<IdContaReceber><![CDATA[$lin_it[IdContaReceber]]]></IdContaReceber>";
				echo "<NumeroDocumento><![CDATA[$lin_it[NumeroDocumento]]]></NumeroDocumento>";
				echo "<Nome><![CDATA[$lin_it[Nome]]]></Nome>";
				echo "<DataVencimento><![CDATA[$lin_it[DataVencimento]]]></DataVencimento>";
				echo "<DataVencimentoTemp><![CDATA[$lin_it[DataVencimentoTemp]]]></DataVencimentoTemp>";
				echo "</Item>";
			}
			
			$sql_fp = "SELECT 
							CaixaMovimentacaoFormaPagamento.IdLoja, 
							CaixaMovimentacaoFormaPagamento.IdCaixa, 
							CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao, 
							CaixaMovimentacaoFormaPagamento.IdFormaPagamento, 
							CaixaMovimentacaoFormaPagamento.QtdParcelas, 
							CaixaMovimentacaoFormaPagamento.ValorParcela, 
							CaixaMovimentacaoFormaPagamento.ValorTotal, 
							FormaPagamento.DescricaoFormaPagamento 
						FROM 
							CaixaMovimentacaoFormaPagamento, 
							FormaPagamento 
						WHERE 
							CaixaMovimentacaoFormaPagamento.IdLoja = '$lin_mv[IdLoja]' AND 
							CaixaMovimentacaoFormaPagamento.IdCaixa = '$lin_mv[IdCaixa]' AND 
							CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao = '$lin_mv[IdCaixaMovimentacao]' AND 
							CaixaMovimentacaoFormaPagamento.IdLoja = FormaPagamento.IdLoja AND 
							CaixaMovimentacaoFormaPagamento.IdFormaPagamento = FormaPagamento.IdFormaPagamento
						ORDER BY 
							CaixaMovimentacaoFormaPagamento.IdFormaPagamento";
			$res_fp = @mysql_query($sql_fp, $con);
			
			while($lin_fp = @mysql_fetch_array($res_fp)){
				$sql_fp_pc = "SELECT 
								FormaPagamentoParcela.PercentualJurosMes 
							FROM 
								FormaPagamentoParcela 
							WHERE 
								FormaPagamentoParcela.IdLoja = '$lin_fp[IdLoja]' AND 
								FormaPagamentoParcela.IdFormaPagamento = '$lin_fp[IdFormaPagamento]' AND 
								FormaPagamentoParcela.QtdParcela = '$lin_fp[QtdParcelas]'";
				$res_fp_pc = @mysql_query($sql_fp_pc, $con);
				$lin_fp_pc = @mysql_fetch_array($res_fp_pc);
				
				$lin_fp[DataVencimentoTemp] = dataConv($lin_fp[DataVencimento], "Y-m-d", "d/m/Y");
				
				if($lin_fp[RazaoSocial] != null) {
					$lin_fp[Nome] = $lin_fp[RazaoSocial];
				}
				
				echo "<FormaPagamento>";
				echo "<IdCaixa><![CDATA[$lin_fp[IdCaixa]]]></IdCaixa>";
				echo "<IdCaixaMovimentacao><![CDATA[$lin_fp[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
				echo "<IdFormaPagamento><![CDATA[$lin_fp[IdFormaPagamento]]]></IdFormaPagamento>";
				echo "<DescricaoFormaPagamento><![CDATA[$lin_fp[DescricaoFormaPagamento]]]></DescricaoFormaPagamento>";
				echo "<QtdParcelas><![CDATA[$lin_fp[QtdParcelas]]]></QtdParcelas>";
				echo "<ValorParcela><![CDATA[$lin_fp[ValorParcela]]]></ValorParcela>";
				echo "<ValorTotal><![CDATA[$lin_fp[ValorTotal]]]></ValorTotal>";
				echo "</FormaPagamento>";
			}
			
			echo "</Movimentacao>";
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
								CaixaMovimentacaoFormaPagamento.IdLoja = '$lin_cx[IdLoja]' AND 
								CaixaMovimentacaoFormaPagamento.IdCaixa = '$lin_cx[IdCaixa]' AND 
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
						CaixaFormaPagamento.IdLoja = '$lin_cx[IdLoja]' AND 
						CaixaFormaPagamento.IdCaixa = '$lin_cx[IdCaixa]'";
		$res_rs = @mysql_query($sql_rs, $con);
		$lin_rs = @mysql_fetch_array($res_rs);
		
		if(empty($lin_rs[ValorAberturaTotal])){
			$lin_rs[ValorAberturaTotal] = 0.00;
		}
		
		if(empty($lin_rs[ValorAtualTotal])){
			$lin_rs[ValorAtualTotal] = 0.00;
		}
		
		echo "<ResumoCaixa>";
		echo "<ValorAberturaTotal><![CDATA[$lin_rs[ValorAberturaTotal]]]></ValorAberturaTotal>";
		echo "<ValorAtualTotal><![CDATA[$lin_rs[ValorAtualTotal]]]></ValorAtualTotal>";
		echo "</ResumoCaixa>";
		echo "</reg>";
	}
	
	echo "</db>";
?>