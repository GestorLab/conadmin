<?
	$localModulo	=	1;
	$localOperacao	=   3;

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	global $con;
	global $_GET;
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION['IdLoja'];
	
	$local_IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == false){
		$local_Erro = 2;
		header("Location: cadastro_processo_financeiro.php?IdProcessoFinanceiro=$IdProcessoFinanceiro&Erro=$local_Erro");
	}else{
		
		$pathSistema 	= getParametroSistema(6,1);
		$pathPHP 		= getParametroSistema(6,4);			
		
		system("$pathPHP $pathSistema/modulos/administrativo/rotinas/enviar_mensagem_processo_financeiro.php $local_Login $local_IdLoja $local_IdProcessoFinanceiro > $pathSistema/modulos/administrativo/rotinas/enviar_mensagem_processo_financeiro.log &");

		$sql	=	"UPDATE ProcessoFinanceiro SET EmailEnviado = 'S' WHERE IdLoja = '$local_IdLoja' and IdProcessoFinanceiro = '$local_IdProcessoFinanceiro'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 64;
		}else{
			$local_Erro = 55;
		}		
		header("Location: cadastro_processo_financeiro.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro&Erro=$local_Erro");
	}	
?>
