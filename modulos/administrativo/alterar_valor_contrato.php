<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdServico	=	$_GET['IdServico'];
	$local_DataInicio	=	$_GET['DataInicio'];
	$local_ValorAntigo	=	$_GET['ValorAntigo'];
	$local_ValorNovo	=	$_GET['ValorNovo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Serviço Valor',false)">
		<? include('filtro_servico.php'); ?>
		<form name='formulario' method='post'>
			<input type='hidden' name='IdServico' value='<?=$local_IdServico?>'>
			<input type='hidden' name='DataInicio' value='<?=$local_DataInicio?>'>
			<input type='hidden' name='ValorAntigo' value='<?=$local_ValorAntigo?>'>
			<input type='hidden' name='ValorNovo' value='<?=$local_ValorNovo?>'>
		</form>
		<div id='sem_permissao'>
			<p id='p1'>Você acabou de alterar o valor do serviço.</p>
			<p id='p2'>Deseja atualizar automaticamente o valor de todos os contratos?</p>
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
			local	=	"cadastro_servico_valor.php?IdServico="+document.formulario.IdServico.value+"&DataInicio="+document.formulario.DataInicio.value;
		} else{
			local	=	"rotinas/editar_valor_servico_contrato.php?IdServico="+document.formulario.IdServico.value+"&DataInicio="+document.formulario.DataInicio.value+"&ValorAntigo="+document.formulario.ValorAntigo.value+"&ValorNovo="+document.formulario.ValorNovo.value;
		}
		
		window.location.replace(local);
	}
</script>
