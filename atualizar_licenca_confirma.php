<?
	include ('files/conecta.php');
	include ('files/funcoes.php');

	$local_Acao			= $_POST['Acao'];
	$local_KeyCode		= $_POST['KeyCode'];
	$local_KeyLicenca	= $_POST['KeyLicenca'];

	switch($local_Acao){
		case 'atualizar_licenca':
			$formDados[Login]	= trim(strtolower($_POST['Login']));
			$formDados[Senha]	= $_POST['Senha'];

			if(validaAutenticacaoLogin($formDados[Login], $formDados[Senha]) != false){
				$Vars		= Vars();
				$KeyCode	= KeyCode($Vars[IdLicenca],1);
			}else{
				header("Location: atualizar_licenca_manual.php");
			}
			break;
		case 'atualizar_licenca_confirma':
			KeyProcess($local_KeyCode, $local_KeyLicenca);
			header("Location: index.php");
			break;
	}
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
			<form action='atualizar_licenca_confirma.php' method='post' name='formulario' onSubmit='return validarConfirma()'>
				<input type='hidden' name='Local' value='atualizar_licenca_confirma'>
				<input type='hidden' name='Acao' value='atualizar_licenca_confirma'>	
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>	
				<table>
					<tr>
						<td class='descCampo' colspan=2><B>Código de Geração</B></td>
					</tr>
					<tr>
						<td class='campo' colspan=2>
							<input type='text' name='KeyCode' value='<?=$KeyCode?>' style='width: 346px' maxlength='34' tabindex=4  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" readonly>
						</td>
					</tr>
					<tr>
						<td class='descCampo' colspan=2><B>Código de Ativação</B></td>
					</tr>
					<tr>
						<td class='campo' colspan=2>
							<input type='text' name='KeyLicenca' value='' style='width: 346px' maxlength='35' tabindex=5  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
						</td>
					</tr>
					<tr>
						<td class='descInfo'><a href='http://www.cntsistemas.com.br' target='_blank'>Informações</a></td>
						<td style='text-align:right'><input type='submit' value='Confirma' class='botao' tabindex=6></td>
					</tr>
				</table>
				<table>
					<tr>
						<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
				<table>
					<tr>
						<td><B>Licença:</B> <?=$Vars[IdLicenca]?></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script>
	verificaErro();
	iniciaConfirma();
	enterAsTab(document.forms.formulario);
</script>
