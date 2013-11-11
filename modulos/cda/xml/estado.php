<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	
	function get_Estado(){
		
		global $con;
		global $_GET;
		
		$Limit 		= $_GET['Limit'];
		$IdPais 	= $_GET['IdPais'];
		$IdEstado 	= $_GET['IdEstado'];
		$NomeEstado = $_GET['NomeEstado'];
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdEstado !=''){		$where .= "and Estado.IdEstado = $IdEstado";	}		
		if($NomeEstado !=''){	$where .= "and (Estado.NomeEstado like '$NomeEstado%' or Estado.SiglaEstado like '$NomeEstado%')";	}
		
		
		$sql	=	"select 
						Estado.IdPais, 
						Pais.NomePais,
						IdEstado, 
						SiglaEstado, 
						NomeEstado 
					from 
						Pais,
						Estado 
					where 
						Pais.IdPais = Estado.IdPais and
						Estado.IdPais=$IdPais $where order by NomeEstado ASC $Limit";
		$res	=	@mysql_query($sql,$con);
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
			$dados	.=	"\n<IdEstado>$lin[IdEstado]</IdEstado>";
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
			$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Estado();
?>
