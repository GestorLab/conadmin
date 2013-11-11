<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Pais(){
		
		global $con;
		global $_GET;
		
		$Limit 		= $_GET['Limit'];
		$IdPais   	= $_GET['IdPais'];
		$NomePais   = $_GET['NomePais'];
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPais !=''){	$where[1] = "IdPais = $IdPais";	}
		if($NomePais !=''){	$where[2] = " NomePais like '$NomePais%'";	}
		
		if($where[1]!='' || $where[2]!=''){
			$where[0] = "where";
		}
		
		if($where[1]!='' && $where[2]!=''){
			$where[2] = " and NomePais like '$NomePais%'";
		}

		
		
		$sql	=	"select IdPais, NomePais from Pais $where[0] $where[1] $where[2] $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdPais>$lin[IdPais]</IdPais>";
			$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Pais();
?>
