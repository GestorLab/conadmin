<?
	$localModulo			= 1;
	$localOperacao			= 143;
	$localSuboperacao		= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];

	$local_IdStatusContrato				= $_POST['Filtro_IdStatusContrato'];	

	switch ($local_Acao){
		case 'exportar':
			header("Location: rotinas/exportar_mikrotik.php?IdStatusContrato=$local_IdStatusContrato&IdLoja=$local_IdLoja");
			break;
		default:
			$local_Acao = 'exportar';
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/mikrotik.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Mikrotik')">
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_mikrotik.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='Mikrotik'>
				<input type='hidden' name='Filtro_IdStatusContrato' value=''>
				<div>
					<div id='cp_tit' style='margin-top:0'>Filtros</div>					
					<div id='cp_filtro_status'>
						<!--table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Status Contrato</td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatusContrato' style='width:320px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
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
							</tr>
						</table-->
						<table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Status Contrato</td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
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
									<input type='button' name='bt_add_status' value='Adicionar' class='botao' style='width:84px;' tabindex='12' onClick="addStatus(document.formulario.IdStatusContrato.value,'')">
								</td>
							</tr>
						</table>
						<table id='tabelaStatus' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'>Id</td>
								<td>Status</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaStatus'>Total: 0</td>
							</tr>
						</table>
					</div>					
				</div>
				<div class='cp_botao'>
					<table style='width:100%; margin-top:9px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='text-align:right; padding-right:5px;' class='campo'>
								<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='9' onClick='cadastrar()'>
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
	inicia();
	enterAsTab(document.forms.formulario);
</script>