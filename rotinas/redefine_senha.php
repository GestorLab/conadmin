<?
	include("../files/conecta.php");
	include("../files/funcoes.php");

	$Path	=   "../";
	include("../classes/envia_mensagem/envia_mensagem.php");

	$MD5		  = $_GET['MD5'];
	$local_Envio  = $_GET['Envio'];

	if($local_Envio == "true"){
		$sql= "Select
				Login
			 From
				Usuario
			Where
				SolicitacaoAlteracaoSenha = '$MD5'";
		$res = mysql_query($sql,$con);
		echo mysql_error();
		if(mysql_num_rows($res) > 0){
			enviarSenha($MD5);
			header("Location: ../aviso_envio_senha_nova.php");			
		} else{
			header("Location: ../aviso_envio_senha_erro.php?Motivo=4");
		}
	}else{
		$sql =  "update Usuario set SolicitacaoAlteracaoSenha = '' where SolicitacaoAlteracaoSenha = '$MD5'";
		$res = mysql_query($sql,$con);	

		header("Location: ../aviso_envio_senha_cancelamento.php");
	}
?>