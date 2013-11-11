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
	</head>
	<body>
		<div id='carregando'><?=dicionario(17)?>...</div>
		<?
			include("files/cabecalho.php");
		?>
		<div id='quadro'>
			<div style='text-align:center; font-weight: bold; font-size: 12px;'><?=dicionario(18)?></div>
			<br />
			<table style='width: 100%;'>
				<tr>
					<td style='text-align:center'><input type='button' value='<?=dicionario(19)?>' class='botao' tabindex=4 style='width: 150px' onClick="window.location = 'rotinas/licenca.php'"></td>
					<td style='text-align:center'><input type='button' value='<?=dicionario(20)?>' class='botao' tabindex=4  style='width: 150px' onClick="window.location = 'atualizar_licenca_manual.php'"></td>
				</tr>				
			</table>
			<br />
			<div style='height: 27px;'><b><?=dicionario(21)?>:</b> <?=date("d/m/Y H:i:s")?>.</div>
		</div>
	</body>
</html>