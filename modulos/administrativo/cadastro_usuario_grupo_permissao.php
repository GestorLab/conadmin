<?
	$localModulo		=	1;
	$localOperacao		=	9;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_CursorPos		= $_GET['CursorPos'];
	$local_Login			= formatText($_GET['Login'],NULL);	
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
		<script type = 'text/javascript' src = 'js/usuario_permissao.js'></script>
		<script type = 'text/javascript' src = 'js/usuario_grupo_permissao_default.js'></script>
		<script type = 'text/javascript' src = 'js/usuario_busca_pessoa_aproximada.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Usuário/Grupo Permissão')">
		<? include('filtro_usuario.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='LoginEditor' value='<?=$local_Login_Sistema?>'>
				<input type='hidden' name='Local' value='UsuarioGrupoPermissao'>
				<input type='hidden' name='Permissoes' value=''>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:148px'>Login</B>Nome Usuário</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaUsuario', true, event, null, 94); limpa_form_usuario(); busca_usuario_lista(); document.formularioUsuario.Login.focus();"></td>
							<td class='campo'>
								<input type='text' name='Login' value='' style='width:170px' maxlength='20' onChange='busca_usuario(this.value,false, document.formulario.Local.value)' tabindex='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"><input class='agrupador' type='text' name='NomeUsuario' value='' style='width:327px' maxlength='30' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Selecionados</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Disponíveis</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:375px; height:90px;' name='IdGrupoPermissaoPermissao' tabindex='2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" multiple>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>
								<img src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Adicionar' onClick="quadro_permissao('add')" />
								<BR />
								<img style='margin-top:5px' src='../../img/estrutura_sistema/ico_seta_right.gif' alt='Remover' onClick="quadro_permissao('rem')" />
							</td>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:375px; height:90px;' name='IdGrupoPermissaoOpcao' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3' multiple>
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
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
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
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='4'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='5'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='6'>
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
				include("files/busca/usuario.php");
			?>
		</div>
	</body>
</html>
<script>
<?
	if($local_Login!=''){
		echo "busca_usuario('$local_Login',false);";		
	}
?>
	function inicia(){
		document.formulario.Login.focus();
	}	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
