<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_usuario_grupo_acesso_radius(){
		global $con;
		global $_GET;		
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdLicenca		= $_SESSION["IdLicenca"];
		$GrupoAcesso	= $_GET["GrupoAcesso"];
		$where 			= '';
		$dadosTemp		= '';
		$ii 			= 0;
		$resultRadius	= false;
		
		if($GrupoAcesso != ''){
			$where = "where and GroupName = '$GrupoAcesso'";
		}
		
		$sql = "select 
					IdCodigoInterno, 
					ValorCodigoInterno 
				from 
					CodigoInterno 
				where 
					IdGrupoCodigoInterno = 10000 and 
					IdCodigoInterno < 20 
				order by 
					ValorCodigoInterno;";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$aux = explode("\n",$lin[ValorCodigoInterno]);
			
			$bd[server][$ii]	= trim($aux[0]); //Host
			$bd[login][$ii]		= trim($aux[1]); //Login
			$bd[senha][$ii]		= trim($aux[2]); //Senha
			$bd[banco][$ii]		= trim($aux[3]); //DB
			
			$IdServidor[$ii]	= $lin[IdCodigoInterno];
			$ii++;
		}
		
		@mysql_close($con);
		
		for($i=0; $i<$ii; $i++){
			$conRadius[$i]	= @mysql_connect($bd[server][$i],$bd[login][$i],$bd[senha][$i]);
			@mysql_select_db($bd[banco][$i], $conRadius[$i]);
			
			$sqlRadius	= "select distinct
								GroupName
							from 
								radgroupreply 
							order by
								GroupName;";
			$resRadius[$i] = @mysql_query($sqlRadius, $conRadius[$i]);
			if(@mysql_num_rows($resRadius[$i]) >= 1){
				$resultRadius = $resRadius[$i];			
			}
			
			while($linRadius = @mysql_fetch_array($resRadius[$i])){
				$dadosTemp	.=	"\n<IdServidor><![CDATA[$IdServidor[$i]]]></IdServidor>";
				$dadosTemp	.=	"\n<GroupName><![CDATA[$linRadius[GroupName]]]></GroupName>";
				
				if($GrupoAcesso == ''){
					$where .= " and GroupName != '$linRadius[GroupName]'";
				}
			}
		}
		
		if(@mysql_num_rows($resultRadius) >= 1){
			header ("content-type: text/xml");
			$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>".$dadosTemp."\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
		
		for($i = 0; $i < count($resRadius); $i++){
			@mysql_close($conRadius[$i]);
		}
	}
	
	echo get_usuario_grupo_acesso_radius();
?>