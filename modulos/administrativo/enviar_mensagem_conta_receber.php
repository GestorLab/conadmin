<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdContaReceber	= $_GET['IdContaReceber'];
	
	if($local_IdContaReceber != ''){
		$sql = "select
					ContaReceber.IdStatus
				from
					ContaReceber
				where
					ContaReceber.IdLoja = $local_IdLoja and
					ContaReceber.IdContaReceber = $local_IdContaReceber;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		if($lin[IdStatus] != 2){
			header("Location: cadastro_enviar_mensagem.php?IdContaReceber=$local_IdContaReceber");
		}
	} else{
		header("Location: cadastro_conta_receber.php");
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
	<body onLoad="ativaNome('Enviar Mensagem')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<p id='p1'>Conta a Receber Quitado. <br />Deseja enviar o Boleto mesmo assim?</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_sim' value='Sim' style='cursor:pointer; width: 44px; height: 25px' onClick="confirmar('cadastro_enviar_mensagem.php?IdContaReceber=<?=$local_IdContaReceber?>')">
					<input type='button' name='bt_nao' value='Não' style='cursor:pointer; width: 44px; height: 25px' onClick="confirmar('cadastro_conta_receber.php?IdContaReceber=<?=$local_IdContaReceber?>')">
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
