<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$local_ValorRepasseTerceiro			= str_replace(".", "", $local_ValorRepasseTerceiro);
		$local_ValorRepasseTerceiro			= str_replace(",", ".", $local_ValorRepasseTerceiro);
		$local_ValorDescontoAConceber		= str_replace(".", "", $local_ValorDescontoAConceber);
		$local_ValorDescontoAConceber		= str_replace(",", ".", $local_ValorDescontoAConceber);
		$local_DataReferenciaInicialTemp	= dataConv($local_DataReferenciaInicial, "d/m/Y", "Y-m-d");
		$local_DataReferenciaFinalTemp		= dataConv($local_DataReferenciaFinal, "d/m/Y", "Y-m-d");
		$local_ObsLancamentoFinanceiro		= '';
		
		if($local_DataReferenciaInicialTemp <= $local_DataReferenciaFinalTemp){
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			
			$tr_i = 0;
			
			$sql	= "SELECT 
							ObsLancamentoFinanceiro,
							ValorRepasseTerceiro,
							ValorDescontoAConceber,
							DataReferenciaInicial,
							DataReferenciaFinal
						FROM 
							LancamentoFinanceiro 
						WHERE 
							IdLoja = '$local_IdLoja' AND 
							IdLancamentoFinanceiro = '$local_IdLancamentoFinanceiro';";
			$res	= mysql_query($sql,$con);
			$lin	= mysql_fetch_array($res);
			
			if($lin[ValorRepasseTerceiro] != $local_ValorRepasseTerceiro){
				$local_ObsLancamentoFinanceiro	.= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Valor Rep.Terceiro [$lin[ValorRepasseTerceiro] > $local_ValorRepasseTerceiro]\n";
			}
			
			if($lin[ValorDescontoAConceber] != $local_ValorDescontoAConceber){
				$local_ObsLancamentoFinanceiro	.= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Desc. a Conceber  [$lin[ValorDescontoAConceber] > $local_ValorDescontoAConceber]\n";
			}
			
			if($lin[DataReferenciaInicial] != $local_DataReferenciaInicialTemp){
				$local_ObsLancamentoFinanceiro	.= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Data Ref. Inicial  [".dataConv($lin[DataReferenciaInicial], "Y-m-d", "d/m/Y")." > $local_DataReferenciaInicial]\n";
			}
			
			if($lin[DataReferenciaFinal] != $local_DataReferenciaFinalTemp){
				$local_ObsLancamentoFinanceiro	.= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Data Ref. Final  [".dataConv($lin[DataReferenciaFinal], "Y-m-d", "d/m/Y")." > $local_DataReferenciaFinal]\n";
			}
				
			if($lin[ObsLancamentoFinanceiro] != ""){
				$local_ObsLancamentoFinanceiro	.= trim($lin[ObsLancamentoFinanceiro]);
			}
			
			if($local_DataReferenciaInicialTemp != ''){
				$local_DataReferenciaInicialTemp = "'".$local_DataReferenciaInicialTemp."'";
			} else{
				$local_DataReferenciaInicialTemp = "(NULL)";
			}
			
			if($local_DataReferenciaFinalTemp != ''){
				$local_DataReferenciaFinalTemp = "'".$local_DataReferenciaFinalTemp."'";
			} else{
				$local_DataReferenciaFinalTemp = "(NULL)";
			}
			
			$sql	=	"
					UPDATE LancamentoFinanceiro SET 
						ValorRepasseTerceiro		= '$local_ValorRepasseTerceiro',
						ValorDescontoAConceber		= '$local_ValorDescontoAConceber',
						ObsLancamentoFinanceiro		= '$local_ObsLancamentoFinanceiro',
						DataReferenciaInicial		=  $local_DataReferenciaInicialTemp,
						DataReferenciaFinal			=  $local_DataReferenciaFinalTemp
					WHERE 
						IdLoja						= $local_IdLoja and
						IdLancamentoFinanceiro		= $local_IdLancamentoFinanceiro;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			// Executa a Sql de Inserção de Codigo Interno
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$local_Erro = 4;		// Mensagem de Alteração Positiva
				$sql = "COMMIT;";
			}else{
				$local_Erro = 5;		// Mensagem de Alteração Negativa
				$sql = "ROLLBACK;";
			}
			
			mysql_query($sql,$con);
		} else{
			$local_Erro = 139;
		}
	}
?>
