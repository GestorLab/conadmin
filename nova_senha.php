<?
	include ('files/conecta.php');
	include ('files/funcoes.php');
	
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET["Erro"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/val_email.js'></script>
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/index.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
		<link REL="SHORTCUT ICON" HREF="img/estrutura_sistema/favicon.ico">
	</head>
	<body>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<form action='rotinas/autentica_redefinir_senha.php' method='post' name='formulario' onSubmit='return validar()'>
				<input type='hidden' name='Local' value='index'>
				<input type='hidden' name='Acao' value='login'>
				<input type='hidden' name='Erro' value='$local_Erro'>
				<table>
					<tr>
						<td class='descCampo'><B>Usuário</B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' value='' style='width: 169px' maxlength='20' tabindex='1' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out')\">
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class='descCampo'><B>E-mail<B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Email' value='' style='width: 346px' autocomplete='off' maxlength='100' tabindex='2' onFocus=\"Foco(this,'in')\" onBlur=\"Foco(this,'out');\">
						</td>	
					</tr>
					<tr>
						<td class='campo' style='text-align:right'>
							<input type='button' value='Voltar' class='botao' tabindex='3' onClick='voltar()'>
							<input type='submit' value='Enviar' class='botao' tabindex='4'>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td><h1 id='helpText' style='font-size:10px' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script>
	function validar(){
		if(document.formulario.Login.value==''){
			document.formulario.Login.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		if(document.formulario.Email.value==''){
			document.formulario.Email.focus();
			mensagens(1,document.formulario.Local.value);
			return false;
		}
		if(isEmail(document.formulario.Email.value)==false){
			document.formulario.Email.focus();
			mensagens(12,document.formulario.Local.value);
			return false;
		}
		return true;	
	}
	
	function voltar(){
		window.location.href = "alterar_senha.php";
	}
	
	function inicia(){
		document.formulario.Login.focus();
	}
	
	verificaErro();
	inicia();	
	enterAsTab(document.forms.formulario);
</script>