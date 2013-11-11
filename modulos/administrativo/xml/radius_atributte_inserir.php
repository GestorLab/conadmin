<?
	$localModulo	= 1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_attributte(){
		global $con;
		global $_GET;
		
		$IdLoja		= $_SESSION["IdLoja"];
		$IdLicenca	= $_SESSION["IdLicenca"];
		$localLogin	= $_SESSION["Login"];
		$IdServidor	= $_GET["IdServidor"];
		$where		= "";
		
		$sql = "select 
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdLoja = '$IdLoja' and 
					IdGrupoCodigoInterno = 10000 and 
					IdCodigoInterno = '$IdServidor'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		mysql_select_db($bd[banco],$conRadius);
		
		$sqlRadius = "(
						select 
							Attribute 
						from 
							radgroupcheck 
						where 
							IdLicenca = '$IdLicenca' 
						group by 
							Attribute
					) union all (
						select 
							Attribute 
						from 
							radgroupreply 
						where 
							IdLicenca = '$IdLicenca' 
						group by 
							Attribute
					)";
		$resRadius = mysql_query($sqlRadius,$conRadius);
		
		if(@mysql_num_rows($resRadius) < 1){
			return "false";
		}
		
		while($linRadius = @mysql_fetch_array($resRadius)){
			$sqlAux	= "select 
							IdCodigoInterno 
						from 
							CodigoInterno 
						where 
							IdLoja = '$IdLoja' and 
							IdGrupoCodigoInterno = 10001 and 
							ValorCodigoInterno = '$linRadius[Attribute]'";
			$resAux	= mysql_query($sqlAux,$con);
			
			if(@mysql_num_rows($resAux) < 1){
				$sql = "select 
							(max(IdCodigoInterno)+1) IdCodigoInterno 
						from 
							CodigoInterno 
						where 
							IdLoja = $IdLoja and 
							IdGrupoCodigoInterno = '10001'";
				$res = mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if($lin[IdCodigoInterno] != NULL){
					$local_IdCodigoInterno = $lin[IdCodigoInterno];
				} else{
					$local_IdCodigoInterno = 1;
				}
				
				$local_DescricaoCodigoInterno = "Servidor Radius - Atributo - ".$linRadius[Attribute];
				
				$sql = "insert into CodigoInterno set 
							IdLoja						= $IdLoja,
							IdGrupoCodigoInterno		= '10001', 
							IdCodigoInterno				= $local_IdCodigoInterno, 
							DescricaoCodigoInterno		= '$local_DescricaoCodigoInterno', 
							ValorCodigoInterno			= '$linRadius[Attribute]',
							DataCriacao					= (concat(curdate(),' ',curtime())),
							LoginCriacao				= '$localLogin';";
				// Executa a Sql de Inserчуo de Codigo Interno
				mysql_query($sql,$con);
			}
		}
		
		mysql_close($conRadius);
	}
	
	echo get_attributte();
?>