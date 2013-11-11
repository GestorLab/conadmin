<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	} else{
		set_time_limit(0);
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "SELECT 
					NotaFiscalLayout.IdNotaFiscalLayout,
					NotaFiscalLayout.Modelo
				FROM
					NotaFiscalLayout,
					NotaFiscalTipo 
				WHERE 
					NotaFiscalLayout.IdNotaFiscalLayout = '$local_ModeloNF' AND 
					NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout AND
					NotaFiscalTipo.IdStatus = 1";
		$res = mysql_query($sql, $con);
		$linNF = mysql_fetch_array($res);
		$NotaFiscal = (mysql_num_rows($res) > 0);
		$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		
		if($NotaFiscal){
			$where = " AND 
				NotaFiscal.DataEmissao >= '".$local_PeriodoApuracao."-01' AND 
				NotaFiscal.DataEmissao <= '".$local_PeriodoApuracao."-31'
			";
		} else{
			$where = " AND (
				(
					ContaReceber.DataNF >= '".$local_PeriodoApuracao."-01' AND 
					ContaReceber.DataNF <= '".$local_PeriodoApuracao."-31'
				) OR (
					NotaFiscal.DataEmissao >= '".$local_PeriodoApuracao."-01' AND 
					NotaFiscal.DataEmissao <= '".$local_PeriodoApuracao."-31'
				)
			)";
		}
		# IEM 6 | IEM 7
		if($NotaFiscal) {
			$sql_vb .= "SELECT 
							SUM(Contrato.Valor) Valor
						FROM
							(SELECT
								NotaFiscal.ValorBaseCalculoICMS Valor
							FROM
								Servico,
								Contrato,
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber,
								ContaReceber,
								NotaFiscal
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
								ContaReceber.IdLoja = NotaFiscal.IdLoja AND
								ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
								NotaFiscal.Modelo = '$linNF[Modelo]' AND
								NotaFiscal.IdStatus = '1'
								$where
							GROUP BY
								NotaFiscal.IdContaReceber
							) Contrato;";
		} else{
			$sql_vb .= "SELECT 
							SUM(Contrato.Valor) Valor
						FROM
							(SELECT
								(LancamentoFinanceiro.Valor - LancamentoFinanceiro.ValorDescontoAConceber) Valor
							FROM
								Servico,
								Contrato,
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber,
								ContaReceber LEFT JOIN NotaFiscal ON (
									ContaReceber.IdLoja = NotaFiscal.IdLoja AND
									ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
									NotaFiscal.Modelo = '$linNF[Modelo]' 
								)
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
										ContaReceber.NumeroNF != '' AND
										ContaReceber.DataNF != '' AND
										ContaReceber.ModeloNF = '$linNF[Modelo]' AND
										NotaFiscal.IdNotaFiscal = ''
									) OR (
										NotaFiscal.IdNotaFiscal != '' AND
										NotaFiscal.IdStatus = '1'
									)
								)
								$where
							) Contrato;";
		}
		
		$res_vb = mysql_query($sql_vb, $con);
		$lin_vb = @mysql_fetch_array($res_vb);
		$lin_vb[Valor] = number_format($lin_vb[Valor] , 2, '.', '');
		
		$sql = "UPDATE SICI SET
					IEM6				= '$lin_vb[Valor]',
					IEM7				= '$lin_vb[Valor]',
					IdStatus			= '2',
					LoginProcessamento	= '$local_Login',
					DataProcessamento	= (concat(curdate(),' ',curtime()))
				WHERE
					PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if($NotaFiscal) {
			$sql_uf = "SELECT DISTINCT 
							Estado.IdPais,
							Estado.IdEstado
						FROM
							Servico,
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber,
							NotaFiscal,
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
							ContaReceber.IdLoja = NotaFiscal.IdLoja AND
							ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
							NotaFiscal.Modelo = '$linNF[Modelo]' AND
							NotaFiscal.IdStatus = '1' AND
							Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
							Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
							PessoaEndereco.IdPais = Estado.IdPais AND 
							PessoaEndereco.IdEstado = Estado.IdEstado
							$where;";
		} else{
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
								NotaFiscal.Modelo = '$linNF[Modelo]' 
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
									ContaReceber.NumeroNF != '' AND 
									ContaReceber.DataNF != '' AND
									ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
									NotaFiscal.IdNotaFiscal = ''
								) OR (
									NotaFiscal.IdNotaFiscal != '' AND
									NotaFiscal.IdStatus = '1' 
								)
							) AND
							Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
							Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
							PessoaEndereco.IdPais = Estado.IdPais AND 
							PessoaEndereco.IdEstado = Estado.IdEstado
							$where;";
		}
		
		$res_uf = mysql_query($sql_uf, $con);
		
		while($lin_uf = @mysql_fetch_array($res_uf)){
			# IEM 10
			$sql = "INSERT INTO SICIEstado SET
						PeriodoApuracao	= '$local_PeriodoApuracao',
						IdPais			= '$lin_uf[IdPais]',
						IdEstado		= '$lin_uf[IdEstado]'";
			
			for($IdTipoPessoa = 1; $IdTipoPessoa < 3; $IdTipoPessoa++){
				# BUSCAR O MENOR E O MAIOR VALOR POR 1 MEGA DEDICADO
				if($NotaFiscal) {
					$sql_dd = "SELECT 
									MIN(Contrato.Valor/Contrato.FatorMega) MinValor,
									MAX(Contrato.Valor/Contrato.FatorMega) MaxValor
								FROM
									(SELECT
										ContratoVigenciaAtiva.Valor,
										Servico.FatorMega
									FROM
										Servico,
										Contrato,
										ContratoVigenciaAtiva,
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber,
										ContaReceber,
										NotaFiscal,
										Pessoa, 
										PessoaEndereco
									WHERE 
										Servico.IdTipoServico = '1' AND 
										Servico.Dedicado = '1' AND
										Servico.Tecnologia IS NOT NULL AND 
										Servico.IdLoja = Contrato.IdLoja AND 
										Servico.IdServico = Contrato.IdServico AND 
										Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja AND 
										Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato AND
										Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
										Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
										LancamentoFinanceiro.IdOrdemServico IS NULL AND
										LancamentoFinanceiro.IdContaEventual IS NULL AND
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
										LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
										LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
										ContaReceber.IdLoja = NotaFiscal.IdLoja AND
										ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
										NotaFiscal.Modelo = '$linNF[Modelo]' AND
										NotaFiscal.IdStatus = '1' AND 
										Contrato.IdPessoa = Pessoa.IdPessoa AND 
										Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
										Contrato.IdPessoa = PessoaEndereco.IdPessoa AND
										Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
										PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
										PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
										$where
									GROUP BY
										Contrato.IdLoja,
										Contrato.IdContrato
									) Contrato;";
				} else{
					$sql_dd = "SELECT 
									MIN(Contrato.Valor/Contrato.FatorMega) MinValor,
									MAX(Contrato.Valor/Contrato.FatorMega) MaxValor
								FROM
									(SELECT
										ContratoVigenciaAtiva.Valor,
										Servico.FatorMega
									FROM
										Servico,
										Contrato,
										ContratoVigenciaAtiva,
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber,
										ContaReceber LEFT JOIN NotaFiscal ON (
											ContaReceber.IdLoja = NotaFiscal.IdLoja AND
											ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
											NotaFiscal.Modelo = '$linNF[Modelo]' 
										),
										Pessoa, 
										PessoaEndereco
									WHERE 
										Servico.IdTipoServico = '1' AND 
										Servico.Dedicado = '1' AND
										Servico.Tecnologia IS NOT NULL AND 
										Servico.IdLoja = Contrato.IdLoja AND 
										Servico.IdServico = Contrato.IdServico AND 
										Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja AND 
										Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato AND
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
												ContaReceber.NumeroNF != '' AND 
												ContaReceber.DataNF != '' AND
												ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
												NotaFiscal.IdNotaFiscal = ''
											) OR (
												NotaFiscal.IdNotaFiscal != '' AND
												NotaFiscal.IdStatus = '1' 
											)
										) AND 
										Contrato.IdPessoa = Pessoa.IdPessoa AND 
										Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
										Contrato.IdPessoa = PessoaEndereco.IdPessoa AND
										Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
										PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
										PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
										$where
									GROUP BY
										Contrato.IdLoja,
										Contrato.IdContrato
									) Contrato;";
				}
				
				$res_dd = @mysql_query($sql_dd, $con);
				$lin_dd = @mysql_fetch_array($res_dd);
				
				$lin_dd[MinValor] = number_format($lin_dd[MinValor], 2, '.', '');
				$lin_dd[MaxValor] = number_format($lin_dd[MaxValor], 2, '.', '');
				# BUSCAR O MENOR E O MAIOR VALOR POR 1 MEGA N�O DEDICADO
				if($NotaFiscal) {
					$sql_nd = "SELECT 
									MIN(Contrato.Valor/Contrato.FatorMega) MinValor,
									MAX(Contrato.Valor/Contrato.FatorMega) MaxValor
								FROM
									(SELECT
										ContratoVigenciaAtiva.Valor,
										Servico.FatorMega
									FROM
										Servico,
										Contrato,
										ContratoVigenciaAtiva,
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber,
										ContaReceber,
										NotaFiscal,
										Pessoa, 
										PessoaEndereco
									WHERE 
										Servico.IdTipoServico = '1' AND 
										Servico.Dedicado = '2' AND
										Servico.Tecnologia IS NOT NULL AND 
										Servico.IdLoja = Contrato.IdLoja AND 
										Servico.IdServico = Contrato.IdServico AND 
										Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja AND 
										Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato AND
										Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
										Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
										LancamentoFinanceiro.IdOrdemServico IS NULL AND
										LancamentoFinanceiro.IdContaEventual IS NULL AND
										LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
										LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
										LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
										LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
										ContaReceber.IdLoja = NotaFiscal.IdLoja AND
										ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
										NotaFiscal.Modelo = '$linNF[Modelo]' AND 
										NotaFiscal.IdStatus = '1' AND 
										Contrato.IdPessoa = Pessoa.IdPessoa AND 
										Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
										Contrato.IdPessoa = PessoaEndereco.IdPessoa AND
										Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
										PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
										PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
										$where
									GROUP BY
										Contrato.IdLoja,
										Contrato.IdContrato
									) Contrato;";
				} else{
					$sql_nd = "SELECT 
									MIN(Contrato.Valor/Contrato.FatorMega) MinValor,
									MAX(Contrato.Valor/Contrato.FatorMega) MaxValor
								FROM
									(SELECT
										ContratoVigenciaAtiva.Valor,
										Servico.FatorMega
									FROM
										Servico,
										Contrato,
										ContratoVigenciaAtiva,
										LancamentoFinanceiro,
										LancamentoFinanceiroContaReceber,
										ContaReceber LEFT JOIN NotaFiscal ON (
											ContaReceber.IdLoja = NotaFiscal.IdLoja AND
											ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
											NotaFiscal.Modelo = '$linNF[Modelo]' 
										),
										Pessoa, 
										PessoaEndereco
									WHERE 
										Servico.IdTipoServico = '1' AND 
										Servico.Dedicado = '2' AND
										Servico.Tecnologia IS NOT NULL AND 
										Servico.IdLoja = Contrato.IdLoja AND 
										Servico.IdServico = Contrato.IdServico AND 
										Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja AND 
										Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato AND
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
												ContaReceber.NumeroNF != '' AND 
												ContaReceber.DataNF != '' AND
												ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
												NotaFiscal.IdNotaFiscal = ''
											) OR (
												NotaFiscal.IdNotaFiscal != '' AND
												NotaFiscal.IdStatus = '1' 
											)
										) AND
										Contrato.IdPessoa = Pessoa.IdPessoa AND 
										Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
										Contrato.IdPessoa = PessoaEndereco.IdPessoa AND
										Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
										PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
										PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
										$where
									GROUP BY
										Contrato.IdLoja,
										Contrato.IdContrato
									) Contrato;";
				}
				
				$res_nd = @mysql_query($sql_nd, $con);
				$lin_nd = @mysql_fetch_array($res_nd);
				
				$lin_nd[MinValor] = number_format($lin_nd[MinValor], 2, '.', '');
				$lin_nd[MaxValor] = number_format($lin_nd[MaxValor], 2, '.', '');
				
				if($IdTipoPessoa % 2 != 0){
					$sql .= ", 
						IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica	= '$lin_nd[MinValor]',
						IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica	= '$lin_nd[MaxValor]',
						IEM10MenorPreco1MbpsDedicadoPessoaJuridica		= '$lin_dd[MinValor]',
						IEM10MaiorPreco1MbpsDedicadoPessoaJuridica		= '$lin_dd[MaxValor]'";
				} else{
					$sql .= ", 
						IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica	= '$lin_nd[MinValor]',
						IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica	= '$lin_nd[MaxValor]',
						IEM10MenorPreco1MbpsDedicadoPessoaFisica	= '$lin_dd[MinValor]',
						IEM10MaiorPreco1MbpsDedicadoPessoaFisica	= '$lin_dd[MaxValor]'";
				}
			}
			
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			# IEM 9
			$sql_gv = "SELECT 
							IdVelocidade
						FROM
							SICIVelocidade;";
			$res_gv = @mysql_query($sql_gv, $con);
			
			while($lin_gv = @mysql_fetch_array($res_gv)) {
				$sql = "INSERT INTO SICIEstadoVelocidade SET 
							PeriodoApuracao	= '$local_PeriodoApuracao',
							IdPais			= '$lin_uf[IdPais]',
							IdEstado		= '$lin_uf[IdEstado]',
							IdVelocidade	= '$lin_gv[IdVelocidade]'";
				
				for($IdTipoPessoa = 1; $IdTipoPessoa < 3; $IdTipoPessoa++){
					if($NotaFiscal) {
						$sql_te = "SELECT 
										(SUM(Contrato.Valor) / COUNT(Contrato.Valor)) Valor
									FROM
										(SELECT
											(LancamentoFinanceiro.Valor - LancamentoFinanceiro.ValorDescontoAConceber) Valor
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber,
											NotaFiscal,
											Pessoa,
											PessoaEndereco
										WHERE 
											Servico.GrupoVelocidade = '".$lin_gv[IdVelocidade]."' AND
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
											ContaReceber.IdLoja = NotaFiscal.IdLoja AND
											ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
											NotaFiscal.Modelo = '$linNF[Modelo]' AND
											NotaFiscal.IdStatus = '1' AND 
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
											$where
										GROUP BY
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					} else{
						$sql_te = "SELECT 
										(SUM(Contrato.Valor) / COUNT(Contrato.Valor)) Valor
									FROM
										(SELECT
											(LancamentoFinanceiro.Valor - LancamentoFinanceiro.ValorDescontoAConceber) Valor
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber LEFT JOIN NotaFiscal ON (
												ContaReceber.IdLoja = NotaFiscal.IdLoja AND
												ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
												NotaFiscal.Modelo = '$linNF[Modelo]' 
											),
											Pessoa,
											PessoaEndereco
										WHERE 
											Servico.GrupoVelocidade = '".$lin_gv[IdVelocidade]."' AND
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
													ContaReceber.NumeroNF != '' AND
													ContaReceber.DataNF != '' AND
													ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
													NotaFiscal.IdNotaFiscal = ''
												) OR (
													NotaFiscal.IdNotaFiscal != '' AND
													NotaFiscal.IdStatus = '1' 
												)
											) AND
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_uf[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_uf[IdEstado]."'
											$where
										GROUP BY
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					}
					
					$res_te = mysql_query($sql_te, $con);
					$lin_te = @mysql_fetch_array($res_te);
					
					$lin_te[Valor] = number_format($lin_te[Valor], 2, '.', '');
					
					if($IdTipoPessoa % 2 != 0){
						$sql .= ", IEM9PessoaJuridica = '$lin_te[Valor]'";
					} else{
						$sql .= ", IEM9PessoaFisica = '$lin_te[Valor]'";
					}
				}
				
				$local_transaction[$tr_i] = @mysql_query($sql, $con);
				$tr_i++;
			}
			
			if($NotaFiscal) {
				$sql_mn = "SELECT DISTINCT
								Cidade.IdPais,
								Cidade.IdEstado,
								Cidade.IdCidade
							FROM
								Servico,
								Contrato,
								LancamentoFinanceiro,
								LancamentoFinanceiroContaReceber,
								ContaReceber,
								NotaFiscal,
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
								ContaReceber.IdLoja = NotaFiscal.IdLoja AND
								ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
								NotaFiscal.Modelo = '$linNF[Modelo]' AND
								NotaFiscal.IdStatus = '1' AND 
								Contrato.IdPessoa = Pessoa.IdPessoa AND 
								Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
								Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
								PessoaEndereco.IdPais = Cidade.IdPais AND 
								PessoaEndereco.IdEstado = Cidade.IdEstado AND 
								PessoaEndereco.IdCidade = Cidade.IdCidade AND 
								Cidade.IdPais = '$lin_uf[IdPais]' AND 
								Cidade.IdEstado = '$lin_uf[IdEstado]'
								$where;";
			} else{
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
									NotaFiscal.Modelo = '$linNF[Modelo]'  
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
										ContaReceber.NumeroNF != '' AND 
										ContaReceber.DataNF != '' AND
										ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
										NotaFiscal.IdNotaFiscal = '' 
									) OR (
										NotaFiscal.IdNotaFiscal != '' AND
										NotaFiscal.IdStatus = '1'
									)
								) AND
								Contrato.IdPessoa = Pessoa.IdPessoa AND 
								Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
								Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND 
								PessoaEndereco.IdPais = Cidade.IdPais AND 
								PessoaEndereco.IdEstado = Cidade.IdEstado AND 
								PessoaEndereco.IdCidade = Cidade.IdCidade AND 
								Cidade.IdPais = '$lin_uf[IdPais]' AND 
								Cidade.IdEstado = '$lin_uf[IdEstado]'
								$where;";
			}
			
			$res_mn = mysql_query($sql_mn, $con);
			
			while($lin_mn = @mysql_fetch_array($res_mn)){
				# IPL 3
				$sql = "INSERT INTO SICICidade SET
							PeriodoApuracao	= '$local_PeriodoApuracao',
							IdPais			= '$lin_mn[IdPais]',
							IdEstado		= '$lin_mn[IdEstado]',
							IdCidade		= '$lin_mn[IdCidade]'";
				
				for($IdTipoPessoa = 1; $IdTipoPessoa < 3; $IdTipoPessoa++){
					# BUSCAR A QUANTIDADE TOTAL DE CONTRATOS DIFERENTE DE CANCELADO 
					if($NotaFiscal) {
						$sql_dc = "SELECT 
										COUNT(*) QTD
									FROM
										(SELECT
											Contrato.IdContrato
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber,
											NotaFiscal,
											Pessoa,
											PessoaEndereco
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
											ContaReceber.IdLoja = NotaFiscal.IdLoja AND
											ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND
											NotaFiscal.Modelo = '$linNF[Modelo]' AND 
											NotaFiscal.IdStatus = '1' AND 
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
											PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
											$where
										GROUP BY 
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					} else{
						$sql_dc = "SELECT 
										COUNT(*) QTD
									FROM
										(SELECT
											Contrato.IdContrato
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber LEFT JOIN NotaFiscal ON (
												ContaReceber.IdLoja = NotaFiscal.IdLoja AND
												ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND
												NotaFiscal.Modelo = '$linNF[Modelo]' 
											),
											Pessoa,
											PessoaEndereco
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
													ContaReceber.NumeroNF != '' AND 
													ContaReceber.DataNF != '' AND
													ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
													NotaFiscal.IdNotaFiscal = '' 
												) OR (
													NotaFiscal.IdNotaFiscal != '' AND
													NotaFiscal.IdStatus = '1' 
												)
											) AND 
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Pessoa.TipoPessoa = '".$IdTipoPessoa."' AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
											PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
											$where
										GROUP BY 
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					}
					
					$res_dc = mysql_query($sql_dc, $con);
					$lin_dc = @mysql_fetch_array($res_dc);
					
					if($lin_dc[QTD] == '') {
						$lin_dc[QTD] = 0;
					}
					
					if($IdTipoPessoa % 2 != 0){
						$sql .= ", IPL3PessoaJuridica = '$lin_dc[QTD]'";
					} else{
						$sql .= ", IPL3PessoaFisica = '$lin_dc[QTD]'";
					}
				}
				
				$local_transaction[$tr_i] = @mysql_query($sql, $con);
				$tr_i++;
				# IPL 4
				$sql_tc = "SELECT 
								IdTecnologia
							FROM
								SICITecnologia;";
				$res_tc = mysql_query($sql_tc, $con);
				
				while($lin_tc = @mysql_fetch_array($res_tc)){
					if($NotaFiscal) {
						$sql_dc = "SELECT 
										COUNT(*) QTD
									FROM
										(SELECT
											Contrato.IdContrato
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber,
											NotaFiscal,
											Pessoa,
											PessoaEndereco
										WHERE 
											Servico.IdTipoServico = '1' AND 
											Servico.Tecnologia = '".$lin_tc[IdTecnologia]."' AND 
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
											ContaReceber.IdLoja = NotaFiscal.IdLoja AND
											ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
											NotaFiscal.Modelo = '$linNF[Modelo]' AND 
											NotaFiscal.IdStatus = '1' AND 
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
											PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
											$where
										GROUP BY
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					} else{
						$sql_dc = "SELECT 
										COUNT(*) QTD
									FROM
										(SELECT
											Contrato.IdContrato
										FROM
											Servico,
											Contrato,
											LancamentoFinanceiro,
											LancamentoFinanceiroContaReceber,
											ContaReceber LEFT JOIN NotaFiscal ON (
												ContaReceber.IdLoja = NotaFiscal.IdLoja AND
												ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
												NotaFiscal.Modelo = '$linNF[Modelo]' 
											),
											Pessoa,
											PessoaEndereco
										WHERE 
											Servico.IdTipoServico = '1' AND 
											Servico.Tecnologia = '".$lin_tc[IdTecnologia]."' AND 
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
													ContaReceber.NumeroNF != '' AND 
													ContaReceber.DataNF != '' AND
													ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
													NotaFiscal.IdNotaFiscal = '' 
												) OR (
													NotaFiscal.IdNotaFiscal != '' AND
													NotaFiscal.IdStatus = '1' 
												)
											) AND 
											Contrato.IdPessoa = Pessoa.IdPessoa AND 
											Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
											Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
											PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
											PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
											PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
											$where
										GROUP BY
											Contrato.IdLoja,
											Contrato.IdContrato
										) Contrato;";
					}
					
					$res_dc = mysql_query($sql_dc, $con);
					$lin_dc = @mysql_fetch_array($res_dc);
					
					if($lin_dc[QTD] == '') {
						$lin_dc[QTD] = 0;
					}
					
					$sql = "INSERT INTO SICICidadeTecnologia SET
							PeriodoApuracao		= '$local_PeriodoApuracao',
							IdPais				= '$lin_mn[IdPais]',
							IdEstado			= '$lin_mn[IdEstado]',
							IdCidade			= '$lin_mn[IdCidade]',
							IdTecnologia		= '$lin_tc[IdTecnologia]',
							IPL4TotalAcessos	= '$lin_dc[QTD]'";
					$local_transaction[$tr_i] = @mysql_query($sql, $con);
					$tr_i++;
					
					$sql_gv = "SELECT 
									IdVelocidade
								FROM
									SICIVelocidade;";
					$res_gv = mysql_query($sql_gv, $con);
					
					while($lin_gv = @mysql_fetch_array($res_gv)){
						if($NotaFiscal) {
							$sql_cg = "SELECT 
											COUNT(*) QTD
										FROM
											(SELECT 
												Contrato.IdContrato
											FROM
												Servico,
												Contrato,
												LancamentoFinanceiro,
												LancamentoFinanceiroContaReceber,
												ContaReceber,
												NotaFiscal,
												Pessoa,
												PessoaEndereco
											WHERE 
												Servico.IdTipoServico = '1' AND 
												Servico.Tecnologia = '".$lin_tc[IdTecnologia]."' AND 
												Servico.GrupoVelocidade = '".$lin_gv[IdVelocidade]."' AND
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
												ContaReceber.IdLoja = NotaFiscal.IdLoja AND
												ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
												NotaFiscal.Modelo = '$linNF[Modelo]' AND 
												NotaFiscal.IdStatus = '1' AND 
												Contrato.IdPessoa = Pessoa.IdPessoa AND 
												Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
												Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
												PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
												PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
												PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
												$where
											GROUP BY
												Contrato.IdLoja,
												Contrato.IdContrato
											) Contrato;";
						} else{
							$sql_cg = "SELECT 
											COUNT(*) QTD
										FROM
											(SELECT 
												Contrato.IdContrato
											FROM
												Servico,
												Contrato,
												LancamentoFinanceiro,
												LancamentoFinanceiroContaReceber,
												ContaReceber LEFT JOIN NotaFiscal ON (
													ContaReceber.IdLoja = NotaFiscal.IdLoja AND
													ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
													NotaFiscal.Modelo = '$linNF[Modelo]' 
												),
												Pessoa,
												PessoaEndereco
											WHERE 
												Servico.IdTipoServico = '1' AND 
												Servico.Tecnologia = '".$lin_tc[IdTecnologia]."' AND 
												Servico.GrupoVelocidade = '".$lin_gv[IdVelocidade]."' AND
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
														ContaReceber.NumeroNF != '' AND 
														ContaReceber.DataNF != '' AND
														ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
														NotaFiscal.IdNotaFiscal = ''
													) OR (
														NotaFiscal.IdNotaFiscal != '' AND
														NotaFiscal.IdStatus = '1' 
													)
												) AND 
												Contrato.IdPessoa = Pessoa.IdPessoa AND 
												Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
												Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
												PessoaEndereco.IdPais = '".$lin_mn[IdPais]."' AND
												PessoaEndereco.IdEstado = '".$lin_mn[IdEstado]."' AND
												PessoaEndereco.IdCidade = '".$lin_mn[IdCidade]."'
												$where
											GROUP BY
												Contrato.IdLoja,
												Contrato.IdContrato
											) Contrato;";
						}
						
						$res_cg = mysql_query($sql_cg, $con);
						$lin_cg = @mysql_fetch_array($res_cg);
						
						if($lin_cg[QTD] == '') {
							$lin_cg[QTD] = 0;
						}
						
						$sql = "INSERT INTO SICICidadeTecnologiaVelocidade SET 
									PeriodoApuracao	= '$local_PeriodoApuracao',
									IdPais			= '$lin_mn[IdPais]',
									IdEstado		= '$lin_mn[IdEstado]',
									IdCidade		= '$lin_mn[IdCidade]',
									IdTecnologia	= '$lin_tc[IdTecnologia]',
									IdVelocidade	= '$lin_gv[IdVelocidade]',
									IPL4			= '$lin_cg[QTD]'";
						$local_transaction[$tr_i] = @mysql_query($sql, $con);
						$tr_i++;
					}
				}
			}
		}
		
		if($NotaFiscal) {
			$sql_lan = "SELECT 
							Servico.IdLoja,
							LancamentoFinanceiro.IdLancamentoFinanceiro
						FROM
							Servico,
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber,
							NotaFiscal 
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
							ContaReceber.IdLoja = NotaFiscal.IdLoja AND 
							ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
							NotaFiscal.Modelo = '$linNF[Modelo]' AND 
							NotaFiscal.IdStatus = '1' 
							".$where.";";
		} else{
			$sql_lan = "SELECT 
							Servico.IdLoja,
							LancamentoFinanceiro.IdLancamentoFinanceiro
						FROM
							Servico,
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber LEFT JOIN NotaFiscal ON (
								ContaReceber.IdLoja = NotaFiscal.IdLoja AND 
								ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
								NotaFiscal.Modelo = '$linNF[Modelo]' 
							) 
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
									ContaReceber.NumeroNF != '' AND 
									ContaReceber.DataNF != '' AND 
									ContaReceber.ModeloNF = '$linNF[Modelo]' AND 
									NotaFiscal.IdNotaFiscal = '' 
								) OR (
									NotaFiscal.IdNotaFiscal != '' AND 
									NotaFiscal.IdStatus = '1'
								)
							)
							".$where.";";
		}
		
		$res_lan = mysql_query($sql_lan, $con);
		
		while($lin_lan = mysql_fetch_array($res_lan)){
			$sql = "INSERT INTO SICILancamento SET 
						PeriodoApuracao			= '$local_PeriodoApuracao',
						IdLoja					= '$lin_lan[IdLoja]',
						IdLancamentoFinanceiro	= '$lin_lan[IdLancamentoFinanceiro]';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
		}
		
		if(!@in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Erro = 147;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 86;
		}
		
		@mysql_query($sql,$con);
	}
?>