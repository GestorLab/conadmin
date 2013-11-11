<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"V";	
	$Path				=   "../../";

	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdPessoaLogin				= $_SESSION["IdPessoa"];
	
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdTicket						= $_POST['IdTicket'];
	$local_IdLojaHelpDesk				= $_POST['IdLojaHelpDesk'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_IdTipoTicket					= $_POST['IdTipoTicket'];
	$local_IdSubTipoTicket				= $_POST['IdSubTipoTicket'];
	$local_Assunto						= formatText($_POST['Assunto'],NULL);
	$local_Mensagem						= formatText($_POST['Mensagem'],NULL);
	$local_LoginAtendimento				= formatText($_POST['LoginAtendimento'],NULL);
	$local_Data							= formatText($_POST['Data'],NULL);
	$local_Hora							= formatText($_POST['Hora'],NULL);
	$local_IdGrupoUsuarioAtendimento	= $_POST['IdGrupoUsuarioAtendimento'];
	$local_IdStatus						= $_POST['IdStatus'];
	$local_LoginCriacao					= $_POST['LoginCriacao'];
	$local_Publica						= $_POST['Publica'];
	$local_ChangeLog					= $_POST['ChangeLog'];
	
	if($local_IdTicket == ''){
		$local_IdTicket = $_GET['IdTicket'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_help_desk.php');
			break;
			
		case 'alterar':
			include('files/editar/editar_help_desk.php');
			break;
			
		case 'finalizar':
			include('files/editar/editar_help_desk.php');
			break;
			
		case 'pendente':
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
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 

		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk_default.js'></script>
		<script type = 'text/javascript' src = 'js/help_desk_busca_pessoa_aproximada.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
	</head>
	<body onLoad="ativaNome('Help Desk')">
		<? include('filtro_help_desk.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_help_desk.php' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='HelpDesk'>
				<input type='hidden' name='Visualizar' value=''>
				<input type='hidden' name='IdMarcador' value=''>
				<input type='hidden' name='IdMarcadorDefault' value='<?=getCodigoInterno(3,98)?>'>
				<input type='hidden' name='CountArquivo' value='0'>
				<input type='hidden' name='ExtensaoAnexo' value='<?=implode(',', $extensao_anexo)?>'>
				<input type='hidden' name='MaxUploads' value='<?=ini_get('max_file_uploads')?>'>
				<input type='hidden' name='MaxSize' value='<?=ini_get('upload_max_filesize')?>'>
				<input type='hidden' name='IdPublicaDefault' value='<?=getCodigoInterno(3,129)?>'>
				<input type="hidden" name="IdStatusTemp" value=''>
				<input type="hidden" name="IdGrupoAtendimento" value=''>
				<input type="hidden" name="LoginAtendimentoTemp" value=''>
				<input type="hidden" name="DataAtualTemp" value='<?=date("YmdHi")?>'>
				<input type="hidden" name="AnexarArquivo" value='1'>
				<input type="hidden" name="TicketLista">
				<input type="hidden" name="PermitirEditarPrioridade" value='<?=permissaoSubOperacao($localModulo, 209, "U")?>'>
	
				<div style='width:830px; margin-left:16px;'>
					<table style='width:100%;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Ticket</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Local de Abertura</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' ><B id='titTableMarcador' style='color:#000; display:none'>Marcador</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' ><span id='titPrioridade' style='text-align:center; display:none;'>Prioridade</span></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdTicket' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_help_desk(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='LocalAbertura' value='' style='width:121px' maxlength='255' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<table id='tableMarcador' style='width:90px; border:1px #A4A4A4 solid; margin:0px; display:none;' cellpading='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td><img id='mVermelho' style='background-color:<?=getParametroSistema(156,1);?>;' alt='<?=getParametroSistema(120,1)?>' title='<?=getParametroSistema(120,1)?>' onClick="addMarcador('1')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
										<td><img id='mAmarelo' style='background-color:<?=getParametroSistema(156,2);?>;' alt='<?=getParametroSistema(120,2)?>' title='<?=getParametroSistema(120,2)?>' onClick="addMarcador('2')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
										<td ><img id='mVerde' style='background-color:<?=getParametroSistema(156,3);?>;' alt='<?=getParametroSistema(120,3)?>' title='<?=getParametroSistema(120,3)?>' onClick="addMarcador('3')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<div id='cpPrioridade' style='display:none;'><img id="nivelPrioridade" src='../../img/estrutura_sistema/prioridade0.gif' onClick="addPrioridade(this)"></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width:100%;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados do Cliente</div>
					
					<?	
						$nome="
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Loja</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razã¯ Social</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>CNPJ</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='IdLojaHelpDesk' value='' autocomplete='off' style='width:70px' maxlength='11' onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='Nome' value='' style='width:229px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' id='cp_RazaoSocial'>
											<input type='text'  name='RazaoSocial' value='' style='width:229px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:140px' maxlength='18' readOnly>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>E-mail</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>
						";
							
						$razao="
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Loja</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px'>Pessoa</B><B style='color:#000'>Razão  Social</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Nome Fantasia</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>CNPJ</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='IdLojaHelpDesk' value='' autocomplete='off' style='width:70px' maxlength='11' onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='1'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:229px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Nome' value='' style='width:229px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:140px' maxlength='18' readOnly>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Email</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>
						";
							
						switch(getCodigoInterno(3,24)){
							case 'Nome':
								echo "$razao";
								break;
							default:
								echo "$nome";
						}
					?>
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Loja</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Nasc.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>RG</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLojaHelpDeskF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="document.formulario.IdLojaHelpDesk.value = this.value;" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='<?=$local_IdPessoa?>' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'><input type='text' class='agrupador' name='NomeF' value='' style='width:184px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNascimento' value='' style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:120px' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:88px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:80px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Fone Residencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Celular</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fax</td>	
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'>Complemento Fone</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' readOnly>
							</td>	
							<td class='separador'>&nbsp;</td>						
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' readOnly>
							</td>			
						</tr>
					</table>
					<table>	
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Pais</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Estado</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Cidade</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:92px' maxlength='11' readOnly><input  class='agrupador' type='text' name='Pais' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' style='width:92px' maxlength='11' onkeypress="mascara(this,event,'int')" readOnly><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" readOnly><input class='agrupador' type='text' name='Cidade' value='' style='width:233px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Chamado</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titTipo'>Tipo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titSubTipo'>SubTipo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoTicket' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 406px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="busca_subtipo_help_desk(this.value); busca_subtipo_help_desk(this.value,'',document.formulario.IdSubTipoTicket2); document.formulario.IdTipoTicket2.value = this.value;" tabindex='4'>
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
								<input type='hidden' name='IdTipoTicketTemp' value=''>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdSubTipoTicket' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 405px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onchange="document.formulario.IdSubTipoTicket2.value = this.value;" tabindex='5'>
									<option value=''></option>
								</select>
								<input type='hidden' name='IdSubTipoTicketTemp' value=''>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><B id='titAssunto'>Assunto</B></td>
							<td class='descCampo'><B id='titPublica' style='margin-left:9px; display:none;'>Pública</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpAssunto' name='Assunto' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onkeyup="document.formulario.Assunto2.value = this.value;" tabindex='6'>
								<input type='hidden' name='AssuntoTemp' value=''>
							</td>
							<td class='campo'>
								<select id='cpPublica' name='Publica' style='width:105px; margin-left:9px; display:none' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="document.formulario.Publica2.value = this.value;" tabindex='7'>
									<option value=''></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 171
												order by 
													IdParametroSistema;";
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
							<td class='descCampo'><B id="tit_cpMensagem">Mensagem</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Mensagem' style='width: 816px;' rows='9' tabindex='8' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeyup="document.formulario.Mensagem2.value = this.value;"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='blAnexoArquivo'>
					<div id='cp_tit'>Anexo de Arquivos</div>
					<table id='tituloTabelaArquivo'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Cadastro de Arquivos&nbsp;[<a style='cursor:pointer;' onClick='addArquivo(null);'>+</a>]</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
					</table>
					<table id='tabelaArquivos'></table>
				</div>
				<div>
					<div id='cp_tit'>Equipe Responsável</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Grupo Atendimento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Atendimento</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuarioAtendimento' style='width:406px' tabindex='31' onChange="busca_login_usuario(this.value,document.formulario.LoginAtendimento); ticket_dia(document.formulario.Data.value,this.value,document.formulario.LoginAtendimento.value,event);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
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
								<input type='hidden' name='IdGrupoUsuarioAtendimentoTemp' value=''>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='LoginAtendimento' style='width:405px' tabindex='32' onChange="limpar_campo(this.value);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
									<option value='' selected></option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Previsã¯ da Etapa</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id="titData" style="color:#000000;">Data</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id="titHora" style="color:#000000;">Hora</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Qtd. Ticket Dia</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpData' name='Data' value='' autocomplete="off" style='width:110px;' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data('titData',this);ticket_dia(this.value,document.formulario.IdGrupoUsuarioAtendimento.value,document.formulario.LoginAtendimento.value,event);" onkeyup="document.formulario.Data2.value = this.value;" tabindex='33'>
								<input type='hidden' name='DataTemp' value=''>
							</td>
							<td class='find'><img id='cpDataIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpData",
							        inputFieldAux  : "cpData2",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpHora' name='Hora' value='' autocomplete="off" style='width:75px;' maxlength='5' onkeypress="mascara(this,event,'hora')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Time('titHora',this);" onkeyup="document.formulario.Hora2.value = this.value;" tabindex='34'>
								<input type='hidden' name='HoraTemp' value=''>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><input type='text' name='TicketDia' onMouseover="verificaQuadroLista(this,event,document.formulario.Data)" value='0' style='width: 95px' readOnly/></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Status</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titStatusNovo'>Novo Status</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='height:25px;'>
								<select name='IdStatus' style='width:406px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onchange='limpa_data_hora(this.value)' tabindex='35'>
									<option value='' selected></option>
									<?
										$sql = "select
													IdParametroSistema,
													ValorParametroSistema
												from 
													ParametroSistema
												where
													IdGrupoParametroSistema = 128 and
													(
														IdParametroSistema < 600 or
														IdParametroSistema > 699
													)
												order by 
													IdParametroSistema;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<input type='hidden' name='IdStatusNovoTemp' value=''>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_ChangeLog' style='display:none;'>
					<div id='cp_tit'>Change Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Change Log</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ChangeLog' style='width:816px;' rows='9' tabindex='36' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onchange='verifica_change_log(this.value);'></textarea>
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
					<table style='width:100%; text-align:right'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left;'>
								<input type='button' style='width:141px' name='bt_visualizar' value='Visualizar Histórico' class='botao' tabindex='37' onClick="buscaVisualizar(true)">
							</td>
							<td class='campo' style='text-align:right; width:375px;'>
								<input type='button' name='bt_imprimir' style='width:131px' value='Imprimir Histórico' class='botao' tabindex='38' onClick="cadastrar('imprimir')">
								<input type='button' name='bt_finalizar' style='width:81px' value='Finalizar' class='botao' tabindex='39' onClick="cadastrar('finalizar')" disabled>
								<input type='button' name='bt_pendente' style='width:151px' value='Pendente Atualização' class='botao' tabindex='40' onClick="cadastrar('pendente')" disabled>
							</td>
							<td class='campo' style='text-align:right; padding-right:5px;  width:250px;'>
								<input type='button' style='width:82px' name='bt_inserir' value='Cadastrar' class='botao' tabindex='41' onClick="cadastrar('inserir')">
								<input type='button' style='width:62px' name='bt_alterar' value='Alterar' class='botao' tabindex='42' onClick="cadastrar('alterar')">
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
				<div id='cp_help_desk_historico' style='display:none; margin-top:0;'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:0'>Histórico</div>
					<table id='tabelaHelpDeskHistorico' class='tableListarCad' cellspacing='0'>
						<tr><td height='9px'></td></tr>
					</table>
				</div>
				<div id="cp_duplicado" style='display:none;'>
					<div>
						<div id='cp_tit'>Dados do Chamado</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='titTipo2'>Tipo</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='titSubTipo2'>SubTipo</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' valign='top'>
									<select name='IdTipoTicket2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 406px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="busca_subtipo_help_desk(this.value); busca_subtipo_help_desk(this.value,'',document.formulario.IdSubTipoTicket2); document.formulario.IdTipoTicket.value = this.value;" tabindex='43'>
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
									<select name='IdSubTipoTicket2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 405px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" onchange="document.formulario.IdSubTipoTicket.value = this.value;" tabindex='44'>
										<option value=''></option>
									</select>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>							
								<td class='descCampo'><B id='titAssunto2'>Assunto</B></td>
								<td class='descCampo'><B id='titPublica2' style='margin-left:9px; display:none;'>Pública</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' id='cpAssunto2' name='Assunto2' value='' style='width:817px' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onkeyup="document.formulario.Assunto.value = this.value;" tabindex='45'>
								</td>
								<td class='campo'>
									<select id='cpPublica2' name='Publica2' style='width:105px; margin-left:9px; display:none;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="document.formulario.Publica.value = this.value;" tabindex='46'>
										<option value=''></option>
										<?
											$sql = "select
														IdParametroSistema,
														ValorParametroSistema
													from 
														ParametroSistema
													where
														IdGrupoParametroSistema = 171
													order by 
														IdParametroSistema;";
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
								<td class='descCampo'><B id="tit_cpMensagem2">Mensagem</B></td>				
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='Mensagem2' style='width: 816px;' rows='9' tabindex='47' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onkeyup="document.formulario.Mensagem.value = this.value;"></textarea>
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Previsã¯ da Etapa</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id="titData2" style="color:#000000;">Data</B></td>
								<td class='find'>&nbsp;</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id="titHora2" style="color:#000000;">Hora</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Qtd. Ticket Dia</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' id='cpData2' name='Data2' value='' autocomplete="off" style='width:110px;' maxlength='10' onkeypress="mascara(this,event,'date');" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onChange="validar_Data('titData2',this); ticket_dia(this.value,document.formulario.IdGrupoUsuarioAtendimento.value,document.formulario.LoginAtendimento.value,event);" onkeyup="document.formulario.Data.value = this.value;" tabindex='48'>
								</td>
								<td class='find'><img id='cpDataIco2' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
								<script type="text/javascript">
									Calendar.setup({
										inputField     : "cpData2",
										inputFieldAux  : "cpData",
										ifFormat       : "%d/%m/%Y",
										button         : "cpDataIco2"
									});
								</script>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' id='cpHora2' name='Hora2' value='' autocomplete="off" style='width:75px;' maxlength='5' onkeypress="mascara(this,event,'hora')" onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" onChange="validar_Time('titHora2',this);" onkeyup="document.formulario.Hora.value = this.value;" tabindex='49'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><input type='text' name='TicketDia2' onMouseover="verificaQuadroLista(this,event,document.formulario.Data2)" style='width: 95px' ReadOnly='ReadOnly'/></td>
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
						<table style='width:100%; text-align:right'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' name='bt_imprimir2' style='width:131px' value='Imprimir Histórico' class='botao' tabindex='50' onClick="cadastrar('imprimir')">
									<input type='button' name='bt_finalizar2' style='width:81px' value='Finalizar' class='botao' tabindex='51' onClick="cadastrar('finalizar')" disabled>
									<input type='button' name='bt_pendente2' style='width:151px' value='Pendente Atualização' class='botao' tabindex='52' onClick="cadastrar('pendente')" disabled>
								</td>
								<td class='campo' style='text-align:right; padding-right:5px;  width:250px;'>
									<input type='button' style='width:82px' name='bt_inserir2' value='Cadastrar' class='botao' tabindex='53' onClick="cadastrar('inserir');">
									<input type='button' style='width:62px' name='bt_alterar2' value='Alterar' class='botao' tabindex='54' onClick="cadastrar('alterar')">
								</td>
							</tr>
						</table>
					</div>
					<div>	
						<table style='width:100%;height:33px;' border='0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
							</tr>							
						</table>
					</div>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdTicket != ""){
			echo "busca_help_desk($local_IdTicket,false);";
			if($local_IdPessoa != ""){
				echo "busca_pessoa($local_IdPessoa,false);";
			}
		}
	?>
	
	function status_inicial(){
		if(document.formulario.IdStatus.value == ''){
			document.formulario.IdStatus.value = '<?=getCodigoInterno(3,4)?>';
		}
		if(document.formulario.LocalAbertura.value == ''){
			document.formulario.LocalAbertura.value = '<?=getParametroSistema(129,2)?>';
		}
	}
	
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
