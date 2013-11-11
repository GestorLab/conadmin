<?	
	$localModulo	= 1;
	$localOperacao	= 10000;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION['Login'];
	//$local_IdLicenca	= $_SESSION['IdLicenca'];//Leonardo - 30-01-13/Não é mais necessário segundo informado pelo Sr. Douglas!	
	$local_id			= $_GET['id'];
	$local_Tipo			= $_GET['Tipo'];
	$local_IdServidor	= $_GET['IdServidor'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "select 
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdLoja = '$local_IdLoja' and 
					IdGrupoCodigoInterno = 10000 and 
					IdCodigoInterno = '$local_IdServidor'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		mysql_select_db($bd[banco],$conRadius);
		
		if($local_IdGrupo == '-1'){
			$local_IdGrupo = $local_NovoGrupo;
		}
		
		$table = "";
		
		if($local_Tipo == 'C'){
			$table = "radgroupcheck";
		}
		
		if($local_Tipo == 'R'){
			$table = "radgroupreply";
		}
		
		$sqlRadius = "delete from 
							$table 
						where 
							id = $local_id;";
		if(mysql_query($sqlRadius,$conRadius) == true){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 6;
		}
	}
?>