<?
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdCabo 		= $_GET['IdCabo'];
	$local_IdLoja		= $_SESSION["IdLoja"];
	
	$sql ="SELECT
				IdLoja,
				IdCabo,
				IdPontoPassagem,
				IdPoste
			FROM 
				CaboPontoPassagem
			WHERE 
				IdLoja		= $local_IdLoja
				AND	IdCabo 	= $local_IdCabo";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sqlDelPonto = "DELETE
						FROM
							CaboPontoPassagem
						WHERE 
							IdLoja = $local_IdLoja
							AND IdCabo = $local_IdCabo
							AND IdPontoPassagem = $lin[IdPontoPassagem]";
		$resDelPonto = mysql_query($sqlDelPonto,$con);
	}
	$sqlDelCabo = "DELETE FROM 
						Cabo
					WHERE 
						IdLoja = $local_IdLoja
						AND IdCabo = $local_IdCabo";
	if(mysql_query($sqlDelCabo,$con)==true){
		echo $local_Erro = 7;
	}else{
		echo $local_Erro = 6;
	}

?>