<?
	$localModulo		=	1;
	$localOperacao		=	74;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdAviso				= formatText($_POST['IdAviso'],NULL);
	$local_Data					= formatText($_POST['Data'],NULL);
	$local_Hora					= formatText($_POST['Hora'],NULL);
	$local_TituloAviso			= formatText($_POST['TituloAviso'],NULL);
	$local_ResumoAviso			= formatText($_POST['ResumoAviso'],NULL);
	$local_IdAvisoForma			= formatText($_POST['IdAvisoForma'],NULL);
	$local_IdGrupoPessoa		= formatText($_POST['IdGrupoPessoa'],NULL);
	$local_Aviso				= formatText($_POST['Aviso'],NULL);
	$local_IdPessoa				= formatText($_POST['IdPessoa'],NULL);
	$local_IdGrupoUsuario		= formatText($_POST['IdGrupoUsuario'],NULL);
	$local_Usuario				= formatText($_POST['Usuario'],NULL);
	$local_IdServico			= $_POST['IdServico'];
	$local_ParametroContrato	= formatText($_POST['ParametroContrato'],NULL);
	
	if($_GET['IdAviso']!=''){
		$local_IdAviso	=	$_GET['IdAviso'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_aviso.php');
			break;		
		case 'alterar':
			include('files/editar/editar_aviso.php');
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
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />				
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_url.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/aviso.js'></script>
		<script type = 'text/javascript' src = 'js/aviso_default.js'></script>
		<script type = 'text/javascript' src = 'js/grupo_pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>		
	</head>
	<style type="text/css">
		input[type=text]:readOnly  		{ background-color: #FFF; }
		input[type=datetime]:readOnly  	{ background-color: #FFF; }
		input[type=date]:readOnly  		{ background-color: #FFF; }
		textarea:readOnly  				{ background-color: #FFF; }
		select:disabled  { background-color: #FFF; }
		select:disabled  { color: #000; }
	</style>
	<body  onLoad="ativaNome('Aviso')">
		<? 
			include('filtro_aviso.php'); 
		?>
	<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_aviso.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Aviso'>
				<input type='hidden' name='DataExpiracao' value=''>
				<input type="hidden" name="DataAtualTemp" value='<?=date("Ymd")?>'>
				<div><table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Aviso</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdAviso' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_aviso(this.value,'false',document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados do Aviso</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Título Aviso</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Resumo Aviso</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Forma Aviso</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='LabelData'>Data Expiração</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='LabelHora'>Hora</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TituloAviso' value='' autocomplete="off" style='width:200px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ResumoAviso' value='' autocomplete="off" style='width:228px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAvisoForma' onChange="filtro_informativo_interno(this.value)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:150px' tabindex='4'>
									<option value='' selected></option>
										<?
											$sql = "select IdAvisoForma, DescricaoAvisoForma from AvisoForma";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdAvisoForma]'>$lin[DescricaoAvisoForma]</option>";
											}
										?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Data' id='cpData' value='' style='width:95px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onkeypress="mascara(this,event,'date')" onChange="validar_Data('LabelData',this); impedir_data_expiracao(this.value)" tabindex='5'>
							</td>
							<td class='find' id='cpDataIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
					    	    inputField     : "cpData",
					    	    ifFormat       : "%d/%m/%Y",
					    	    button         : "cpDataIco"
					    	});
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Hora' value='' style='width:60px' maxlength='5' onFocus="Foco(this,'in',true)" onkeyPress="mascara(this,event,'hora');" onBlur="Foco(this,'out')" onChange="validar_Hora('LabelHora',this)" tabindex='6'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Descrição Aviso</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Aviso' style='width: 817px;' rows=5 tabindex='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Filtros</div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='8'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>Nome Fantasia</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='8'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
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
							<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:124px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px; color:#000'>Serviço</b>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo Serviço</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 350); limpa_form_servico(); busca_servico_lista(); document.formularioServico.DescricaoServico.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:575px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:156px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>								
								<td class='descCampo'>Grupo Pessoa</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:34px; color:#000'>Valor Parametro Contrato</B></td>
							</tr>
							<tr>								
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoPessoa', true, event, null, 200); document.formularioGrupoPessoa.Nome.value='';  valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdGrupoPessoa' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13' onChange='busca_grupo_pessoa(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='DescricaoGrupoPessoa' value='' style='width:575px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'><input type='text' name='ParametroContrato' value='' autocomplete="off" style='width:150px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'></td>
							</tr>	
					</table>
					<table id='GrupoUsuarioBloco' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Grupo Usuário</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuario' style='width:406px;' tabindex='14' onChange="busca_login_usuario(this.value,document.formulario.Usuario);" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
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
								<input type='hidden' name='IdGrupoUsuarioTemp' value=''>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Usuario' style='width:405px;' tabindex='15' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');">
									<option value='' selected></option>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:203px'  readOnly>
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
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='16' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='17' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='18' onClick="excluir(document.formulario.IdAviso.value)">
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
				include("files/busca/grupo_pessoa.php");
				include("files/busca/pessoa.php");
				include("files/busca/servico.php");
			?>
		</div>
	</div>
</body>
</html>
<script language='JavaScript' type='text/javascript'> 
<?
	if($local_IdAviso!=''){
		echo "busca_aviso($local_IdAviso,false,document.formulario.Local.value);";
	}
?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>