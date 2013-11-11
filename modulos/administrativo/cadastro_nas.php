<?
	$localModulo		= 1;
	$localOperacao		= 10002;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_id			= formatText($_POST['id'],NULL);
	$local_Nasname 		= $_POST['nasname'];
	$local_Shortname 	= $_POST['shortname'];
	$local_Type			= $_POST['type'];
	$local_Ports		= $_POST['ports'];
	$local_Secret		= $_POST['secret'];
	$local_Server		= $_POST['server'];
	$local_Community	= $_POST['community'];
	$local_Description	= $_POST['description'];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$restartFreeRadius = false;
	
	if($_GET['id']!=''){
		$local_id = $_GET['id'];
	}
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_nas.php');
			$restartFreeRadius = true;
			break;

		case 'alterar':
			include('files/editar/editar_nas.php');
			$restartFreeRadius = true;
			break;

		default:
			$local_Acao = 'inserir';
	}

	mysql_close($con);
	include('../../files/conecta.php');

	if($restartFreeRadius == true){
		// Solicita a reinicialização do radius.
		$sql = "UPDATE ParametroSistema SET
					ValorParametroSistema = 1
				WHERE
					IdGrupoParametroSistema = 203 and
					IdParametroSistema = 1";
		@mysql_query($sql,$con);
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
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/nas.js'></script>
		<script type='text/javascript' src='js/nas_default.js'></script>
		
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('<?=dicionario(1023)?>')">
		<? include('filtro_nas.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_nas.php' onSubmit='return validar()'>
				<input type="hidden" name="Acao" value="<?=$local_Acao?>" />
				<input type="hidden" name="Erro" value="<?=$local_Erro?>" />
				<input type="hidden" name="Local" value="nas" />
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>NAS</td>
						<td class='separador'>&nbsp;</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>
							<input type='text' name='id' autocomplete="off" style='width:70px' onChange="busca_nas(this.value,true,document.formulario.Local.value)"  onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
					</tr>
				</table>
				<div id='cp_tit'><?=dicionario(1031)?></div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>NAS Name</B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B>Short Name</B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B>Type</B></td>
						<td class='separador'>&nbsp;</td>
						
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='nasname' autocomplete="off" style='width:350px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='shortname' autocomplete="off" style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='type' autocomplete="off" style='width:182px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Ports</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B>Secret</B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Server</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Community</td>
						<td class='separador'>&nbsp;</td>
						
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='ports' autocomplete="off" style='width:80px' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='secret' autocomplete="off" style='width:253px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='server' autocomplete="off" style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='community' autocomplete="off" style='width:182px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='100' />
						</td>
						<td class='separador'>&nbsp;</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Descrição</B></td>
						<td class='separador'>&nbsp;</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<textarea tabindex="106" onblur="Foco(this,'out');" onfocus="Foco(this,'in')" maxlength="255" rows="5" style="width: 816px; outline: medium none; border-color: rgb(164, 164, 164); outline-offset: 0px; background-color: rgb(255, 255, 255);" name="description" type="textarea"></textarea>
						</td>							
						<td class='separador'>&nbsp;</td>							
					</tr>
				</table>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='107' onClick='validar();cadastrar()' disabled>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='108' onClick='cadastrar()' >
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='109' onClick="excluir(document.formulario.id.value)">
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
<script type='text/javascript'> 
	<?
		if($local_id!=''){
			echo "busca_nas($local_id,false,document.formulario.Local.value);";
		}
	?>
	if(document.formulario.id.value == ''){
		document.formulario.id.focus();
	}
	verificaAcao();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>