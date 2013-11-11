<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_group_name(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION["IdLoja"];
		$local_IdLicenca		= $_SESSION["IdLicenca"];
		$IdServidor				= $_GET["IdServidor"];
		$Limit 					= $_GET['Limit'];
		$where					=	"";
		
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
		
		$conRadius	= mysql_connect($bd[server],$bd[login],$bd[senha]);
		
		mysql_select_db($bd[banco],$conRadius);
		
		$sqlRadius = "select distinct 
							GroupName 
						from 
							radgroupcheck
						UNION 
						select distinct 
							GroupName 
						from 
							radgroupreply
						order by 
							GroupName ASC";
		$resRadius = mysql_query($sqlRadius,$conRadius);
		
		if(@mysql_num_rows($resRadius) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($linRadius = @mysql_fetch_array($resRadius)){
				$dados	.=	"\n<GroupName><![CDATA[$linRadius[GroupName]]]></GroupName>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
		
		mysql_close($conRadius);
	}
	
	echo get_group_name();
?>