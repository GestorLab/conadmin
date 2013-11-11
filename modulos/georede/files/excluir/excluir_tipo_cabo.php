<?
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdTipoCabo	= $_GET['IdTipoCabo'];
	
	$sql = "DELETE	FROM CaboTipo
			WHERE 
				IdLoja = $local_IdLoja
				AND IdCaboTipo = $local_IdTipoCabo;";
	if(mysql_query($sql,$con)==true){
		echo $local_Erro = 7;
	}else{
		echo $local_Erro = 6;
	}

?>