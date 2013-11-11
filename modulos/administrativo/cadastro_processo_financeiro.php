<?
	$localModulo		=	1;
	$localCancelar 		= $_GET['Cancelar'];
	
	if($localCancelar == true && $localCancelar != ""){
		$localOperacao		=	58; 
	}else{
		$localOperacao		=	3;
	}

	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdProcessoFinanceiro			= $_POST['IdProcessoFinanceiro'];
	$local_Status						= formatText($_POST['Status'],NULL);
	$local_IdStatus						= formatText($_POST['IdStatus'],NULL);
	$local_MesReferencia				= formatText($_POST['MesReferencia'],NULL);		
	$local_MesVencimento				= formatText($_POST['MesVencimento'],NULL);
	$local_MenorVencimento				= formatText($_POST['MenorVencimento'],NULL);
	$local_Filtro_IdPessoa				= formatText($_POST['Filtro_IdPessoa'],NULL);
	$local_Filtro_IdLocalCobranca		= formatText($_POST['Filtro_IdLocalCobranca'],NULL);
	$local_Filtro_IdLocalCobrancaTemp	= formatText($_POST['Filtro_IdLocalCobrancaTemp'],NULL);
	$local_Filtro_TipoLancamento		= formatText($_POST['Filtro_TipoLancamento'],NULL);
	$local_Filtro_IdTipoPessoa			= formatText($_POST['Filtro_IdTipoPessoa'],NULL);
	$local_Filtro_IdGrupoStatusContrato	= formatText($_POST['Filtro_IdGrupoStatusContrato'],NULL);
	$local_Filtro_IdStatusContrato		= formatText($_POST['Filtro_IdStatusContrato'],NULL);
	$local_Filtro_FormaAvisoCobranca	= formatText($_POST['Filtro_FormaAvisoCobranca'],NULL);
	$local_Filtro_IdContrato			= formatText($_POST['Filtro_IdContrato'],NULL);
	$local_Filtro_IdPaisEstadoCidade	= formatText($_POST['Filtro_IdPaisEstadoCidade'],NULL);
	$local_Filtro_IdServico				= formatText($_POST['Filtro_IdServico'],NULL);
	$local_Filtro_IdAgenteAutorizado	= formatText($_POST['Filtro_IdAgenteAutorizado'],NULL);
	$local_Filtro_TipoCobranca			= formatText($_POST['Filtro_TipoCobranca'],NULL);
	$local_Filtro_TipoContrato			= formatText($_POST['Filtro_TipoContrato'],NULL);
	$local_Filtro_IdGrupoPessoa			= formatText($_POST['Filtro_IdGrupoPessoa'],NULL);
	$local_Filtro_VencimentoContrato	= formatText($_POST['Filtro_VencimentoContrato'],NULL);
	$local_DataNotaFiscal				= formatText($_POST['DataNotaFiscal'],NULL);	
	$local_IdTipoLocalCobranca			= $_POST['IdTipoLocalCobranca'];
	$local_Cancelar						= $_GET['Cancelar'];
	$local_TipoEmail					= $_GET['TipoEmail'];
	
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
	
	$ImgOrdernarASC = "<img src='../../img/estrutura_sistema/seta_$localOrdemDirecao.gif' alt='Ordenado por' />";
	
	if($local_Cancelar == "" && $_POST['Cancelar']!=""){
		$local_Cancelar	=	$_POST['Cancelar'];
	}	
	
	if($_GET['IdProcessoFinanceiro']!=''){
		$local_IdProcessoFinanceiro	=	$_GET['IdProcessoFinanceiro'];		
	}
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];		
	}
	if($_GET['IdContrato']!=''){
		$local_IdContrato	=	$_GET['IdContrato'];
	}
	$IdStatusContratoObrigatoriedade = getCodigoInterno(3,230);
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_processo_financeiro.php');
			break;		
		case 'alterar':
			include('files/editar/editar_processo_financeiro.php');
			break;
		case 'processar':
			include('rotinas/processar_processo_financeiro.php');
			$local_Acao =	'processar';
			break;
		case 'confirmar':
			$sql = "select 
						count(*) Qtd
					from 
						LancamentoFinanceiro
					where 
						LancamentoFinanceiro.IdLoja = $local_IdLoja and 
						LancamentoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			if($lin[Qtd] <= (getCodigoInterno(17,1)*30)){
				include('rotinas/confirmar_processo_financeiro.php');
			}else{
				header("Location: processo_financeiro_confirmar.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");					
			}
			$local_Acao =	'confirmar';
			break;
		case 'cancelar':
			include('rotinas/cancelar_processo_financeiro.php');
			$local_Acao =	'cancelar';
			break;
		case 'imprimir':
			switch($local_IdTipoLocalCobranca){
				case '3':
					header("Location: cadastro_arquivo_remessa.php?IdLocalCobranca=$local_IdLocalCobranca");
					break;
				default:
					include('rotinas/rotina_processo_financeiro_gerar_boletos.php');
			}
			$local_Acao =	'imprimir';
			break;
		case 'enviar':			
			header("Location: processo_financeiro_confirmar_enviar_boletos.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");	
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/processo_financeiro.js'></script>
		<script type = 'text/javascript' src = 'js/processo_financeiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/agente_default.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_pessoa_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
	</head>
	<body  onLoad="ativaNome('<?=dicionario(682)?>');">
		<? include('filtro_processo_financeiro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_processo_financeiro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='AcaoTemp' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ProcessoFinanceiro'>
				<input type='hidden' name='EmailEnviado' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='Data' value='<?=date("Ymd")?>'>
				<input type='hidden' name='DataNotaFiscalTemp' value=''>
				<input type='hidden' name='Filtro_IdGrupoPessoa' value=''>
				<input type='hidden' name='Filtro_IdPessoa' value=''>
				<input type='hidden' name='Filtro_IdContrato' value=''>
				<input type='hidden' name='Filtro_VencimentoContrato' value=''>
				<input type='hidden' name='Filtro_IdPaisEstadoCidade' value=''>
				<input type='hidden' name='Filtro_IdServico' value=''>
				<input type='hidden' name='Filtro_IdStatusContrato' value=''>
				<input type='hidden' name='Filtro_IdAgenteAutorizado' value=''>
				<input type='hidden' name='Filtro_IdLocalCobrancaTemp' value=''>
				<input type='hidden' name='DataEmissao' value=''>
				<input type='hidden' name='Visualizar' value=''>
				<input type='hidden' name='IdTipoLocalCobranca' value=''>
				<input type='hidden' name='QdtLancamentos' value=''>
				<input type='hidden' name='QtdContaReceber' value=''>
				<input type='hidden' name='Cancelar' value='<?=$local_Cancelar?>'>
				<input type='hidden' name='Ordenacao' value='ASC'>
				<input type='hidden' name='Botoes_Financeiro' value='Visualizar'>
				<input type='hidden' name='IdStatusContratoObrigatoriedade' value='<?=$IdStatusContratoObrigatoriedade?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(657)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProcessoFinanceiro' value='<?=$local_IdProcessoFinanceiro?>' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_processo_financeiro(this.value, true, document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 732px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(656)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cp_MesReferencia'><?=dicionario(196)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_MesVencimento'><?=dicionario(508)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(658)?>.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(40)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(1052)?></B></td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataNotaFiscal' style='display:none'><?=dicionario(859)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesReferencia' value='' style='width:90px' maxlength='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="mascara(this,event,'mes')" onChange="verifica_mes('cp_MesReferencia',this)"  tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesVencimento' value='' style='width:100px' maxlength='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="mascara(this,event,'mes')" onChange="verifica_mes('cp_MesVencimento',this);"  tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='MenorVencimento' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"  tabindex='4'>
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:250px' onBlur="Foco(this,'out');" tabindex='5' onChange="verificaLocalCobranca(this.value);">
									<option value=''>&nbsp;</option>
									<?/*
										$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobrancaGeracao where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
										}*/
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_TipoCobranca' id='cpFiltro_TipoCobranca' style='width:109px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=134 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>				
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNotaFiscal' id='cpDataNotaFiscal' style='width:105px; display:none' value='' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataNotaFiscal',this)" tabindex='6'>
							</td>
							<td class='find'><img id='cpDataNotaFiscalIco' style='display:none' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataNotaFiscal",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataNotaFiscalIco"
							    });
							</script>
						</tr>
					</table>
					<!--table>
						<tr>
							<td class='find'>&nbsp;</td>
							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							
						</tr>
					</table-->
				</div>
				<div>
					<div class='cp_tit' id='Filtros'><?=dicionario(559)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><?=dicionario(659)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(226)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(731)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(857)?></td>
						</tr>
						<tr>								
							<td class='find'>&nbsp;</td> 	
							<td class='campo'>
								<select name='Filtro_TipoLancamento' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  style='width:181px' onChange='verificaTipoLancamento(this.value)' tabindex='6'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=49 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_TipoContrato' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  style='width:181px' tabindex='6'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_IdTipoPessoa' style='width:181px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 1 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_FormaAvisoCobranca' style='width:246px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=76 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<div id='cp_filtro_status'>
						<table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?if($IdStatusContratoObrigatoriedade == 1){ echo "<B>"; }?><?=dicionario(174)?><?if($IdStatusContratoObrigatoriedade == 1){ echo "<B>"; }?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_status' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='12' onClick="addStatus(document.formulario.IdStatusContrato.value,'')">
								</td>
							</tr>
						</table>
						<table id='tabelaStatus' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(141)?></td>
								<td><?=dicionario(140)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaStatus'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_vencimento_contrato'>
						<table id='titTabelaVencimentoContrato'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(860)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='VencimentoContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select distinct DiaCobranca from Contrato where IdLoja = $local_IdLoja order by DiaCobranca";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[DiaCobranca]'>$lin[DiaCobranca]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_vencimento_contrato' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='14' onClick="addVencimentoContrato(document.formulario.VencimentoContrato.value,'')">
								</td>
							</tr>
						</table>
						<table id='tabelaVencimentoContrato' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'><?=dicionario(860)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaVencimentoContrato'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>					
					<div id='cp_filtro_grupo_pessoa'>
						<table id='titTabelaPessoa' style='margin:10px 0 5px 0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:40px; color:#000'><?=dicionario(149)?></B><?=dicionario(794)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value=''; valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdGrupoPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_grupo_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'><input type='text' class='agrupador' name='DescricaoGrupoPessoa' value='' style='width:646px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_grupo_pessoa' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='16' onClick="busca_grupo_pessoa(document.formulario.IdGrupoPessoa.value,false,'AdicionarProcessoFinanceiro')">
								</td>
							</tr>
						</table>					
						<table id='tabelaGrupoPessoa' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'><?=dicionario(141)?></td>
								<td><?=dicionario(106)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='8' id='totaltabelaGrupoPessoa'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_pessoa'>
					<?	
						$nome="	<table id='titTabelaPessoa' style='margin:10px 0 5px 0'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B>".dicionario(85)."</td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='17'><input type='text' class='agrupador' name='Nome' value='' style='width:646px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_pessoa' value='".dicionario(640)."' class='botao' style='width:84px;' tabindex='18' onClick=\"busca_pessoa(document.formulario.IdPessoa.value,false,'AdicionarProcessoFinanceiro')\">
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='titTabelaPessoa' style='margin:10px 0 5px 0'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='17'><input type='text' class='agrupador' name='Nome' value='' style='width:646px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_pessoa' value='Adicionar' class='botao' style='width:84px;' tabindex='18' onClick=\"busca_pessoa(document.formulario.IdPessoa.value,false,'AdicionarProcessoFinanceiro')\">
									</td>
								</tr>
							</table>";
							
							switch(getCodigoInterno(3,24)){
								case 'Nome':
									echo "$razao";
									break;
								default:
									echo "$nome";
							}
						?>
						<table id='tabelaPessoa' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(26)?></td>
								<td><?=dicionario(172)?></td>
								<td><?=dicionario(85)?></td>
								<td><?=dicionario(213)?></td>
								<td><?=dicionario(194)?></td>
								<td><?=dicionario(186)?></td>
								<td><?=dicionario(139)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='8' id='totaltabelaPessoa'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_cidade'>
						<table style='margin:10px 0 5px 0' id='titTabelaCidade'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:54px; color:#000'><?=dicionario(256)?></B><?=dicionario(257)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(157)?></b><?=dicionario(259)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(186)?></B><?=dicionario(260)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaPais', true, event, null, 265); document.formularioPais.NomePais.value=''; valorCampoPais = ''; busca_pais_lista(); document.formularioPais.NomePais.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19' onChange="busca_pais(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')"><input  class='agrupador' type='text' name='Pais' value='<?=$local_Pais?>' style='width:138px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaEstado', true, event, null, 265); document.formularioEstado.NomeEstado.value=''; valorCampoEstado = ''; busca_estado_lista(); document.formularioEstado.NomeEstado.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20' onChange='busca_estado(document.formulario.IdPais.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Estado' value='<?=$local_Estado?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaCidade', true, event, null, 265); document.formularioCidade.NomeCidade.value=''; valorCampoCidade = ''; busca_cidade_lista(); document.formularioCidade.NomeCidade.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21' onChange='busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Cidade' value='<?=$local_Cidade?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_cidade' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='22' onClick="busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,document.formulario.IdCidade.value,false,'Servico')">
								</td>
							</tr>
						</table>
						<table id='tabelaCidade' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'>&nbsp;</td>
								<td><?=dicionario(257)?></td>
								<td><?=dicionario(259)?></td>
								<td><?=dicionario(260)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaCidade'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_servico'>
						<table style='margin:10px 0 5px 0' id='titTabelaServico'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:34px; color:#000'><?=dicionario(30)?></b><?=dicionario(223)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(436)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 350);"></td>
								<td class='campo'>
									<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='23'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:455px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdTipoServico' style='width:180px' disabled>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_servico' value='Adicionar' class='botao' style='width:84px;' tabindex='24' onClick="busca_servico(document.formulario.IdServico.value,false,'AdicionarServico','busca')">
								</td>
							</tr>
						</table>
						<table id='tabelaServico' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(30)?></td>
								<td><?=dicionario(223)?></td>
								<td><?=dicionario(436)?></td>
								<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaServico'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_contrato'>
						<table style='margin:10px 0 5px 0' id='titTabelaContrato'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(27)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(224)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(226)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContrato', true, event, null, 285);"></td>
								<td class='campo'>
									<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value);" tabindex='25'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdServicoContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServicoContrato' value='' style='width:300px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescPeriodicidadeContrato' value=''  style='width:145px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescTipoContrato' value=''  style='width:80px' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_contrato' value='Adicionar' class='botao' style='width:84px;' tabindex='25' onClick="addContrato()">
								</td>
							</tr>
						</table>
						<table id='tabelaContrato' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(27)?></td>
								<td><?=dicionario(85)?></td>
								<td><?=dicionario(223)?></td>
								<td><?=dicionario(82)?></td>
								<td><?=dicionario(283)?></td>
								<td><?=dicionario(284)?></td>
								<td><?=dicionario(285)?>.</td>
								<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
								<td class='valor'><?=dicionario(286)?>. (<?=getParametroSistema(5,1)?>)</td>
								<td class='valor'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
								<td><?=dicionario(140)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='12' id='totaltabelaContrato'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
				</div>
				<div id='cp_filtro_agente'>			
					<table id='titTabelaAgente' style='margin:10px 0 5px 0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:5px; color:#000'><?=dicionario(32)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(32)?>' onClick="vi_id('quadroBuscaAgente', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdAgenteAutorizado' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_agente(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='26'><input type='text' class='agrupador' name='NomeAgenteAutorizado' value='' style='width:646px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_add_agente' value='Adicionar' class='botao' style='width:84px;' tabindex='27' onClick="busca_agente(document.formulario.IdAgenteAutorizado.value,false,'AdicionarProcessoFinanceiro')">
							</td>
						</tr>
					</table>

					<table id='tabelaAgente' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:60px'><?=dicionario(778)?></td>
							<td><?=dicionario(178)?></td>
							<td><?=dicionario(140)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='4' id='totaltabelaAgente'><?=dicionario(128)?>: 0</td>
						</tr>
					</table>
				</div>				
				<div id='cp_log'>
					<div id='cp_tit' style='margin-top:10px'><?=dicionario(804)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(870)?> [ <a href="#" onclick="atualizaLogProcessamento('',false,document.formulario.IdProcessoFinanceiro.value)"><?=dicionario(264)?></a> ]</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='LogProcessamento' style='width: 816px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'   readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'    readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'   maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:202px'  readOnly>
							</td>								
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(784)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(785)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(786)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(787)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:202px'readOnly>
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
				</div>
				<div class='cp_botao'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:70px' name='bt_visualizar' value='<?=dicionario(871)?>' class='botao' tabindex='102' onClick="visualizar_botoes_financeiro(this.name)">
							</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:95px' name='bt_enviar' value='<?=dicionario(401)?>' class='botao' tabindex='103' onClick="cadastrar('enviar')">
								<input type='button' style='width:100px' name='bt_imprimir' value='<?=dicionario(402)?>' class='botao' tabindex='104' onClick="cadastrar('imprimir')">
							</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:75px' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='105' onClick="cadastrar('inserir')">
								<input type='button' style='width:60px' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='106' onClick="cadastrar('alterar')">
								<input type='button' style='width:60px' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='107' onClick="excluir(document.formulario.IdProcessoFinanceiro.value)">
							</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='separador' style='width:12px'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:80px' name='bt_processar' value='<?=dicionario(789)?>' class='botao' tabindex='108' onClick="cadastrar('processar')">
								<input type='button' style='width:70px' name='bt_confirmar' value='<?=dicionario(404)?>' class='botao' tabindex='109' onClick="cadastrar('confirmar')">
								<input type='button' style='width:70px' name='bt_cancelar' value='<?=dicionario(405)?>' class='botao' tabindex='110' onClick="cadastrar('cancelar')">
							</td>
						</tr>						
					</table>
				</div>
				<div class='cp_botao' id='mostra_LF_CR' style='display:none'>
					<table>
						<tr style='float:left'>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:160px' name='bt_LF' value='<?=dicionario(649)?>' class='botao' tabindex='102' onClick="buscaVisualizar(true);">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:125px' name='bt_CR' value='<?=dicionario(56)?>' class='botao' tabindex='103' onClick="buscaVisualizarContaReceber(true)">
							</td>
						</tr>
					</table>
				</div>
				<div>
					<table style='width:100%;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>				
				<div id='cp_lancamentos_financeiros' style='display:none;'>
					<div id='cp_tit' style='margin:10px 0 0 0'><?=dicionario(872)?></div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='cursor: pointer'><?=dicionario(873)?>.</td>
							<td style='cursor: pointer'><?=dicionario(1035)?></td>
							<td id='Tipo' style='cursor: pointer'><?=dicionario(82)?></td>
							<td style='cursor: pointer'><?=dicionario(678)?>.</td>
							<td style='cursor: pointer'><?=dicionario(85)?></td>
							<td style='cursor: pointer'><?=dicionario(125)?></td>
							<td style='cursor: pointer'><?=dicionario(202)?></td>
							<td class='valor' style='cursor: pointer'><?=dicionario(204)?></td>
							<td class='bt_lista' style='cursor: pointer'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='7' id='tabelaLancFinanceiroTotal'></td>
							<td id='tabelaLancFinanceiroTotalValor' class='valor'>0,00</td>
							<td>&nbsp;</td>
						</tr>
					</table>
					<table id='helpText2table' style='display:none; width:100%'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>						
					</table>
				</div>
				<div id='cp_contas_receber' style='display:none;'>
					<div id='cp_tit' style='margin:10px 0 0 0'><?=dicionario(1034)?></div>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(423)?></td>
							<td><?=dicionario(760)?></td>
							<td><?=dicionario(362)?></td>
							<td><?=dicionario(200)?></td>
							<td><?=dicionario(399)?></td>
							<td class='valor'><?=dicionario(204)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaContaReceberTotal'></td>
							<td id='tabelaContaReceberTotalValor' class='valor'>0,00</td>
							<td>&nbsp;</td>
						</tr>
					</table>
					<table id='helpText2table' style='display:none; width:100%'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/grupo_pessoa.php");
				include("files/busca/pessoa.php");
				include("files/busca/pais.php");
				include("files/busca/estado.php");
				include("files/busca/cidade.php");
				include("files/busca/contrato.php");
				include("files/busca/servico.php");
				include("files/busca/agente.php");
			?>
		</div>
	</body>	
</html>
<script>
	quantLancamentos =<?= getCodigoInterno(17,1);?>;
<?	
	if($local_IdProcessoFinanceiro!=''){
		echo "busca_processo_financeiro($local_IdProcessoFinanceiro,false);";		
		echo "scrollWindow('bottom');";		
	}else{
		if($local_IdPessoa != ''){
			echo "busca_pessoa($local_IdPessoa,false,'AdicionarProcessoFinanceiro');";
		}
		if($local_IdContrato != ''){
			echo "busca_contrato($local_IdContrato,false,'AdicionarContrato');";
		}
	}
?>
	function statusInicial(){
		if(document.formulario.IdProcessoFinanceiro.value == ''){
			document.formulario.Filtro_TipoLancamento.value  = '<?=getCodigoInterno(3,35)?>';
		}
		if(document.formulario.IdStatus.value == ''){
			document.formulario.IdStatus.value  = '<?=getCodigoInterno(3,13)?>';
		}
	}
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>