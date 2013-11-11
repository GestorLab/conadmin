<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
	</head>
	<body  onLoad="ativaNome('Executar rotinas diárias')">
		<div id='carregando'>carregando</div>
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Após executar esta rotina, você será desconectado do sistema ConAdmin<br>e direcionado para a tela de autenticação.<br>Este processo pode demorar alguns minutos.</p>
			<p id='p2'>Para continuar, clique em avançar.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer' onClick="confirmar('voltar')">
					<input type='button' name='bt_confirmar' value='Avançar' style='cursor:pointer' onClick="confirmar('avancar')">
				</td>
			</tr>
		</table>
	</body>
</html>
<script>
	function confirmar(acao){
		if(acao=='voltar'){
			local	=	"cadastro_executar_rotina.php";
			window.location.replace(local);
		}else{
			// Carregando...
			carregando(true);

			local	=	"rotinas/executar_rotina_diaria.php";
			parent.location.replace(local);
		}
	}
</script>