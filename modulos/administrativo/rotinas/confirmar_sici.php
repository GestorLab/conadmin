<?
	if(!permissaoSubOperacao($localModulo, $localOperacao, "P")){
		$local_Erro = 2;
	} else{
		set_time_limit(0);
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		
		$where = " AND (
				(
					ContaReceber.DataNF >= '".$local_PeriodoApuracao."-01' AND 
					ContaReceber.DataNF <= '".$local_PeriodoApuracao."-31'
				) OR (
					NotaFiscal.DataEmissao >= '".$local_PeriodoApuracao."-01' AND 
					NotaFiscal.DataEmissao <= '".$local_PeriodoApuracao."-31'
				)
			)";
		
		$sql = "UPDATE SICI SET
					Fistel											= '$local_NumeroFistel',
					IAU1											= '$local_IAU1NumeroCAT',
					IPL1TotalKMCaboPrestadora						= '$local_IPL1TotalKMCaboPrestadora',
					IPL1TotalKMCaboTerceiro							= '$local_IPL1TotalKMCaboTerceiro',
					IPL1CrescimentoPrevistoKMCaboPrestadora			= '$local_IPL1CrescimentoPrevistoKMCaboPrestadora',
					IPL1CrescimentoPrevistoKMCaboTerceiro			= '$local_IPL1CrescimentoPrevistoKMCaboTerceiro',
					IPL2TotalKMFibraPrestadora						= '$local_IPL2TotalKMFibraPrestadora',
					IPL2TotalKMFibraTerceiro						= '$local_IPL2TotalKMFibraTerceiro',
					IPL2CrescimentoPrevistoKMFibraPrestadora		= '$local_IPL2CrescimentoPrevistoKMFibraPrestadora',
					IPL2CrescimentoPrevistoKMFibraTerceiro			= '$local_IPL2CrescimentoPrevistoKMFibraTerceiro',
					IEM1Indicador									= '$local_IEM1Indicador',
					IEM1ValorTotalAplicadoEquipamento				= '$local_IEM1ValorTotalAplicadoEquipamento',
					IEM1ValorTotalAplicadoPesquisaDesenvolvimento	= '$local_IEM1ValorTotalAplicadoPesquisaDesenvolvimento',
					IEM1ValorTotalAplicadoMarketing					= '$local_IEM1ValorTotalAplicadoMarketing',
					IEM1ValorTotalAplicadoSoftware					= '$local_IEM1ValorTotalAplicadoSoftware',
					IEM1ValorTotalAplicadoServico					= '$local_IEM1ValorTotalAplicadoServico',
					IEM1ValorTotalAplicadoCentralAtendimento		= '$local_IEM1ValorTotalAplicadoCentralAtendimento',
					IEM2ValorFaturamentoServico						= '$local_IEM2ValorFaturamentoServico',
					IEM2ValorFaturamentoIndustrizalizacaoServico	= '$local_IEM2ValorFaturamentoIndustrizalizacaoServico',
					IEM2ValorFaturamentoServicoAdicional			= '$local_IEM2ValorFaturamentoServicoAdicional',
					IEM3											= '$local_IEM3ValorInvestimentoRealizado',
					IEM7											= '$local_IEM7TotalLiquido',
					IEM8ValorTotalCustos							= '$local_IEM8ValorTotalCusto',
					IEM8ValorDespesaPublicidade						= '$local_IEM8ValorDespesaPublicidade',
					IEM8ValorDespesaInterconexao					= '$local_IEM8ValorDespesaInterconexao',
					IEM8ValorDespesaOperacaoManutencao				= '$local_IEM8ValorDespesaOperacaoManutencao',
					IEM8ValorDespesaVenda							= '$local_IEM8ValorDespesaVenda',
					IdStatus										= '3',
					LoginConfirmacao								= '$local_Login', 
					DataConfirmacao									= (concat(curdate(),' ',curtime()))
				WHERE
					PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql_uf = "SELECT DISTINCT 
						Estado.IdPais,
						Estado.IdEstado
					FROM
						Servico,
						Contrato,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						ContaReceber LEFT JOIN NotaFiscal ON (
							ContaReceber.IdLoja = NotaFiscal.IdLoja AND
							ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
							NotaFiscal.Modelo = '21' AND
							NotaFiscal.IdStatus = 1 
						),
						PessoaEndereco,
						Estado 
					WHERE 
						Servico.IdTipoServico = '1' AND 
						Servico.Tecnologia IS NOT NULL AND 
						Servico.IdLoja = Contrato.IdLoja AND 
						Servico.IdServico = Contrato.IdServico AND 
						Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
						Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
						LancamentoFinanceiro.IdOrdemServico IS NULL AND
						LancamentoFinanceiro.IdContaEventual IS NULL AND
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
						LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
						(
							(
								ContaReceber.NumeroNF IS NOT NULL AND 
								ContaReceber.DataNF IS NOT NULL AND
								ContaReceber.ModeloNF = '21'
							) OR (
								NotaFiscal.IdNotaFiscal IS NOT NULL
							)
						) AND
						Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
						Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
						PessoaEndereco.IdPais = Estado.IdPais AND 
						PessoaEndereco.IdEstado = Estado.IdEstado
						".$where.";";
		$res_uf = mysql_query($sql_uf, $con);
		
		while($lin_uf = @mysql_fetch_array($res_uf)){
			$IEM4	= $_POST["IEM4_".$lin_uf[IdPais]."_".$lin_uf[IdEstado]];
			$IEM5	= $_POST["IEM5_".$lin_uf[IdPais]."_".$lin_uf[IdEstado]];
			
			$sql = "UPDATE SICIEstado SET
						IEM4	= '".$IEM4."',
						IEM5	= '".$IEM5."'
					WHERE
						PeriodoApuracao = '".$local_PeriodoApuracao."' AND
						IdPais = '".$lin_uf[IdPais]."' AND
						IdEstado = '".$lin_uf[IdEstado]."';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			
			$sql_mn = "SELECT DISTINCT
							Cidade.IdPais,
							Cidade.IdEstado,
							Cidade.IdCidade
						FROM
							Servico,
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber LEFT JOIN NotaFiscal ON (
								ContaReceber.IdLoja = NotaFiscal.IdLoja AND
								ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
								NotaFiscal.Modelo = '21' AND
								NotaFiscal.IdStatus = 1 
							),
							Pessoa,
							PessoaEndereco,
							Cidade
						WHERE 
							Servico.IdTipoServico = '1' AND 
							Servico.Tecnologia IS NOT NULL AND 
							Servico.IdLoja = Contrato.IdLoja AND 
							Servico.IdServico = Contrato.IdServico AND 
							Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
							Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
							LancamentoFinanceiro.IdOrdemServico IS NULL AND
							LancamentoFinanceiro.IdContaEventual IS NULL AND
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
							(
								(
									ContaReceber.NumeroNF IS NOT NULL AND 
									ContaReceber.DataNF IS NOT NULL AND
									ContaReceber.ModeloNF = '21'
								) OR (
									NotaFiscal.IdNotaFiscal IS NOT NULL
								)
							) AND
							Contrato.IdPessoa = Pessoa.IdPessoa AND 
							Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
							Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
							PessoaEndereco.IdPais = Cidade.IdPais AND 
							PessoaEndereco.IdEstado = Cidade.IdEstado AND 
							PessoaEndereco.IdCidade = Cidade.IdCidade AND 
							Cidade.IdPais = '".$lin_uf[IdPais]."' AND 
							Cidade.IdEstado = '".$lin_uf[IdEstado]."'
							".$where.";";
			$res_mn = mysql_query($sql_mn, $con);
			
			while($lin_mn = @mysql_fetch_array($res_mn)){
				$IPL6 = 0.00;
				$sql_tc = "SELECT 
								IdTecnologia 
							FROM 
								SICITecnologia;";
				$res_tc = mysql_query($sql_tc, $con);
				
				while($lin_tc = @mysql_fetch_array($res_tc)){
					$IPL5	= $_POST["IPL5_".$lin_mn[IdPais]."_".$lin_mn[IdEstado]."_".$lin_mn[IdCidade]."_".$lin_tc[IdTecnologia]];
					$IPL5	= (float)str_replace(",", ".", $IPL5);
					$IPL6	+= $IPL5;
					
					$sql = "UPDATE SICICidadeTecnologia SET 
								IPL5	= '".$IPL5."'
							WHERE
								PeriodoApuracao = '".$local_PeriodoApuracao."' AND
								IdPais = '".$lin_mn[IdPais]."' AND
								IdEstado = '".$lin_mn[IdEstado]."' AND
								IdCidade = '".$lin_mn[IdCidade]."' AND
								IdTecnologia = '".$lin_tc[IdTecnologia]."';";
					$local_transaction[$tr_i] = @mysql_query($sql, $con);
					$tr_i++;
				}
				
				$sql = "UPDATE SICICidade SET 
							IPL6	= '".$IPL6."'
						WHERE
							PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							IdPais = '".$lin_mn[IdPais]."' AND
							IdEstado = '".$lin_mn[IdEstado]."' AND
							IdCidade = '".$lin_mn[IdCidade]."';";
				$local_transaction[$tr_i] = @mysql_query($sql, $con);
				$tr_i++;
			}
		}
		
		if(!in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Erro = 174;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 175;
		}
		
		@mysql_query($sql,$con);
	}
?>