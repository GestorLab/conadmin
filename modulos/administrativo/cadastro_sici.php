<?php
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "V";	
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$local_IdLoja											= $_SESSION["IdLoja"];
	$local_Login											= $_SESSION["Login"];
	$local_Erro												= $_GET["Erro"];
	$local_Acao 											= $_POST["Acao"];
	$local_IdTipoApuracao									= $_POST["IdTipoApuracao"];
	$local_PeriodoApuracao									= $_POST["PeriodoApuracao"];
	$local_ModeloNF											= $_POST["IdNF"];
	$local_NumeroFistel										= $_POST["NumeroFistel"];
	$local_IAU1NumeroCAT									= $_POST["IAU1NumeroCAT"];
	$local_IPL1TotalKMCaboPrestadora						= (float)str_replace(",", ".", $_POST["IPL1TotalKMCaboPrestadora"]);
	$local_IPL1TotalKMCaboTerceiro							= (float)str_replace(",", ".", $_POST["IPL1TotalKMCaboTerceiro"]);
	$local_IPL1CrescimentoPrevistoKMCaboPrestadora			= (float)str_replace(",", ".", $_POST["IPL1CrescimentoPrevistoKMCaboPrestadora"]);
	$local_IPL1CrescimentoPrevistoKMCaboTerceiro			= (float)str_replace(",", ".", $_POST["IPL1CrescimentoPrevistoKMCaboTerceiro"]);
	$local_IPL2TotalKMFibraPrestadora						= (float)str_replace(",", ".", $_POST["IPL2TotalKMFibraPrestadora"]);
	$local_IPL2TotalKMFibraTerceiro							= (float)str_replace(",", ".", $_POST["IPL2TotalKMFibraTerceiro"]);
	$local_IPL2CrescimentoPrevistoKMFibraPrestadora			= (float)str_replace(",", ".", $_POST["IPL2CrescimentoPrevistoKMFibraPrestadora"]);
	$local_IPL2CrescimentoPrevistoKMFibraTerceiro			= (float)str_replace(",", ".", $_POST["IPL2CrescimentoPrevistoKMFibraTerceiro"]);
	$local_IEM1Indicador									= (float)str_replace(",", ".", $_POST["IEM1Indicador"]);
	$local_IEM1ValorTotalAplicadoMarketing					= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoMarketing"]);
	$local_IEM1ValorTotalAplicadoEquipamento				= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoEquipamento"]);
	$local_IEM1ValorTotalAplicadoSoftware					= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoSoftware"]);
	$local_IEM1ValorTotalAplicadoPesquisaDesenvolvimento	= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoPesquisaDesenvolvimento"]);
	$local_IEM1ValorTotalAplicadoServico					= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoServico"]);
	$local_IEM1ValorTotalAplicadoCentralAtendimento			= (float)str_replace(",", ".", $_POST["IEM1ValorTotalAplicadoCentralAtendimento"]);
	$local_IEM2ValorFaturamentoServico						= (float)str_replace(",", ".", $_POST["IEM2ValorFaturamentoServico"]);
	$local_IEM2ValorFaturamentoIndustrizalizacaoServico		= (float)str_replace(",", ".", $_POST["IEM2ValorFaturamentoIndustrizalizacaoServico"]);
	$local_IEM2ValorFaturamentoServicoAdicional				= (float)str_replace(",", ".", $_POST["IEM2ValorFaturamentoServicoAdicional"]);
	$local_IEM3ValorInvestimentoRealizado					= (float)str_replace(",", ".", $_POST["IEM3ValorInvestimentoRealizado"]);
	$local_IEM6TotalBruto									= (float)str_replace(",", ".", $_POST["IEM6TotalBruto"]);
	$local_IEM7TotalLiquido									= (float)str_replace(",", ".", $_POST["IEM7TotalLiquido"]);
	$local_IEM8ValorTotalCusto								= (float)str_replace(",", ".", $_POST["IEM8ValorTotalCusto"]);
	$local_IEM8ValorDespesaOperacaoManutencao				= (float)str_replace(",", ".", $_POST["IEM8ValorDespesaOperacaoManutencao"]);
	$local_IEM8ValorDespesaPublicidade						= (float)str_replace(",", ".", $_POST["IEM8ValorDespesaPublicidade"]);
	$local_IEM8ValorDespesaVenda							= (float)str_replace(",", ".", $_POST["IEM8ValorDespesaVenda"]);
	$local_IEM8ValorDespesaInterconexao						= (float)str_replace(",", ".", $_POST["IEM8ValorDespesaInterconexao"]);
	
	if($local_PeriodoApuracao == ""){
		$local_PeriodoApuracao = $_GET["PeriodoApuracao"];
	}
	
	switch($local_Acao){
		case "inserir":
			include("files/inserir/inserir_sici.php");
			break;
		case "processar":
			include("rotinas/processar_sici.php");
			break;
		case "confirmar":
			include("rotinas/confirmar_sici.php");
			break;
		case "entregar":
			include("rotinas/confirmar_entrega_sici.php");
			break;
		case "cancelar":
			include("rotinas/cancelar_sici.php");
			break;
		case "exportar":
			header("Location: cadastro_sici_exporta.php?PeriodoApuracao=$local_PeriodoApuracao");
			break;
		default:
			$local_Acao = "inserir";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='js/sici.js'></script>
		<script type='text/javascript' src='js/sici_default.js'></script>
		
    	<style type="text/css">
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
			.campo { vertical-align:top; }
			#cp_Status { text-align:right; font-size:17px; font-weight: bold; }
			#tb_Visualizar, #bt_imprimir { margin-top:10px; display:none; }
		</style>
	</head>
	<body onLoad="ativaNome('SICI')">
		<div class='ocultarPrint'><? include('filtro_sici.php'); ?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_sici.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='SICI' />
				<input type='hidden' name='IdStatus' value='0' />
				<input type='hidden' name='IdTipoApuracao' value='0' />
				<input type='hidden' name='Moeda' value='<?=getParametroSistema(5,1)?>' />
				<input type='hidden' name='VisualizacaoLimit' value='<?=getCodigoInterno(17,1)?>' />
				<input type='hidden' name='QtdLancamento' value='0' />
				<input type='hidden' name='MesAtual' value='<?=date("m/Y")?>' />
				<input type='hidden' name='IdNFLayout' value='' />
				<div >
					<div>
						<div id='cp_tit' style='margin-top:0'>Filtros</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='tit_PeriodoApuracao'>Período de Apuração</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='tit_NF'>Mod. Nota Fiscal</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descricao' rowspan='2' id='status'><div id="cp_Status">&nbsp;</div></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input style='width:180px' type='text' name='PeriodoApuracao' value='' autocomplete='off' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" maxlength='7' onkeypress="mascara(this,event,'mes')" onchange="busca_sici(this.value, true, document.formulario.Local.value);" />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdNF' onFocus="Foco(this,'in')"  style='width:360px' onBlur="Foco(this,'out');">
										<option value=''>&nbsp;</option>
										<?
											$sql = "select
														IdNotaFiscalLayout,
														Modelo,
														DescricaoNotaFiscalLayout
													from
														NotaFiscalLayout
													where
														IdNotaFiscalLayout = 1 or IdNotaFiscalLayout = 2
													order by
														DescricaoNotaFiscalLayout";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdNotaFiscalLayout]'>$lin[DescricaoNotaFiscalLayout]</option>";
											}
										?>
									</select>							
								</td>
								<td class='separador'>&nbsp;</td>
							</tr>
						</table>
						<table style='display:none' id='tb_fistel'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='tit_Fistel' style='display:none;'>Número do FISTEL</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input style='width:180px; display:none;' type='text' name='NumeroFistel' value='' autocomplete='off' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='11' />
									<div id='cp_dc_Fistel'></div>
								</td>
							</tr>
						</table>
					</div>
					<div style='margin-top:10px;'><div id='bl_IndicadoresSICIUFMunicipio' style='display:none;'></div></div>
					<div id='bl_IndicadoresSICI' style='display:none;'>
						<div id='cp_tit' style='margin-top:0'>Indicadores</div>
						<div id="IAU1" style="display:none;">
							<div id='cp_sub_tit'><u>IAU 1</u> - Prestação do Serviço de Comunicação Multimídia.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Número do Centro de Atendimento Telefônico:</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IAU1NumeroCAT' value='' autocomplete='off' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='19' />
										<div id='cp_dc_IAU1NumeroCAT'></span>
									</td>
								</tr>
							</table>
						</div>
						<div id="IPL1" style="display:none;">
							<div id='cp_sub_tit'><u>IPL 1</u> - Rede de Fibra Óptica (Quantidade de Cabos).</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Total em KM de cabo da Prestadora:</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Crescimento previsto em KM do cabo da Prestadora:</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL1TotalKMCaboPrestadora' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL1TotalKMCaboPrestadora'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL1CrescimentoPrevistoKMCaboPrestadora' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL1CrescimentoPrevistoKMCaboPrestadora'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Total em KM de cabo de Terceiros:</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Crescimento previsto em KM do cabo de Terceiros:</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL1TotalKMCaboTerceiro' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL1TotalKMCaboTerceiro'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL1CrescimentoPrevistoKMCaboTerceiro' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL1CrescimentoPrevistoKMCaboTerceiro'></div>
									</td>
								</tr>
							</table>
						</div>
						<div id="IPL2" style="display:none;">
							<div id='cp_sub_tit'><u>IPL 2</u> - Rede de Fibra Óptica (Quantidade de Fibras).</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Total em KM de fibra implantada pela Prestadora:</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Crescimento previsto em KM de fibra da Prestadora:</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL2TotalKMFibraPrestadora' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL2TotalKMFibraPrestadora'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL2CrescimentoPrevistoKMFibraPrestadora' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL2CrescimentoPrevistoKMFibraPrestadora'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Total em KM de fibra implantada por Terceiros:</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Crescimento previsto em KM de fibra de Terceiros:</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL2TotalKMFibraTerceiro' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL2TotalKMFibraTerceiro'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IPL2CrescimentoPrevistoKMFibraTerceiro' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='21' />
										<div id='cp_dc_IPL2CrescimentoPrevistoKMFibraTerceiro'></div>
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM1" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 1</u> - Investimento na Planta.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Indicador (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em Marketing/Propaganda (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1Indicador' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1Indicador'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoMarketing' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoMarketing'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em equipamentos (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em software (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoEquipamento' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoEquipamento'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoSoftware' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoSoftware'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em Pesquisa e Desenv. (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em serviços (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoPesquisaDesenvolvimento' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoPesquisaDesenvolvimento'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoServico' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoServico'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Valor total aplicado em central de atendimento (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM1ValorTotalAplicadoCentralAtendimento' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM1ValorTotalAplicadoCentralAtendimento'></div>
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM2" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 2</u> - Faturamento com a Prestação do Serviço.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Faturamento com prestação do serviço (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Faturamento com exploração ind. de serviços (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM2ValorFaturamentoServico' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM2ValorFaturamentoServico'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM2ValorFaturamentoIndustrizalizacaoServico' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM2ValorFaturamentoIndustrizalizacaoServico'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Faturamento com provimento de serviços de valor adicionado (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM2ValorFaturamentoServicoAdicional' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM2ValorFaturamentoServicoAdicional'></div>
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM3" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 3</u> - Investimentos realizados.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Valor consolidado do investimento realizado (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM3ValorInvestimentoRealizado' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='18' />
										<div id='cp_dc_IEM3ValorInvestimentoRealizado'></div>
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM6" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 6</u> - Receita Operacional Bruta.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'>Receita operacional bruta total (<?=getParametroSistema(5,1)?>):</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM6TotalBruto' value='' maxlength='18' readonly='readonly' />
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM7" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 7</u> - Receita Operacional Líquida.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'>Receita operacional líquida total (<?=getParametroSistema(5,1)?>):</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM7TotalLiquido' value='' maxlength='18' readonly='readonly' />
									</td>
								</tr>
							</table>
						</div>
						<div id="IEM8" style="display:none;">
							<div id='cp_sub_tit'><u>IEM 8</u> - Despesas envolvendo operação e manutenção, publicidade e vendas e interconexão.</div>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Despesas envolvendo operação e manutenção (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Despesas envolvendo publicidade (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM8ValorDespesaOperacaoManutencao' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calcula_iem7();" maxlength='18' />
										<div id='cp_dc_IEM8ValorDespesaOperacaoManutencao'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM8ValorDespesaPublicidade' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calcula_iem7();" maxlength='18' />
										<div id='cp_dc_IEM8ValorDespesaPublicidade'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><b>Despesas envolvendo vendas (<?=getParametroSistema(5,1)?>):</b></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><b>Despesas envolvendo interconexão (<?=getParametroSistema(5,1)?>):</b></td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM8ValorDespesaVenda' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calcula_iem7();" maxlength='18' />
										<div id='cp_dc_IEM8ValorDespesaVenda'></div>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM8ValorDespesaInterconexao' value='' autocomplete='off' onkeypress="mascara(this,event,'float')" onkeydown="backspace(this,event)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calcula_iem7();" maxlength='18' />
										<div id='cp_dc_IEM8ValorDespesaInterconexao'></div>
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'>Valor total dos custos (<?=getParametroSistema(5,1)?>):</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo' style='vertical-align:top;'>
										<input style='width:399px' type='text' name='IEM8ValorTotalCusto' value='0,00' maxlength='18' readonly='readonly' />
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div id='cp_log'>
						<div id='cp_tit' style='margin-top:10px'>Log</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Usuário Cadastro</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Cadastro</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Usuário Confirmação</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Confirmação</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginConfirmacao' value='' style='width:180px' maxlength='20' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataConfirmacao' value='' style='width:203px' readOnly>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Usuário Processamento</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Processamento</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Usuário Confirmação de Entrega</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Data Confirmação de Entrega</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginProcessamento' value='' style='width:180px' maxlength='20' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataProcessamento' value='' style='width:202px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginConfirmacaoEntrega' value='' style='width:180px' maxlength='20' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataConfirmacaoEntrega' value='' style='width:203px' readOnly>
								</td>
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='capo'>
									<input type='button' name='bt_exportar' value='Exportar' class='botao' onClick="cadastrar('exportar');" />
									<input type='button' name='bt_visualizar' value='Visualizar' class='botao' onClick="visualizar(document.formulario.PeriodoApuracao.value);" />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' name='bt_inserir' value='Cadastrar' class='botao' onClick="cadastrar('inserir');" />
									<input type='button' name='bt_excluir' value='Excluir' class='botao' onClick="excluir(document.formulario.PeriodoApuracao.value);" />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='width:285px; text-align:right;'>
									<input type='button' name='bt_processar' value='Processar' class='botao' onClick="cadastrar('processar');" />
									<input type='button' name='bt_confirmar' value='Confirmar' class='botao' onClick="cadastrar('confirmar');" />
									<input type='button' name='bt_entrega' value='Confirmar Entrega' class='botao' onClick="cadastrar('entregar');" />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='width:80px; text-align:right;'>
									<input type='button' name='bt_cancelar' value='Cancelar' class='botao' onClick="cadastrar('cancelar');" />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
							</tr>
						</table>
					</div>
				</div>
				<div><h1 class='ocultar' id='tit_SICI'></h1></div>
				<div id='tb_Visualizar'>
					<div id='cp_tit' style='margin:0'>Visualizar</div>
					<table id='tabelaVisualizar' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>CO</td>
							<td>CR</td>
							<td>LF</td>
							<td>Descrição do Serviço</td>
							<td>Número NF</td>
							<td>Data NF</td>
							<td class='valor'>Valor&nbsp;(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor&nbsp;Desc.&nbsp;(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor&nbsp;Final&nbsp;(<?=getParametroSistema(5,1)?>)</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td id='tabelaTotalVisualizar' class='tableListarEspaco' colspan='7'>Total: 0</td>
							<td id='tabelaTotalValor' class='valor'>0,00</td>
							<td id='tabelaTotalValorDesconto' class='valor'>0,00</td>
							<td id='tabelaTotalValorFinal' class='valor'>0,00</td>
							<td class='find'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='bt_imprimir'>
					<table style='width:848px; margin-top:10px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='imprimir_frame();' />
							</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript">
		<?
			if($local_PeriodoApuracao != ''){
				echo "busca_sici('$local_PeriodoApuracao', false, document.formulario.Local.value);";
			}
		?>
			inicia();
			verificaAcao();
			verificaErro();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>