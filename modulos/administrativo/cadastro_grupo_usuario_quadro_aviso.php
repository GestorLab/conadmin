<?
	$localModulo		=	1;
	$localOperacao		=	41;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$local_Login					= $_SESSION["Login"];
	$local_Acao 					= $_POST['Acao'];
	$local_CursorPos				= $_GET['CursorPos'];
	$local_IdGrupoUsuario			= formatText($_GET['IdGrupoUsuario'],NULL);
	$local_DescricaoGrupoUsuario	= formatText($_GET['DescricaoGrupoUsuario'],NULL);
	$local_Erro						= $_GET['Erro'];

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
		<script type = 'text/javascript' src = 'js/grupo_usuario_default.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_usuario_quadro_aviso.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_usuario_quadro_aviso_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Grupo Usuário/Quadro Aviso')">
		<? include('filtro_grupo_usuario.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='Login' value='<?=$local_Login?>'>
				<input type='hidden' name='Local' value='GrupoUsuarioQuadroAviso'>
				<input type='hidden' name='Permissoes' value=''>
				<div  id='quadro1'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:10px'>Grupo Usuár.</B>Nome Grupo Usuário</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoUsuario', true, event, null, 94); document.formularioGrupoUsuario.Nome.value=''; valorCampoGrupoUsuario=''; busca_grupo_usuario_lista(); document.formularioGrupoUsuario.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdGrupoUsuario' value='' style='width:70px' maxlength='20' onChange='busca_grupo_usuario(this.value)' tabindex='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"><input class='agrupador' type='text' name='DescricaoGrupoUsuario' value='<?=$local_DescricaoGrupoUsuario?>' style='width:327px' maxlength='30' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Quadros de Aviso e Alertas Selecionados</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Quadros de Aviso e Alertas Disponíveis</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:375px' name='IdGrupoUsuarioPermissao' size=6 onFocus="Foco(this,'in')" onClick="busca_grupo_usuario_permissoes_dados(this.value)" onBlur="Foco(this,'out')" tabindex='5' multiple>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>
								<img src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Adicionar' onClick="quadro_aviso('add')" />
								<BR />
								<img style='margin-top:5px' src='../../img/estrutura_sistema/ico_seta_right.gif' alt='Remover' onClick="quadro_aviso('rem')" />
							</td>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:376px' name='IdGrupoUsuarioOpcao' size=6 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' multiple>
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
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='7'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='8'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='9'>
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
				include("files/busca/grupo_usuario.php");
			?>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdGrupoUsuario!=''){
		echo "busca_grupo_usuario('".$local_IdGrupoUsuario."',false);";		
	}
?>	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
