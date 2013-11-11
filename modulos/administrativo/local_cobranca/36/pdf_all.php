<?
	set_time_limit(0);
	ini_set("memory_limit","1024M");

	require("../../../../classes/fpdf/class.fpdf.php");
	require("include/class.boleto.php");
	require("include/funcoes_bradesco.php");
		
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
	
	$i		 = 0;
	$idCarne = 0;
	
	$sql = "select 
				distinct 
				ContaReceber.IdContaReceber,
				ContaReceber.IdCarne
			from 
				LancamentoFinanceiro, 
				LancamentoFinanceiroContaReceber,
				Pessoa,
				ContaReceber
			where 
				LancamentoFinanceiro.IdLoja = $IdLoja and 
				LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and 
				LancamentoFinanceiro.IdLoja = ContaReceber.IdLoja and 
				$filtro_sql
				LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
				LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
				ContaReceber.IdPessoa = Pessoa.IdPessoa and
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
			$pdf = new Boleta('P','mm','a4');

			// Processos e configurações
			$pdf->SetDisplayMode('real');
			$pdf->SetAutoPageBreak(false, 0);
			//$pdf->SetMargins(10, 5, 10);
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
			
			switch($idCarne){
				case 0:
					$pdf->AddPage();
					$pdf->Tracejado(0.9);
					$posY = -0.5;
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					break;
				case 1:
					$pdf->Tracejado(94.3);
					$posY = 92.8;	
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					break;
				case 2:
					$pdf->Tracejado(187.2);
					$posY = 185.6;	
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					$pdf->Tracejado(280.1);
					break;
			}	
			$idCarne++;
			
			if($idCarne>2){
				$idCarne=0;
			} 
		}
		$i++;
	}

	if($i > 0){
		$pdf->Output("Boletos_Loja-$IdLoja"."_ProcessoFinanceiro-$IdProcessoFinanceiro".".pdf","D");
	}
?>
