<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];

	$local_Email					= $_POST['Email'];
	
	switch ($local_Acao){
		case 'teste':
			include("rotinas/teste_email.php");			
			break;			
		default:
			$local_Acao 	= 'teste';
			break;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/teste_email.js'></script>

	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onLoad="ativaNome('Teste de Email')">
		<div id='carregando'>carregando</div>
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post' action='cadastro_teste_email.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>							
				<div id='cp_tit' style='margin-top:0'>Email</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><B>Para</B></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='Email' style='width:300px' value='' maxlength='255' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="validar_Email(this.value);" tabindex='1'>
						</td>
					</tr>
				</table>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_testar' value='Testar' class='botao' tabindex='2' onClick='cadastrar()'>
							</td>
						</tr>
					</table>				
				</div>
			</form>
		</div>
	</body>
</html>
<script>
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
