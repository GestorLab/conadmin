<?
	$localModulo	=	1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_Logomarca(){
		global $con;
		
		$sql = "select 
					ValorParametroSistema 
				from
					ParametroSistema 
				where 
					IdGrupoParametroSistema = 4 and 
					IdParametroSistema = 2";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			$lin = mysql_fetch_array($res);
			
			$arr		= explode(";",$lin['ValorParametroSistema']);
			$arr1		= explode(" ",$arr[0]);
			$arr2		= explode(" ",$arr[1]);
			$arr3		= explode(" ",$arr[2]);
			$arr4		= explode(" ",$arr[3]);
			$width		= str_replace("px","",$arr1[1]);
			$marginLeft	= str_replace("px","",$arr3[2]);
			$marginTop	= str_replace("px","",$arr4[2]);
			$float		= $arr2[2];
			
			$dados	.=	"\n<Largura>$width</Largura>";
			$dados	.=	"\n<MargemEsquerda>$marginLeft</MargemEsquerda>";
			$dados	.=	"\n<MargemSuperior>$marginTop</MargemSuperior>";
			
			$dados	.=	"\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Logomarca();
?>