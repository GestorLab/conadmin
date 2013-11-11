<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"C";	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
	}else{
		$local_IdContaReceber					= $_POST['IdContaReceber'];
		$local_IdContaReceberRecebimento		= $_POST['IdContaReceberRecebimento'];
		$local_ObsCancelamento					= formatText($_POST['ObsCancelamento'],NULL);
		$local_CreditoFuturo					= formatText($_POST['CreditoFuturo'],NULL);
		$local_IdLocalEstorno					= formatText($_POST['IdLocalEstorno'],NULL);
		$local_IdContratoEstorno				= $_POST['IdContratoEstorno'];
		$local_IdCancelarNotaFiscal				= $_POST['IdCancelarNotaFiscal'];
		$local_NumeroNF							= $_POST['NumeroNF'];
		$local_DataNF							= $_POST['DataNF'];
		$local_CancelarNotaFiscalRecebimento	= $_POST['CancelarNotaFiscalRecebimento'];
		$tr_i									= 0;
		$dados									= array(
			"IdLoja"						=> $local_IdLoja,
			"IdContaReceber"				=> $local_IdContaReceber,
			"IdContaReceberRecebimento"		=> $local_IdContaReceberRecebimento,
			"CancelarNotaFiscalRecebimento"	=> $local_CancelarNotaFiscalRecebimento,
			"IdCancelarNotaFiscal"			=> $local_IdCancelarNotaFiscal,
			"NumeroNF"						=> $local_NumeroNF,
			"Login"							=> $local_Login,
			"IdLocalEstorno"				=> $local_IdLocalEstorno,
			"IdContratoEstorno"				=> $local_IdContratoEstorno,
			"CreditoFuturo"					=> $local_CreditoFuturo,
			"ObsCancelamento"				=> $local_ObsCancelamento
		);
		
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		if(conta_receber_cancelar_recebimento($dados) == true){
			$sql = "COMMIT;";
			$local_Erro = 67;			// Mensagem de Alteraчуo Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 68;			// Mensagem de Alteraчуo Negativa
		}
		
		mysql_query($sql,$con);
		header("Location: ../../cadastro_conta_receber.php?IdContaReceber=$local_IdContaReceber&Erro=$local_Erro");
	}
?>