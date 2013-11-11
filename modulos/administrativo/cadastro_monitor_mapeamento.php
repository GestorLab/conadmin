<?
	$localModulo		= 1;
	$localOperacao		= 196;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	
	$localIdMonitor	= $_GET["IdMonitor"];
	
	$sql = "SELECT 
				Pais.NomePais
			FROM 
				Loja, 
				Pessoa, 
				PessoaEndereco, 
				Pais
			WHERE
				Loja.IdLoja = 1 AND Loja.IdPessoa = Pessoa.IdPessoa AND 
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa AND 
				Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco AND 
				PessoaEndereco.IdPais = Pais.IdPais";
	$res = mysql_query($sql, $con);
	$lin = mysql_fetch_array($res);
	
	$focoMap = "{pais: '{$lin["NomePais"]}'}";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/impress.css' media='print' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/monitor_mapeamento.js'></script>
		
		<script type="text/javascript">
			<?php echo "initialize_map('map_canvas', get_lat_lng, {$focoMap});"; ?>
		</script>
		
		<style type="text/css">
			html, body { 
				height: 100%; 
				margin: 0; 
				padding: 0; 
			}
			.impress { 
				padding: 0 2px 2px 2px; 
				cursor: pointer; 
				border: 1px #A4A4A4 solid; 
				height: 20px;
			}
			@media print { 
				html, body { 
					height: auto; 
				}
			}
		</style>
	</head>
	<body onLoad="ativaNome('Monitor Mapeamento');">
		<?php include("filtro_monitor.php"); ?>
		<br />
		<div class='graficos' id="map_canvas" style='text-align:center; top:14px;'></div>
		<br /><br />
		<div style='text-align:center;'><input class='impress' type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick="javascript:parent.frames['conteudo'].print();"></div>
		<br />
	</body>
</html>