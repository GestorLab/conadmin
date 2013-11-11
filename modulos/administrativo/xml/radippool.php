<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_id			= $_GET['id'];
	
	function get_radippool(){
		global $con;
		global $local_IdLoja;
		global $local_id;
		
		$sql = "select 
				ValorCodigoInterno 
			from 
				CodigoInterno 
			where 
				IdLoja = $local_IdLoja and 
				IdGrupoCodigoInterno = 10000 and 
				IdCodigoInterno = 1";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		
		$aux = explode("\n",$lin[ValorCodigoInterno]);
		
		$bd[server]	= trim($aux[0]); //Host
		$bd[login]	= trim($aux[1]); //Login
		$bd[senha]	= trim($aux[2]); //Senha
		$bd[banco]	= trim($aux[3]); //DB		
		
		$conRadius = mysql_connect($bd[server],$bd[login],$bd[senha]) or die(mysql_error());
		
		mysql_select_db($bd[banco]);	
		
		$sql = "SELECT
					IdLicenca,
					IdLoja,
					id,
					pool_name,
					framedipaddress,
					nasipaddress,
					calledstationid,
					callingstationid,
					expiry_time,
					username,
					pool_key
				FROM 
					radippool
				WHERE
					id = $local_id";

		$res = mysql_query($sql,$conRadius);
		
		if(mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
		
			$dados	.=	"\n<IdLicenca><![CDATA[$lin[IdLicenca]]]></IdLicenca>";
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<id><![CDATA[$lin[id]]]></id>";
			$dados	.=	"\n<pool_name><![CDATA[$lin[pool_name]]]></pool_name>";
			$dados	.=	"\n<framedipaddress><![CDATA[$lin[framedipaddress]]]></framedipaddress>";
			$dados	.=	"\n<nasipaddress><![CDATA[$lin[nasipaddress]]]></nasipaddress>";
			$dados	.=	"\n<calledstationid><![CDATA[$lin[calledstationid]]]></calledstationid>";
			$dados	.=	"\n<callingstationid><![CDATA[$lin[callingstationid]]]></callingstationid>";
			$dados	.=	"\n<expiry_time><![CDATA[$lin[expiry_time]]]></expiry_time>";
			$dados	.=	"\n<username><![CDATA[$lin[username]]]></username>";
			$dados	.=	"\n<pool_key><![CDATA[$lin[pool_key]]]></pool_key>";
	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
		
		mysql_close($conRadius);
	}
	echo get_radippool();
?>
