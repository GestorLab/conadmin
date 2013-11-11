<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	$local_Acao					= $_GET['Acao'];
	$local_ReEnviarEmail		= $_GET['ReEnviarEmail'];

	switch($local_Acao){
		case 'enviar':			
			header("Location: processo_financeiro_enviar_boletos.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro");
			$local_Acao = 'enviar'; 
			break;
		default:
			$local_Acao = 'enviar'; 
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
			<p id='p1'>Atenção! Você está prestes a iniciar o envio de e-mail dos títulos deste processo financeiro <br /> para seus respectivos e-mails configurados.</p>
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
			local	=	"processo_financeiro_confirmar_enviar_boletos.php?IdProcessoFinanceiro="+document.formulario.IdProcessoFinanceiro.value+"&Acao=enviar";			
		}
		window.location.replace(local);
	}
</script>
