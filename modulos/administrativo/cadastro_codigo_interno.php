<?
	$localModulo		=	1;
	$localOperacao		=	5;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdGrupoCodigoInterno				=	$_POST['IdGrupoCodigoInterno'];	
	$local_IdCodigoInterno					=	$_POST['IdCodigoInterno'];	
	$local_DescricaoGrupoCodigoInterno		=	trim($_POST['DescricaoGrupoCodigoInterno']);	
	$local_IdCodigoInterno					= 	$_POST['IdCodigoInterno'];	
	$local_DescricaoCodigoInterno			= 	trim($_POST['DescricaoCodigoInterno']);	
	$local_ValorCodigoInterno				=	trim($_POST['ValorCodigoInterno']);	
	
	if($_GET['IdGrupoCodigoInterno']!=''){
		$local_IdGrupoCodigoInterno	= $_GET['IdGrupoCodigoInterno'];
	}
	if($_GET['IdCodigoInterno']!=''){
		$local_IdCodigoInterno	= $_GET['IdCodigoInterno'];
	}	

	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_codigo_interno.php');
			executaRotinaCodigoInterno($local_IdGrupoCodigoInterno);
			break;		
		case 'alterar':
			include('files/editar/editar_codigo_interno.php');
			executaRotinaCodigoInterno($local_IdGrupoCodigoInterno);
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/grupo_codigo_interno_default.js'></script>
		<script type = 'text/javascript' src = 'js/codigo_interno.js'></script>
		<script type = 'text/javascript' src = 'js/codigo_interno_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Códigos Internos')">
	<? include('filtro_codigo_interno.php'); ?>
	<div id='carregando'>carregando</div>
	<div id='conteudo'>	
		<form name='formulario' method='post' action='cadastro_codigo_interno.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value=<?=$local_Erro?>>
			<input type='hidden' name='Local' value='CodigoInterno'>
			<div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Grupo Código Interno</B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B style='margin-right: 16px; color:#000'>Cod. Interno</B><B>Nome Código Interno</td>
					</tr>
					<tr>
						<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoCodigo', true, event, null, 94); document.formularioGrupoCodigo.Nome.value=''; valorCampoGrupoCodigo=''; busca_grupo_codigo_interno_lista(); document.formularioGrupoCodigo.Nome.focus();"></td>
						<td class='campo'>
							<input type='text' name='IdGrupoCodigoInterno' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_grupo_codigo_interno(this.value,false)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoCodigoInterno' value='' style='width:300px' maxlength='100' readOnly>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='IdCodigoInterno' value='' autocomplete="off" style='width:80px' maxlength='11' onChange='busca_codigo_interno(document.formulario.IdGrupoCodigoInterno.value,this.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'><input class='agrupador' type='text' name='DescricaoCodigoInterno' value="" style='width:338px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Valor Código Interno</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<textarea type='textarea' name='ValorCodigoInterno'  style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='4'></textarea>
						</td>
					</tr>
				</table>
			</div>
			<div id='cp_log'>
				<div id='cp_tit'>Log</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'>Usuário Cadastro</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Data Cadastro</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Usuário Alteração</td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Data Alteração</td>
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
							<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
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
							<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='101' onClick='cadastrar()'>
							<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='102' onClick='cadastrar()'>
							<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='103' onClick='excluir(document.formulario.IdGrupoCodigoInterno.value,document.formulario.IdCodigoInterno.value)'>
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
				include("files/busca/grupo_codigo_interno.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdCodigoInterno!='') {
		echo "busca_codigo_interno($local_IdGrupoCodigoInterno,$local_IdCodigoInterno,false);";		
	} elseif($local_IdGrupoCodigoInterno!='') {
		echo "busca_grupo_codigo_interno($local_IdGrupoCodigoInterno,false);";
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
