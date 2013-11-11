<?
	set_time_limit(0);

	$localModulo		=	1;
	$localOperacao		=	27;//criar permissao
	$localSuboperacao	=	"E";// criar permissaoSubOperacao
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";
	
	include ('../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdContaSMS		= $_POST['IdContaSMS'];
	$local_Celular			= $_POST['sms'];
	$local_IdOperadora		= $_POST['IdOperadora'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){
		if($local_IdContaSMS != ''){
			$Erro = enviaTesteContaSMS($local_IdLoja, $local_IdContaSMS, $local_Celular, $local_IdOperadora);
			
			echo "cadastro_conta_sms.php?IdContaSMS=".$local_IdContaSMS."&Erro=".$Erro;
		}
	}	
?>