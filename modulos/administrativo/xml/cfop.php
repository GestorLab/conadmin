<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_cfop(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$CFOP			 		= $_GET['CFOP'];
		$CFOPBusca		 		= $_GET['CFOPBusca'];
		$NaturezaOperacao	  	= $_GET['NaturezaOperacao'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($CFOP != ''){	
			$where .= " and CFOP=$CFOP";	
		}
		if($CFOPBusca != ''){	
			$where .= " and CFOP like '$CFOPBusca%'";	
		}
		if($NaturezaOperacao !=''){	
			$where .= " and NaturezaOperacao like '$NaturezaOperacao%'";	
		}
		
		$sql	=	"select
						CFOP, 
						NaturezaOperacao
					from 
						CFOP
					where
						1 $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<CFOP><![CDATA[$lin[CFOP]]]></CFOP>";
			$dados	.=	"\n<NaturezaOperacao><![CDATA[$lin[NaturezaOperacao]]]></NaturezaOperacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_cfop();
?>