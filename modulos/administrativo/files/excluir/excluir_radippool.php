<?	
	$localModulo	= 1;
	$localOperacao	= 10000;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_id			= $_GET['Id'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "select 
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdLoja = $local_IdLoja and 
					IdGrupoCodigoInterno = 10000";
					
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		mysql_select_db($bd[banco],$conRadius);
	
		
		$sqlRadius = "DELETE FROM 
							radius.radippool
						WHERE 
							IdLoja = $local_IdLoja
							AND id = $local_id";
		if(mysql_query($sqlRadius,$conRadius) == true){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 6;
		}
	}
?>