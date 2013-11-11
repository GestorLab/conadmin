<?
	$localModulo		=	1;
	$localOperacao		=	155;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdMalaDireta		= $_GET['IdMalaDireta'];
	$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
	$local_Acao				= $_GET['Acao'];

	switch($local_Acao){
		case 'enviar':			
			header("Location: rotinas/enviar_mensagem_mala_direta.php?IdMalaDireta=$local_IdMalaDireta&IdTipoMensagem=$local_IdTipoMensagem");
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
		<? include('filtro_mala_direta.php'); ?>
		<form name='formulario' method='post'>
			<input type='hidden' name='IdMalaDireta' value='<?=$local_IdMalaDireta?>'>
			<input type='hidden' name='IdTipoMensagem' value='<?=$local_IdTipoMensagem?>'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
		</form>
		<div id='sem_permissao'>
			<p id='p1'>Atenção! Você está prestes a iniciar o envio de e-mail desta mala direta <br /> para seus respectivos e-mails configurados.</p>
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
			local	=	"cadastro_mala_direta.php?IdMalaDireta="+document.formulario.IdMalaDireta.value;
		}else{
			local	=	"mala_direta_confirmar_enviar_mensagem.php?IdMalaDireta="+document.formulario.IdMalaDireta.value+"&IdTipoMensagem="+document.formulario.IdTipoMensagem.value+"&Acao=enviar";			
		}
		window.location.replace(local);
	}
</script>