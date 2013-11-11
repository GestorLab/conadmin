<?
	$localModulo		=	1;
	$localOperacao		=	30;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$extensoesTitle = "Extens&#245;es Suportadas:<br/>&nbsp&nbsp&nbsp&nbsp *.gif *.jpg *.png";
	$extensoes = "new Array('gif','jpg','png')";
	
	$local_IdLocalCobranca						= $_POST['IdLocalCobranca'];
	$local_IdTipoLocalCobranca					= $_POST['IdTipoLocalCobranca'];
	$local_IdNotaFiscalTipo						= $_POST['IdNotaFiscalTipo'];
	$local_DescricaoLocalCobranca				= formatText($_POST['DescricaoLocalCobranca'],NULL);
	$local_AbreviacaoNomeLocalCobranca			= formatText($_POST['AbreviacaoNomeLocalCobranca'],NULL);
	$local_AvisoRegressivo						= formatText($_POST['AvisoRegressivo'],NULL);
	$local_IdArquivoRetornoTipo					= formatText($_POST['IdArquivoRetornoTipo'],NULL);
	$local_IdLocalCobrancaLayout				= formatText($_POST['IdLocalCobrancaLayout'],NULL);
	$local_IdDuplicataLayout					= $_POST['IdDuplicataLayout'];
	$local_ValorDespesaLocalCobranca			= formatText($_POST['ValorDespesaLocalCobranca'],NULL);
	$local_PercentualJurosDiarios				= formatText($_POST['PercentualJurosDiarios'],NULL);
	$local_PercentualMulta						= formatText($_POST['PercentualMulta'],NULL);
	$local_ValorCobrancaMinima					= formatText($_POST['ValorCobrancaMinima'],NULL);
	$local_IdArquivoRemessaTipo					= formatText($_POST['IdArquivoRemessaTipo'],NULL);
	$local_ValorTaxaReImpressaoBoleto			= formatText($_POST['ValorTaxaReImpressaoBoleto'],NULL);
	$local_IdLojaCobrancaUnificada				= $_POST['IdLojaCobrancaUnificada'];	
	$local_IdLocalCobrancaUnificada				= $_POST['IdLocalCobrancaUnificada'];
	$local_IdPessoa								= formatText($_POST['IdPessoa'],NULL);
	$local_EndArquivo							= formatText($_POST['EndArquivo'],NULL);
	$local_OpcaoImagem							= formatText($_POST['OpcaoImagem'],NULL);
	$local_DiasCompensacao						= formatText($_POST['DiasCompensacao'],NULL);
	$local_AvisoFaturaAtraso					= $_POST['AvisoFaturaAtraso'];
	$local_IdStatus								= $_POST['IdStatus'];
	$local_InicioNossoNumero					= $_POST['InicioNossoNumero'];
	$local_IdAtualizarVencimentoViaCDA			= $_POST['IdAtualizarVencimentoViaCDA'];
	$local_IdAtualizarRemessaViaCDA				= $_POST['IdAtualizarRemessaViaCDA'];
	$local_IdAtualizarRemessaViaContaReceber	= $_POST['IdAtualizarRemessaViaContaReceber'];
	$local_IdContraApresentacao					= $_POST['IdContraApresentacao'];	
	$local_IdCobrarMultaJurosProximaFatura		= $_POST['IdCobrarMultaJurosProximaFatura'];
	$local_MsgDemonstrativo						= $_POST['MsgDemonstrativo'];
	
	if($_GET['IdLoja']!=''){
		$local_IdLoja	= $_GET['IdLoja'];	
	}
	if($_GET['IdLocalCobranca']!=''){
		$local_IdLocalCobranca	= $_GET['IdLocalCobranca'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_local_cobranca.php');
			break;		
		case 'alterar':
			include('files/editar/editar_local_cobranca.php');
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
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/procurar.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/local_cobranca.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_layout_default.js'></script>
		<script type = 'text/javascript' src = 'js/duplicata_layout_default.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_retorno_tipo_default.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_remessa_tipo_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(40)?>')">
		<? include('filtro_local_cobranca.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_local_cobranca.php' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Local' value='LocalCobranca'>
				<input type='hidden' name='tempEndArquivo' id='tempEndArquivo' value=''>
				<input type='hidden' name='ExtLogo' value=''>
				<input type='hidden' name='IdAtualizarVencimentoViaCDADefault' value='<?=getCodigoInterno(3, 153)?>'>
				<input type='hidden' name='IdContraApresentacaoDefault' value='<?=getCodigoInterno(3, 156)?>'>
				<input type='hidden' name='IdCobrarMultaJurosProximaFaturaDefault' value='<?=getCodigoInterno(3, 157)?>'>
				<input type='hidden' name='IdAtualizarRemessaViaCDADefault' value='<?=getCodigoInterno(3,167)?>'>
				<input type='hidden' name='IdAtualizarRemessaViaContaReceberDefault' value='<?=getCodigoInterno(3,168)?>'>
				<input type='hidden' name='SelecionarCamposUmaOpcao' value='<?=getCodigoInterno(3,237)?>'>
				<div>
					<table>
						<tr>
							<td>&nbsp;</td>
							<td class='descCampo'><?=dicionario(285)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(82)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLocalCobranca' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_local_cobranca(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoLocalCobranca' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='habilitar_arquivo_remessa_tipo(this.value);' tabindex='2'>
									<option value=''></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=78 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<div id='cp_tit'><?=dicionario(816)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(817)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(818)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(819)?></td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(820)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(821)?></B></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoLocalCobranca' value='' style='width:282px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='AbreviacaoNomeLocalCobranca' value='' style='width:141px' maxlength='8' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='InicioNossoNumero' value='' style='width:120px' maxlength='10' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='5'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='AvisoRegressivo' value='' style='width:103px' maxlength='11' onFocus="Foco(this,'in')"  onKeypress="mascara(this,event,'numerico')" onBlur="Foco(this,'out');" tabindex='6'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DiasCompensacao' value='' style='width:102px' maxlength='8' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='7'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(392)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(822)?> (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(823)?> (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(824)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(825)?> (<?=getParametroSistema(5,1)?>)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDespesaLocalCobranca' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='8'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualJurosDiarios' value='' style='width:150px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="reais(this,event,3)" onkeydown="backspace(this,event,3)" tabindex='9'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualMulta' value='' style='width:150px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="reais(this,event,3)" onkeydown="backspace(this,event,3)" tabindex='10'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorCobrancaMinima' value='' style='width:149px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='11'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTaxaReImpressaoBoleto' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='12'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(826)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(827)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(828)?></td>
							<td class='descCampo'><span id='titIdAtualizarRemessaViaCDA' style='margin-left:9px; display:none;'><?=dicionario(829)?></span></td>
							<td class='descCampo'><span id='titIdAtualizarRemessaViaContaReceber' style='margin-left:9px; display:none;'><?=dicionario(830)?>.</span></td>
							<td class='descCampo'><B id='titStatus' style='margin-left:9px;'><?=dicionario(140)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdContraApresentacao' style='width:210px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='13'>
									<?
										$sql = "select 
													IdParametroSistema, 
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema = 207;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdCobrarMultaJurosProximaFatura' style='width:216px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='14'>
									<?
										$sql = "select 
													IdParametroSistema, 
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema = 208;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAtualizarVencimentoViaCDA' style='width:181px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='15'>
									<?
										$sql = "select 
													IdParametroSistema, 
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema = 204;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo'>
								<select name='IdAtualizarRemessaViaCDA' style='width:108px; margin-left:9px; display:none;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='15'>
									<?
										$sql = "select 
													IdParametroSistema, 
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema =223";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo'>
								<select name='IdAtualizarRemessaViaContaReceber' style='width:143px; margin-left:9px; display:none;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='15'>
									<?
										$sql = "select 
													IdParametroSistema, 
													ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													IdGrupoParametroSistema = 224";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo'>
								<select name='IdStatus' style='width:182px; margin-left:5px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='16'>
									<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=170 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'  ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
											}
										?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(831)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(832)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='campo'>
								<select name='IdNotaFiscalTipo' style='width: 395px; margin: 1px 0 1px 0;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='17'>
									<option value=''></option>
									<?
										$sql = "select 													
													NotaFiscalTipo.IdNotaFiscalTipo, 
													NotaFiscalLayout.DescricaoNotaFiscalLayout 
												from 
													NotaFiscalTipo,
													NotaFiscalLayout
												where
													NotaFiscalTipo.IdLoja = $local_IdLoja and 
													NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout and
													NotaFiscalTipo.IdStatus = 1													
												order by 
													NotaFiscalLayout.DescricaoNotaFiscalLayout asc";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdNotaFiscalTipo]'>$lin[DescricaoNotaFiscalLayout]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaDuplicataLayout', true, event, null, 260); document.formularioDuplicataLayout.Nome.value=''; valorCampoDuplicataLayout=''; busca_duplicata_layout_lista(); document.formularioDuplicataLayout.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdDuplicataLayout' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_duplicata('',this.value,false,document.formulario.Local.Value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'><input class='agrupador' type='text' name='DescricaoDuplicata' value='' style='width:313px' maxlength='100' readOnly>
							</td>							
						</tr>
					</table>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(833)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titArquivoRetornoTipo' style='display: none; color:#000'><?=dicionario(837)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaLocalCobrancaLayout', true, event, null, 215); document.formularioLocalCobrancaLayout.Nome.value=''; valorCampoLocalCobrancaLayout=''; busca_local_cobranca_layout_lista(); document.formularioLocalCobrancaLayout.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdLocalCobrancaLayout' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_local_cobranca_layout(this.value,true,document.formulario.Local.Value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='19'><input class='agrupador' type='text' name='DescricaoLocalCobrancaLayout' value='' style='width:314px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img id='cpLupaArquivoRetornoTipo' style='display: none; margin-left:4px' src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaArquivoRetornoTipo', true, event, null, 260); document.formularioArquivoRetornoTipo.Nome.value=''; valorCampoArquivoRetornoTipo=''; busca_arquivo_retorno_tipo_lista(); document.formularioArquivoRetornoTipo.Nome.focus();"></td>							
							<td class='campo'>
								<span id='cpArquivoRetornoTipo' style='display: none;'><input type='text' name='IdArquivoRetornoTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_arquivo_retorno_tipo('',this.value,false,document.formulario.Local.Value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'><input class='agrupador' type='text' name='DescricaoArquivoRetornoTipo' value='' style='width:313px' maxlength='100' readOnly></span>
							</td>
						</tr>
					</table>									
					<table id='tbAvisoFaturaAtraso' cellpadding='0' cellspacing='0' style='display:none'>
						<tr>
							<td>
								<table style='margin:0;' id='titAvisoFaturaAtraso'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(834)?></B></td>	
									</tr>
								</table>
							</td>
							<td>
								<table style='margin:0;'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><span id='titArquivoRemessaTipo' style='display: none;'><?=dicionario(838)?></span></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table style='margin:0;' id='cpAvisoFaturaAtraso'>
									<tr>
										<td class='find'>&nbsp;</td>							
										<td class='campo' style='padding-right:7px;'>
											<select name='AvisoFaturaAtraso' style='width: 395px; margin: 1px 0 1px 0;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='21'>
												<option value=''></option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=169 order by ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table style='margin:0;'>
									<tr>
										<td class='find'><img id='cpLupaArquivoRemessaTipo' style='margin-left:4px; display: none;' src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaArquivoRemessaTipo', true, event, null, 305); document.formularioArquivoRemessaTipo.Nome.value=''; valorCampoArquivoRemessaTipo=''; busca_arquivo_remessa_tipo_lista(); document.formularioArquivoRemessaTipo.Nome.focus();"></td>
										<td class='campo'>
											<span id='cpArquivoRemessaTipo' style='display: none;'><input type='text' name='IdArquivoRemessaTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_arquivo_remessa_tipo(this.value,false,document.formulario.Local.Value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'><input class='agrupador' type='text' name='DescricaoArquivoRemessaTipo' value='' style='width:313px' maxlength='100' readOnly></span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Mensagem Demonstrativo Boleto</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><textarea name='MsgDemonstrativo' style='width: 816px; height: 50px;' ></textarea></td>
					</tr>
					</table>
					<div id='cp_tit'><?=dicionario(835)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><?=dicionario(1)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='cp_localCobranca'><?=dicionario(40)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='campo'>
								<select name='IdLojaCobrancaUnificada' style='width:405px' onFocus="Foco(this,'in')" onChange='busca_local_cobranca_unificada(this.value,"")' onBlur="Foco(this,'out')" tabindex='22'>
									<option value=''></option>
									<?
										$sql = "select 
													IdLoja, 
													DescricaoLoja 
												from 
													Loja 
												where 
													IdStatus = 1
												order by 
													DescricaoLoja asc";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLoja]'>$lin[DescricaoLoja]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobrancaUnificada' style='width:406px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='23' disabled>
									<option value=''></option>								
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'><?=dicionario(836)?></div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><font style='margin-right:36px'>".dicionario(26)."</font>".dicionario(85)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>".dicionario(172)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(179)."</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='24'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100'>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><font style='margin-right:36px'>".dicionario(26)."</font><B style='color:#000'>".dicionario(172)."</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(92)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(179)."</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='24'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100'>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readOnly>
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
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><font style='margin-right:36px'><?=dicionario(26)?></font><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(104)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(210)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='25'><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:123px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' colspan='3'><?=dicionario(839)?></td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top' id='EndArquivo' style='display: block;'>																
								<div style='border-right: 1px #A4A4A4 solid; width: 1px; height: 22px; margin-top: 1px; float: left'> </div>
								<input type="text" id="fakeupload"  name="fakeupload" class="fakeupload" style='width: 370px; margin-left: 0' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='26'/>								
								<div id='bt_procurar' style='margin-left: 370px;' tabindex='27'></div>
								<input type="file" id="realupload" onmousemove="quadro_alt(event, this, '<?=$extensoesTitle?>');" name='EndArquivo' size='1' class="realupload" onchange="document.formulario.fakeupload.value = this.value; document.formulario.tempEndArquivo.value=document.formulario.EndArquivo.value; verificaImagem();verificar_obrigatoriedade(this,<?=$extensoes?>);" /> 																								
								<p style='width: 460px; margin-top:3px'><?=dicionario(840)?>.</p>
							</td>																	
							<td class='campo' valign='top' id='cp_OpcaoImagem' style='display: none'>
								<select name='OpcaoImagem' style='width:360px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='28'>
									<option value=''></option>
									<option value='1'><?=dicionario(841)?></option>
									<option value='2'><?=dicionario(147)?></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<div id='VerImagem' style='width:200px; height:40px; background-repeat: no-repeat;'></div>
							</td>																				
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(571)?></div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='29' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='30' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='31' onClick='excluir(document.formulario.IdLocalCobranca.value)'>
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
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/local_cobranca_layout.php");
				include("files/busca/duplicata_layout.php");
				include("files/busca/arquivo_retorno_tipo.php");
				include("files/busca/arquivo_remessa_tipo.php");
				include("files/busca/pessoa.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdLocalCobranca!=''){
		echo "busca_local_cobranca($local_IdLocalCobranca,false);";		
	}
?>
	if(document.formulario.IdNotaFiscalTipo[2] == undefined && document.formulario.SelecionarCamposUmaOpcao.value == 1){
		document.formulario.IdNotaFiscalTipo[1].selected	= true;
	}
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
