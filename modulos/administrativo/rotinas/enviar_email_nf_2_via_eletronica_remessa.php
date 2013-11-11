<?
	$localModulo		=	1;
	$localOperacao		=	110;
	$localSuboperacao	=	"E";
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";
		
	include ('../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_IdNotaFiscalLayout		= $_POST['IdNotaFiscalLayout'];
	$local_MesReferencia			= $_POST['MesReferencia'];
	$local_IdStatusArquivoMestre	= $_POST['IdStatusArquivoMestre'];
	$local_Email					= $_POST['Email'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){

		if($local_IdNotaFiscalLayout != '' && $local_MesReferencia != '' && $local_IdStatusArquivoMestre != ''){
			$RetornoEmailNF2ViaRemessa = enviaNF2ViaRemessa($local_IdLoja, $local_IdNotaFiscalLayout, $local_MesReferencia, $local_IdStatusArquivoMestre, $local_Email);

			if($RetornoEmailNF2ViaRemessa != false){			
				enviaMensagem($local_IdLoja, $RetornoEmailNF2ViaRemessa);			
				header("Location: ../listar_reenvio_mensagem.php?IdHistoricoMensagem=".$RetornoEmailNF2ViaRemessa."&Erro=64");
			}
		}
	}	
?>
