<?
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");

	$Path	=   "../../../";
	include("../../../classes/envia_mensagem/envia_mensagem.php");

	$MD5		  = $_GET['MD5'];
	$local_Envio  = $_GET['Envio'];

	if($local_Envio == "true"){	
		enviarSenhaCDA($MD5);
		header("Location: ../aviso_envio_senha_nova.php");			
	}else{
		$sql =  "update Pessoa set SolicitacaoAlteracaoSenhaCDA = '' where SolicitacaoAlteracaoSenhaCDA = '$MD5'";
		$res = mysql_query($sql,$con);	
		
		header("Location: ../aviso_envio_senha_cancelamento.php");
	}
?>