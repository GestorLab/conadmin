<?
	$localModulo			= 1;
	$localOperacao			= 32;
	
	#$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
	$local_Login			= $_GET['Login'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "
			DELETE FROM
				UsuarioGrupoPermissao 
			WHERE 
				IdGrupoPermissao = $local_IdGrupoPermissao 
			AND 
				Login LIKE '$local_Login';";
		if(@mysql_query($sql,$con) == true){
			echo $local_Erro = 37;
		} else{
			echo $local_Erro = 38;
		}
	}
?>
