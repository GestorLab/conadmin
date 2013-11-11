<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_url						= "";
	$local_IdHistoricoMensagem		= $_GET['IdHistoricoMensagem'];
	$local_IdContaEventual			= $_GET['IdContaEventual'];	
	$local_IdContaReceber			= $_GET['IdContaReceber'];
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_IdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];
	
	//Verificação de Tipo Email: Mensagem Enviada.
	if($local_IdHistoricoMensagem != ""){
		$local_url	= "location='cadastro_reenvio_mensagem.php?IdHistoricoMensagem=$local_IdHistoricoMensagem'";
	}
	
	//Verificação de Tipo Email: Conta Receber.
	if($local_IdContaEventual != ""){
		$local_url	= "location='cadastro_conta_eventual.php?IdContaEventual=$local_IdContaEventual'";
	}
	
	//Verificação de Tipo Email: Conta Enventual.
	if($local_IdContaReceber != ""){
		$local_url	= "location='cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber'";
	}
	
	//Verificação de Tipo Email: Ordem de Serviço.
	if($local_IdOrdemServico != ""){
		$local_url	= "location='cadastro_ordem_servico.php?IdOrdemServico=$local_IdOrdemServico'";
	}
	
	//Verificação de Tipo Email: Nota Fiscal Layout.
	if($local_IdNotaFiscalLayout != ""){
		$local_url	= "javascript:history.back()";
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
		<div id='sem_permissao'>
			<p id='p1' style='margin-left:15px'>Tipo de mensagem desativado. </p>
			<p id='p2' style='margin-left:15px'>Por favor, entre em contato com o suporte para mais instruções.</p>
			</br>
			<input type='button' value='Voltar' style="cursor:pointer" onClick="<?=$local_url?>" />
		</div>	
	</body>
</html>