<?
	$localModulo		=	1;
	$localOperacao		=	11;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_CursorPos		= $_GET['CursorPos'];
	$local_IdGrupoPermissao	= $_GET['IdGrupoPermissao'];	
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
		<script type = 'text/javascript' src = 'js/grupo_permissao_permissao.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_permissao_permissao_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Grupo Permiss�o/Permiss�o')">
		<? include('filtro_grupo_permissao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='LoginEditor' value='<?=$local_Login_Sistema?>'>
				<input type='hidden' name='Local' value='GrupoPermissaoPermissao'>
				<input type='hidden' name='Permissoes' value=''>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:12px'>Grupo Perm.</B>Nome Grupo Permiss�o</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Loja</B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoPermissao', true, event, null, 94); document.formularioGrupoPermissao.Nome.value=''; valorCampoGrupoParametro=''; busca_grupo_permissao_lista(); document.formularioGrupoPermissao.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdGrupoPermissao' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_grupo_permissao(this.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoPermissao' value='' style='width:319px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select style='width:406px' name='IdLoja' onChange="busca_modulo(); listar_grupo_permissao(this.value,document.formulario.IdGrupoPermissao.value);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
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
							<td class='descCampo'><B>M�dulo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Sub-M�dulo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:406px' name='IdModulo' onChange='busca_operacao()' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select style='width:405px' name='IdOperacao' onChange='atualiza_sub_operacao()' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'>
								</select>
							</td>		
						</tr>
					</table>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Permiss�es</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>A��es</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:375px' name='IdOperacaoPermissao' size=6 onChange="busca_sub_operacao_permissoes_dados()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5' multiple>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='find'>
								<img name='add' src='../../img/estrutura_sistema/ico_seta_left.gif' alt='Adicionar' onClick="quadro_permissao('add')" />
								<BR style='margin-top:5px'/>
								<img name='rem' src='../../img/estrutura_sistema/ico_seta_right.gif' alt='Remover' onClick="quadro_permissao('rem')" />
							</td>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select style='width:376px' name='IdOperacaoOpcao' size=6 tabindex='6' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" multiple>
							</td>
						</tr>
					</table>
				</div>	
				<div id='cp_log'>
				<div id='cp_tit'>Log</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usu�rio Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usu�rio Altera��o</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Altera��o</td>
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
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_conexao' value='Derrubar Conex�o' onclick='derrubar_conexao(document.formulario.IdGrupoPermissao.value);' class='botao' tabindex='7'>
							</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='8'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='9'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='10'>
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
					<div id='cp_tit' style='margin-bottom:0; margin-top:10px'>Permiss�es</div>
					<table id='tabelaPermissao' style='margin-top:0' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Loja</td>
							<td>M�dulo</td>
							<td>Opera��o</td>
							<td>Sub-Opera��o</td>
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
				include("files/busca/grupo_permissao.php");
			?>
		</div>
	</body>
</html>
<script>
<?
	if($_GET['IdGrupoPermissao']!=''){
		echo "busca_grupo_permissao('".$_GET['IdGrupoPermissao']."');";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
