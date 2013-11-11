<?
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdPoste 		= $_GET['IdPoste'];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	$sql ="DELETE FROM  
			  	Poste
			WHERE 
				IdLoja = $local_IdLoja
				AND IdPoste = $local_IdPoste";
	if(mysql_query($sql,$con)==true){
		echo $local_Erro = 7;
	}elseif(mysql_error()){
		echo $local_Erro = 33;
	}else{
		echo $local_Erro = 6;
	}

?>