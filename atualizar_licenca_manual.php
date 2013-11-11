<?
	include ('files/conecta.php');
	include ('files/funcoes.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<script type = 'text/javascript' src = 'js/funcoes.js'></script>
		<script type = 'text/javascript' src = 'js/atualizar_licenca.js'></script>
		<script type = 'text/javascript' src = 'js/mensagens.js'></script>
		<script type = 'text/javascript' src = 'js/event.js'></script>
	</head>
	<body>
		<div id='carregando'>carregando...</div>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<h1>Atualização de Licença</h1>
			<form action='atualizar_licenca_confirma.php' method='post' name='formulario' onSubmit='return validar()'>
				<input type='hidden' name='Local' value='atualizar_licenca'>
				<input type='hidden' name='Acao' value='atualizar_licenca'>	
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>	
				<table>
					<tr>
						<td class='descCampo'><B>Usuário</B></td>
						<td class='descCampo'><B>Senha</B></td>
					</tr>
					<tr>
						<td class='campo'>
							<input type='text' name='Login' value='' style='width: 169px' maxlength='20' tabindex=2  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
						<td class='campo'>
							<input type='password' name='Senha' value='' style='width: 169px' maxlength='8' tabindex=3  onFocus="Foco(this,'in');"  onBlur="Foco(this,'out')">
						</td>
					</tr>
					<tr>
						<td class='descInfo'><a href='http://www.cntsistemas.com.br' target='_blank'>Informações</a></td>
						<td style='text-align:right'><input type='submit' value='Avançar' class='botao' tabindex=4></td>
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
	enterAsTab(document.forms.formulario);
</script>
