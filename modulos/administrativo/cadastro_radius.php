<?
	$localModulo		= 1;
	$localOperacao		= 10000;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLicenca	= $_SESSION["IdLicenca"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_id			= formatText($_POST['id'],NULL);
	$local_IdServidor	= formatText($_POST['IdServidor'],NULL);
	$local_Tipo			= formatText($_POST['Tipo'],NULL);
	$local_IdGrupo		= formatText($_POST['IdGrupo'],NULL);
	$local_NovoGrupo	= formatText($_POST['NovoGrupo'],NULL);
	$local_Atributo		= formatText($_POST['Atributo'],NULL);
	$local_NovoAtributo	= formatText($_POST['NovoAtributo'],NULL);
	$local_Operador		= formatText($_POST['Operador'],NULL);
	$local_Valor		= formatText($_POST['Valor'],NULL);
	
	if($_GET['id']!=''){
		$local_id = $_GET['id'];
	}
	
	if($_GET['IdServidor']!=''){
		$local_IdServidor = $_GET['IdServidor'];
	}
	
	if($_GET['Tipo']!=''){
		$local_Tipo = $_GET['Tipo'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_radius.php');
			break;		
		case 'alterar':
			include('files/editar/editar_radius.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
	include('../../files/conecta.php');
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
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>		
		<script type='text/javascript' src='js/radius.js'></script>
		<script type='text/javascript' src='js/radius_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Servidor Radius')">
		<? include('filtro_radius.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_radius.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Radius'>
				<input type='hidden' name='id' value='<?=$local_id?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Servidor Radius</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Grupo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='titNovoGrupo' style='display:none'><B>Novo Grupo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdServidor' tabindex='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:300px' onChange="inserirAtributo(this.value); buscaGrupo(this.value); buscaAtributo(this.value);">
									<option value='0' selected></option>
										<?
											$sql = "select 
														IdCodigoInterno, 
														DescricaoCodigoInterno 
													from 
														CodigoInterno 
													where
														IdLoja = $local_IdLoja and 
														IdGrupoCodigoInterno=10000 and
														IdCodigoInterno <= 19
													order by 
														ValorCodigoInterno";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdCodigoInterno]'>$lin[DescricaoCodigoInterno]</option>";
											}
										?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Tipo' tabindex='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:70px'>
									<option value='' selected></option>
									<option value='C' <?=compara($localTipo,'C',"selected='selected'","")?>>Check</option>
									<option value='R' <?=compara($localTipo,'R',"selected='selected'","")?>>Reply</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupo' tabindex='3' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:207px' OnChange="verificaGrupo(this.value); lista_atributos(document.formulario.IdServidor.value,this.value)">
									<option value='0' selected></option>
									<option value='-1'>Novo Grupo</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cpNovoGrupo' style='display:none' >
								<input type='text' name='NovoGrupo' style='width:206px' onkeypress="return mascara(this,event,'filtroCaractere','','')" value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='4'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Atributo</B></td>
							<td class='descCampo'><B id='titNovoAtributo' style='display:none; margin-left:9px;'>Atributo</B></td>
							<td class='descCampo'><B id='titOperador' style='margin-left:8px;'>Operador</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Valor</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='Atributo' tabindex='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:300px' OnChange="verificaAtributo(this.value);">
									<option value='0' selected></option>
										<?
											$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=10001 order by ValorCodigoInterno ASC";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[ValorCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
											}
										?>
									<option value='-1'>Novo Atributo</option>
								</select>
							</td>
							<td class='campo'>
								<input type='text' name='NovoAtributo' style='width:150px; display:none; margin-left:9px;' value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6'>
							</td>
							<td class='campo'>
								<select name='Operador' style='width:70px; margin-left:7px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='7'>
									<option value='0' selected></option>
										<?
											$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=10002 order by ValorCodigoInterno";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[ValorCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
											}
										?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='Valor' name='Valor' style='width:424px' value='' maxlength='253' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8'>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:5px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='9' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='10' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='11' onClick="excluir(document.formulario.id.value,document.formulario.Tipo.value,document.formulario.IdServidor.value)">
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
				<div id='carregando2' style='margin-bottom:0'>carregando</div>
				<div id='cp_radius' style='display:none; margin-top:10px'>
					<div id='cp_tit' style='margin-bottom:0'>Atributos</div>
					<table id='tabelaRadius' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>&nbsp;</td>
							<td>Grupo</td>
							<td>Tipo</td>
							<td>Atributo</td>
							<td>Operador</td>
							<td>Valor</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='7' id='tabelaRadiusTotal'>Total:0</td>
						</tr>
					</table>
					<table id='helpText2table'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script type='text/javascript'> 
<?
	if($local_id != '' && $local_Tipo != "" && $local_IdServidor != ""){
		echo "busca_radius($local_id,'$local_Tipo',$local_IdServidor,false,document.formulario.Local.value);";
		echo "scrollWindow('bottom');";	
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>