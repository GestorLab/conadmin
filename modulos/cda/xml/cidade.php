<?
	$localModulo	=	0;

	include ('../../../files/conecta.php');
	
	function get_Cidade(){
		
		global $con;
		global $_GET;
		
		$Limit 		= $_GET['Limit'];
		$IdPais 	= $_GET['IdPais'];
		$IdEstado 	= $_GET['IdEstado'];
		$IdCidade	= $_GET['IdCidade'];
		$NomeCidade = $_GET['NomeCidade'];
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		if($IdPais !=''){		$where .= " and Cidade.IdPais = $IdPais";	}
		if($IdEstado !=''){		$where .= " and Cidade.IdEstado = $IdEstado";	}
		if($IdCidade !=''){		$where .= " and Cidade.IdCidade = $IdCidade";	}
		if($NomeCidade !=''){	$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		
		
		$sql	=	"select 
						Cidade.IdPais, 
						Pais.NomePais,
						Cidade.IdEstado, 
						Estado.SiglaEstado,
						Estado.NomeEstado,
						Cidade.IdCidade, 
						Cidade.NomeCidade 
					from 
						Pais,
						Estado,
						Cidade 
					where 
						Pais.IdPais = Estado.IdPais and
						Estado.IdPais = Cidade.IdPais and
						Estado.IdEstado = Cidade.IdEstado $where $Limit";
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
			$dados	.=	"\n<IdCidade>$lin[IdCidade]</IdCidade>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Cidade();
?>
