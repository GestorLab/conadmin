<?
	$localModulo		=	2;
	$localOperacao		=	1;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	$Path				=   "../../";
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');
	include ('rotinas/verifica.php');
	
	$local_IdLoja						= $_SESSION["IdLojaHD"];
	$local_Login						= $_SESSION["LoginHD"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_Ticket						= $_GET['Ticket'];
	
	$local_IdTicket						= $_POST['IdTicket'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_IdContrato					= $_POST['IdContrato'];
	$local_IdStatus						= $_POST['IdStatus'];
	$local_IdTipoTicket					= $_POST['IdTipoTicket'];
	$local_IdSubTipoTicket				= $_POST['IdSubTipoTicket'];
	$local_Mensagem						= formatText($_POST['Mensagem'],NULL);
	$local_Assunto						= formatText($_POST['Assunto'],NULL);
	$local_IdGrupoUsuarioAtendimento	= $_POST['IdGrupoUsuarioAtendimento'];
	$local_LoginAtendimento				= $_POST['LoginAtendimento'];
	$local_Data							= $_POST['Data'];
	$local_Hora							= $_POST['Hora'];
	
	if($local_IdTicket == ''){
		$local_IdTicket = $_GET['IdTicket'];
	}

	if($local_Ticket != ''){
		$sql = "select
					IdTicket
				from
					HelpDesk
				where
					MD5 = '$local_Ticket'";
		$res = mysql_query($sql,$conCNT);
		$lin = mysql_fetch_array($res);

		$local_IdTicket = $lin[IdTicket];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_help_desk.php');
			break;
			
		case 'alterar':
			include('files/editar/editar_help_desk.php');
			break;
			
		case 'aceitar':
			include('files/editar/editar_help_desk.php');
			break;
			
		case 'reabrir':
			include('files/editar/editar_help_desk.php');
			break;
			
		case 'encaminhar':
			include('files/editar/editar_help_desk.php');
			break;
			
		default:
			$local_Acao 	= 'inserir';
			break;
	}
	
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/procurar.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNomeHelpDesk('Ticket')">
		<? include('filtro_help_desk.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_help_desk.php' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='HelpDesk'>
				<input type='hidden' name='IdPessoa' value='<?=getParametroSistema(4,7)?>'>
				<input type='hidden' name='IdContrato' value='<?=getParametroSistema(4,8)?>'>
				<input type='hidden' name='CountArquivo' value='0'>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='ExtensaoAnexo' value='<?=implode(',', $extensao_anexo)?>'>
				<input type='hidden' name='MaxUploads' value='<?=ini_get('max_file_uploads')?>'>
				<input type='hidden' name='MaxSize' value='<?=ini_get('upload_max_filesize')?>'>
				<div>
					<table style='width:845px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Ticket</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdTicket' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(); busca_help_desk(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' style='text-align:right;'>
								<div id='txtAdvertencia' style='float:right; width:363px; text-align:center;'>
									<B>
										Favor abrir um ticket para cada solicitação.
										<br />
										Tickets com mais de uma solicitação serão devolvidos.
									</B>
								</div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width:262px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Ticket</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>SubTipo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoTicket' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 406px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="busca_subtipo_help_desk(this.value); busca_subtipo_help_desk(this.value,'',document.formulario.IdSubTipoTicket2); document.formulario.IdTipoTicket2.value = this.value;" tabindex='2'>
									<option value=''></option>
									<?
										$sql = "select
													IdTipoHelpDesk,
													DescricaoTipoHelpDesk
												from 
													HelpDeskTipo 
												where
													IdStatus=1
												order by
													DescricaoTipoHelpDesk;";
										$res = @mysql_query($sql,$conCNT);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdTipoHelpDesk]'>$lin[DescricaoTipoHelpDesk]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdSubTipoTicket' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 405px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onchange="document.formulario.IdSubTipoTicket2.value = this.value;" tabindex='3'>
									<option value=''></option>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Assunto</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Assunto' value='' style='width:816px' tabindex='4' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeyup="document.formulario.Assunto2.value = this.value;">
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='tit_cpMensagem'>Mensagem</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Mensagem' style='width: 816px;' rows=9 tabindex='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeyup="document.formulario.Mensagem2.value = this.value;"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Anexo de Arquivos</div>
					<table id='tituloTabelaArquivo'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Cadastro de Arquivos&nbsp;[<a style='cursor:pointer;' onClick='addArquivo()'>+</a>]</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
					</table>
					<table id='tabelaArquivos'></table>
				</div>
				<div id="cpEquipeResponsavel" style='display:none;'>
					<div id='cp_tit'>Equipe Responsável</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titGrupoAtendimento' style='color:#000'>Grupo Atendimento</B></td>
							<td class='separador' id='tit_sepGrupoAtendimento'>&nbsp;</td>
							<td class='descCampo'><B id='titUsuarioAtendimento' style='color:#000'>Usuário Atendimento</B></td>
							<td class='separador' id='tit_sepUsuarioAtendimento'>&nbsp;</td>
							<td class='descCampo'><B id='titData' style='color:#000'>Data</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titHora' style='color:#000'>Hora</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuarioAtendimento' style='width:220px' disabled>
									<option value='' selected></option>
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
													UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
													UsuarioGrupoUsuario.Login = Usuario.Login and 
													UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
													Usuario.IdPessoa = Pessoa.IdPessoa and 
													Pessoa.TipoUsuario = 1 and 
													Usuario.IdStatus = 1 
												GROUP by 
													UsuarioGrupoUsuario.IdGrupoUsuario;";
										$res = @mysql_query($sql,$conCNT);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador' id='cap_sepGrupoAtendimento'>&nbsp;</td>
							<td class='campo'>
								<select name='LoginAtendimento' style='width:372px' disabled>
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador' id="cap_sepUsuarioAtendimento">&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Data' id='cpData' value='' autocomplete="off" style='width:110px;' maxlength='10' readOnly>
							</td>
							<td class='separador' id="titData">&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpHora' name='Hora' value='' autocomplete="off" style='width:75px;' maxlength='5' readOnly>
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
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px; text-align:right'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left;'>
								<input type='button' name='bt_imprimir' style='width:131px' value='Imprimir Histórico' class='botao' tabindex='1005' onClick="cadastrar('imprimir')">
							</td>
							<td class='campo' style='padding-right:0; width:314px;'>
								<input type='button' style='width:82px;' name='bt_aceitar' value='Concluir' class='botao' tabindex='1006' onClick="cadastrar('aceitar')" disabled>
								<input type='button' style='width:80px;' name='bt_reabrir' value='Reabrir' class='botao' tabindex='1007' onClick="cadastrar('reabrir')" disabled>
								<input type='button' style='width:140px;' name='bt_encaminhar' value='Enc. CNTSistemas' class='botao' tabindex='1007' onClick="cadastrar('encaminhar')" disabled>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right; padding-right:0;  width:168px;'>
								<input type='button' style='width:82px' name='bt_inserir' value='Cadastrar' class='botao' tabindex='1006' onClick="cadastrar('inserir')">
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%;' border='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
				<div id='cp_help_desk_historico'  style='display:none;margin-top:0;'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:0'>Histórico</div>
					<table id='tabelaHelpDeskHistorico' class='tableListarCad' cellspacing='0'>
						<tr><td height='9px'></td></tr>
					</table>
				</div>
				<div id="cp_duplicado" style='display:none;'>
					<div>
						<div id='cp_tit'>Dados do Ticket</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Tipo</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B>SubTipo</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' valign='top'>
									<select name='IdTipoTicket2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 406px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" onChange="busca_subtipo_help_desk(this.value); busca_subtipo_help_desk(this.value,'',document.formulario.IdSubTipoTicket); document.formulario.IdTipoTicket.value = this.value;" tabindex='2'>
										<option value=''></option>
										<?
											$sql = "select
														IdTipoHelpDesk,
														DescricaoTipoHelpDesk
													from 
														HelpDeskTipo 
													where
														IdStatus=1
													order by
														DescricaoTipoHelpDesk;";
											$res = @mysql_query($sql,$conCNT);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdTipoHelpDesk]'>$lin[DescricaoTipoHelpDesk]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' valign='top'>
									<select name='IdSubTipoTicket2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 405px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" onchange="document.formulario.IdSubTipoTicket.value = this.value;" tabindex='3'>
										<option value=''></option>
									</select>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Assunto</B></td>				
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Assunto2' value='' style='width:816px' tabindex='4' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeyup="document.formulario.Assunto.value = this.value;">
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='tit_cpMensagem2'>Mensagem</B></td>				
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Mensagem2' style='width: 816px;' rows=9 tabindex='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeyup="document.formulario.Mensagem.value = this.value;"></textarea>
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
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao2' value='' style='width:180px' maxlength='20'  readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao2' value='' style='width:202px'  readOnly>
								</td>							
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px; text-align:right'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:left;'>
									<input type='button' name='bt_imprimir2' style='width:131px' value='Imprimir Histórico' class='botao' tabindex='1005' onClick="cadastrar('imprimir')">
								</td>
								<td class='campo' style='padding-right:0; width:314px;'>
									<input type='button' style='width:82px;' name='bt_aceitar2' value='Concluir' class='botao' tabindex='1006' onClick="cadastrar('aceitar')" disabled>
									<input type='button' style='width:80px;' name='bt_reabrir2' value='Reabrir' class='botao' tabindex='1007' onClick="cadastrar('reabrir')" disabled>
									<input type='button' style='width:140px;' name='bt_encaminhar2' value='Enc. CNTSistemas' class='botao' tabindex='1007' onClick="cadastrar('encaminhar')" disabled>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='text-align:right; padding-right:0;  width:168px;'>
									<input type='button' style='width:82px' name='bt_inserir2' value='Cadastrar' class='botao' tabindex='1006' onClick="cadastrar('inserir')">
								</td>
							</tr>
						</table>
					</div>
					<div>	
						<table style='width:100%;' border='0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdTicket != ""){
			echo "busca_help_desk($local_IdTicket,false);";
		}
	?>
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
