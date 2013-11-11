<?
	$localModulo = 1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContaReceberCaixaMovimentacao(){
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdContaReceber			= $_GET['IdContaReceber'];
		$ContasReceber			= $_GET['ContasReceber'];
		$Nome					= $_GET['Nome'];
		$NumeroDocumento		= $_GET['NumeroDocumento'];
		$DataVencimento			= $_GET['DataVencimento'];
		$DataLancamento			= $_GET['DataLancamento'];
		$IdLocalCobranca		= $_GET['IdLocalCobranca'];
		$DataPagamento			= $_GET['DataPagamento'];
		$IdStatus				= $_GET['IdStatus'];
		$adicional				= "";
		$where					= "";
		$LeftJoinContaReceber	= "";
		
		if($IdContaReceber != ''){
			$where .= " and ContaReceberDados.IdContaReceber=$IdContaReceber";
			$where .= " and ContaReceberDados.IdStatus != 7";
		}
		
		if($ContasReceber != ''){
			$where .= " and ContaReceberDados.IdContaReceber not in ($ContasReceber)";
		}
		
		if($Nome != ''){
			$where .= " and (Pessoa.Nome like '%$Nome%' or Pessoa.RazaoSocial like '%$Nome%')";
		}
		
		if($NumeroDocumento != ''){
			$where .= " and ContaReceberDados.NumeroDocumento=$NumeroDocumento";
		}
		
		if($DataVencimento != ''){
			$where .= " and ContaReceberDados.DataVencimento='".dataConv($DataVencimento,'d/m/Y','Y-m-d')."'";
		}
		
		if($DataLancamento != ''){
			$where .= " and ContaReceberDados.DataLancamento='".dataConv($DataLancamento,'d/m/Y','Y-m-d')."'";
		}
		
		if($IdLocalCobranca != ''){
			$where .= " and ContaReceberDados.IdLocalCobranca=$IdLocalCobranca";
		}
		
		if($DataPagamento != ''){
			$where .= " and ContaReceberRecebimento.DataRecebimento='".dataConv($DataPagamento,'d/m/Y','Y-m-d')."'";
			$LeftJoinContaReceber = " left join ContaReceberRecebimento on (
				ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
				ContaReceberRecebimento.IdLoja = $IdLoja
			)";
		}
		
		if($IdStatus != ""){
			$adicional .= ",ContaReceberRecebimento.ValorRecebido";
			$where .= " and ContaReceberDados.IdStatus = $IdStatus 
						and ContaReceberDados.IdStatus not in (0, 7)
						and ContaReceberRecebimento.IdStatus NOT IN (3,0) and
						ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
						ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja";
			$LeftJoinContaReceber = ",ContaReceberRecebimento";
		}else{
			$where .= " and	ContaReceberDados.IdStatus not in (0,2,7)";
		}
		
		if($Limit != ""){
			$Limit = " limit $Limit";
		}
		
		if($Limit = ""){
			$Limit = " limit 60";
		}
		
		if($IdStatus != 2){
			$sql = "select
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.DataLancamento,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.ValorFinal,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.ValorJuros,
					ContaReceberDados.ValorMulta,
					ContaReceberDados.IdStatus,
					ContaReceberDados.IdlocalCobranca,
					Pessoa.IdPessoa,
					substr(Pessoa.Nome,1,30) Nome,
					substr(Pessoa.RazaoSocial,1,30) RazaoSocial
					$adicional
				from
					ContaReceberDados,
					$LeftJoinContaReceber
					Pessoa
				where
					ContaReceberDados.IdLoja = $IdLoja and
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa and 
					ContaReceberDados.IdContaReceber not in (
						select 
							ContaReceberAgrupadoItem.IdContaReceber 
						from
							ContaReceberAgrupadoItem 
						where 
							ContaReceberAgrupadoItem.IdLoja = '$IdLoja'
					)
					$where
				order by
					ContaReceberDados.IdContaReceber desc
				Limit 0,60";
			$res = @mysql_query($sql,$con);
		}else{
			$sql = "SELECT
				ContaReceberDados.IdLoja,
				ContaReceberDados.IdContaReceber,
				ContaReceberDados.NumeroDocumento,
				ContaReceberDados.DataLancamento,
				ContaReceberDados.DataVencimento,
				ContaReceberDados.ValorFinal,
				ContaReceberDados.ValorDesconto,
				ContaReceberDados.ValorJuros,
				ContaReceberDados.ValorMulta,
				ContaReceberDados.IdStatus,
				ContaReceberDados.IdlocalCobranca,
				Pessoa.IdPessoa,
				SUBSTR(Pessoa.Nome,1,30) Nome,
				SUBSTR(Pessoa.RazaoSocial,1,30) RazaoSocial,
				ContaReceberRecebimento.ValorRecebido
			FROM
				ContaReceberDados,
				ContaReceberRecebimento,
				Pessoa
			WHERE
				ContaReceberDados.IdLoja = 1 AND
				Pessoa.IdPessoa = ContaReceberDados.IdPessoa AND
				ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber AND
				ContaReceberDados.IdContaReceber NOT IN (
									SELECT 
										ContaReceberAgrupadoItem.IdContaReceber 
									FROM
										ContaReceberAgrupadoItem 
									WHERE 
										ContaReceberAgrupadoItem.IdLoja = '$IdLoja'
								) AND
				ContaReceberRecebimento.IdStatus NOT IN(0,3)
				$where
			Limit 0,60";
			
			$res = @mysql_query($sql,$con);
		}
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[ValorDesconto] == ''){
					$lin[ValorDesconto] = 0;
				}
				
				if($IdStatus == 2){
					$lin[DiaUtil] = 0;
				}
				else{
					$lin[DiaUtil] = dia_util($lin[DataVencimento]);
				}
				
				$lin[DiaUtilTemp] = dataConv($lin[DiaUtil],"Y-m-d","Ymd");
				
				if($lin[DiaUtil] != date("Y-m-d") && (int) $lin[DiaUtilTemp] < date("Ymd")){
					$lin[DiaAtraso] = diferencaDiaData($lin[DiaUtil]." 00:00:00", date("Y-m-d 00:00:00"));
					
					$sql_lc = "select 
								PercentualJurosDiarios,
								PercentualMulta,
								CobrarMultaJurosProximaFatura
							from
								LocalCobranca 
							where
								IdLocalCobranca = $lin[IdLocalCobranca]";
					$res_lc = mysql_query($sql_lc, $con);
					$lin_lc = mysql_fetch_array($res_lc);
					
					$lin[ValorMulta] = ((float)$lin[ValorFinal] * (float)$lin_lc[PercentualMulta]) / 100;
					$lin[ValorJuros] = (((float)$lin[ValorFinal] * (float)$lin_lc[PercentualJurosDiarios]) / 100) * (int)$lin[DiaAtraso];
				} else{
					$lin[DiaAtraso] = 0;
					$lin[ValorMulta] = 0.00;
					$lin[ValorJuros] = 0.00;
				}
				
				if($Status != 2){
					$sql7	=	"select 
										sum(ValorDescontoAConceber) ValorDescontoAConceber,
										min(LimiteDesconto) LimiteDesconto
								from 
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber 
								where 
										LancamentoFinanceiro.IdLoja = $IdLoja and 
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
										LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and 
										LancamentoFinanceiroContaReceber.IdContaReceber = $lin[IdContaReceber]";
					$res7	=	@mysql_query($sql7,$con);
					$lin7	=	@mysql_fetch_array($res7);
				}
				$DataLimiteDesconto = incrementaData($lin[DataVencimento],$lin7[LimiteDesconto]);
								
				$dados .= "\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
				$dados .= "\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
				$dados .= "\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
				$dados .= "\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados .= "\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
				$dados .= "\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				$dados .= "\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				$dados .= "\n<DiaAtraso><![CDATA[$lin[DiaAtraso]]]></DiaAtraso>";
				$dados .= "\n<Valor><![CDATA[".number_format($lin[ValorFinal],2,',','.')."]]></Valor>";
				$dados	.=	"\n<DataLimiteDesconto><![CDATA[".dataConv($DataLimiteDesconto,'Y-m-d','d/m/Y')."]]></DataLimiteDesconto>";
				$dados	.=	"\n<ValorDescontoAConceber><![CDATA[".number_format($lin7['ValorDescontoAConceber'],2,',','.')."]]></ValorDescontoAConceber>";
				$dados .= "\n<ValorMulta><![CDATA[$lin[ValorMulta]]]></ValorMulta>";
				$dados .= "\n<ValorJuros><![CDATA[$lin[ValorJuros]]]></ValorJuros>";
				$dados .= "\n<CobrarMultaJurosProximaFatura><![CDATA[$lin_lc[CobrarMultaJurosProximaFatura]]]></CobrarMultaJurosProximaFatura>";
				$dados .= "\n<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
				$dados .= "\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
				//$dados .= "\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";/Leonardo - 28-01-13 09:17/Nao é necessário buscar o desconto do CR pois o campo desconto é para adição de um novo desconto pelo operador do caixa!
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_ContaReceberCaixaMovimentacao();
?>