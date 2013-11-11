<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_processo(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdProcesso				= $_GET['IdProcesso'];
		$where					=	"";
		
		$sql	=	"SHOW FULL PROCESSLIST";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdProcesso>$lin[Id]</IdProcesso>";
			$dados	.=	"\n<User><![CDATA[$lin[User]]]></User>";
			$dados	.=	"\n<Host><![CDATA[$lin[Host]]]></Host>";
			$dados	.=	"\n<DB><![CDATA[$lin[db]]]></DB>";
			$dados	.=	"\n<Command><![CDATA[$lin[Command]]]></Command>";
			$dados	.=	"\n<Time><![CDATA[$lin[Time]]]></Time>";
			$dados	.=	"\n<State><![CDATA[$lin[State]]]></State>";
			$dados	.=	"\n<Info><![CDATA[$lin[Info]]]></Info>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_processo();
?>
