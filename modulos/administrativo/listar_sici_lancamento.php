<?
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "R";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado					= $_POST['filtro_tipoDado'];
	$filtro_periodo_apuracao			= $_POST['filtro_periodo_apuracao'];
	$filtro_descricao_servico			= $_POST['filtro_descricao_servico'];
	$filtro_numero_nota_fiscal			= $_POST['filtro_numero_nota_fiscal'];
	$filtro_data_nota_fiscal_inicial	= $_POST['filtro_data_nota_fiscal_inicial'];
	$filtro_data_nota_fiscal_final		= $_POST['filtro_data_nota_fiscal_final'];
	$filtro_limit						= $_POST['filtro_limit'];
	
	if($filtro_protocolo_tipo == ''){
		$filtro_protocolo_tipo = $_GET['IdProtocoloTipo'];
	}
	
	if($_GET["filtro_limit"] != null){
		$filtro_limit = $_GET["filtro_limit"];
	}
	
	if($_GET["PeriodoApuracao"] != null){
		$filtro_periodo_apuracao = $_GET["PeriodoApuracao"];
	}
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	$filtro_sql2 = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_descricao_servico != "") {
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql2 .= " AND Servico.DescricaoServico LIKE '%$filtro_descricao_servico%'";
	}
	
	if($filtro_numero_nota_fiscal != "") {
		$filtro_url .= "&NumeroNF=".$filtro_numero_nota_fiscal;
		$filtro_sql = " AND (
			ContaReceber.NumeroNF = '$filtro_numero_nota_fiscal' OR 
			NotaFiscal.IdNotaFiscal = '$filtro_numero_nota_fiscal'
		)";
	}
	
	if($filtro_data_nota_fiscal_inicial != "") {
		$filtro_url .= "&DataNFInicial=".$filtro_data_nota_fiscal_inicial;
		$filtro_data_nota_fiscal_inicial = dataConv($filtro_data_nota_fiscal_inicial, "d/m/Y", "Y-m-d");
		$temp_sql_cr = " ContaReceber.DataNF >= '$filtro_data_nota_fiscal_inicial'";
		$temp_sql_nf = " NotaFiscal.DataEmissao >= '$filtro_data_nota_fiscal_inicial'";
	}
	
	if($filtro_data_nota_fiscal_final != "") {
		$filtro_url .= "&DataNFFinal=".$filtro_data_nota_fiscal_final;
		$filtro_data_nota_fiscal_final = dataConv($filtro_data_nota_fiscal_final, "d/m/Y", "Y-m-d");
		
		if($temp_sql_cr != null){
			$temp_sql_cr .= " AND";
			$temp_sql_nf .= " AND";
		}
		
		$temp_sql_cr .= " ContaReceber.DataNF <= '$filtro_data_nota_fiscal_final'";
		$temp_sql_nf .= " NotaFiscal.DataEmissao <= '$filtro_data_nota_fiscal_final'";
	}
	
	if($temp_sql_cr != null){
		$filtro_sql .= " AND (
			(
				$temp_sql_cr
			) OR (
				$temp_sql_nf 
			)
		)";
	}

	$filtro_url .= "&PeriodoApuracao=".$filtro_periodo_apuracao;
	$filtro_periodo_apuracao = dataConv($filtro_periodo_apuracao, "m/Y", "Y-m");
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_sici_lancamento_xsl.php$filtro_url\"?>";
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
				NotaFiscalLayout.IdNotaFiscalLayout,
				NotaFiscalLayout.Modelo
			FROM
				NotaFiscalLayout,
				NotaFiscalTipo 
			WHERE 
				NotaFiscalLayout.Modelo = '21' AND 
				NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND 
				NotaFiscalTipo.IdStatus = 1";
	$res = mysql_query($sql, $con);
	$NotaFiscal = (mysql_num_rows($res) > 0);
	
	if($NotaFiscal){
		$sql = "SELECT 
					SICI.PeriodoApuracao,
					SICILancamento.IdLoja,
					SICILancamento.IdLancamentoFinanceiro,
					NotaFiscalItem.ValorTotal Valor,
					NotaFiscalItem.ValorDesconto ValorDescontoAConceber,
					NotaFiscalItem.ValorBaseCalculoICMS ValorFinal,
					LancamentoFinanceiro.IdContrato,
					ContaReceber.IdContaReceber,
					ContaReceber.NumeroNF,
					ContaReceber.DataNF,
					ContaReceber.ModeloNF,
					NotaFiscal.IdNotaFiscal,
					NotaFiscal.DataEmissao,
					NotaFiscal.Modelo 
				FROM
					SICI, 
					SICILancamento,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceber,
					NotaFiscal,
					NotaFiscalItem
				WHERE 
					SICI.PeriodoApuracao = '$filtro_periodo_apuracao' AND 
					SICI.PeriodoApuracao = SICILancamento.PeriodoApuracao AND 
					SICILancamento.IdLoja = LancamentoFinanceiro.IdLoja AND 
					SICILancamento.IdlancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND 
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND 
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND 
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND 
					ContaReceber.IdLoja = NotaFiscal.IdLoja AND 
					ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
					NotaFiscal.Modelo = '21' AND 
					NotaFiscal.IdLoja = NotaFiscalItem.IdLoja AND
					NotaFiscal.IdNotaFiscal = NotaFiscalItem.IdNotaFiscal AND 
					ContaReceber.IdContaReceber = NotaFiscalItem.IdContaReceber AND
					LancamentoFinanceiro.IdLancamentoFinanceiro = NotaFiscalItem.IdLancamentoFinanceiro
					$filtro_sql
				ORDER BY
					SICILancamento.PeriodoApuracao,
					LancamentoFinanceiro.IdContrato,
					ContaReceber.IdContaReceber, 
					LancamentoFinanceiro.IdLancamentoFinanceiro
				$Limit;";
	} else{
		$sql = "SELECT 
					SICI.PeriodoApuracao,
					SICILancamento.IdLoja,
					SICILancamento.IdLancamentoFinanceiro,
					LancamentoFinanceiro.Valor,
					LancamentoFinanceiro.ValorDescontoAConceber,
					(LancamentoFinanceiro.Valor - LancamentoFinanceiro.ValorDescontoAConceber) ValorFinal,
					LancamentoFinanceiro.IdContrato,
					ContaReceber.IdContaReceber,
					ContaReceber.NumeroNF,
					ContaReceber.DataNF,
					ContaReceber.ModeloNF,
					NotaFiscal.IdNotaFiscal,
					NotaFiscal.DataEmissao,
					NotaFiscal.Modelo 
				FROM
					SICI, 
					SICILancamento,
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber,
					ContaReceber LEFT JOIN NotaFiscal ON (
						ContaReceber.IdLoja = NotaFiscal.IdLoja AND 
						ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
						NotaFiscal.Modelo = '21' 
					) 
				WHERE 
					SICI.PeriodoApuracao = '$filtro_periodo_apuracao' AND 
					SICI.PeriodoApuracao = SICILancamento.PeriodoApuracao AND 
					SICILancamento.IdLoja = LancamentoFinanceiro.IdLoja AND 
					SICILancamento.IdlancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND 
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND 
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND 
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber
					$filtro_sql
				ORDER BY
					SICILancamento.PeriodoApuracao,
					LancamentoFinanceiro.IdContrato,
					ContaReceber.IdContaReceber, 
					LancamentoFinanceiro.IdLancamentoFinanceiro
				$Limit;";
	}
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$sql_tmp = "SELECT 
						Servico.DescricaoServico 
					FROM 
						Contrato, 
						Servico 
					WHERE 
						Contrato.IdLoja = '".$lin[IdLoja]."' AND
						Contrato.IdContrato = '".$lin[IdContrato]."' AND
						Contrato.IdLoja = Servico.IdLoja AND 
						Contrato.IdServico = Servico.IdServico
						$filtro_sql2;";
		$res_tmp = mysql_query($sql_tmp, $con);
		
		if(@mysql_num_rows($res_tmp) > 0){
			$lin_tmp = mysql_fetch_array($res_tmp);
			$lin[DescricaoServico] = $lin_tmp[DescricaoServico];
			
			if($lin[IdNotaFiscal] != null){
				$lin[NumeroNF] = $lin[IdNotaFiscal];
				$lin[DataNF] = $lin[DataEmissao];
				$lin[ModeloNF] = $lin[Modelo];
			}
			
			if($lin[NumeroNF] == null || $lin[ModeloNF] == null){
				$lin[NumeroModeloNF] == $lin[NumeroNF];
			} else{
				$lin[NumeroModeloNF] = $lin[NumeroNF]." Mod. ".$lin[ModeloNF];
			}
			
			$lin[DataNF]				= dataConv($lin[DataNF], "Y-m-d", "Ymd");
			$lin[DataNFTemp]			= dataConv($lin[DataNF], "Ymd", "d/m/Y");
			$lin[PeriodoApuracao]		= dataConv($lin[PeriodoApuracao], "Y-m", "Ym");
			$lin[PeriodoApuracaoTemp]	= dataConv($lin[PeriodoApuracao], "Ym", "m/Y");
			
			echo "<reg>";
			echo 	"<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			echo 	"<PeriodoApuracao><![CDATA[$lin[PeriodoApuracao]]]></PeriodoApuracao>";
			echo 	"<PeriodoApuracaoTemp><![CDATA[$lin[PeriodoApuracaoTemp]]]></PeriodoApuracaoTemp>";
			echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			echo 	"<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
			echo 	"<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			echo 	"<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			echo 	"<NumeroModeloNF><![CDATA[$lin[NumeroModeloNF]]]></NumeroModeloNF>";
			echo 	"<DataNF><![CDATA[$lin[DataNF]]]></DataNF>";
			echo 	"<DataNFTemp><![CDATA[$lin[DataNFTemp]]]></DataNFTemp>";
			echo 	"<ModeloNF><![CDATA[$lin[ModeloNF]]]></ModeloNF>";
			echo 	"<IdLancamentoFinanceiro><![CDATA[$lin[IdLancamentoFinanceiro]]]></IdLancamentoFinanceiro>";
			echo 	"<Valor><![CDATA[$lin[Valor]]]></Valor>";
			echo 	"<ValorDescontoAConceber><![CDATA[$lin[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
			echo 	"<ValorFinal><![CDATA[$lin[ValorFinal]]]></ValorFinal>";
			echo "</reg>";
		}
	}
	
	echo "</db>";
?>