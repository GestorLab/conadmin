<?
	set_time_limit(0);

	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"E";
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";
	
	include ('../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdContaEventual	= $_POST['IdContaEventual'];
	$local_Email			= $_POST['Email'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){

		if($local_IdContaEventual != ''){
			$RetornoMensagemContaEventual =  enviaContaEventual($local_IdLoja, $local_IdContaEventual);

			if($RetornoMensagemContaEventual != false){					
				enviaEmail($local_IdLoja, $RetornoMensagemContaEventual);

				header("Location: ../listar_reenvio_mensagem.php?IdHistoricoMensagem=".$RetornoMensagemContaEventual."&Erro=64");
			}
		}else{
			header("Location: ../cadastro_conta_eventual.php?IdContaEnventual=$local_IdContaEventual");
		}		
	}	
?>
