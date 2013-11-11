<?
	$localModulo		=	1;
	$localOperacao		=	32;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdGrupoUsuario			= $_POST['IdGrupoUsuario'];
	$local_DescricaoGrupoUsuario	= formatText($_POST['DescricaoGrupoUsuario'],NULL);
	$local_OrdemServico				= $_POST['OrdemServico'];
	$local_LoginSupervisor			= $_POST['LoginSupervisor'];
	
	if($_GET['IdGrupoUsuario']!=''){
		$local_IdGrupoUsuario	= $_GET['IdGrupoUsuario'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_grupo_usuario.php');
			break;		
		case 'alterar':
			include('files/editar/editar_grupo_usuario.php');
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
		<script type = 'text/javascript' src = 'js/grupo_usuario.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_usuario_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Grupo Usuário')">
		<? include('filtro_grupo_usuario.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
		<form name='formulario' method='post' action='cadastro_grupo_usuario.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value=<?=$local_Erro?>>
			<input type='hidden' name='Local' value='GrupoUsuario'>
			<div>
				<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:12px'>Grupo Usuár.</B><B>Nome Grupo Usuário</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Grupo para Ordem de Serviço</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdGrupoUsuario' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_grupo_usuario(this.value);busca_login_supervisor(this.value,document.formulario.LoginSupervisor)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'><input class='agrupador' type='text' name='DescricaoGrupoUsuario' value='' style='width:555px' maxlength='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='OrdemServico' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:170px' tabindex='3'>
									<option value='' selected></option>
									<?
										$sql = "
												select 
													IdParametroSistema,
													ValorParametroSistema
												from
													ParametroSistema
												where
													IdGrupoParametroSistema=141
												order by
													IdParametroSistema Desc;
										";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' style='width: 298px;'><?=dicionario(861)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>
								<select name='LoginSupervisor' style='width:298px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
									<option value=''></option>
									
								</select>
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
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='7' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='8' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='9' onClick="excluir(document.formulario.IdGrupoUsuario.value)">
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
				<div id='cp_usuario_grupo_usuario' style='width:100%; display:none;'>
					<div id='cp_tit' style='margin:0;'>Usuários pertencentes ao Grupo</div>
					<table id='tabelaUsuarioGrupoUsuario' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Usuário</td>
							<td>Nome do Usuário</td>
							<td>E-mail</td>
							<td>Data Criação</td>
							<td>Status</td>
							<td />
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaUsuarioGrupoUsuarioTotal'></td>
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
	if($local_IdGrupoUsuario!=''){
		echo "busca_grupo_usuario(".$local_IdGrupoUsuario.",false);";		
	}
?>
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>