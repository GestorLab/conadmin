<?
	$localModulo = 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_SICIVisualizar(){
		global $con;
		global $_GET;
		
		$PeriodoApuracao	= $_GET['PeriodoApuracao'];
		
		if($PeriodoApuracao != ''){
			$PeriodoApuracao = dataConv($PeriodoApuracao, "m/Y", "Y-m");
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
						SICI.PeriodoApuracao = '$PeriodoApuracao' AND 
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
						NotaFiscalItem.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro;";
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
						SICI.PeriodoApuracao = '$PeriodoApuracao' AND 
						SICI.PeriodoApuracao = SICILancamento.PeriodoApuracao AND 
						SICILancamento.IdLoja = LancamentoFinanceiro.IdLoja AND 
						SICILancamento.IdlancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND 
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND 
						LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND 
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber;";
		}
		
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
		
			while($lin = @mysql_fetch_array($res)){
				$sql_tmp = "SELECT 
								Servico.DescricaoServico 
							FROM 
								Contrato, 
								Servico 
							WHERE 
								Contrato.IdLoja = '".$lin[IdLoja]."' AND
								Contrato.IdContrato = '".$lin[IdContrato]."' AND
								Contrato.IdLoja = Servico.IdLoja AND 
								Contrato.IdServico = Servico.IdServico;";
				$res_tmp = mysql_query($sql_tmp, $con);
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
				
				$dados .= "\n<PeriodoApuracao><![CDATA[$lin[PeriodoApuracao]]]></PeriodoApuracao>";
				$dados .= "\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
				$dados .= "\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
				$dados .= "\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados .= "\n<IdLancamentoFinanceiro><![CDATA[$lin[IdLancamentoFinanceiro]]]></IdLancamentoFinanceiro>";
				$dados .= "\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				$dados .= "\n<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
				$dados .= "\n<DataNF><![CDATA[$lin[DataNF]]]></DataNF>";
				$dados .= "\n<ModeloNF><![CDATA[$lin[ModeloNF]]]></ModeloNF>";
				$dados .= "\n<NumeroModeloNF><![CDATA[$lin[NumeroModeloNF]]]></NumeroModeloNF>";
				$dados .= "\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados .= "\n<ValorDescontoAConceber><![CDATA[$lin[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
				$dados .= "\n<ValorFinal><![CDATA[$lin[ValorFinal]]]></ValorFinal>";
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_SICIVisualizar();
?>