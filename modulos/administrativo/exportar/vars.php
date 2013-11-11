<?
	$localModulo		=	1;
	$localOperacao		=	107;
	$localSuboperacao	=	"V";	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	 
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];

	$local_Filtro_IdContaReceber	= $_GET['Filtro_IdContaReceber'];
	$local_IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
	$local_Filtro_IdPessoa 			= $_GET['Filtro_IdPessoa'];
	$local_IdLocalCobranca 			= $_GET['IdLocalCobranca'];
	$local_IdServico	 			= $_GET['Filtro_IdServico'];
	$local_FormatoSaida 			= $_GET['FormatoSaida'];

	$IdLoja					= $local_IdLoja;
	$IdContaReceber			= $local_Filtro_IdContaReceber;
	$IdProcessoFinanceiro	= $local_IdProcessoFinanceiro;
	$IdPessoa				= $local_Filtro_IdPessoa;
	$IdLocalCobranca		= $local_IdLocalCobranca;
	$IdServico				= $local_IdServico;
?>
