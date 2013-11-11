<?
	$localModulo		=	1;
	$localOperacao		=	8;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_CursorPos		= $_GET['CursorPos'];
	$local_Login			= formatText($_GET['Login'],NULL);	
	$local_Erro				= $_GET['Erro'];

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
		<script type = 'text/javascript' src = 'js/usuario_permissao_default.js'></script>
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
	<body  onLoad="ativaNome('Usuário/Permissão')">
		<? include('filtro_usuario.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='LoginEditor' value='<?=$local_Login_Sistema?>'>
				<input type='hidden' name='Local' value='UsuarioPermissao'>
				<input type='hidden' name='Permissoes' value=''>
				<div  id='quadro1'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:148px'>Login</B>Nome Usuário</td>
							<td class='separador'></td>
							<td class='descCampo'><B id="titLoja">Loja</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaUsuario', true, event, null, 94); limpa_form_usuario(); busca_usuario_lista(); document.formularioUsuario.Login.focus();"></td>
							<td class='campo'>
								<input type='text' name='Login' value='' style='width:170px' maxlength='20' onChange="busca_usuario(this.value,'false',document.formulario.Local.value)" tabindex='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"><input class='agrupador' type='text' name='NomeUsuario' value='<?=$local_NomeUsuario?>' style='width:327px' maxlength='30' readOnly>
							</td>
							<td class='separador'></td>
							<td class='campo'>
								<select style='width:305px' name='IdLoja' onChange="busca_modulo(); listar_usuario_permissao(this.value,document.formulario.Login.value)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
									<?
										$sql = "select IdLoja, DescricaoLoja from Loja where IdLoja in (".lojas_permissao_login($local_Login_Sistema).") and IdStatus=1 order by DescricaoLoja";
										$res = mysql_query($sql,$con);
										while($lin = mysql_fetch_array($res)){
											echo "<option value='$lin[IdLoja]'>$lin[DescricaoLoja]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Módulo</B></td>
							<td class='separador'></td>
							<td class='descCampo'><B>Sub-Módulo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:406px' name='IdModulo' onChange='busca_operacao()' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
								</select>
							</td>	
							<td class='separador'></td>
							<td class='campo'>
								<select style='width:407px' name='IdOperacao' onChange='atualiza_sub_operacao()' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
								</select>
							</td>
						</tr>
					</table>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Permissões</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Ações</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:375px' name='IdOperacaoPermissao' size=6 onChange="busca_sub_operacao_permissoes_dados()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5' multiple>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>
								<img src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Adicionar' onClick="quadro_permissao('add')" />
								<BR />
								<img style='margin-top:5px' src='../../img/estrutura_sistema/ico_seta_right.gif' alt='Remover' onClick="quadro_permissao('rem')" />
							</td>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:376px' name='IdOperacaoOpcao' size=6 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' multiple>
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
				<div style='margin-bottom:0;'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:10px'>Permissões</div>
					<table id='tabelaPermissao' style='margin-top:0' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>Módulo</td>
							<td>Operação</td>
							<td>Sub-Operação</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='4' id='totaltabelaPermissao'>Total: 0</td>
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
		echo "busca_usuario('".$local_Login."',false);";		
	}
?>	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
