<?
	include("../files/conecta.php");
	include("../files/funcoes.php");
	
	$Path = "../";
	include("../classes/envia_mensagem/envia_mensagem.php");
	
	$local_Local	= $_POST['Local'];
	$local_Login	= $_POST['Login'];
	$local_Email	= $_POST['Email'];
	$local_Erro		= "../aviso_envio_senha_erro.php?Motivo=1";
	
	$sql = "SELECT 
				Pessoa.IdPessoa,
				Usuario.Login,
				Pessoa.Email
			FROM
				Pessoa,
				Usuario
			WHERE 
				Pessoa.IdPessoa = Usuario.IdPessoa and
				Usuario.Login = '$local_Login'";
	$res = mysql_query($sql, $con);
	if($lin = mysql_fetch_array($res)){
		$lin[Email] = str_replace(" ","",trim($lin[Email]));
		$lin[Email] = explode(';',$lin[Email]);
		
		if($lin[Email][0] != "" && $lin[Email][0] != NULL){
			if(in_array($local_Email,$lin[Email])){
				$local_IdPessoa = $lin[IdPessoa];
				$local_IP = $_SERVER[REMOTE_ADDR];
				$sql = "SELECT 
							(MAX(IdSolicitacao) + 1) IdSolicitacao 
						FROM 
							SolicitacaoSenha;";
				$res = mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if($lin[IdSolicitacao] != NULL){
					$local_IdSolicitacao = $lin[IdSolicitacao];
				} else{
					$local_IdSolicitacao = 1;
				}
				
				$sql = "INSERT INTO SolicitacaoSenha SET
							IdSolicitacao	= '$local_IdSolicitacao',
							Login			= '$local_Login',
							IP				= '$local_IP',
							IdPessoa		= '$local_IdPessoa',
							DataSolicitacao = (concat(curdate(),' ',curtime()));";
				if(mysql_query($sql, $con)){
					enviarRedefinicaoSenha($local_Login,$local_Email);
					$local_Erro = "../aviso_envio_senha.php";
				}
			} else{
				$local_Erro = "../aviso_envio_senha_erro.php?Motivo=3";
			}
		} else{
			$local_Erro = "../aviso_envio_senha_erro.php?Motivo=2";
		}
	}
	
	header("Location: $local_Erro");
?>