<?
	$localModulo		=	1;
	$localOperacao		=	10;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdGrupoPermissao			= $_POST['IdGrupoPermissao'];
	$local_DescricaoGrupoPermissao	= formatText($_POST['DescricaoGrupoPermissao'],NULL);
	$local_LimiteVisualizacao		= formatText($_POST['LimiteVisualizacao'],NULL);	
	$local_IpAcesso					= formatText($_POST['IpAcesso'],NULL);
	
	if($_GET['IdGrupoPermissao']!=''){
		$local_IdGrupoPermissao	= $_GET['IdGrupoPermissao'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_grupo_permissao.php');
			break;		
		case 'alterar':
			include('files/editar/editar_grupo_permissao.php');
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_permissao.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_permissao_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Grupo Permissão')">
		<? include('filtro_grupo_permissao.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
		<form name='formulario' method='post' action='cadastro_grupo_permissao.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value=<?=$local_Erro?>>
			<input type='hidden' name='Local' value='GrupoPermissao'>
			<div>
				<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:12px'>Grupo Perm.</B><B>Nome Grupo Permissão</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Limite Visualização</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdGrupoPermissao' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_grupo_permissao(this.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoPermissao' value='' style='width:392px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>
								<input type='text' name='LimiteVisualizacao' value='' style='width:110px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>IPs para Acesso ao Sistema</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='IpAcesso' style='width: 816px;' rows=5 tabindex='4' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
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
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao' style='height:50px;'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_conexao' value='Derrubar Conexão' onclick='derrubar_conexao(document.formulario.IdGrupoPermissao.value);' class='botao' tabindex='5'>
							</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='6' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='7' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='8' onClick="excluir(document.formulario.IdGrupoPermissao.value)">
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
				<div id='cp_usuario_grupo_permissao' style='width:100%; display:none;'>
					<div id='cp_tit' style='margin:0;'>Usuários pertencentes ao Grupo</div>
					<table id='tabelaUsuarioGrupoPermissao' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Usuário</td>
							<td>Nome do Usuário</td>
							<td>E-mail</td>
							<td>Data Criação</td>
							<td>Status</td>
							<td />
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaUsuarioGrupoPermissaoTotal'></td>
						</tr>
					</table>
					<table>
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
<script>
<?
	if($local_IdGrupoPermissao!=''){
		echo "busca_grupo_permissao(".$local_IdGrupoPermissao.",false);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
