<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	//array( Pessoa , Contrato, Lanc. Financ., Contas a Receber, Email Enviados, Conta Eventual)
	$array_operacao 	= array(  "1", "2", "18", "17", "31", "27") ;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
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
		<script type = 'text/javascript' src = 'js/conta_eventual.js'></script>
		<script type = 'text/javascript' src = 'js/conta_eventual_busca_pessoa_aproximada.js'></script>
	</head>
	<body onLoad="ativaNome('<?=dicionario(28)?>')">
		<? include('filtro_conta_eventual.php'); ?>
	</body>
</html>
<?
	$local_IdLoja						= 1;
	$local_IdPessoa						= 1;
	$local_DescricaoContaEventual		= 'Teste de Conta Eventual Automático';
	$local_FormaCobranca				= 2;
	$local_OcultarReferencia			= 2;
	$local_IdContratoAgrupador			= '';
	$local_IdLocalCobranca				= 10;
	$local_ArrayValor					= '25,00#25,00#25,00#25,00';
	$local_ArrayDespesa					= '1,50#1,50#1,50#1,50';
	$local_ValorDespesaLocalCobranca	= '3,00';
	$local_QtdParcela					= 4;
	$local_ArrayVencimento				= '01/05/2009#01/06/2009#01/07/2009#01/08/2009';
	$local_ObsContaEventual				= 'Automatico';
	$local_Login						= 'root';

//	echo conta_eventual($local_IdLoja,$local_IdPessoa,$local_DescricaoContaEventual,$local_FormaCobranca,$local_OcultarReferencia,$local_IdContratoAgrupador,$local_IdLocalCobranca,$local_ArrayValor,$local_ArrayDespesa,$local_ValorDespesaLocalCobranca,$local_QtdParcela,$local_ArrayVencimento,$local_ObsContaEventual,$local_Login);
?>
