<?
	$localModulo		=	1;
	$localOperacao		=	10;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login_Sistema	= $_SESSION["Login"];	
	$local_IdLoja_Sistema	= $_SESSION['IdLoja'];
	$local_IdLoja			= $_GET['IdLoja'];
	$local_IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
	$local_IdModulo			= $_GET['IdModulo'];
	$local_IdOperacao		= $_GET['IdOperacao'];
	$local_IdSubOperacao	= $_GET['IdSubOperacao'];
	$local_Acao				= $_GET['Acao'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{		
		switch($local_Acao){
			case "add":	
				$sql = "START TRANSACTION;";
				mysql_query($sql,$con);
				$tr_i = 0;
				/*
				$sql = "UPDATE
							UsuarioGrupoPermissao,
							LogAcesso
						SET
							LogAcesso.Fechada = '1'
						WHERE 
							UsuarioGrupoPermissao.IdGrupoPermissao = '$local_IdGrupoPermissao' AND 
							UsuarioGrupoPermissao.Login = LogAcesso.Login AND 
							LogAcesso.Fechada = '2';";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);
				$tr_i++;
				*/
				$sql = "INSERT INTO 
							GrupoPermissaoSubOperacao
						SET
							IdGrupoPermissao = $local_IdGrupoPermissao, 
							IdLoja = $local_IdLoja, 
							IdModulo = $local_IdModulo, 
							IdOperacao = $local_IdOperacao, 
							IdSubOperacao = '$local_IdSubOperacao', 
							LoginCriacao = '$local_Login_Sistema', 
							DataCriacao = concat(curdate(),' ', curtime());";
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
				/*
				$sql = "UPDATE
							UsuarioGrupoPermissao,
							LogAcesso
						SET
							LogAcesso.Fechada = '1'
						WHERE 
							UsuarioGrupoPermissao.IdGrupoPermissao = '$local_IdGrupoPermissao' AND 
							UsuarioGrupoPermissao.Login = LogAcesso.Login AND 
							LogAcesso.Fechada = '2';";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);
				$tr_i++;
				*/
				$sql = "DELETE FROM 
							GrupoPermissaoSubOperacao 
						WHERE 
							IdGrupoPermissao = $local_IdGrupoPermissao AND 
							IdLoja = $local_IdLoja AND 
							IdModulo = $local_IdModulo AND 
							IdOperacao = $local_IdOperacao AND 
							IdSubOperacao = '$local_IdSubOperacao';";
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
		
		echo $local_Erro;
	}
?>