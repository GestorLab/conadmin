<?php 
	$localModulo		= 1;
	$localOperacao		= 58;
	$localSuboperacao	= "M";	
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_IdContaReceber			= $_POST['IdContaReceber'];
	$local_IdStatus					= $_POST['IdStatus'];
	$local_VarStatus				= $_POST['VarStatus'];
	$local_DataFinal				= $_POST['DataFinal'];
	$local_DataBloqueioStatus		= $_POST['DataBloqueioStatus'];
	$local_DataTerminoStatus		= $_POST['DataTerminoStatus'];
	$local_DataUltimaCobrancaStatus	= $_POST['DataUltimaCobrancaStatus'];
	$local_Obs						= formatText($_POST['Obs'],NULL);
	$local_Contratos				= formatText($_POST['Contratos'],NULL);
	
	switch($local_Acao){	
		case 'alterar':
			print "çsdklfj";
			#include('files/editar/editar_conta_receber_mudar_status.php');
			break;
		default:
			$local_Acao  = 'alterar';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='../../classes/calendar/calendar.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-setup.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-br.js'></script>
		<script type='text/javascript' src='js/conta_receber_mudar_status.js'></script>
		
		<style type='text/css'>
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(991); ?>')">
		<?php 
			include('filtro_conta_receber_mudar_status.php'); 
		?>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_receber_mudar_status.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?php echo $local_Acao; ?>' />
				<input type='hidden' name='Erro' value='<?php echo $local_Erro; ?>' />
				<input type='hidden' name='Local' value='ContaReceberStatus' />
				<input type='hidden' name='ContasReceber' value='<?php echo $local_ContasReceber; ?>' />
				<div id='cp_dadosContaReceber'>
					<div id='cp_tit' style='margin-top:0'><?php echo dicionario(454); ?></div>
					<table cellpadding='0' cellspacing='0'>
						<tr>
							<td>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?php echo dicionario(140); ?></B></td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdStatus' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:310px' tabindex='1'>
												<option value='0' selected></option>
												<?php 
													$sql = "select 
																IdParametroSistema, 
																ValorParametroSistema 
															from 
																ParametroSistema 
															where 
																IdGrupoParametroSistema = 35 
															order by 
																ValorParametroSistema";
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
						</tr>
					</table>
					<table id='avisoAtivo' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><?php echo dicionario(749); ?>.</td>
						</tr>
					</table>
					<table id='tableObs' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpObs'><?php echo dicionario(159); ?></B></td>				
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
								<input type='button' name='bt_voltar' value='<?php echo dicionario(14); ?>' class='botao' tabindex='102' onClick='volta r()' />
								<input type='submit' name='bt_alterar' value='<?php echo dicionario(404); ?>' class='botao' tabindex='103' />
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
		<script type='text/javascript'>
			verificaErro();
			verificaAcao();
			inicia();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>