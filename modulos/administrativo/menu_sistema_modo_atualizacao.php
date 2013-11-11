<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	if(is_dir("../../atualizacao")){
		$msg		= "Você esta preste a tirar o sistema do modo de atualização.";
		$atualizar	= 0;
	} else{
		$msg		= "Você esta preste a colocar o sistema em modo de atualização.";
		$atualizar	= 1;
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Sistema em Modo de Atualização')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'><?=$msg?></p>
			<p id='p2'>Para continuar, clique em confirmar.</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer; width: 55px; height: 25px' onClick="confirmar('listar_operacao_avancada.php')">
					<input type='button' name='bt_confirmar' value='Confirmar' style='cursor:pointer; width: 80px; height: 25px' onClick="confirmar('sistema_modo_atualizacao.php?Atualizar=<?=$atualizar?>')">
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
