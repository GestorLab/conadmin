<?
	set_time_limit(0);
	ini_set("memory_limit","512M");

	if($Background == 's'){
		include($Path."/files/conecta.php");
		include($Path."/files/funcoes.php");
		include($Path."/files/envia_email.php");
	
		$IdLoja 				= $Vars[1];
		$IdProcessoFinanceiro	= $Vars[2];

		$LogProcessamento = date("d/m/Y H:i:s")." [automatico] - Inicio de Geração dos boletos para serem enviados ao e-mail: ".getCodigoInterno(17,2);
		
		$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
									LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
								  WHERE 
									IdLoja=$IdLoja AND 
									IdProcessoFinanceiro=$IdProcessoFinanceiro";
		mysql_query($sqlProcessoFinanceiro,$con);

	}else{		
		include("../../../../files/conecta.php");
		//include("../../../../files/funcoes.php");
	
		$IdLoja 				= $_GET['IdLoja'];
		$IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
		$IdCarne				= $_GET['IdCarne'];
		$IdContaEventual		= $_GET['IdContaEventual'];
	}

	if($IdProcessoFinanceiro != '' || $IdCarne != ''){
		$filtro_sql	= "";

		if($IdProcessoFinanceiro != ""){
			$filtro_sql	.= "LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and ";
			$fileName	 = "ProcessoFinanceiro-$IdProcessoFinanceiro";
		}
		
		if($IdCarne != ""){
			$filtro_sql	.= "ContaReceber.IdCarne = $IdCarne and ";
			$fileName	 = "Carne-$IdCarne";
		}
		
		$i		= 0;
		$iCarne = 0;

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
	}

	if($IdContaEventual != ''){
		$sql = "select 
					LancamentoFinanceiroContaReceber.IdContaReceber
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiro.IdContaEventual = $IdContaEventual";
		$fileName	 = "ContaEventual-$IdContaEventual";
	}
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res) == 0){
		header("Location: ../../cadastro_processo_financeiro.php?IdProcessoFinanceiro=$IdProcessoFinanceiro&Erro=128");
	}
	while($lin = mysql_fetch_array($res)){
		if($i == 0){
			// Inicia o boleto			
			$pdf = new Boleta('P','mm','a4');
			// Processos e configurações
			$pdf->SetDisplayMode('real');
			$pdf->SetAutoPageBreak(false, 0);
			
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
					$pdf->Tracejado(1);
					$posY = null;
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					break;
				case 1:
					$pdf->Tracejado(98.8);
					$posY = 98.9;	
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					break;
				case 2:
					$pdf->Tracejado(197.6);
					$posY = 197.7;	
					$pdf->TituloCarne($IdLoja, $IdContaReceber, $con);
					$pdf->Tracejado(296.4);
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
		if($Background == 's'){
			$pathSistema	= getParametroSistema(6,1);

			$pdf->Output($pathSistema."/temp/Boletos_Loja-$IdLoja"."_$fileName".".pdf","F");

			// Disparo o e-mail com o link para os boletos.
			email_LinkBoleto($IdLoja, $IdProcessoFinanceiro);
		}else{
			$pdf->Output("Boletos_Loja-$IdLoja"."_$fileName".".pdf","D");
		}

		$sql	=	"UPDATE ProcessoFinanceiro SET IdStatusBoleto = '2' WHERE IdLoja = '$IdLoja' and IdProcessoFinanceiro = '$IdProcessoFinanceiro'";
		mysql_query($sql,$con);
	}
?>
