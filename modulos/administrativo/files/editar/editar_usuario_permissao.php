<?
	$localModulo		=	1;
	$localOperacao		=	8;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_IdLoja_Sistema	= $_SESSION["IdLoja"];
	$local_Login			= $_GET['Login'];
	$local_IdLoja			= $_GET["IdLoja"];
	$local_IdModulo			= $_GET['IdModulo'];
	$local_IdOperacao		= $_GET['IdOperacao'];
	$local_IdSubOperacao	= $_GET['IdSubOperacao'];
	$local_Acao				= $_GET['Acao'];	
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
		
		$IdSubOperacao = subOperacao_permissao_login($local_Login_Sistema, $local_IdLoja_Sistema, $local_IdModulo, $local_IdOperacao);
		$IdSubOperacao = explode(",",$IdSubOperacao);
		
		if(in_array($local_IdSubOperacao,$IdSubOperacao)){
			switch($local_Acao){
				case "add":
					$sql = "START TRANSACTION;";
					mysql_query($sql,$con);
					$tr_i = 0;
					
					$sql = "UPDATE
								LogAcesso
							SET
								LogAcesso.Fechada = '1'
							WHERE 
								LogAcesso.Login = '$local_Login' AND 
								LogAcesso.IdLoja = '$local_IdLoja' AND 
								LogAcesso.Fechada = '2';";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					$sql = "INSERT INTO 
								UsuarioSubOperacao 
									(Login, IdLoja, IdModulo, IdOperacao, IdSubOperacao, DataCriacao, LoginCriacao)
								VALUES 
									('$local_Login', $local_IdLoja, $local_IdModulo, $local_IdOperacao, '$local_IdSubOperacao', concat(curdate(),' ', curtime()), '$local_Login_Sistema');";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					for($i=0; $i<$tr_i; $i++){
						if($local_transaction[$i] == false){
							$local_transaction = false;
						}
					}
					
					if($local_transaction == true){
						$sql = "COMMIT;";
						$local_Erro = 36;
					}else{
						$sql = "ROLLBACK;";
						$local_Erro = 35;
					}
					
					mysql_query($sql,$con);
					break;
					
				case "rem":
					$sql = "START TRANSACTION;";
					mysql_query($sql,$con);
					$tr_i = 0;
					
					$sql = "UPDATE
								LogAcesso
							SET
								LogAcesso.Fechada = '1'
							WHERE 
								LogAcesso.Login = '$local_Login' AND 
								LogAcesso.IdLoja = '$local_IdLoja' AND 
								LogAcesso.Fechada = '2';";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					$sql = "DELETE FROM 
								UsuarioSubOperacao 
							WHERE 
								Login='$local_Login' AND 
								IdLoja=$local_IdLoja AND 
								IdModulo=$local_IdModulo AND 
								IdOperacao=$local_IdOperacao AND 
								IdSubOperacao='$local_IdSubOperacao';";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					$tr_i++;
					
					for($i=0; $i<$tr_i; $i++){
						if($local_transaction[$i] == false){
							$local_transaction = false;
						}
					}
					
					if($local_transaction == true){
						$sql = "COMMIT;";
						$local_Erro = 37;
					}else{
						$sql = "ROLLBACK;";
						$local_Erro = 38;
					}
					
					mysql_query($sql,$con);		
					break;
			}
		}else{
			echo $local_Erro = 2;
		}
	}
?>