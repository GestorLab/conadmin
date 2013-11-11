<?
	$localModulo		=	1;
	$localOperacao		=	121;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	$array_operacao 	= array("121");
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');

	$local_Login = $_SESSION["Login"];
	
	$local_IdContaReceber	= $_POST['IdContaReceber'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Nota Fiscal Gerar em Massa')">
		<div id='sem_permissao'>
			<p id='p1'>Você está prestes a emitir nota fiscal para os contas à receber <br />relacionados na tela anterior.</p>
			<p id='p2'>Para continuar, clique em avançar.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<form name='formulario' method='post' action='rotinas/gerar_nota_fiscal_em_massa.php'>
						<input type='hidden' name='IdContaReceber' value='<?=$local_IdContaReceber?>'>						
						<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer; width: 55px; height: 25px' onClick="confirmar('cadastro_gerar_nota_fiscal_em_massa.php')">
						<input type='Submit' name='bt_confirmar' value='Avançar' style='cursor:pointer; width: 80px; height: 25px'>
					</form>
				</td>
			</tr>			
		</table>
	</body>
</html>
<script>
	function confirmar(local){
		if(local == '' || local == undefined){
			local = 'conteudo.php';
		}
		window.location.replace(local);
	}
</script>
