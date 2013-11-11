<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nas(){
		
		global $con;	
		$local_IdLoja					= $_SESSION["IdLoja"];		
		$localId						= $_GET['id'];	
		
		$sql	=	"SELECT
						id,
						nasname,
						shortname,
						type,
						ports,
						secret,
						server,
						community,
						description
					FROM 
						radius.nas
						where id = $localId";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<id><![CDATA[$lin[id]]]></id>";
			$dados	.=	"\n<nasname><![CDATA[$lin[nasname]]]></nasname>";
			$dados	.=	"\n<shortname><![CDATA[$lin[shortname]]]></shortname>";
			$dados	.=	"\n<type><![CDATA[$lin[type]]]></type>";
			$dados	.=	"\n<ports><![CDATA[$lin[ports]]]></ports>";
			$dados	.=	"\n<secret><![CDATA[$lin[secret]]]></secret>";
			$dados	.=	"\n<server><![CDATA[$lin[server]]]></server>";
			$dados	.=	"\n<community><![CDATA[$lin[community]]]></community>";
			$dados	.=	"\n<description><![CDATA[$lin[description]]]></description>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nas();
?>