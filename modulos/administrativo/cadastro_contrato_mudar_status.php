<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"M";	

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContrato				= $_POST['IdContrato'];
	$local_IdStatus					= $_POST['IdStatus'];
	$local_VarStatus				= $_POST['VarStatus'];
	$local_DataFinal				= $_POST['DataFinal'];
	$local_DataBloqueioStatus		= $_POST['DataBloqueioStatus'];
	$local_DataTerminoStatus		= $_POST['DataTerminoStatus'];
	$local_DataUltimaCobrancaStatus	= $_POST['DataUltimaCobrancaStatus'];
	$local_Obs						= formatText($_POST['Obs'],NULL);
	$local_Contratos				= formatText($_POST['Contratos'],NULL);
	
	switch ($local_Acao){	
		case 'alterar':
			include('files/editar/editar_contrato_mudar_status.php');
			break;
		default:
			$local_Acao  = 'alterar';
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_mudar_status.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(748)?>')">
		<? include('filtro_contrato_mudar_status.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_contrato_mudar_status.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ContratoStatus'>
				<input type='hidden' name='Contratos' value='<?=$local_Contratos?>'>
				<div id='cp_dadosContrato'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(454)?></div>
					<table cellpading='0' cellspacing='0'>
						<tr>
							<td>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(140)?></B></td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 310px' onChange=verifica_status(this.value) tabindex='1'>
												<option value='0' selected></option>
												<?
													
													$sql = "select 
																IdParametroSistema, 
																ValorParametroSistema 
															from 
																ParametroSistema 
															where 
																IdGrupoParametroSistema = 69 and 
																IdParametroSistema not in (305, 304, 101) order by ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td>
								<table id='tableAtivoTemp'  style='display:none;'>
									<tr>
										<td class='descCampo'><B id='titDataBloqueio'><?=dicionario(676)?></B></td>
										<td class='find'>&nbsp;</td>
									</tr>
									<tr>
										<td class='campo'>
											<input type='text' name='DataBloqueioStatus' id='cpDataBloqueioStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataBloqueio',this);" tabindex='2'>
										</td>
										<td class='find' id='imgDataBloqueioStatus'><img id='cpDataBloqueioStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataBloqueioStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataBloqueioStatusIco"
										    });
										</script>
									</tr>
								</table>
								<table id='tableCancelado'  style='display:none;'>
									<tr>
										<td class='descCampo'><B id='titDataUltimaCobranca'><?=dicionario(434)?>.</B></td>
										<td class='find'>&nbsp;</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='titDataTermino'><?=dicionario(233)?></B></td>
										<td class='find'>&nbsp;</td>
									</tr>
									<tr>
										<td class='campo'>
											<input type='text' name='DataUltimaCobrancaStatus' id='cpDataUltimaCobrancaStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataUltimaCobranca',this);" tabindex='2'>
										</td>
										<td class='find' id='imgDataUltimaCobrancaStatus'><img id='cpDataUltimaCobrancaStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataUltimaCobrancaStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataUltimaCobrancaStatusIco"
										    });
										</script>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DataTerminoStatus' id='cpDataTerminoStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataTermino',this);" tabindex='3'>
										</td>
										<td class='find' id='imgDataTerminoStatus'><img id='cpDataTerminoStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataTerminoStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataTerminoStatusIco"
										    });
										</script>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table id='avisoAtivo' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><?=dicionario(749)?>.</td>
						</tr>
					</table>
					<table id='tableObs' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpObs'><?=dicionario(159)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px;'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='<?=dicionario(14)?>' class='botao' tabindex='102' onClick="voltar()">
								<input type='submit' name='bt_alterar' value='<?=dicionario(404)?>' class='botao' tabindex='103'>
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
	</body>	
</html>
<script>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>