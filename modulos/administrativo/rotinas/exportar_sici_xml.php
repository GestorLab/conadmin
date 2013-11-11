<?php
	set_time_limit(0);
	
	$localModulo				= 1;
	$localOperacao				= 159;
	$localSuboperacao			= "V";
	$pathXML					= "../../../";
	$local_PeriodoApuracao		= $_GET["PeriodoApuracao"];
	$local_IdMetodoExportacao	= $_GET["IdMetodoExportacao"];
	
	if($PeriodoApuracao != ""){
		$local_PeriodoApuracao = $PeriodoApuracao;
	}
	
	if($IdMetodoExportacao != ""){
		$local_IdMetodoExportacao = $IdMetodoExportacao;
	}
	
	if((int)$local_IdMetodoExportacao != 2){
		include($pathXML."files/conecta.php");
		include($pathXML."files/funcoes.php");
		include($pathXML."rotinas/verifica.php");
	}
	
	$local_IdLoja = $_SESSION["IdLoja"];
	# MONTANDO COMPARAÇÃO PARA TODAS A CONSULTAS
	if($local_PeriodoApuracao != ''){
		if(@ereg("([0-9])/([0-9])", $local_PeriodoApuracao)){
			$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		}
		
		list($Ano, $Mes) = explode("-", $local_PeriodoApuracao);
		
		$PeriodoApuracaoInical = $local_PeriodoApuracao;
		$PeriodoApuracaoFinal = $local_PeriodoApuracao;
		$where = " AND 
			SUBSTRING(ContaReceber.DataNF, 1, 7) >= '$PeriodoApuracaoInical' AND 
			SUBSTRING(ContaReceber.DataNF, 1, 7) <= '$PeriodoApuracaoFinal'";
	}
	
	$sql = "SELECT 
				Fistel,
				IAU1,
				IPL1TotalKMCaboPrestadora,
				IPL1TotalKMCaboTerceiro,
				IPL1CrescimentoPrevistoKMCaboPrestadora,
				IPL1CrescimentoPrevistoKMCaboTerceiro,
				IPL2TotalKMFibraPrestadora,
				IPL2TotalKMFibraTerceiro,
				IPL2CrescimentoPrevistoKMFibraPrestadora,
				IPL2CrescimentoPrevistoKMFibraTerceiro,
				IEM1Indicador,
				IEM1ValorTotalAplicadoEquipamento,
				IEM1ValorTotalAplicadoPesquisaDesenvolvimento,
				IEM1ValorTotalAplicadoMarketing,
				IEM1ValorTotalAplicadoSoftware,
				IEM1ValorTotalAplicadoServico,
				IEM1ValorTotalAplicadoCentralAtendimento,
				IEM3,
				IEM2ValorFaturamentoServico,
				IEM2ValorFaturamentoIndustrizalizacaoServico,
				IEM2ValorFaturamentoServicoAdicional,
				IEM8ValorTotalCustos,
				IEM8ValorDespesaPublicidade,
				IEM8ValorDespesaInterconexao,
				IEM8ValorDespesaOperacaoManutencao,
				IEM8ValorDespesaVenda,
				IEM6,
				IEM7
			FROM
				SICI
			WHERE
				PeriodoApuracao = '$local_PeriodoApuracao';";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	
	$cont = 0;
	$TipoPessoa = Array();
	$sql_tp = "SELECT 
					ParametroSistema.IdParametroSistema, 
					ParametroSistema.ValorParametroSistema 
				FROM 
					ParametroSistema 
				WHERE 
					ParametroSistema.IdGrupoParametroSistema = '1'
				ORDER BY
					ParametroSistema.IdParametroSistema DESC;";
	$res_tp = @mysql_query($sql_tp, $con);
	
	while($lin_tp = @mysql_fetch_array($res_tp)){
		$TipoPessoa[$cont][IdTipoPessoa] = $lin_tp[IdParametroSistema];
		$TipoPessoa[$cont][TipoPessoa] = $lin_tp[ValorParametroSistema];
		$cont++;
	}
	
	$Estado = Array();
	$Cidade = Array();
	$sql_mn = "SELECT 
						SICICidade.IdPais,
						SICICidade.IdEstado,
						SICICidade.IdCidade,
						Cidade.NomeCidade,
						Estado.SiglaEstado
					FROM
						SICICidade,
						Cidade,
						Estado
					WHERE
						SICICidade.PeriodoApuracao = '2013-01' AND
						Estado.IdEstado = SICICidade.IdEstado AND
						SICICidade.IdPais = Cidade.IdPais AND
						SICICidade.IdEstado = Cidade.IdEstado AND
						SICICidade.IdCidade = Cidade.IdCidade;";
	$res_mn = mysql_query($sql_mn, $con);
	
	while($lin_mn = @mysql_fetch_array($res_mn)) {
		$Temp = Array(
			"IdPais" => $lin_mn[IdPais],
			"IdEstado" => $lin_mn[IdEstado],
			"SiglaEstado" => $lin_mn[SiglaEstado]
		);
		
		if(!in_array($Temp, $Estado)){
			$i = count($Estado);
			$Estado[$i] = $Temp;
		}
		
		$i = count($Cidade);
		$Cidade[$i] = $Temp;
		$Cidade[$i][IdCidade] = $lin_mn[IdCidade];
		$Cidade[$i][CodigoCidade] = $lin_mn[IdEstado].str_pad($lin_mn[IdCidade], 5, "0", STR_PAD_LEFT);
	}
	
	$LinhaXML = array();
	$LinhaXML[count($LinhaXML)] = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
	$LinhaXML[count($LinhaXML)] = "\n<root>";
	$LinhaXML[count($LinhaXML)] = "\n\t<UploadSICI ano=\"".$Ano."\" mes=\"".$Mes."\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t<Outorga fistel=\"".$lin[Fistel]."\">";
	# IEM 4 - INT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM4\">";
	$sql_uf = "SELECT
					SICIEstado.IEM4,
					Estado.SiglaEstado
				FROM
					SICIEstado,
					Estado
				WHERE
					SICIEstado.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
					SICIEstado.IdPais = Estado.IdPais AND
					SICIEstado.IdEstado = Estado.IdEstado;";
	$res_uf = mysql_query($sql_uf, $con);
	
	while($lin_uf = @mysql_fetch_array($res_uf)){
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo uf=\"".$lin_uf[SiglaEstado]."\" item=\"a\" valor=\"".$lin_uf[IEM4]."\" />";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 5 - INT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM5\">";
	$sql_uf = "SELECT
					SICIEstado.IEM5,
					Estado.SiglaEstado
				FROM
					SICIEstado,
					Estado
				WHERE
					SICIEstado.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
					SICIEstado.IdPais = Estado.IdPais AND
					SICIEstado.IdEstado = Estado.IdEstado;";
	$res_uf = mysql_query($sql_uf, $con);
	
	while($lin_uf = @mysql_fetch_array($res_uf)){
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo uf=\"".$lin_uf[SiglaEstado]."\" item=\"a\" valor=\"".$lin_uf[IEM5]."\" />";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 9 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM9\">";
	$GrupoVelocidade = array("", "a", "b", "c", "d", "e");
	
	$Apurador = "";
	$ValoresFisicos; 
	$Contador=0;
	$ValoresJuridicos;
	
	$sql_gv = "SELECT
					SICIEstadoVelocidade.IdPais,
					SICIEstadoVelocidade.IdEstado,
					SICIEstadoVelocidade.IdVelocidade,
					SICIEstadoVelocidade.IEM9PessoaFisica,
					SICIEstadoVelocidade.IEM9PessoaJuridica
				FROM
					SICIEstadoVelocidade
				WHERE
					SICIEstadoVelocidade.PeriodoApuracao = '".$local_PeriodoApuracao."'
				ORDER BY
					SICIEstadoVelocidade.IdPais,
					SICIEstadoVelocidade.IdEstado,
					SICIEstadoVelocidade.IdVelocidade;";
	$res_gv = mysql_query($sql_gv, $con);
	
	while($lin_gv = mysql_fetch_array($res_gv)) {
		//$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"".$GrupoVelocidade[$lin_gv[IdParametroSistema]]."\" valor=\"".number_format($lin_te[Valor], 2, ',', '')."\" />";
		$ValoresFisicos[$Contador] = $lin_gv[IEM9PessoaFisica];
		$ValoresJuridicos[$Contador] = $lin_gv[IEM9PessoaJuridica];
		$Contador++;
	}
	
	$Apurador = array(	'IEM9PessoaFisica' => $ValoresFisicos,
						'IEM9PessoaJuridica' => $ValoresJuridicos);
	
	$TipoIEM9Pessoa =  array("IEM9PessoaFisica","IEM9PessoaJuridica");
	
	for($cont = 0; $cont < count($TipoPessoa); $cont++) {
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Pessoa item=\"".$TipoPessoa[$cont][TipoPessoa][0]."\">";
		$Contador = 0;
		
		for($cont_e = 0; $cont_e < count($Estado); $cont_e++){
			# BUSCAR OS GURPOS DE VELOCIDADES CADASTRADOS NO SISTEMA
			$sql_gv = "SELECT 
							ParametroSistema.IdParametroSistema, 
							ParametroSistema.ValorParametroSistema 
						FROM 
							ParametroSistema 
						WHERE 
							ParametroSistema.IdGrupoParametroSistema = '190';";
			$res_gv = @mysql_query($sql_gv, $con);
			
			while($lin_gv = @mysql_fetch_array($res_gv)){
				# OBTER A QUANTIDADE DE CONTRATO PARA CADA GRUPO DE VELOCIDADE
				$sql_te = "SELECT 
								(SUM(Contrato.Valor) / COUNT(Contrato.Valor)) Valor
							FROM
								(SELECT
									LancamentoFinanceiro.Valor
								FROM
									Servico,
									Contrato,
									LancamentoFinanceiro,
									LancamentoFinanceiroContaReceber,
									ContaReceber LEFT JOIN NotaFiscal ON (
										ContaReceber.IdLoja = NotaFiscal.IdLoja AND
										ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
										NotaFiscal.IdStatus = 1 
									),
									Pessoa,
									PessoaEndereco
								WHERE 
									Servico.IdLoja = '".$local_IdLoja."' AND 
									Servico.GrupoVelocidade = '".$lin_gv[IdParametroSistema]."' AND
									Servico.IdTipoServico = '1' AND 
									Servico.Tecnologia IS NOT NULL AND 
									Servico.IdLoja = Contrato.IdLoja AND 
									Servico.IdServico = Contrato.IdServico AND 
									Contrato.IdStatus >= '200' AND
									Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
									Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
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
									Pessoa.TipoPessoa = '".$TipoPessoa[$cont][IdTipoPessoa]."' AND 
									Contrato.IdPessoa = PessoaEndereco.IdPessoa AND 
									Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
									PessoaEndereco.IdPais = '".$Estado[$cont_e][IdPais]."' AND
									PessoaEndereco.IdEstado = '".$Estado[$cont_e][IdEstado]."'
									$where
								GROUP BY
									Contrato.IdContrato
								) Contrato;";
				$res_te = mysql_query($sql_te, $con);
				$lin_te = @mysql_fetch_array($res_te);
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"".$GrupoVelocidade[$lin_gv[IdParametroSistema]]."\" valor=\"".number_format($Apurador[$TipoIEM9Pessoa[$cont]][$Contador], 2, ',', '')."\" />";
				$Contador++;
			}
		}
		
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t</Pessoa>";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	
	# IEM 10 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM10\">";
	
	for($cont = 0; $cont < count($TipoPessoa); $cont++) {
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Pessoa item=\"".$TipoPessoa[$cont][TipoPessoa][0]."\">";
		
		for($cont_e = 0; $cont_e < count($Estado); $cont_e++){
			# BUSCAR O MENOR E O MAIOR VALOR POR 1 MEGA DEDICADO
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
									NotaFiscal.IdStatus = 1 
								),
								Pessoa, 
								PessoaEndereco
							WHERE 
								Servico.IdLoja = '".$local_IdLoja."' AND 
								Servico.IdTipoServico = '1' AND 
								Servico.Dedicado = '1' AND
								Servico.Tecnologia IS NOT NULL AND 
								Servico.IdLoja = Contrato.IdLoja AND 
								Servico.IdServico = Contrato.IdServico AND 
								Contrato.IdStatus >= '200' AND 
								Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja AND 
								Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato AND
								Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
								Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
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
								Pessoa.TipoPessoa = '".$TipoPessoa[$cont][IdTipoPessoa]."' AND 
								Contrato.IdPessoa = PessoaEndereco.IdPessoa AND
								Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco AND
								PessoaEndereco.IdPais = '".$Estado[$cont_e][IdPais]."' AND
								PessoaEndereco.IdEstado = '".$Estado[$cont_e][IdEstado]."'
								$where
							GROUP BY
								Contrato.IdContrato
							) Contrato;";
			$res_nd = @mysql_query($sql_nd, $con);
			$lin_nd = @mysql_fetch_array($res_nd);
			
			if($lin_nd[MinValor] == '') {
				$lin_nd[MinValor] = 0.00;
			}
			
			if($lin_nd[MaxValor] == '') {
				$lin_nd[MaxValor] = 0.00;
			}
			# BUSCAR O MENOR E O MAIOR VALOR POR 1 MEGA NÃO DEDICADO
			$sql_dd = "SELECT
							SICIEstado.IdPais,
							SICIEstado.IdEstado,
							SICIEstado.IEM4,
							SICIEstado.IEM5,
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
							SICIEstado,
							Estado
						WHERE
							SICIEstado.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							SICIEstado.IdPais = Estado.IdPais AND
							SICIEstado.IdEstado = Estado.IdEstado;";
			$res_dd = @mysql_query($sql_dd, $con);
			$lin_dd = @mysql_fetch_array($res_dd);
			
			if($lin_dd[MinValor] == '') {
				$lin_dd[MinValor] = 0.00;
			}
			
			if($lin_dd[MaxValor] == '') {
				$lin_dd[MaxValor] = 0.00;
			}
			
			if($TipoPessoa[$cont][TipoPessoa][0] == "F"){
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"a\" valor=\"".number_format($lin_dd[IEM10MenorPreco1MbpsNaoDedicadoPessoaFisica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"b\" valor=\"".number_format($lin_dd[IEM10MenorPreco1MbpsDedicadoPessoaFisica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"c\" valor=\"".number_format($lin_dd[IEM10MaiorPreco1MbpsNaoDedicadoPessoaFisica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"d\" valor=\"".number_format($lin_dd[IEM10MaiorPreco1MbpsDedicadoPessoaFisica], 2, ',', '')."\" />";
			}else{
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"a\" valor=\"".number_format($lin_dd[IEM10MenorPreco1MbpsNaoDedicadoPessoaJuridica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"b\" valor=\"".number_format($lin_dd[IEM10MenorPreco1MbpsDedicadoPessoaJuridica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"c\" valor=\"".number_format($lin_dd[IEM10MaiorPreco1MbpsNaoDedicadoPessoaJuridica], 2, ',', '')."\" />";
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Conteudo uf=\"".$Estado[$cont_e][SiglaEstado]."\" item=\"d\" valor=\"".number_format($lin_dd[IEM10MaiorPreco1MbpsDedicadoPessoaJuridica], 2, ',', '')."\" />";
			}
		}
		
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t</Pessoa>";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IPL 3 - INT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IPL3\">";
	
	for($cont_e = 0; $cont_e < count($Estado); $cont_e++){
		$sql_mn = "SELECT 
							SICICidade.PeriodoApuracao,
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
							SICICidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							SICICidade.IdPais = '".$Estado[$cont_e][IdPais]."' AND
							SICICidade.IdEstado = '".$Estado[$cont_e][IdEstado]."' AND
							SICICidade.IdPais = Cidade.IdPais AND
							SICICidade.IdEstado = Cidade.IdEstado AND
							SICICidade.IdCidade = Cidade.IdCidade;";
		$res_mn = mysql_query($sql_mn, $con);
		$cont_c = 0;
		
		while($lin_tmp = mysql_fetch_array($res_mn)) {
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Municipio codmunicipio=\"".$Cidade[$cont_c][CodigoCidade]."\">";
				
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Pessoa item=\"".$TipoPessoa[0][TipoPessoa][0]."\">";
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t\t<Conteudo item=\"a\" valor=\"".$lin_tmp[IPL3PessoaFisica]."\" />";
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t</Pessoa>";
			
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Pessoa item=\"".$TipoPessoa[1][TipoPessoa][0]."\">";
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t\t<Conteudo item=\"a\" valor=\"".$lin_tmp[IPL3PessoaJuridica]."\" />";
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t</Pessoa>";
			
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t</Municipio>";
			$cont_c++;
		}
	}
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IPL 4 - INT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"QAIPL4SM\">";
	
	for($cont_c = 0; $cont_c < count($Cidade); $cont_c++) {
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Municipio codmunicipio=\"".$Cidade[$cont_c][CodigoCidade]."\">";
		$cont_t = 0;
		$Tecnologia = Array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
		$sql_tc = "SELECT 
						SICITecnologia.IdTecnologia
					FROM 
						SICITecnologia;";
		$res_tc = @mysql_query($sql_tc, $con);

		while($lin_tc = @mysql_fetch_array($res_tc)){
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t<Tecnologia item=\"".$Tecnologia[$cont_t]."\">";
			# -> IPL 5 - FLOAT
			$sql_in = "SELECT 
							SICICidadeTecnologia.IPL5
						FROM 
							SICICidadeTecnologia 
						WHERE 
							SICICidadeTecnologia.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							SICICidadeTecnologia.IdPais = '".$Cidade[$cont_c][IdPais]."' AND
							SICICidadeTecnologia.IdEstado = '".$Cidade[$cont_c][IdEstado]."' AND
							SICICidadeTecnologia.IdCidade = '".$Cidade[$cont_c][IdCidade]."' AND
							SICICidadeTecnologia.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
			$res_in = @mysql_query($sql_in, $con);
			$lin_in = @mysql_fetch_array($res_in);
			$IPL5CapacidadeTotalImplantadaTecnologia = (int)$lin_in[IPL5];
			
			if($IPL5CapacidadeTotalImplantadaTecnologia == ""){
				$IPL5CapacidadeTotalImplantadaTecnologia = 0.00;
			}
			
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t\t<Conteudo nome=\"QAIPL5SM\" valor=\"".number_format($IPL5CapacidadeTotalImplantadaTecnologia, 2, ",", "")."\" />";
			$sql_tmp = "SELECT 
								SICICidadeTecnologia.IPL4TotalAcessos
							FROM
								SICICidadeTecnologia
							WHERE
								SICICidadeTecnologia.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
								SICICidadeTecnologia.IdPais = '".$Cidade[$cont_c][IdPais]."' AND
								SICICidadeTecnologia.IdEstado = '".$Cidade[$cont_c][IdEstado]."' AND
								SICICidadeTecnologia.IdCidade = '".$Cidade[$cont_c][IdCidade]."' AND
								SICICidadeTecnologia.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
			$res_tmp = mysql_query($sql_tmp, $con);
			$lin_tmp = @mysql_fetch_array($res_tmp);
			
			if($lin_tmp[IPL4TotalAcessos] == '') {
				$lin_tmp[IPL4TotalAcessos] = 0;
			}
			
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t\t<Conteudo nome=\"total\" valor=\"".$lin_tmp[IPL4TotalAcessos]."\" />";
			
			# BUSCAR A QUANTIDADE DE CONTRATOS PARA UM GRUPO DE VELOCIDADE ESPECIFICO
			$sql_co = "SELECT 
							SICICidadeTecnologiaVelocidade.IdVelocidade,
							SICICidadeTecnologiaVelocidade.IPL4
						FROM
							SICICidadeTecnologiaVelocidade
							
						WHERE
							SICICidadeTecnologiaVelocidade.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							SICICidadeTecnologiaVelocidade.IdPais = '".$Cidade[$cont_c][IdPais]."' AND
							SICICidadeTecnologiaVelocidade.IdEstado = '".$Cidade[$cont_c][IdEstado]."' AND
							SICICidadeTecnologiaVelocidade.IdCidade = '".$Cidade[$cont_c][IdCidade]."' AND
							SICICidadeTecnologiaVelocidade.IdTecnologia = '".$lin_tc[IdTecnologia]."'";
			$res_co = mysql_query($sql_co, $con);
			$i = 1;
			while($lin_co = mysql_fetch_array($res_co)){				
				if($lin_co[QTD] == '') {
					$lin_co[QTD] = 0;
				}
				$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t\t<Conteudo valor=\"".(14+$i)."\" faixa=\"".$lin_co[IPL4]."\" />";
				
				$i++;
				if($i == 6) $i = 0;
				
			}
			$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t\t</Tecnologia>";
			$cont_t++;
		}
		
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t</Municipio>";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IPL 6 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IPL6IM\">";
	
	for($cont_c = 0; $cont_c < count($Cidade); $cont_c++) {
		$IPL6CapacidadeTotalImplantada = 0;
		$sql_tc = "SELECT 
						SICITecnologia.IdTecnologia
					FROM 
						SICITecnologia;";
		$res_tc = @mysql_query($sql_tc, $con);

		while($lin_tc = @mysql_fetch_array($res_tc)){
			$sql_in = "SELECT 
							SICICidadeTecnologia.IPL5
						FROM 
							SICICidadeTecnologia 
						WHERE 
							SICICidadeTecnologia.PeriodoApuracao = '".$local_PeriodoApuracao."' AND
							SICICidadeTecnologia.IdPais = '".$Cidade[$cont_c][IdPais]."' AND
							SICICidadeTecnologia.IdEstado = '".$Cidade[$cont_c][IdEstado]."' AND
							SICICidadeTecnologia.IdCidade = '".$Cidade[$cont_c][IdCidade]."' AND
							SICICidadeTecnologia.IdTecnologia = '".$lin_tc[IdTecnologia]."';";
			$res_in = @mysql_query($sql_in, $con);
			$lin_in = @mysql_fetch_array($res_in);
			$IPL6CapacidadeTotalImplantada += $lin_in[IPL5];
		}
		
		$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Municipio codmunicipio=\"".$Cidade[$cont_c][CodigoCidade]."\" valor=\"".number_format($IPL6CapacidadeTotalImplantada, 2, ",", "")."\" />";
	}
	
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IAU 1 - STRING
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IAU1\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo valor=\"".$lin[IAU1]."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IPL 1 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IPL1\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IPL1TotalKMCaboPrestadora], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"b\" valor=\"".number_format($lin[IPL1TotalKMCaboTerceiro], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"c\" valor=\"".number_format($lin[IPL1CrescimentoPrevistoKMCaboPrestadora], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"d\" valor=\"".number_format($lin[IPL1CrescimentoPrevistoKMCaboTerceiro], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IPL 2 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IPL2\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" value=\"".number_format($lin[IPL2TotalKMFibraPrestadora], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"b\" value=\"".number_format($lin[IPL2TotalKMFibraTerceiro], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"c\" value=\"".number_format($lin[IPL2CrescimentoPrevistoKMFibraPrestadora], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"d\" value=\"".number_format($lin[IPL2CrescimentoPrevistoKMFibraTerceiro], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 1 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM1\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM1Indicador], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"b\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoMarketing], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"c\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoEquipamento], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"d\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoSoftware], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"e\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoPesquisaDesenvolvimento], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"f\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoServico], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"g\" valor=\"".number_format($lin[IEM1ValorTotalAplicadoCentralAtendimento], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 2 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM2\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM2ValorFaturamentoServico], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"b\" valor=\"".number_format($lin[IEM2ValorFaturamentoIndustrizalizacaoServico], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"c\" valor=\"".number_format($lin[IEM2ValorFaturamentoServicoAdicional], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 3 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM3\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM3], 2, ",", "")."\" />"; 
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 6 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM6\">";
	$sql_tmp = "SELECT 
					SUM(Contrato.Valor) Valor
				FROM
					(SELECT
						LancamentoFinanceiro.Valor
					FROM
						Servico,
						Contrato,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						ContaReceber LEFT JOIN NotaFiscal ON (
							ContaReceber.IdLoja = NotaFiscal.IdLoja AND
							ContaReceber.IdContaReceber = NotaFiscal.IdContaReceber AND 
							NotaFiscal.IdStatus = 1 
						)
					WHERE 
						Servico.IdLoja = '$local_IdLoja' AND 
						Servico.IdTipoServico = '1' AND 
						Servico.Tecnologia is not null AND 
						Servico.IdLoja = Contrato.IdLoja AND 
						Servico.IdServico = Contrato.IdServico AND 
						Contrato.IdStatus >= '200' AND
						Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
						Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
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
						)
						$where
					) Contrato;";
	$res_tmp = mysql_query($sql_tmp, $con);
	$lin_tmp = @mysql_fetch_array($res_tmp);
	$IEM6TotalBruto = $lin_tmp[Valor];
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM6] , 2, ',', '')."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 7 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM7\">";
	$IEM7TotalLiquido = $IEM6TotalBruto - (int)(str_replace(",", "", $lin[IEM8ValorDespesaInterconexao]));
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM7] , 2, ',', '')."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	# IEM 8 - FLOAT
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t<Indicador Sigla=\"IEM8\">";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"a\" valor=\"".number_format($lin[IEM8ValorTotalCustos], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"b\" valor=\"".number_format($lin[IEM8ValorDespesaOperacaoManutencao], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"c\" valor=\"".number_format($lin[IEM8ValorDespesaPublicidade], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"d\" valor=\"".number_format($lin[IEM8ValorDespesaVenda], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t\t<Conteudo item=\"e\" valor=\"".number_format($lin[IEM8ValorDespesaInterconexao], 2, ",", "")."\" />";
	$LinhaXML[count($LinhaXML)] = "\n\t\t\t</Indicador>";
	$LinhaXML[count($LinhaXML)] = "\n\t\t</Outorga>";
	$LinhaXML[count($LinhaXML)] = "\n\t</UploadSICI>";
	$LinhaXML[count($LinhaXML)] = "\n</root>";
	$NomeXML = "SICI_".$local_PeriodoApuracao.".xml";
	
	if((int)$local_IdMetodoExportacao == 2){
		$FileName = "temp/".$NomeXML;
		$Anexo = $Path.$FileName;
		
		@unlink($Anexo);
		
		$File = fopen($Anexo, "a");
		
		fwrite($File, implode("", $LinhaXML));			
		fclose($File);
	} else{
		header("content-type: text/xml");
		header("Content-Disposition: attachment; filename=".$NomeXML);
		
		echo implode("", $LinhaXML);
	}
?>