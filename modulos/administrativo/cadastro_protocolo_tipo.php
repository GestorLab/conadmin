<?
	$localModulo		=	1;
	$localOperacao		=	169;
	$localSuboperacao	=	"V";

	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdProtocoloTipo				= $_POST['IdProtocoloTipo'];
	$local_DescricaoProtocoloTipo		= formatText($_POST['DescricaoProtocoloTipo'],NULL);
	$local_AberturaCDA					= $_POST['AberturaCDA'];
	$local_IdStatus						= $_POST['IdStatus'];
	$local_IdGrupoUsuarioAbertura		= $_POST['IdGrupoUsuarioAbertura'];
	$local_LoginAbertura				= formatText($_POST['LoginAbertura'],NULL);
	
	if($local_IdProtocoloTipo == ''){
		$local_IdProtocoloTipo = $_GET['IdProtocoloTipo'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_protocolo_tipo.php');
			break;
		case 'alterar':
			include('files/editar/editar_protocolo_tipo.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/protocolo_tipo.js'></script>
		<script type='text/javascript' src='js/protocolo_tipo_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	</head>
	<body onLoad="ativaNome('Tipo')">
		<? include('filtro_protocolo_tipo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_protocolo_tipo.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ProtocoloTipo'>
				
				<div style='width:830px;'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Tipo</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProtocoloTipo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_protocolo_tipo(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1'>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Tipo</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><B>Descrição Tipo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoProtocoloTipo' value='' style='width:816px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='15'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Abertura via CDA</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Status</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='AberturaCDA' style='width:106px' tabindex='16' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
									<?
										$sql = "select 
													ParametroSistema.IdParametroSistema, 
													ParametroSistema.ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													ParametroSistema.IdGrupoParametroSistema = 220
												group by 
													ParametroSistema.ValorParametroSistema;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatus' style='width:106px' tabindex='16' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
									<?
										$sql = "select 
													ParametroSistema.IdParametroSistema, 
													ParametroSistema.ValorParametroSistema 
												from 
													ParametroSistema 
												where 
													ParametroSistema.IdGrupoParametroSistema = 221
												group by 
													ParametroSistema.ValorParametroSistema;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Equipe Responsável</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Grupo Abertura</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Abertura</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuarioAbertura' style='width:406px' tabindex='16' onChange="busca_login_usuario(this.value,document.formulario.LoginAbertura);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
									<?
										$sql = "select 
													UsuarioGrupoUsuario.IdGrupoUsuario, 
													GrupoUsuario.DescricaoGrupoUsuario 
												from 
													UsuarioGrupoUsuario, 
													GrupoUsuario, 
													Usuario, 
													Pessoa 
												where 
													UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
													UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
													UsuarioGrupoUsuario.Login = Usuario.Login and 
													UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
													Usuario.IdPessoa = Pessoa.IdPessoa and 
													Pessoa.TipoUsuario = 1 and 
													Usuario.IdStatus = 1 
												GROUP by 
													UsuarioGrupoUsuario.IdGrupoUsuario;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='LoginAbertura' style='width:405px' tabindex='17' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
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
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' style='width:80px' name='bt_inserir' value='Cadastrar' class='botao' tabindex='18' onClick="cadastrar('inserir')">
								<input type='button' style='width:60px' name='bt_alterar' value='Alterar' class='botao' tabindex='19' onClick="cadastrar('alterar')">
								<input type='button' style='width:60px' name='bt_excluir' value='Excluir' class='botao' tabindex='20' onClick="excluir(document.formulario.IdProtocoloTipo.value)">
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%;height:33px;' border='0'>
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
<script type="text/javascript">
	<?
		if($local_IdProtocoloTipo != ""){
			echo "busca_protocolo_tipo($local_IdProtocoloTipo,false,document.formulario.Local.value);";
		}
	?>
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>