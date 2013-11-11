<?
	if(!permissaoSubOperacao($localModulo, $localOperacao, "C")){
		$local_Erro = 2;
	} else{
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		
		$sql = "UPDATE SICI SET
					IAU1											= (NULL),
					IPL1TotalKMCaboPrestadora						= (NULL),
					IPL1TotalKMCaboTerceiro							= (NULL),
					IPL1CrescimentoPrevistoKMCaboPrestadora			= (NULL),
					IPL1CrescimentoPrevistoKMCaboTerceiro			= (NULL),
					IPL2TotalKMFibraPrestadora						= (NULL),
					IPL2TotalKMFibraTerceiro						= (NULL),
					IPL2CrescimentoPrevistoKMFibraPrestadora		= (NULL),
					IPL2CrescimentoPrevistoKMFibraTerceiro			= (NULL),
					IEM1Indicador									= (NULL),
					IEM1ValorTotalAplicadoEquipamento				= (NULL),
					IEM1ValorTotalAplicadoPesquisaDesenvolvimento	= (NULL),
					IEM1ValorTotalAplicadoMarketing					= (NULL),
					IEM1ValorTotalAplicadoSoftware					= (NULL),
					IEM1ValorTotalAplicadoServico					= (NULL),
					IEM1ValorTotalAplicadoCentralAtendimento		= (NULL),
					IEM3											= (NULL),
					IEM6											= (NULL),
					IEM7											= (NULL),
					IEM2ValorFaturamentoServico						= (NULL),
					IEM2ValorFaturamentoIndustrizalizacaoServico	= (NULL),
					IEM2ValorFaturamentoServicoAdicional			= (NULL),
					IEM8ValorTotalCustos							= (NULL),
					IEM8ValorDespesaPublicidade						= (NULL),
					IEM8ValorDespesaInterconexao					= (NULL),
					IEM8ValorDespesaOperacaoManutencao				= (NULL),
					IEM8ValorDespesaVenda							= (NULL),
					Fistel											= (NULL),
					IdStatus										= '1',
					LoginProcessamento								= (NULL),
					DataProcessamento								= (NULL),
					LoginConfirmacao								= (NULL),
					DataConfirmacao									= (NULL)
				WHERE
					PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICICidadeTecnologiaVelocidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICICidadeTecnologia WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICICidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICIEstadoVelocidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICIEstado WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM SICILancamento WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if(!@in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Erro = 67;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 68;
		}
		
		@mysql_query($sql,$con);
	}
?>