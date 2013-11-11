<?
	$localModulo		= 1;
	$localOperacao		= 138;
	
	$local_IdLoja 		= $_SESSION["IdLoja"];
	$local_IdServico	= $_GET['IdServico'];
	$local_CFOP			= $_GET['CFOP'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "
			DELETE FROM 
				ServicoCFOP 
			WHERE 
				ServicoCFOP.IdLoja = '$local_IdLoja' AND 
				ServicoCFOP.IdServico = '$local_IdServico' AND
				ServicoCFOP.CFOP = '$local_CFOP';";
		if(@mysql_query($sql,$con) == true){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 6;
		}
	}
?>
