<?
	$localModulo		= 1;
	$localOperacao		= 159;
	$localSuboperacao	= "V";
	$Path				= "../../";
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	include("../../classes/envia_mensagem/envia_mensagem.php");
	
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Login				= $_SESSION["Login"];
	$local_Erro					= $_GET["Erro"];
	$local_Acao 				= $_POST["Acao"];
	$local_FormatoArquivo		= $_POST['FormatoArquivo'];
	$local_PeriodoApuracao		= $_POST['PeriodoApuracao'];
	$local_IdMetodoExportacao	= $_POST['IdMetodoExportacao'];
	$local_Email				= $_POST['Email'];
	
	if($local_PeriodoApuracao == ''){
		$local_PeriodoApuracao = $_GET['PeriodoApuracao'];
	}
	
	switch($local_Acao) {
		case "exportar":
			enviarEmailSICIExportacao($local_IdLoja, $local_Email, $local_PeriodoApuracao, $local_FormatoArquivo, $local_IdMetodoExportacao);
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
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/sici_exporta.js'></script>
		
	    <style type="text/css">
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('SICI');">
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='SICIExportar' />
				<input type='hidden' name='PeriodoApuracao' value='<?=$local_PeriodoApuracao?>' />
				<div id='cp_tit' style='margin-top:0'>Dados da Exportação</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><b>Formato do Arquivo</b></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><b>Método</b></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><b id="tit_MetodoExportacao" style="display:none;">Para</b></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<select name='FormatoArquivo' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='1'>
								<option value='' selected></option>
								<?
									$sql = "SELECT 
												IdParametroSistema, 
												ValorParametroSistema 
											FROM 
												ParametroSistema 
											WHERE 
												IdGrupoParametroSistema = 238
											ORDER BY
												ValorParametroSistema;";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='".strtolower($lin[ValorParametroSistema])."'>$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='IdMetodoExportacao' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="verificar_metodo_exportacao(this.value);" tabindex='2'>
								<option value='' selected></option>
								<?
									$sql = "SELECT 
												IdParametroSistema,
												ValorParametroSistema 
											FROM 
												ParametroSistema 
											WHERE 
												IdGrupoParametroSistema = 241
											ORDER BY
												ValorParametroSistema;";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Email' style='width:300px; display:none;' value='<?=$Email?>' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="validar_Email(this.value);" tabindex='3' />
						</td>
					</tr>
				</table>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='2' onClick="cadastrar('voltar');" />
								<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='3' onClick="cadastrar('exportar');" />
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
		<script type="text/javascript">
			inicia();
			enterAsTab(document.forms.formulario);
		</script>
	</body>
</html>