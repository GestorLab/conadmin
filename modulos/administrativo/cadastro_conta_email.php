<?
	$localModulo		= 1;
	$localOperacao		= 165;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET['Erro'];	
	
	$local_IdContaEmail					= $_POST['IdContaEmail'];
	$local_DescricaoContaEmail			= formatText($_POST['DescricaoContaEmail'],NULL);
	$local_NomeRemetente				= formatText($_POST['NomeRemetente'],NULL);
	$local_EmailRemetente				= formatText($_POST['EmailRemetente'],NULL);
	$local_NomeResposta					= formatText($_POST['NomeResposta'],NULL);
	$local_EmailResposta				= formatText($_POST['EmailResposta'],NULL);
	$local_Usuario						= formatText($_POST['Usuario'],NULL);
	$local_Senha						= formatText($_POST['Senha'],NULL);
	$local_ServidorSMTP					= formatText($_POST['ServidorSMTP'],NULL);
	$local_Porta						= $_POST['Porta'];
	$local_RequerAutenticacao			= $_POST['RequerAutenticacao'];
	$local_SMTPSeguro					= $_POST['SMTPSeguro'];
	$local_IntervaloEnvio				= $_POST['IntervaloEnvio'];
	$local_QtdTentativaEnvio			= $_POST['QtdTentativaEnvio'];
	$local_LoginCriacao					= $_POST['LoginCriacao'];
	$local_DataCriacao					= $_POST['DataCriacao'];
	$local_LoginAlteracao				= $_POST['LoginAlteracao'];
	$local_DataAlteracao				= $_POST['DataAlteracao'];
	$local_LimiteDiario					= $_POST['LimiteEnvioDiario'];
	
	if($local_IdContaEmail == '') {
		$local_IdContaEmail = $_GET['IdContaEmail'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_conta_email.php');
			break;
		case 'alterar':
			include('files/editar/editar_conta_email.php');
			break;
		case 'testar':
			header("Location: cadastro_enviar_mensagem.php?IdContaEmail=$local_IdContaEmail");
			break;
		default:
			$local_Acao = 'inserir';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../classes/calendar/calendar.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-setup.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-br.js'></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/conta_email.js'></script>
		<script type='text/javascript' src='js/conta_email_default.js'></script>
		
		<style type='text/css'>
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
			#conteudo table tr td { vertical-align:top; }
		</style>
	</head>
	<body onLoad="ativaNome('Contas de E-mail')">
		<? 
			include('filtro_conta_email.php'); 
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_conta_email.php' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='ContaEmail' />
					<input type='hidden' name='IntervaloEnvioDefault' value='<?=getCodigoInterno(3,160)?>' />
					<input type='hidden' name='QtdTentativaEnvioDefault' value='<?=getCodigoInterno(3,161)?>' />
					<input type='hidden' name='SelecionarCamposUmaOpcao' value='<?=getCodigoInterno(3,237)?>'>				
					<div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Conta E-mail</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdContaEmail' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_conta_email(this.value,true,document.formulario.Local.value,'<?=$local_Login?>');" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Dados da Conta de E-mail</div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B>Descrição</B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescricaoContaEmail' value='' style='width:816px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Nome do Remetente</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b id='titEmailRemetente'>E-mail do Remetente</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeRemetente' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='EmailRemetente' value='' style='width:400px' maxlength='100' onChange="validar_email(this,'titEmailRemetente')" onFocus="Foco(this,'in')" onKeypress="return mascara(this,event,'filtroCaractereEmail','','')" onBlur="Foco(this,'out')" tabindex='4' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Nome de Resposta</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b id='titEmailResposta'>E-mail de Resposta</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='NomeResposta' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='5' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='EmailResposta' value='' style='width:400px' maxlength='100' onChange="validar_email(this,'titEmailResposta')" onFocus="Foco(this,'in')" onKeypress="return mascara(this,event,'filtroCaractereEmail','','')" onBlur="Foco(this,'out')" tabindex='6' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b id='titUsuario'>Nome da Conta</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b>Senha</b></td>	
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Limite Diário</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Usuario' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='7' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Senha' value='' style='width:300px' maxlength='32'  onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LimiteEnvioDiario' value='' style='width:84px' maxlength='32' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b>Servidor de Saída (SMTP)</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b>Porta</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b>Requer Autenticação</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Autenticação Segura</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='ServidorSMTP' value='' style='width:399px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='9' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='Porta' value='' style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='10' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='RequerAutenticacao' style='width:154px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='11'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=215 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='SMTPSeguro' style='width:154px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='12'>
										<option value='' selected></option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=216 order by ValorParametroSistema";
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
								<td class='descCampo'><b>Intervalo de Envio</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b>N° de Tentativas de Envio</b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IntervaloEnvio' value='' style='width:191px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='13' />
									<div>Intervalo em segundos</div>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='QtdTentativaEnvio' value='' style='width:191px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='14' />
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'>Log</div>
						<table id='cpHistorico' style='display:none'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(1033)?> </td>				
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='HistoricoObs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" readOnly></textarea>
								</td>
							</tr>
						</table>
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
								<td class='separador'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao' value='' style='width:181px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao' value='' style='width:202px' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly />
								</td>							
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='button' style='width:90px;' name='bt_testar' value='Testar Conta' class='botao' tabindex='15' onClick="cadastrar('testar')" />									
								</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' style='width:74px;' name='bt_inserir' value='Cadastrar' class='botao' tabindex='16' onClick="cadastrar('inserir')" />
									<input type='button' style='width:57px;' name='bt_alterar' value='Alterar' class='botao' tabindex='17' onClick="cadastrar('alterar')" />
									<input type='button' style='width:57px;' name='bt_excluir' value='Excluir' class='botao' tabindex='18' onClick="excluir(document.formulario.IdContaEmail.value)" />
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
		<script type='text/javascript'>
		<!-- 
		<?
			if($local_IdContaEmail != '') {
				echo "busca_conta_email($local_IdContaEmail,false,document.formulario.Local.value,'$local_Login');";
				echo "scrollWindow('bottom');";	
			}
		?>
			function inicia(){
				if(document.formulario.Acao.value == "inserir"){
					document.formulario.IntervaloEnvio.value = document.formulario.IntervaloEnvioDefault.value;
					document.formulario.QtdTentativaEnvio.value = document.formulario.QtdTentativaEnvioDefault.value;
				}		
				document.formulario.IdContaEmail.focus();
			}
			if(document.formulario.IdContaEmail.value == "" &&document.formulario.SMTPSeguro[2] == undefined && document.formulario.SelecionarCamposUmaOpcao.value == 1){
				document.formulario.SMTPSeguro[1].selected	= true;
			}
			verificaAcao();
			verificaErro();
			inicia();
			enterAsTab(document.forms.formulario);
			-->
		</script>
	</body>
</html>