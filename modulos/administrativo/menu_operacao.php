<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	$array_operacao 	= array("58") ;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');

	$local_Login = $_SESSION["Login"];
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
	<body onLoad="ativaNome('Operações Especiais')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Você está prestes a acessar uma área restrita a pessoas<br/>que conheçam o sistema o ConAdmin a fundo.</p>
			<p id='p2'>Para continuar, clique em avançar.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer; width: 55px; height: 25px' onClick="confirmar('conteudo.php')">
					<input type='button' name='bt_confirmar' value='Avançar' style='cursor:pointer; width: 80px; height: 25px' onClick="confirmar('listar_operacao.php')">
				</td>
			</tr>
			<?
				if($local_Login == 'root'){
					echo "<tr>
							<td class='campo'><input type='button' name='bt_avancadas' value='Avançadas' style='cursor:pointer; width: 139px; height: 25px' onClick=\"confirmar('listar_operacao_avancada.php')\"></td></tr>";
				}
			?>
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
