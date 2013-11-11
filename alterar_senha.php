<?
	include ('files/conecta.php');
	include ('files/funcoes.php');
	
	$local_Erro	=	$_GET['Erro'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/alterar_senha.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<form action='rotinas/alterar_senha.php' method='post' name='formulario' onSubmit='return validar()'>
				<input type='hidden' name='Local' value='index'>
				<input type='hidden' name='Acao' value='login'>	
				<input type='hidden' name='Browser' value=''>	
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='NivelSenha' />
				<table>
					<tr>
						<td class='descCampo'><B><?=dicionario(2)?></B></td>
						<td class='descCampo'><B><?=dicionario(10)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' value='' style='width: 169px' maxlength='20' tabindex=2 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="cadastrar(event)">
						</td>
						<td class='campo'>
							<input type='password' name='SenhaAtual' value='' style='width: 169px' tabindex=3 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="cadastrar(event)">
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'><B><?=dicionario(11)?></B></td>
						<td class='descCampo'><B><?=dicionario(12)?></B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='password' name='NovaSenha' value='' style='width: 169px'  tabindex='4' onFocus="Foco(this,'in')" onkeyup="verificaSenha('UsuarioAlterarSenha');"  onBlur="Foco(this,'out');cancelaVerificaSenha();" onKeyDown="cadastrar(event)">
						</td>
						<td class='campo'>
							<input type='password' name='ConfirmaSenha' value='' style='width: 169px'  tabindex='5'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="cadastrar(event)">
						</td>
					</tr>
					<tr>
						<td class='descInfo'><a href='nova_senha.php'><?=dicionario(13)?></a>&nbsp;&nbsp;<a href='nova_senha.php'><img src='img/estrutura_sistema/ico_chave.gif' alt='Esqueceu sua senha?'></a></td>
						<td style='text-align:right'>
							<input type='button' value='<?=dicionario(14)?>' class='botao' tabindex=6 onClick='voltar()'>
							<input type='submit' value='<?=dicionario(15)?>' class='botao' tabindex=7>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<table>								
								<tr>
									<td>
										<div id="statusSenha" style="margin-top:10px; display:none;">
											<div id="estadoSenha"></div>
											<div style="width:176px; height: 8px; background-color: #DDD; margin-top:1px;">
												<div id="nivel" style="height: 8px;"></div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script>
	verificaErro();
	inicia();
</script>