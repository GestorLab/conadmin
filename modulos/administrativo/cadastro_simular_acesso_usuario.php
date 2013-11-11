<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_Login		 		= trim($_POST['Login'],NULL);
	$local_IdPessoa		 		= formatText($_POST['IdPessoa'],NULL);
	$local_IdStatus				= formatText($_POST['IdStatus'],NULL);
	$local_LimiteVisualizacao	= formatText($_POST['LimiteVisualizacao'],NULL);
	$local_IpAcesso				= formatText($_POST['IpAcesso'],NULL);
	$local_DataExpiraSenha		= formatText($_POST['DataExpiraSenha'],NULL);

	if($_GET['Login']!=''){
		$local_Login	= $_GET['Login'];	
	}
	
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	= $_GET['IdPessoa'];	
	}
	
	list($local_LoginNovo, $local_IdPessoaNovo) = explode('_', $local_Login);
	
	switch ($local_Acao){
		case 'simular':
			header("Location: rotinas/simular_acesso_usuario.php?NovoLogin=$local_LoginNovo&NovoIdPessoa=$local_IdPessoaNovo");
			break;			
		default:
			$local_Acao 	= 'simular';
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script>  
		<script type = 'text/javascript' src = '../../js/event.js'></script>  
		<script type = 'text/javascript' src = 'js/simular_acesso_usuario.js'></script>
		<script type = 'text/javascript' src = 'js/simular_acesso_usuario_default.js'></script>
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
	<body  onLoad="ativaNome('Usuário/Simular Acesso')">
		<div id='filtroBuscar'></div>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_simular_acesso_usuario.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='SimularAcessoUsuario'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:148px'>Login</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>								
								<select name='Login' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="busca_usuario(this.value.split('_')[0]);" style='width:176px' tabindex='1'>
									<option value='' selected></option>
									<?
										$sql = "select 
													IdPessoa,
													Login 
												from 
													Usuario 
												where 
													IdStatus = 1 order by Login asc";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[Login]_$lin[IdPessoa]'  ".compara($local_LoginNovo,$lin[Login],"selected", "").">$lin[Login]</option>";
										}
									?>
								</select>
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
									<td class='descCampo'><font style='margin-right:36px'>Pessoa</font>Nome Pessoa</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>									
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
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
									<td class='descCampo'><font style='margin-right:36px'>Pessoa</font><font style='color:#000'>Razão Social</font></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>Nome Fantasia</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
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
							<td class='descCampo'><font style='margin-right:36px'>Pessoa</font>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
						</tr>
						<tr>							
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' title='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:124px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Usuário</div>
						<table>
							<tr>
								<td valign='top'>									
									<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'>Limite Visualização</td>
											<td class='separador'></td>
											<td class='descCampo'>Status</td>
										</tr>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='find'>
												<input type='text' name='LimiteVisualizacao' value='' style='width:170px' maxlength='11' readOnly>
											</td>
											<td class='separador'></td>
											<td class='campo'>
												<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:176px' disabled>
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
										</tr>
									</table>
									<table>
										<tr>
											<td class='find'>&nbsp;</td>
											<td class='descCampo'>Data de Expiração</td>															
										</tr>
										<tr>
											
											<td class='find'>&nbsp;</td>
											<td class='campo'>
												<input type='text' name='DataExpiraSenha' id='cpData' value='' style='width:170px' maxlength='10' readOnly>												
											</td>											
										</tr>
									</table>
								</td>
								<td class='separador'>&nbsp;</td>
								<td>
									<table>
										<tr>
											<td class='descCampo'>IPs para Acesso ao Sistema</td>				
										</tr>
										<tr>
											<td class='campo'>
												<textarea name='IpAcesso' style='width: 438px; height:64px' readOnly></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									
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
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_simular' value='Simular' class='botao' tabindex='2' onClick='cadastrar()'>							
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
				include("files/busca/pessoa.php");
			?>
		</div>
	</body>
</html>
<script>
<?
	if($local_LoginNovo!=''){
		echo "busca_usuario('$local_LoginNovo',false);";		
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
	}
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
