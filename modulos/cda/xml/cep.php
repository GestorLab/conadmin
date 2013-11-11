<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	
	function get_cep(){
		
		global $con;
		global $_GET;
		
		$Limit 		= $_GET['Limit'];
		$CEP		= $_GET['CEP'];
		$where		= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql	=	"select
				      Cep,
				      Endereco,
				      Bairro,
				      IdPais,
				      IdEstado,
				      IdCidade
				from
				      Cep 
				where
					  Cep	=	'$CEP' $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<Cep><![CDATA[$lin[Cep]]]></Cep>";
			$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
			$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";
			$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
			$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
			$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_cep();
?>
