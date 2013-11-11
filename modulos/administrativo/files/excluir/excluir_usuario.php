<?
	$localModulo	=	1;
	$localOperacao	=	6;
	
	$local_Login	=	$_GET['Login'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		// Sql de Inserção de Usuario //
		$IdLoja		= $_SESSION["IdLoja"];
		$IdLicenca	= $_SESSION["IdLicenca"];
		$ii			= 0;
		
		$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $IdLoja and IdGrupoCodigoInterno = 10000 and IdCodigoInterno <= 20 order by ValorCodigoInterno;";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$aux = explode("\n",$lin[ValorCodigoInterno]);
			
			$bd[server][$ii]	= trim($aux[0]); //Host
			$bd[login][$ii]		= trim($aux[1]); //Login
			$bd[senha][$ii]		= trim($aux[2]); //Senha
			$bd[banco][$ii]		= trim($aux[3]); //DB
			
			$ii++;
		}
		
		@mysql_close($con);
		
		for($i=0; $i<$ii; $i++){
			$conRadius	= @mysql_connect($bd[server][$i],$bd[login][$i],$bd[senha][$i]);
			@mysql_select_db($bd[banco][$i], $conRadius);
			
			$sql	= "START TRANSACTION;";
			@mysql_query($sql,$conRadius);
			$tr_i	= 0;
			
			$sqlRadius	= "select 
								usergroup.Id, 
								radgroupreply.GroupName
							from 
								radgroupreply, 
								(select
									usergroup.id AS Id,
									usergroup.IdLicenca,
									usergroup.GroupName
								 from
									usergroup
								 where
									IdLoja = '$IdLoja' and
									IdLicenca = '$IdLicenca' and
									UserName = '$local_Login' and
									Id > 99999
								) usergroup
							where 
								radgroupreply.GroupName = usergroup.GroupName and
								radgroupreply.IdLicenca = usergroup.IdLicenca and
								radgroupreply.Attribute = 'Mikrotik-Group'
							group by
								radgroupreply.GroupName;";
			$resRadius = @mysql_query($sqlRadius, $conRadius);
			if($linRadius = @mysql_fetch_array($resRadius)){
				$IdRadius = $linRadius[Id];
				
				$sql1Radius	= "delete from 
									usergroup 
								where 
									IdLicenca = '$IdLicenca' and 
									IdLoja = '$IdLoja' and 
									Id = '$linRadius[Id]' and 
									UserName = '$local_Login';";
				$local_transaction[$tr_i] = @mysql_query($sql1Radius, $conRadius);
				$tr_i++;
				
				$sql1Radius	= "delete from 
									radcheck 
								where 
									IdLicenca = '$IdLicenca' and 
									IdLoja = '$IdLoja' and 
									Id = '$linRadius[Id]' and 
									Attribute = 'Password' and
									UserName = '$local_Login';";
				$local_transaction[$tr_i] = @mysql_query($sql1Radius, $conRadius);
				$tr_i++;
			}
			
			for($aux=0; $aux<$tr_i; $aux++){
				if($local_transaction[$aux] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
			}else{
				$sql = "ROLLBACK;";
			}
			
			@mysql_query($sql,$conRadius);
			@mysql_close($conRadius);
		}
		
		@include ('../../../../files/conecta.php');
		
		$sql	=	"DELETE FROM Usuario WHERE Login='$local_Login';";
		if(@mysql_query($sql,$con) == true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
