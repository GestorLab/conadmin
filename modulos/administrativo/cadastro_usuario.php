<?
	$localModulo		=	1;
	$localOperacao		=	6;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja						= $_SESSION["IdLoja"];
	$local_Login_Sistema		= $_SESSION["Login"];
	$local_Acao 				= $_POST['Acao'];
	$local_Erro					= $_GET['Erro'];
	
	$local_Login		 		= $_POST['Login'];
	$local_IdPessoa		 		= formatText($_POST['IdPessoa'],NULL);
	$local_Password		 		= formatText($_POST['Password'],NULL);
	$local_IdStatus				= formatText($_POST['IdStatus'],NULL);
	$local_IdAcessoSimultaneo	= formatText($_POST['IdAcessoSimultaneo'],NULL);
	$local_LimiteVisualizacao	= formatText($_POST['LimiteVisualizacao'],NULL);
	$local_IpAcesso				= formatText($_POST['IpAcesso'],NULL);
	$local_DataExpiraSenha		= formatText($_POST['DataExpiraSenha'],NULL);
	$local_ForcaAlterarSenha	= formatText($_POST['ForcaAlterarSenha'],NULL);
	$local_InteracaoMikrotik	= formatText($_POST['InteracaoMikrotik'],NULL);
	$local_IdGrupoAcesso		= formatText($_POST['IdGrupoAcesso'],NULL);
	$local_ServidorRadius		= formatText($_POST['ServidorRadius'],NULL);

	if($_GET['Login']!=''){
		$local_Login	= url_string_xsl($_GET['Login'],'url',false);	
	}
	
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	= $_GET['IdPessoa'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_usuario.php');
			break;		
		case 'alterar':
			include('files/editar/editar_usuario.php');
			break;
		default:
			$local_Acao = 'inserir';
			break;
	}
	
	$local_Confirmacao 	= "";
	$local_Password		= "";
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script>  
		<script type = 'text/javascript' src = '../../js/event.js'></script>  
		<script type = 'text/javascript' src = 'js/usuario.js'></script>
		<script type = 'text/javascript' src = 'js/usuario_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Usuários')">
		<? include('filtro_usuario.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_usuario.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value=<?=$local_Erro?> />
				<input type='hidden' name='Local' value='Usuario' />
				<input type='hidden' name='ServidorRadius' value='' />
				<input type='hidden' name='ForcaAlterarSenhaDefault' value='<?=getCodigoInterno(3,121)?>' />
				<input type='hidden' name='InteracaoMikrotik' />
				<input type='hidden' name='NivelSenha' />
				<input type='hidden' name='LocalLogin' value='<?=$local_Login_Sistema?>' />
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:148px'>Login</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Login' value='' style='width:170px' maxlength='20' onChange='busca_usuario(this.value);' tabindex='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados da Pessoa</div>
					
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 116); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:146px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>Nome Fantasia</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 116); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();\"></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:146px' maxlength='18' readOnly>
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
							<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 116); limpa_form_pessoa(); busca_pessoa_lista(); document.formularioPessoa.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readOnly>
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
				</div>
				<div>
					<div id='cp_tit'>Dados do Usuário</div>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td valign='top'>								
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Limite Visualização</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Data de Expiração</td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='LimiteVisualizacao' value='' style='width:170px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='5'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DataExpiraSenha' id='cpData' value='' style='width:148px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='6'>												
										</td>
										<td class='find' id='cpDataIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
											<script type="text/javascript">
											    Calendar.setup({
											        inputField     : "cpData",
										   		    ifFormat       : "%d/%m/%Y",
						    					    button         : "cpDataIco"
											    });
										</script>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B>Status</B></td>
										<td class='separador'>&nbsp;</td>										
										<td class='descCampo'><B>Acesso Simultâneo</B></td>										
									</tr>
									<tr>										
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdStatus' tabindex='7' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:176px; margin-left:1px;'>
												<option value='' selected></option>
													<?
														$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=30 order by ValorParametroSistema";
														$res = @mysql_query($sql,$con);
														while($lin = @mysql_fetch_array($res)){
															echo"<option value='$lin[IdParametroSistema]'  ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
														}
													?>
											</select>
										</td>									
										<td class='separador'></td>										
										<td class='campo'>
											<select name='IdAcessoSimultaneo' tabindex='8' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:152px; margin-left:1px;'>
												<option value='' selected></option>
													<?
														$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=158 order by ValorParametroSistema";
														$res = @mysql_query($sql,$con);
														while($lin = @mysql_fetch_array($res)){
															echo"<option value='$lin[IdParametroSistema]'  ".compara($local_IdAcessoSimultaneo,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
														}
													?>
											</select>
										</td>
									</tr>
								</table>							
							</td>
							<td>&nbsp;</td>
							<td>
								<table>
									<tr>
										<td class='descCampo'>IPs para Acesso ao Sistema</td>				
									</tr>
									<tr>
										<td class='campo'>
											<textarea name='IpAcesso' style='width: 437px; height:60px' tabindex='9' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
										</td>
									</tr>
								</table>								
							</td>								
						</tr>
						<tr>
							<td colspan='3'>							
							
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_tit'>Senha</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Senha</B></td>
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'><B>Confirme sua Senha</B></td>					
						<td class='separador'>&nbsp;</td>
						<td class='descCampo'>Força Alterar Senha</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'  style="vertical-align:top;">
							<input type='password' name='Password' style='width:170px' onkeyup="verificaSenha('Usuario');" onFocus="Foco(this,'in');verificaSenha('Usuario')"  onBlur="Foco(this,'out');" tabindex='10'>
						</td>
						<td class='separador'>&nbsp;</td>
						<td class='campo' style="vertical-align:top;">
							<input type='password' name='Confirmacao' style='width:170px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
						</td>		
						<td class='separador'>&nbsp;</td>
						<td class='campo'>
							<select name='ForcaAlterarSenha' tabindex='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:205px'>
								<?
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=148 order by ValorParametroSistema";
									$res = @mysql_query($sql,$con);
									while($lin = @mysql_fetch_array($res)){
										echo"<option value='$lin[IdParametroSistema]'  ".compara($local_IdStatus,$lin[IdParametroSistema],"selected", "").">$lin[ValorParametroSistema]</option>";
									}
								?>
								<option value="<?=getCodigoInterno(3,241)?>">Automaticamente em <?=getCodigoInterno(3,241)?> dias.</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td colspan='5'>						
							<div id="statusSenha" style="margin-top:5px; display:none;">
								<div id="estadoSenha"></div>
								<div style="width:176px; height: 8px; background-color: #DDD; margin-top:1.5px;">
									<div id="nivel" style="height: 8px;"></div>
								</div>
							</div>
						</td>
					</tr>					
				</table>		
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
								<input type='text' name='DataCriacao' value='' style='width:201px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:201px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='1001' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='1002' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='1003' onClick="excluir(document.formulario.Login.value)">
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
				<div id='cp_grupo_usuario' style='display:block;'>
					<div id='cp_tit' style='margin:10px 0 0 0'>Grupos do Usuario</div>
					<table id='tabelaGrupoUsuario' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Id</td>
							<td>Nome Grupo Usuario</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaGrupoUsuarioTotal'>Total: 0</td>
						</tr>
					</table>
				</div>
				<div id='cp_grupo_permissao' style='display:block;'>
					<div id='cp_tit' style='margin:10px 0 0 0'>Permissões do Usuario</div>
					<table id='tabelaGrupoPermissao' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>Id</td>
							<td>Nome Grupo Permissão</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaGrupoPermissaoTotal'>Total: 0</td>
						</tr>
					</table>
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
	if($local_Login!=''){
		echo "busca_usuario('$local_Login',false);";		
	}else{
		if($local_IdPessoa != ''){
			echo "busca_pessoa('$local_IdPessoa',false);";		
		}
	}
?>
	function status_inicial(){
		if(document.formulario.IdStatus.value == ''){
			document.formulario.IdStatus.value = '<?=getCodigoInterno(3,4)?>';
		}
		if(document.formulario.IdAcessoSimultaneo.value == ''){
			document.formulario.IdAcessoSimultaneo.value = '<?=getCodigoInterno(3,118)?>';
		}
		if(document.formulario.Login.value == '' ){
			document.formulario.ForcaAlterarSenha.value = document.formulario.ForcaAlterarSenhaDefault.value;
		}
	}

	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
