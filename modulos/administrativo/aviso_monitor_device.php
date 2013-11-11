<?php
$localModulo		=	1;
$localOperacao		=	205;
$localSuboperacao	=	"V";

include ('../../files/conecta.php');
include ('../../files/funcoes.php');
include ('../../rotinas/verifica.php');

$local_Login		= $_SESSION["Login"];
$local_IdLoja		= $_SESSION["IdLoja"];
$local_Acao 		= $_POST['Acao'];
$local_Erro			= $_GET['Erro'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type="text/javascript" src="js/device_monitor.js"></script>
	</head>
	<style type="text/css">
		.escondeElemento {
			display: none;
		}
	</style>
<body onload="ativaNome('Monitor')">
	<?php include("filtro_device.php");?>
	<div id="conteudo">
		<div class="escondeElemento" id="sem_permissao">
			<p id="p1">Não há monitores disponíveis para este perfil.<br />Dúvidas entre em contato com nosso suporte.</p>
			<p id="p2"></p>
		</div>
		<table class="escondeElemento" style="width: 100%; margin-top: 30px; text-align: center">
		<tr>
			<td class="campo">
				<input type="button" name="bt_voltar" value="Voltar" style='cursor:pointer; width: 55px; height: 25px' />
				</td>
			</tr>
		</table>
		<div id="deviceMonitor"></div>
		<div id="monitor"></div>
		<table>
			<tr>
				<td class='find'>&nbsp;</td>
				<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
			</tr>
		</table>
	</div>
</body>
</html>