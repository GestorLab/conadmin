<?
	set_time_limit(0);
	ini_set("memory_limit","256M");

	require("../../../../classes/fpdf/class.fpdf.php");
	require("include/class.boleto.php");
	require("include/funcoes_bnb.php");
		
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");
	
	$IdLoja 				= $_GET['IdLoja'];
	$IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$IdCarne				= $_GET['IdCarne'];

	$Path = "../../../../";
	
	$filtro_sql	= "";
	
	if($IdProcessoFinanceiro != "")
		$filtro_sql		.= "LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and ";
	
	if($IdCarne != "")
		$filtro_sql		.= "ContaReceber.IdCarne = $IdCarne and ";
	
	$i		= 0;
	$iCarne = 0;
	
	$sql = "select 
				distinct 
				ContaReceber.IdContaReceber,
				ContaReceber.IdCarne
			from 
				LancamentoFinanceiro, 
				LancamentoFinanceiroContaReceber,
				Contrato,
				Pessoa,
				ContaReceber
			where 
				LancamentoFinanceiro.IdLoja = $IdLoja and 
				LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
				LancamentoFinanceiro.IdLoja = Contrato.IdLoja and 
				LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and 
				$filtro_sql
				LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
				LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
				LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Pessoa.Cob_FormaCorreio = 'S' and
				ContaReceber.IdStatus != '0'
			order by
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.IdPessoa,
				LancamentoFinanceiroContaReceber.IdContaReceber";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		if($i == 0){
			// Inicia o boleto
			$pdf=new Boleta();

			// Processos e configurações
			$pdf->SetDisplayMode('real');
			$pdf->SetMargins(10, 5, 10);
		}
		
		$IdContaReceber = $lin[IdContaReceber];

		if($lin[IdCarne] != ''){
			$sqlCarne = "select 
					IdContaReceber
				from 
					ContaReceber
				where 
					IdLoja = $IdLoja and 
					IdCarne = $lin[IdCarne]
				order by
					IdContaReceber
				limit 0,1";
			$resCarne = mysql_query($sqlCarne,$con);
			$linCarne = mysql_fetch_array($resCarne);

			if($linCarne[IdContaReceber] == $lin[IdContaReceber]){
				$pdf->AddPage();
				$pdf->Cabecalho($IdLoja, $con);
				$pdf->DemonstrativoCarne($IdLoja, $lin[IdCarne], $con);
				$pdf->Tracejado(147.5);
				$pdf->Titulo($IdLoja, $IdContaReceber, $con);
			}else{
				if($iCarne%2 != 0){
					$pdf->AddPage();
					$posY = 3;
					$pdf->Titulo($IdLoja, $IdContaReceber, $con);
					$posY = null;
					$pdf->Tracejado(147.5);
				}else{
					$pdf->Titulo($IdLoja, $IdContaReceber, $con);
				}
			}
			
			$iCarne++;
		}else{
			$iCarne = 0;
			$pdf->AddPage();
			$pdf->Cabecalho($IdLoja, $con);
			$pdf->Demonstrativo($IdLoja, $IdContaReceber, $con);
			$pdf->Titulo($IdLoja, $IdContaReceber, $con);
		}
		
		$i++;
	}

	if($i > 0){
		$pdf->Output("Boletos_Loja-$IdLoja"."_ProcessoFinanceiro-$IdProcessoFinanceiro".".pdf","D");
	}
?>
