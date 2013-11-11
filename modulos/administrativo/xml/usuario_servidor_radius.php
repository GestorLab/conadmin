<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_servidor_radius(){
		global $con;
		global $_GET;		
		
		$IdLoja					= $_SESSION["IdLoja"];
		$IdLicenca				= $_SESSION["IdLicenca"];
		$where					= "";
		$i 						= 0;
		$resultRadius			= false;
		
		$sql = "select 
					IdCodigoInterno,
					subString(DescricaoCodigoInterno,1,46) DescricaoCodigoInterno
				from 
					CodigoInterno 
				where 
					IdLoja = $IdLoja and 
					IdGrupoCodigoInterno = 10000 and
					IdCodigoInterno < 20
				order by 
					ValorCodigoInterno;";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >= 1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	= @mysql_fetch_array($res)){
			$dados	.=	"\n<IdServidor>$lin[IdCodigoInterno]</IdServidor>";
			$dados	.=	"\n<DescricaoServidor><![CDATA[$lin[DescricaoCodigoInterno]]]></DescricaoServidor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_servidor_radius();
?>
