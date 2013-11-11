<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"P";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$local_Acao					= $_GET['Acao'];

	switch($local_Acao){
		case 'confirmar':
			// Executa a confirmação do processo financeiro em background
			system("cd ".getParametroSistema(6,1)."modulos/administrativo/rotinas && ".getParametroSistema(6,4)." confirmar_processo_financeiro_background.php $local_Login $local_IdLoja $local_IdProcessoFinanceiro > confirmar_processo_financeiro_background.log &");
			header("Location: cadastro_processo_financeiro.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");
			break;
		default:
			$local_Acao = 'confirmar'; 
			break;
	}

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
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
		</form>
		<div id='sem_permissao'>
			<p id='p1'>Devido a quantidade de lançamentos financeiros ser superior a <?=getCodigoInterno(17,1)*30?> registros,<br>o mesmo será executado via terminal.<br>Após seu término, será enviado um e-mail para <?=getCodigoInterno(38,2)?>, informando a conclusão da confirmação do processo financeiro.</p>
			<p id='p2'>Deseja continuar?</p>		
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
			local	=	"processo_financeiro_confirmar.php?IdProcessoFinanceiro="+document.formulario.IdProcessoFinanceiro.value+"&Acao=confirmar";			
		}
		window.location.replace(local);
	}
</script>
