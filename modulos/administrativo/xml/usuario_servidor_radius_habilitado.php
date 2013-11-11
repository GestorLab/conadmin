<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servidor_redius_habilitado(){
		
		global $con;
		global $_GET;		
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdLicenca		= $_SESSION["IdLicenca"];
		$Usuario		= $_GET["Usuario"];
		$GrupoMikrotik	= $_GET["GrupoMikrotik"];
		$where 			= '';
		$ii 			= 0;
		$resultRadius	= false;
		
		if($GrupoAcesso != ''){
			$where = " and radgroupreply.GroupName = '$GrupoMikrotik'";
		}
		
		$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $IdLoja and IdGrupoCodigoInterno=10000 and IdCodigoInterno < 20 order by ValorCodigoInterno;";
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
			
			$sqlRadius	= "select 
								radgroupreply.id, 
								radgroupreply.GroupName
							from 
								radgroupreply, 
								(select
									usergroup.IdLicenca,
									usergroup.GroupName
								 from
									usergroup
								 where
									IdLoja = '$IdLoja' and
									IdLicenca = '$IdLicenca' and
									UserName = '$Usuario'
								) usergroup
							where 
								radgroupreply.GroupName = usergroup.GroupName and
								radgroupreply.IdLicenca = usergroup.IdLicenca and
								radgroupreply.Attribute = 'Mikrotik-Group' $where
							group by
								radgroupreply.GroupName;";
			$resRadius[$i] = @mysql_query($sqlRadius, $conRadius[$i]);
			if(@mysql_num_rows($resRadius[$i]) >= 1){
				$resultRadius = $resRadius[$i];			
			}
		}
		if(@mysql_num_rows($resultRadius) >= 1){
			header ("content-type: text/xml");
			$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		for($i=0; $i<count($resRadius); $i++){
			while($linRadius = @mysql_fetch_array($resRadius[$i])){
				$dados	.=	"\n<IdServidor><![CDATA[$IdServidor[$i]]]></IdServidor>";
			}
		}
		for($i=0; $i<count($resRadius); $i++){
			@mysql_close($conRadius[$i]);
		}
		if(mysql_num_rows($resultRadius) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_servidor_redius_habilitado();
?>
