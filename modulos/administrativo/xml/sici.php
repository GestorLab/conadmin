<?
	$localModulo = 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_SICI(){
		global $con;
		global $_GET;
		
		$PeriodoApuracao	= $_GET['PeriodoApuracao'];
		$IdTipoApuracao		= 1; # MENSAL
		$Moeda				= getParametroSistema(5, 1);
		
		if($PeriodoApuracao != ''){
			if(strpos($PeriodoApuracao, "-") != 4){
				$PeriodoApuracao = dataConv($PeriodoApuracao, "m/Y", "Y-m");
			}
			
			$where = " AND (
				(
					ContaReceber.DataNF >= '".$PeriodoApuracao."-01' AND 
					ContaReceber.DataNF <= '".$PeriodoApuracao."-31'
				) or (
					NotaFiscal.DataEmissao >= '".$PeriodoApuracao."-01' AND 
					NotaFiscal.DataEmissao <= '".$PeriodoApuracao."-31'
				)
			)";
			
			list($Ana, $Mes) = explode("-",$PeriodoApuracao);
			
			if((int)$Mes == 12){
				$IdTipoApuracao = 3; # ANUAL
			} else if((int)$Mes == 6){
				$IdTipoApuracao = 2; # SEMESTRAL
			}
		}
		
		$sql_sici = "SELECT
						PeriodoApuracao,
						IdNotaFiscalLayout,
						DescricaoPeriodoApuracao,
						IAU1,
						DescricaoIAU1,
						IPL1TotalKMCaboPrestadora,
						DescricaoIPL1TotalKMCaboPrestadora,
						IPL1TotalKMCaboTerceiro,
						DescricaoIPL1TotalKMCaboTerceiro,
						IPL1CrescimentoPrevistoKMCaboPrestadora,
						DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora,
						IPL1CrescimentoPrevistoKMCaboTerceiro,
						DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro,
						IPL2TotalKMFibraPrestadora,
						DescricaoIPL2TotalKMFibraPrestadora,
						IPL2TotalKMFibraTerceiro,
						DescricaoIPL2TotalKMFibraTerceiro,
						IPL2CrescimentoPrevistoKMFibraPrestadora,
						DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora,
						IPL2CrescimentoPrevistoKMFibraTerceiro,
						DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro,
						IEM1Indicador,
						DescricaoIEM1Indicador,
						IEM1ValorTotalAplicadoEquipamento,
						DescricaoIEM1ValorTotalAplicadoEquipamento,
						IEM1ValorTotalAplicadoPesquisaDesenvolvimento,
						DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento,
						IEM1ValorTotalAplicadoMarketing,
						DescricaoIEM1ValorTotalAplicadoMarketing,
						IEM1ValorTotalAplicadoSoftware,
						DescricaoIEM1ValorTotalAplicadoSoftware,
						IEM1ValorTotalAplicadoServico,
						DescricaoIEM1ValorTotalAplicadoServico,
						IEM1ValorTotalAplicadoCentralAtendimento,
						DescricaoIEM1ValorTotalAplicadoCentralAtendimento,
						IEM2ValorFaturamentoServico,
						DescricaoIEM2ValorFaturamentoServico,
						IEM2ValorFaturamentoIndustrizalizacaoServico,
						DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico,
						IEM2ValorFaturamentoServicoAdicional,
						DescricaoIEM2ValorFaturamentoServicoAdicional,
						IEM3,
						DescricaoIEM3,
						IEM6,
						IEM7,
						IEM8ValorTotalCustos,
						IEM8ValorDespesaPublicidade,
						DescricaoIEM8ValorDespesaPublicidade,
						IEM8ValorDespesaInterconexao,
						DescricaoIEM8ValorDespesaInterconexao,
						IEM8ValorDespesaOperacaoManutencao,
						DescricaoIEM8ValorDespesaOperacaoManutencao,
						IEM8ValorDespesaVenda,
						DescricaoIEM8ValorDespesaVenda,
						Fistel,
						DescricaoFistel,
						IdStatus,
						LoginCriacao,
						DataCriacao,
						LoginProcessamento,
						DataProcessamento,
						LoginConfirmacao,
						DataConfirmacao,
						LoginConfirmacaoEntrega,
						DataConfirmacaoEntrega
					FROM
						SICI LEFT JOIN (
							SELECT 
								CONCAT('Remessa anterior: ', PeriodoApuracao) DescricaoPeriodoApuracao,
								CASE IAU1 
									WHEN '' THEN 'Ex: xxxx-xxxxxx'
									ELSE CONCAT('Remessa anterior: ', IAU1) 
								END DescricaoIAU1,
								CONCAT('Remessa anterior: ', REPLACE(IPL1TotalKMCaboPrestadora, '.', ',')) DescricaoIPL1TotalKMCaboPrestadora,
								CONCAT('Remessa anterior: ', REPLACE(IPL1TotalKMCaboTerceiro, '.', ',')) DescricaoIPL1TotalKMCaboTerceiro,
								CONCAT('Remessa anterior: ', REPLACE(IPL1CrescimentoPrevistoKMCaboPrestadora, '.', ',')) DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora,
								CONCAT('Remessa anterior: ', REPLACE(IPL1CrescimentoPrevistoKMCaboTerceiro, '.', ',')) DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro,
								CONCAT('Remessa anterior: ', REPLACE(IPL2TotalKMFibraPrestadora, '.', ',')) DescricaoIPL2TotalKMFibraPrestadora,
								CONCAT('Remessa anterior: ', REPLACE(IPL2TotalKMFibraTerceiro, '.', ',')) DescricaoIPL2TotalKMFibraTerceiro,
								CONCAT('Remessa anterior: ', REPLACE(IPL2CrescimentoPrevistoKMFibraPrestadora, '.', ',')) DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora,
								CONCAT('Remessa anterior: ', REPLACE(IPL2CrescimentoPrevistoKMFibraTerceiro, '.', ',')) DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1Indicador, '.', ',')) DescricaoIEM1Indicador,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoEquipamento, '.', ',')) DescricaoIEM1ValorTotalAplicadoEquipamento,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoPesquisaDesenvolvimento, '.', ',')) DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoMarketing, '.', ',')) DescricaoIEM1ValorTotalAplicadoMarketing,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoSoftware, '.', ',')) DescricaoIEM1ValorTotalAplicadoSoftware,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoServico, '.', ',')) DescricaoIEM1ValorTotalAplicadoServico,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM1ValorTotalAplicadoCentralAtendimento, '.', ',')) DescricaoIEM1ValorTotalAplicadoCentralAtendimento,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM2ValorFaturamentoServico, '.', ',')) DescricaoIEM2ValorFaturamentoServico,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM2ValorFaturamentoIndustrizalizacaoServico, '.', ',')) DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM2ValorFaturamentoServicoAdicional, '.', ',')) DescricaoIEM2ValorFaturamentoServicoAdicional,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM3, '.', ',')) DescricaoIEM3,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM8ValorDespesaPublicidade, '.', ',')) DescricaoIEM8ValorDespesaPublicidade,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM8ValorDespesaInterconexao, '.', ',')) DescricaoIEM8ValorDespesaInterconexao,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM8ValorDespesaOperacaoManutencao, '.', ',')) DescricaoIEM8ValorDespesaOperacaoManutencao,
								CONCAT('Remessa anterior: $Moeda ', REPLACE(IEM8ValorDespesaVenda, '.', ',')) DescricaoIEM8ValorDespesaVenda,
								CONCAT('Remessa anterior: ', Fistel) DescricaoFistel
							FROM
								SICI
							WHERE
								PeriodoApuracao < '$PeriodoApuracao' AND 
								IdStatus > 2
							ORDER BY 
								PeriodoApuracao DESC
							LIMIT 1
						) Descricao ON (TRUE)
					WHERE
						PeriodoApuracao = '$PeriodoApuracao'
					ORDER BY 
						PeriodoApuracao DESC;";
		$res_sici = @mysql_query($sql_sici, $con);
		
		if(@mysql_num_rows($res_sici) > 0){
			$lin_sici = @mysql_fetch_array($res_sici);
			$lin_sici[Status] = getParametroSistema(240, $lin_sici[IdStatus]);
			$lin_sici[CorStatus] = getCodigoInterno(51, $lin_sici[IdStatus]);
			
			header("content-type: text/xml");
			
			$dados = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			$dados .= "\n<PeriodoApuracao><![CDATA[$lin_sici[PeriodoApuracao]]]></PeriodoApuracao>";
			$dados .= "\n<IdNotaFiscalLayout><![CDATA[$lin_sici[IdNotaFiscalLayout]]]></IdNotaFiscalLayout>";
			$dados .= "\n<DescricaoPeriodoApuracao><![CDATA[$lin_sici[DescricaoPeriodoApuracao]]]></DescricaoPeriodoApuracao>";
			$dados .= "\n<IdTipoApuracao><![CDATA[$IdTipoApuracao]]></IdTipoApuracao>";
			
			$sql_uf = "SELECT 
							SICIEstado.IdPais,
							SICIEstado.IdEstado,
							SICIEstado.IEM4,
							Descricao.DescricaoIEM4,
							SICIEstado.IEM5,
							Descricao.DescricaoIEM5,
							SICIEstado.IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica,
							SICIEstado.IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica,
							SICIEstado.IEM10MenorPreco1MbpsDedicadoPessoaFisica,
							SICIEstado.IEM10MaiorPreco1MbpsDedicadoPessoaFisica,
							SICIEstado.IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica,
							SICIEstado.IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica,
							SICIEstado.IEM10MenorPreco1MbpsDedicadoPessoaJuridica,
							SICIEstado.IEM10MaiorPreco1MbpsDedicadoPessoaJuridica,
							Estado.SiglaEstado
						FROM 
							SICIEstado LEFT JOIN (
								SELECT 
									SICIEstado.IdPais,
									SICIEstado.IdEstado,
									CONCAT('Remessa anterior: ', SICIEstado.IEM4) DescricaoIEM4,
									CONCAT('Remessa anterior: ', SICIEstado.IEM5) DescricaoIEM5
								FROM
									SICIEstado,
									SICI
								WHERE
									SICI.PeriodoApuracao = SICIEstado.PeriodoApuracao AND 
									SICI.PeriodoApuracao = (
										SELECT 
											PeriodoApuracao
										FROM
											SICI
										WHERE
											PeriodoApuracao < '$PeriodoApuracao' AND 
											SUBSTRING(PeriodoApuracao, 6, 2) IN(6, 12) AND
											IdStatus > 2
										ORDER BY 
											PeriodoApuracao DESC
										LIMIT 1
									)
							) Descricao ON (
								SICIEstado.IdPais = Descricao.IdPais AND
								SICIEstado.IdEstado = Descricao.IdEstado
							),
							Estado
						WHERE
							SICIEstado.PeriodoApuracao = '$lin_sici[PeriodoApuracao]' AND
							SICIEstado.IdPais = Estado.IdPais AND
							SICIEstado.IdEstado = Estado.IdEstado;";
			$res_uf = mysql_query($sql_uf, $con);
			
			while($lin_uf = @mysql_fetch_array($res_uf)){
				$dados .= "\n<IndicadorEstado>";
				$dados .= "\n<IdPais><![CDATA[$lin_uf[IdPais]]]></IdPais>";
				$dados .= "\n<IdEstado><![CDATA[$lin_uf[IdEstado]]]></IdEstado>";
				$dados .= "\n<UF><![CDATA[$lin_uf[SiglaEstado]]]></UF>";
				$dados .= "\n<IEM4><![CDATA[$lin_uf[IEM4]]]></IEM4>";
				$dados .= "\n<DescricaoIEM4><![CDATA[$lin_uf[DescricaoIEM4]]]></DescricaoIEM4>";
				$dados .= "\n<IEM5><![CDATA[$lin_uf[IEM5]]]></IEM5>";
				$dados .= "\n<DescricaoIEM5><![CDATA[$lin_uf[DescricaoIEM5]]]></DescricaoIEM5>";
				$dados .= "\n<IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica><![CDATA[$lin_uf[IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica]]]></IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica>";
				$dados .= "\n<IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica><![CDATA[$lin_uf[IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica]]]></IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica>";
				$dados .= "\n<IEM10MenorPreco1MbpsDedicadoPessoaFisica><![CDATA[$lin_uf[IEM10MenorPreco1MbpsDedicadoPessoaFisica]]]></IEM10MenorPreco1MbpsDedicadoPessoaFisica>";
				$dados .= "\n<IEM10MaiorPreco1MbpsDedicadoPessoaFisica><![CDATA[$lin_uf[IEM10MaiorPreco1MbpsDedicadoPessoaFisica]]]></IEM10MaiorPreco1MbpsDedicadoPessoaFisica>";
				$dados .= "\n<IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica><![CDATA[$lin_uf[IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica]]]></IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica>";
				$dados .= "\n<IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica><![CDATA[$lin_uf[IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica]]]></IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica>";
				$dados .= "\n<IEM10MenorPreco1MbpsDedicadoPessoaJuridica><![CDATA[$lin_uf[IEM10MenorPreco1MbpsDedicadoPessoaJuridica]]]></IEM10MenorPreco1MbpsDedicadoPessoaJuridica>";
				$dados .= "\n<IEM10MaiorPreco1MbpsDedicadoPessoaJuridica><![CDATA[$lin_uf[IEM10MaiorPreco1MbpsDedicadoPessoaJuridica]]]></IEM10MaiorPreco1MbpsDedicadoPessoaJuridica>";
				
				$sql_vl = "SELECT 
								SICIEstadoVelocidade.IdVelocidade,
								SICIEstadoVelocidade.IEM9PessoaFisica,
								SICIEstadoVelocidade.IEM9PessoaJuridica,
								SICIVelocidade.DescricaoVelocidade
							FROM 
								SICIEstadoVelocidade,
								SICIVelocidade
							WHERE
								SICIEstadoVelocidade.PeriodoApuracao = '$lin_sici[PeriodoApuracao]' AND
								SICIEstadoVelocidade.IdPais = '$lin_uf[IdPais]' AND
								SICIEstadoVelocidade.IdEstado = '$lin_uf[IdEstado]' AND
								SICIEstadoVelocidade.IdVelocidade = SICIVelocidade.IdVelocidade;";
				$res_vl = mysql_query($sql_vl, $con);
				
				while($lin_vl = @mysql_fetch_array($res_vl)){
					$dados .= "\n<GrupoVelocidade>";
					$dados .= "\n<IdVelocidade><![CDATA[".$lin_vl[IdVelocidade]."]]></IdVelocidade>";
					$dados .= "\n<DescricaoVelocidade><![CDATA[".$lin_vl[DescricaoVelocidade]."]]></DescricaoVelocidade>";
					$dados .= "\n<IEM9PessoaFisica><![CDATA[".$lin_vl[IEM9PessoaFisica]."]]></IEM9PessoaFisica>";
					$dados .= "\n<IEM9PessoaJuridica><![CDATA[".$lin_vl[IEM9PessoaJuridica]."]]></IEM9PessoaJuridica>";
					$dados .= "\n</GrupoVelocidade>";
				}
				
				$sql_mn = "SELECT 
								SICICidade.IdPais,
								SICICidade.IdEstado,
								SICICidade.IdCidade,
								SICICidade.IPL3PessoaFisica,
								SICICidade.IPL3PessoaJuridica,
								SICICidade.IPL6,
								Cidade.NomeCidade
							FROM
								SICICidade,
								Cidade
							WHERE
								SICICidade.PeriodoApuracao = '$lin_sici[PeriodoApuracao]' AND
								SICICidade.IdPais = '$lin_uf[IdPais]' AND
								SICICidade.IdEstado = '$lin_uf[IdEstado]' AND
								SICICidade.IdPais = Cidade.IdPais AND
								SICICidade.IdEstado = Cidade.IdEstado AND
								SICICidade.IdCidade = Cidade.IdCidade;";
				$res_mn = mysql_query($sql_mn, $con);
				
				while($lin_mn = @mysql_fetch_array($res_mn)){
					$dados .= "\n<IndicadorCidade>";
					$dados .= "\n<IdCidade><![CDATA[$lin_mn[IdCidade]]]></IdCidade>";
					$dados .= "\n<NomeCidade><![CDATA[$lin_mn[NomeCidade]]]></NomeCidade>";
					$dados .= "\n<IPL3PessoaFisica><![CDATA[$lin_mn[IPL3PessoaFisica]]]></IPL3PessoaFisica>";
					$dados .= "\n<IPL3PessoaJuridica><![CDATA[$lin_mn[IPL3PessoaJuridica]]]></IPL3PessoaJuridica>";
					$dados .= "\n<IPL6><![CDATA[$lin_mn[IPL6]]]></IPL6>";
					
					$sql_tc = "SELECT 
								SICICidadeTecnologia.IdTecnologia,
								SICICidadeTecnologia.IPL5,
								Descricao.DescricaoIPL5,
								SICICidadeTecnologia.IPL4TotalAcessos,
								SICITecnologia.DescricaoTecnologia
							FROM
								SICICidadeTecnologia LEFT JOIN (
									SELECT 
										SICICidadeTecnologia.IdTecnologia,
										CONCAT('Remessa anterior: ', REPLACE(SICICidadeTecnologia.IPL5, '.', ',')) DescricaoIPL5
									FROM
										SICICidadeTecnologia,
										SICI
									WHERE
										SICI.PeriodoApuracao = SICICidadeTecnologia.PeriodoApuracao AND 
										SICI.PeriodoApuracao = (
											SELECT 
												PeriodoApuracao 
											FROM 
												SICI 
											WHERE 
												PeriodoApuracao < '$PeriodoApuracao' AND 
												SUBSTRING(PeriodoApuracao, 6, 2) IN(6, 12) AND
												IdStatus > 2 
											ORDER BY 
												PeriodoApuracao DESC
											LIMIT 1
										) AND 
										SICICidadeTecnologia.IdPais = '$lin_mn[IdPais]' AND
										SICICidadeTecnologia.IdEstado = '$lin_mn[IdEstado]' AND
										SICICidadeTecnologia.IdCidade = '$lin_mn[IdCidade]' 
								) Descricao ON (
									SICICidadeTecnologia.IdTecnologia = Descricao.IdTecnologia
								),
								SICITecnologia
							WHERE
								SICICidadeTecnologia.PeriodoApuracao = '$lin_sici[PeriodoApuracao]' AND
								SICICidadeTecnologia.IdPais = '$lin_mn[IdPais]' AND
								SICICidadeTecnologia.IdEstado = '$lin_mn[IdEstado]' AND
								SICICidadeTecnologia.IdCidade = '$lin_mn[IdCidade]' AND
								SICICidadeTecnologia.IdTecnologia = SICITecnologia.IdTecnologia;";
					$res_tc = mysql_query($sql_tc, $con);
					
					while($lin_tc = @mysql_fetch_array($res_tc)){
						if((int)$lin_sici[IdStatus] < 3){
							$lin_tc[IPL5] = null;
						}
						
						$dados .= "\n<Tecnologia>";
						$dados .= "\n<IdTecnologia><![CDATA[$lin_tc[IdTecnologia]]]></IdTecnologia>";
						$dados .= "\n<DescricaoTecnologia><![CDATA[$lin_tc[DescricaoTecnologia]]]></DescricaoTecnologia>";
						$dados .= "\n<IPL4TotalAcessos><![CDATA[$lin_tc[IPL4TotalAcessos]]]></IPL4TotalAcessos>";
						
						$sql_vl = "SELECT 
										SICICidadeTecnologiaVelocidade.IdVelocidade,
										SICICidadeTecnologiaVelocidade.IPL4,
										SICIVelocidade.DescricaoVelocidade
									FROM
										SICICidadeTecnologiaVelocidade,
										SICIVelocidade
									WHERE
										SICICidadeTecnologiaVelocidade.PeriodoApuracao = '$lin_sici[PeriodoApuracao]' AND
										SICICidadeTecnologiaVelocidade.IdPais = '$lin_mn[IdPais]' AND
										SICICidadeTecnologiaVelocidade.IdEstado = '$lin_mn[IdEstado]' AND
										SICICidadeTecnologiaVelocidade.IdCidade = '$lin_mn[IdCidade]' AND
										SICICidadeTecnologiaVelocidade.IdTecnologia = '$lin_tc[IdTecnologia]' AND
										SICICidadeTecnologiaVelocidade.IdVelocidade = SICIVelocidade.IdVelocidade;";
						$res_vl = mysql_query($sql_vl, $con);
						
						while($lin_vl = @mysql_fetch_array($res_vl)){
							$dados .= "\n<Velocidade>";
							$dados .= "\n<IdVelocidade><![CDATA[$lin_vl[IdVelocidade]]]></IdVelocidade>";
							$dados .= "\n<DescricaoVelocidade><![CDATA[$lin_vl[DescricaoVelocidade]]]></DescricaoVelocidade>";
							$dados .= "\n<IPL4><![CDATA[$lin_vl[IPL4]]]></IPL4>";
							$dados .= "\n</Velocidade>";
						}
						
						$dados .= "\n<IPL5><![CDATA[$lin_tc[IPL5]]]></IPL5>";
						$dados .= "\n<DescricaoIPL5><![CDATA[$lin_tc[DescricaoIPL5]]]></DescricaoIPL5>";
						$dados .= "\n</Tecnologia>";
					}
					
					$dados .= "\n</IndicadorCidade>";
				}
				
				$dados .= "\n</IndicadorEstado>";
			}
			
			if((int)$lin_sici[IdStatus] < 3){
				$lin_sici[IAU1]												= null;
				$lin_sici[IPL1TotalKMCaboPrestadora]						= null;
				$lin_sici[IPL1TotalKMCaboTerceiro]							= null;
				$lin_sici[IPL1CrescimentoPrevistoKMCaboPrestadora]			= null;
				$lin_sici[IPL1CrescimentoPrevistoKMCaboTerceiro]			= null;
				$lin_sici[IPL2TotalKMFibraPrestadora]						= null;
				$lin_sici[IPL2TotalKMFibraTerceiro]							= null;
				$lin_sici[IPL2CrescimentoPrevistoKMFibraPrestadora]			= null;
				$lin_sici[IPL2CrescimentoPrevistoKMFibraTerceiro]			= null;
				$lin_sici[IEM1Indicador]									= null;
				$lin_sici[IEM1ValorTotalAplicadoEquipamento]				= null;
				$lin_sici[IEM1ValorTotalAplicadoPesquisaDesenvolvimento]	= null;
				$lin_sici[IEM1ValorTotalAplicadoMarketing]					= null;
				$lin_sici[IEM1ValorTotalAplicadoSoftware]					= null;
				$lin_sici[IEM1ValorTotalAplicadoServico]					= null;
				$lin_sici[IEM1ValorTotalAplicadoCentralAtendimento]			= null;
				$lin_sici[IEM2ValorFaturamentoServico]						= null;
				$lin_sici[IEM2ValorFaturamentoIndustrizalizacaoServico]		= null;
				$lin_sici[IEM2ValorFaturamentoServicoAdicional]				= null;
				$lin_sici[IEM3]												= null;
				$lin_sici[IEM8ValorDespesaPublicidade]						= null;
				$lin_sici[IEM8ValorDespesaInterconexao]						= null;
				$lin_sici[IEM8ValorDespesaOperacaoManutencao]				= null;
				$lin_sici[IEM8ValorDespesaVenda]							= null;
			}
			
			if((int)$lin_sici[IdStatus] > 1){
				$sql_vis = "SELECT 
								PeriodoApuracao 
							FROM
								SICILancamento 
							WHERE 
								PeriodoApuracao = '$lin_sici[PeriodoApuracao]';";
				$res_vis = mysql_query($sql_vis,$con);
				$QtdLancamento = @mysql_num_rows($res_vis);
			}
			
			$dados .= "\n<IAU1NumeroCAT><![CDATA[$lin_sici[IAU1]]]></IAU1NumeroCAT>";
			$dados .= "\n<DescricaoIAU1NumeroCAT><![CDATA[$lin_sici[DescricaoIAU1]]]></DescricaoIAU1NumeroCAT>";
			$dados .= "\n<IPL1TotalKMCaboPrestadora><![CDATA[$lin_sici[IPL1TotalKMCaboPrestadora]]]></IPL1TotalKMCaboPrestadora>";
			$dados .= "\n<DescricaoIPL1TotalKMCaboPrestadora><![CDATA[$lin_sici[DescricaoIPL1TotalKMCaboPrestadora]]]></DescricaoIPL1TotalKMCaboPrestadora>";
			$dados .= "\n<IPL1TotalKMCaboTerceiro><![CDATA[$lin_sici[IPL1TotalKMCaboTerceiro]]]></IPL1TotalKMCaboTerceiro>";
			$dados .= "\n<DescricaoIPL1TotalKMCaboTerceiro><![CDATA[$lin_sici[DescricaoIPL1TotalKMCaboTerceiro]]]></DescricaoIPL1TotalKMCaboTerceiro>";
			$dados .= "\n<IPL1CrescimentoPrevistoKMCaboPrestadora><![CDATA[$lin_sici[IPL1CrescimentoPrevistoKMCaboPrestadora]]]></IPL1CrescimentoPrevistoKMCaboPrestadora>";
			$dados .= "\n<DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora><![CDATA[$lin_sici[DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora]]]></DescricaoIPL1CrescimentoPrevistoKMCaboPrestadora>";
			$dados .= "\n<IPL1CrescimentoPrevistoKMCaboTerceiro><![CDATA[$lin_sici[IPL1CrescimentoPrevistoKMCaboTerceiro]]]></IPL1CrescimentoPrevistoKMCaboTerceiro>";
			$dados .= "\n<DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro><![CDATA[$lin_sici[DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro]]]></DescricaoIPL1CrescimentoPrevistoKMCaboTerceiro>";
			$dados .= "\n<IPL2TotalKMFibraPrestadora><![CDATA[$lin_sici[IPL2TotalKMFibraPrestadora]]]></IPL2TotalKMFibraPrestadora>";
			$dados .= "\n<DescricaoIPL2TotalKMFibraPrestadora><![CDATA[$lin_sici[DescricaoIPL2TotalKMFibraPrestadora]]]></DescricaoIPL2TotalKMFibraPrestadora>";
			$dados .= "\n<IPL2TotalKMFibraTerceiro><![CDATA[$lin_sici[IPL2TotalKMFibraTerceiro]]]></IPL2TotalKMFibraTerceiro>";
			$dados .= "\n<DescricaoIPL2TotalKMFibraTerceiro><![CDATA[$lin_sici[DescricaoIPL2TotalKMFibraTerceiro]]]></DescricaoIPL2TotalKMFibraTerceiro>";
			$dados .= "\n<IPL2CrescimentoPrevistoKMFibraPrestadora><![CDATA[$lin_sici[IPL2CrescimentoPrevistoKMFibraPrestadora]]]></IPL2CrescimentoPrevistoKMFibraPrestadora>";
			$dados .= "\n<DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora><![CDATA[$lin_sici[DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora]]]></DescricaoIPL2CrescimentoPrevistoKMFibraPrestadora>";
			$dados .= "\n<IPL2CrescimentoPrevistoKMFibraTerceiro><![CDATA[$lin_sici[IPL2CrescimentoPrevistoKMFibraTerceiro]]]></IPL2CrescimentoPrevistoKMFibraTerceiro>";
			$dados .= "\n<DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro><![CDATA[$lin_sici[DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro]]]></DescricaoIPL2CrescimentoPrevistoKMFibraTerceiro>";
			$dados .= "\n<IEM1Indicador><![CDATA[$lin_sici[IEM1Indicador]]]></IEM1Indicador>";
			$dados .= "\n<DescricaoIEM1Indicador><![CDATA[$lin_sici[DescricaoIEM1Indicador]]]></DescricaoIEM1Indicador>";
			$dados .= "\n<IEM1ValorTotalAplicadoEquipamento><![CDATA[$lin_sici[IEM1ValorTotalAplicadoEquipamento]]]></IEM1ValorTotalAplicadoEquipamento>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoEquipamento><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoEquipamento]]]></DescricaoIEM1ValorTotalAplicadoEquipamento>";
			$dados .= "\n<IEM1ValorTotalAplicadoPesquisaDesenvolvimento><![CDATA[$lin_sici[IEM1ValorTotalAplicadoPesquisaDesenvolvimento]]]></IEM1ValorTotalAplicadoPesquisaDesenvolvimento>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento]]]></DescricaoIEM1ValorTotalAplicadoPesquisaDesenvolvimento>";
			$dados .= "\n<IEM1ValorTotalAplicadoMarketing><![CDATA[$lin_sici[IEM1ValorTotalAplicadoMarketing]]]></IEM1ValorTotalAplicadoMarketing>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoMarketing><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoMarketing]]]></DescricaoIEM1ValorTotalAplicadoMarketing>";
			$dados .= "\n<IEM1ValorTotalAplicadoSoftware><![CDATA[$lin_sici[IEM1ValorTotalAplicadoSoftware]]]></IEM1ValorTotalAplicadoSoftware>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoSoftware><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoSoftware]]]></DescricaoIEM1ValorTotalAplicadoSoftware>";
			$dados .= "\n<IEM1ValorTotalAplicadoServico><![CDATA[$lin_sici[IEM1ValorTotalAplicadoServico]]]></IEM1ValorTotalAplicadoServico>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoServico><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoServico]]]></DescricaoIEM1ValorTotalAplicadoServico>";
			$dados .= "\n<IEM1ValorTotalAplicadoCentralAtendimento><![CDATA[$lin_sici[IEM1ValorTotalAplicadoCentralAtendimento]]]></IEM1ValorTotalAplicadoCentralAtendimento>";
			$dados .= "\n<DescricaoIEM1ValorTotalAplicadoCentralAtendimento><![CDATA[$lin_sici[DescricaoIEM1ValorTotalAplicadoCentralAtendimento]]]></DescricaoIEM1ValorTotalAplicadoCentralAtendimento>";
			$dados .= "\n<IEM2ValorFaturamentoServico><![CDATA[$lin_sici[IEM2ValorFaturamentoServico]]]></IEM2ValorFaturamentoServico>";
			$dados .= "\n<DescricaoIEM2ValorFaturamentoServico><![CDATA[$lin_sici[DescricaoIEM2ValorFaturamentoServico]]]></DescricaoIEM2ValorFaturamentoServico>";
			$dados .= "\n<IEM2ValorFaturamentoIndustrizalizacaoServico><![CDATA[$lin_sici[IEM2ValorFaturamentoIndustrizalizacaoServico]]]></IEM2ValorFaturamentoIndustrizalizacaoServico>";
			$dados .= "\n<DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico><![CDATA[$lin_sici[DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico]]]></DescricaoIEM2ValorFaturamentoIndustrizalizacaoServico>";
			$dados .= "\n<IEM2ValorFaturamentoServicoAdicional><![CDATA[$lin_sici[IEM2ValorFaturamentoServicoAdicional]]]></IEM2ValorFaturamentoServicoAdicional>";
			$dados .= "\n<DescricaoIEM2ValorFaturamentoServicoAdicional><![CDATA[$lin_sici[DescricaoIEM2ValorFaturamentoServicoAdicional]]]></DescricaoIEM2ValorFaturamentoServicoAdicional>";
			$dados .= "\n<IEM3ValorInvestimentoRealizado><![CDATA[$lin_sici[IEM3]]]></IEM3ValorInvestimentoRealizado>";
			$dados .= "\n<DescricaoIEM3ValorInvestimentoRealizado><![CDATA[$lin_sici[DescricaoIEM3]]]></DescricaoIEM3ValorInvestimentoRealizado>";
			$dados .= "\n<IEM6TotalBruto><![CDATA[$lin_sici[IEM6]]]></IEM6TotalBruto>";
			$dados .= "\n<IEM7TotalLiquido><![CDATA[$lin_sici[IEM7]]]></IEM7TotalLiquido>";
			$dados .= "\n<IEM8ValorTotalCusto><![CDATA[$lin_sici[IEM8ValorTotalCustos]]]></IEM8ValorTotalCusto>";
			$dados .= "\n<IEM8ValorDespesaPublicidade><![CDATA[$lin_sici[IEM8ValorDespesaPublicidade]]]></IEM8ValorDespesaPublicidade>";
			$dados .= "\n<DescricaoIEM8ValorDespesaPublicidade><![CDATA[$lin_sici[DescricaoIEM8ValorDespesaPublicidade]]]></DescricaoIEM8ValorDespesaPublicidade>";
			$dados .= "\n<IEM8ValorDespesaInterconexao><![CDATA[$lin_sici[IEM8ValorDespesaInterconexao]]]></IEM8ValorDespesaInterconexao>";
			$dados .= "\n<DescricaoIEM8ValorDespesaInterconexao><![CDATA[$lin_sici[DescricaoIEM8ValorDespesaInterconexao]]]></DescricaoIEM8ValorDespesaInterconexao>";
			$dados .= "\n<IEM8ValorDespesaOperacaoManutencao><![CDATA[$lin_sici[IEM8ValorDespesaOperacaoManutencao]]]></IEM8ValorDespesaOperacaoManutencao>";
			$dados .= "\n<DescricaoIEM8ValorDespesaOperacaoManutencao><![CDATA[$lin_sici[DescricaoIEM8ValorDespesaOperacaoManutencao]]]></DescricaoIEM8ValorDespesaOperacaoManutencao>";
			$dados .= "\n<IEM8ValorDespesaVenda><![CDATA[$lin_sici[IEM8ValorDespesaVenda]]]></IEM8ValorDespesaVenda>";
			$dados .= "\n<DescricaoIEM8ValorDespesaVenda><![CDATA[$lin_sici[DescricaoIEM8ValorDespesaVenda]]]></DescricaoIEM8ValorDespesaVenda>";
			$dados .= "\n<QtdLancamento><![CDATA[$QtdLancamento]]></QtdLancamento>";
			$dados .= "\n<Fistel><![CDATA[$lin_sici[Fistel]]]></Fistel>";
			$dados .= "\n<DescricaoFistel><![CDATA[$lin_sici[DescricaoFistel]]]></DescricaoFistel>";
			$dados .= "\n<IdStatus><![CDATA[$lin_sici[IdStatus]]]></IdStatus>";
			$dados .= "\n<Status><![CDATA[$lin_sici[Status]]]></Status>";
			$dados .= "\n<CorStatus><![CDATA[$lin_sici[CorStatus]]]></CorStatus>";
			$dados .= "\n<LoginCriacao><![CDATA[$lin_sici[LoginCriacao]]]></LoginCriacao>";
			$dados .= "\n<DataCriacao><![CDATA[$lin_sici[DataCriacao]]]></DataCriacao>";
			$dados .= "\n<LoginProcessamento><![CDATA[$lin_sici[LoginProcessamento]]]></LoginProcessamento>";
			$dados .= "\n<DataProcessamento><![CDATA[$lin_sici[DataProcessamento]]]></DataProcessamento>";
			$dados .= "\n<LoginConfirmacao><![CDATA[$lin_sici[LoginConfirmacao]]]></LoginConfirmacao>";
			$dados .= "\n<DataConfirmacao><![CDATA[$lin_sici[DataConfirmacao]]]></DataConfirmacao>";
			$dados .= "\n<LoginConfirmacaoEntrega><![CDATA[$lin_sici[LoginConfirmacaoEntrega]]]></LoginConfirmacaoEntrega>";
			$dados .= "\n<DataConfirmacaoEntrega><![CDATA[$lin_sici[DataConfirmacaoEntrega]]]></DataConfirmacaoEntrega>";
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_SICI();
?>