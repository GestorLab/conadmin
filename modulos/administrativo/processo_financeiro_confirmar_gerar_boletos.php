<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdProcessoFinanceiro	=	$_GET['IdProcessoFinanceiro'];

	$sqlStatusBoleto = "select
				IdStatusBoleto
			from
				ProcessoFinanceiro
			where
				IdLoja = $local_IdLoja and
				IdProcessoFinanceiro = $local_IdProcessoFinanceiro";
	$resStatusBoleto = mysql_query($sqlStatusBoleto,$con);
	$linStatusBoleto = mysql_fetch_array($resStatusBoleto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
	</head>
	<body>
		<? include('filtro_processo_financeiro.php'); ?>
		<form name='formulario' method='post'>
			<input type='hidden' name='IdProcessoFinanceiro' value='<?=$local_IdProcessoFinanceiro?>'>
		</form>
		<div id='sem_permissao'>
			<?
				switch($linStatusBoleto[IdStatusBoleto]){
					case 0:
						// Processo em andamento
						echo "<p id='p1'>Devido a quantidade de lançamentos financeiros ser superior a ".getCodigoInterno(17,1)." registros,<br>o mesmo será executado em background.</p>";
						echo "<p id='p2'>Após seu término, será enviado um e-mail para (".getCodigoInterno(38,2)."), com o link<br>para download  do arquivo em PDF com os boletos.</p>";
						break;
					case 1:
						// Processo em andamento
						echo "<p id='p1'>Processo em ANDAMENTO. Deseja inicia-lo novamente?</p>";
						echo "<p id='p2'>Após seu término, será enviado um e-mail para (".getCodigoInterno(38,2)."), com o link<br>para download  do arquivo em PDF com os boletos.</p>";
						break;
					case 2:
						// Processo em andamento
						echo "<p id='p1'>Devido a quantidade de lançamentos financeiros ser superior a ".getCodigoInterno(17,1)." registros,<br>o mesmo será executado em background.</p>";
						echo "<p id='p2'>Após seu término, será enviado um e-mail para (".getCodigoInterno(38,2)."), com o link<br>para download  do arquivo em PDF com os boletos.</p>";
						break;
				}
			?>
			<p id='p2'>Atenção, este processo pode demorar!</p>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_confirmar' value='Sim' style='cursor:pointer' onClick="confirmar('avancar')">
					<input type='button' name='bt_voltar' value='Não' style='cursor:pointer' onClick="confirmar('voltar')">
				</td>
			</tr>
		</table>
	</body>
</html>
<script>
	function confirmar(acao){
		if(acao=='voltar'){
			local	=	"cadastro_processo_financeiro.php?IdProcessoFinanceiro="+document.formulario.IdProcessoFinanceiro.value;
		}else{
			local	=	"local_cobranca/gerar_boletos_background.php?IdProcessoFinanceiro="+document.formulario.IdProcessoFinanceiro.value;;
		}
		window.location.replace(local);
	}
</script>
