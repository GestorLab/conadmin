<?
	if($Background == 's'){
		include($Path."/files/conecta.php");
		include($Path."/files/funcoes.php");
		include($Path."/classes/envia_mensagem/envia_mensagem.php");
	
		ini_set("memory_limit",getParametroSistema(138,1));
	
		$IdLoja 				= $Vars[1];
		$IdProcessoFinanceiro	= $Vars[2];
		$TotalPartes			= (int)$Vars[4];
		$Parte					= $Vars[5];

		if($TotalPartes == 0){

			$LogProcessamento = date("d/m/Y H:i:s")." [automatico] - Inicio de Geração dos boletos para serem enviados ao e-mail: ".getCodigoInterno(38,2);
			
			$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
										LogProcessamento=concat('$LogProcessamento','\n',LogProcessamento)
									  WHERE 
										IdLoja=$IdLoja AND 
										IdProcessoFinanceiro=$IdProcessoFinanceiro";
			mysql_query($sqlProcessoFinanceiro,$con);
		}

	}else{		
		include("../../../../files/conecta.php");
		include("../../../../files/funcoes.php");
		
		ini_set("memory_limit",getParametroSistema(138,1));
	
		$IdLoja 				= $_GET['IdLoja'];
		$IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
		$IdCarne				= $_GET['IdCarne'];
		$IdContaEventual		= $_GET['IdContaEventual'];
		$IdOrdemServico			= $_GET['IdOrdemServico'];
		$ImprimirContaReceber	= $_GET['ImprimirContaReceber'];				
	}
	
	$pathSistema	= getParametroSistema(6,1);
	$where = "";
	
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
		
		if($ImprimirContaReceber != ""){
			switch($ImprimirContaReceber){
				case 1:
					$filtro_sql .=	" ContaReceber.IdStatus = 1 and";
					break;
				case 2:
					$filtro_sql .=	" ContaReceber.IdStatus = 2 and";
					break;
			}					
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

	if($IdOrdemServico != ''){
		$sql = "select 
					LancamentoFinanceiroContaReceber.IdContaReceber
				from
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where
					LancamentoFinanceiro.IdLoja = $IdLoja and
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiro.IdOrdemServico = $IdOrdemServico";
		$fileName	 = "OrdemServico-$IdOrdemServico";
	}

	if($IdProcessoFinanceiro != ''){
		$qtdMax = 500;
		if($TotalPartes > 0){
			$LimitIni = $qtdMax*($Parte-1);
			$sql .= " limit $LimitIni,$qtdMax";
		}

		$res = mysql_query($sql,$con);

		if($TotalPartes == 0){
			$qtd	= mysql_num_rows($res);
			$TotalPartes  = (int)($qtd/$qtdMax);
			if($qtd%$qtdMax > 0){
				$TotalPartes++;
			}
			if($qtd > $qtdMax){
				$sql .= " limit 0,$qtdMax";
				$res = mysql_query($sql,$con);
				$Parte = 1;
			}
		}

		if($Parte != ''){
			$fileName .= "_pt".$Parte."-".$TotalPartes;
		}
	}else{
		$res = mysql_query($sql,$con);
	}

	if(mysql_num_rows($res) == 0){
		header("Location: ../../cadastro_processo_financeiro.php?IdProcessoFinanceiro=$IdProcessoFinanceiro&Erro=128");
	}
	while($lin = mysql_fetch_array($res)){
		if($i == 0){
			// Inicia o boleto
			$pdf = new Boleta('P','mm','a4');

			// Processos e configurações
			$pdf->SetDisplayMode('real');
			$pdf->SetAutoPageBreak(true, 0);
		}
		
		$IdContaReceber = $lin[IdContaReceber];

		if($lin[IdCarne] != ''){
			
			$PatchAllQueryPersonalizado = $pathSistema."modulos/administrativo/local_cobranca/".$IdLocalCobrancaLayout."/pdf_all_query.php";
			
			if(file_exists($PatchAllQueryPersonalizado) == true && $IdLocalCobrancaLayout != ''){
				include($PatchAllQueryPersonalizado);
			}else{
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
					// Capa + Titulo Inferior
					$iCarne = 0;
					$pdf->AddPage();
					$pdf->Cabecalho($IdLoja, $con);
					$pdf->DemonstrativoCarne($IdLoja, $lin[IdCarne], $con);
					$pdf->Tracejado(147.5);
					$pdf->Titulo($IdLoja, $IdContaReceber, $con);
				}else{
					if($iCarne%2 != 0 || $i == 0){
						// Titulo Superior
						$pdf->AddPage();
						$posY = 3;
						$pdf->Titulo($IdLoja, $IdContaReceber, $con);
						$posY = null;
						$pdf->Tracejado(147.5);

						if($i == 0){
							$iCarne = 1;
						}
					}else{
						// Titulo Inferior
						$pdf->Titulo($IdLoja, $IdContaReceber, $con);
					}
				}
				
				$iCarne++;
			}
		}else{
			$iCarne = 0;
			$pdf->AddPage();
			$pdf->Cabecalho($IdLoja, $con);
			$pdf->Demonstrativo($IdLoja, $IdContaReceber, $con);
			$pdf->Titulo($IdLoja, $IdContaReceber, $con);
			$pdf->DemonstrativoVerso($IdLoja, $IdContaReceber, $con);

			switch($IdLocalCobrancaLayout){
				case 15:
					// Dados Remetente e Destinatário
					$pdf->AddPage();
					$pdf->RemetenteDestinario($IdLoja, $IdContaReceber, $con);
					break;
			}
		}
		
		$i++;
	}

	if($i > 0){
		if($Background == 's'){

			@mkdir($pathSistema."temp");

			if(!file_exists($pathSistema."temp/Boletos_Loja-$IdLoja"."_$fileName".".pdf")){
				$pdf->Output($pathSistema."temp/Boletos_Loja-$IdLoja"."_$fileName".".pdf","F");
			}

			if($Parte == 1){

				$Parte++;

				$sql = "select
							LocalCobranca.IdLocalCobrancaLayout
						from
							ProcessoFinanceiro,
							LocalCobranca
						where
							ProcessoFinanceiro.IdLoja = $IdLoja and
							ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and
							ProcessoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and
							ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
				
				$file="$lin[IdLocalCobrancaLayout]/pdf_all.php";
				$pathSistema	= getParametroSistema(6,1);
				$pathPHP		= getParametroSistema(6,4);

				for($iParte = $Parte; $iParte<=$TotalPartes; $iParte++){
					if(!file_exists($pathSistema."temp/Boletos_Loja-$IdLoja"."_ProcessoFinanceiro-$IdProcessoFinanceiro"."_pt$iParte"."-$TotalPartes.pdf")){
						$fileurl = $pathSistema."/modulos/administrativo/local_cobranca/".$file." $IdLoja $IdProcessoFinanceiro s $TotalPartes $iParte";
						system("$pathPHP $fileurl > $pathSistema/modulos/administrativo/local_cobranca/gerar_boletos_background.log &");
					}
				}
			}else{

				if($Parte == ''){
					$TotalPartes = '';
				}

				if($TotalPartes == '' || $TotalPartes == $Parte){

					mysql_close($con);

					sleep(240);

					include($Path."/files/conecta.php");

					// Disparo o e-mail com o link para os boletos.
					enviaLinkBoleto($IdLoja, $IdProcessoFinanceiro);
				}
			}
		}else{
			@unlink("Boletos_Loja-$IdLoja"."_$fileName".".pdf");
			$pdf->Output("Boletos_Loja-$IdLoja"."_$fileName".".pdf","D");
		}

		$sql	=	"UPDATE ProcessoFinanceiro SET IdStatusBoleto = '2' WHERE IdLoja = '$IdLoja' and IdProcessoFinanceiro = '$IdProcessoFinanceiro'";
		mysql_query($sql,$con);
	}
?>