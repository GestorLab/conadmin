<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_HelpDeskUsuario(){
		
		global $con;
		global $_GET;
		$where = '';
		
		$local_IdStatus	= $_GET['IdStatus'];
		
		if($local_IdStatus != ''){
			$where = " and IdStatus = $local_IdStatus ";
		}
		
		$sql	=	"select 
						Login 
					from 
						Usuario
					where
						1 
						$where
					order by 
						Login;";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_HelpDeskUsuario();
?>
