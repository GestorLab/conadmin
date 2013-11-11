<?
	set_time_limit(0);

	$localModulo		=	1;
	$localOperacao		=	155;
	$localSuboperacao	=	"E";
		
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$Path = "../../../";
	
	include('../../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdMalaDireta		= $_GET['IdMalaDireta'];
	$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"E") == true){
		
		if($local_IdMalaDireta != ''){			
			
			$sql = "select						
						LogEnvio
					from
						MalaDireta
					where
						IdLoja = $local_IdLoja and
						IdMalaDireta = $local_IdMalaDireta";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);

			$LogEnvio = date("d/m/Y H:i:s")." [$local_Login] - Iniciando envio de e-mails para os clientes.\n".$lin[LogEnvio];

			$sql = "update MalaDireta set
						LogEnvio 	= '$LogEnvio',
						LoginEnvio	= '$local_Login', 
						DataEnvio	= (concat(curdate(),' ',curtime()))
					where
						IdLoja = $local_IdLoja and
						IdMalaDireta = $local_IdMalaDireta";
			@mysql_query($sql,$con);			

			$sql = "select								
						Email,
						IdPessoa
					from
						MalaDiretaEmail
					where
						IdLoja = $local_IdLoja and
						IdMalaDireta = $local_IdMalaDireta";
			$res = @mysql_query($sql,$con);
			while($lin = @mysql_fetch_array($res)){
				enviarEmailMalaDireta($local_IdLoja, $local_IdMalaDireta, $local_IdTipoMensagem, $lin[Email], $lin[IdPessoa]);			
			}
			
			header("Location: ../cadastro_mala_direta.php?IdMalaDireta=$local_IdMalaDireta");
		}else{
			header("Location: ../cadastro_mala_direta.php?IdMalaDireta=$local_IdMalaDireta");
		}		
	}	
?>
