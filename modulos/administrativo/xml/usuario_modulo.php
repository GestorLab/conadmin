<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_modulo(){
		
		global $con;
		global $_GET;
		
		$Login		= $_GET['Login'];
		$IdLoja		= $_GET['IdLoja'];
		
		$sql	=	"select
					    Modulo.IdModulo,
					    Modulo.DescricaoModulo
					from					   
					    Modulo";
		$res	= @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdModulo>$lin[IdModulo]</IdModulo>";
			$dados	.=	"\n<DescricaoModulo><![CDATA[$lin[DescricaoModulo]]]></DescricaoModulo>";	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}		
	}
	echo get_usuario_modulo();
?>
