<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_codigo_interno(){
		
		global $con;
		global $_GET;
		
		$IdLoja 					= $_SESSION["IdLoja"];
		$Limit 						= $_GET['Limit'];
		$IdGrupoCodigoInterno		= $_GET['IdGrupoCodigoInterno'];
		$IdCodigoInterno 			= $_GET['IdCodigoInterno'];
		$DescricaoCodigoInterno  	= $_GET['DescricaoCodigoInterno'];
		$Ordem					  	= $_GET['Ordem'];
		$where						= "";
		$order						= "";	
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoCodigoInterno != ''){			$where .= " and GrupoCodigoInterno.IdGrupoCodigoInterno=$IdGrupoCodigoInterno";	}
		if($IdCodigoInterno != ''){					$where .= " and CodigoInterno.IdCodigoInterno=$IdCodigoInterno";	}
		if($DescricaoCodigoInterno !=''){			$where .= " and CodigoInterno.DescricaoCodigoInterno like '$DescricaoCodigoInterno%'";	}
		if($Ordem !=''){							$order	= " order by $Ordem ASC";	}
		
		$sql	=	"select
					     CodigoInterno.IdGrupoCodigoInterno,
					     GrupoCodigoInterno.DescricaoGrupoCodigoInterno,
					     GrupoCodigoInterno.Editavel,
					     CodigoInterno.IdCodigoInterno,
					     CodigoInterno.DescricaoCodigoInterno,
						 CodigoInterno.ValorCodigoInterno,
						 CodigoInterno.LoginCriacao,
				      	 CodigoInterno.DataCriacao,
				         CodigoInterno.LoginAlteracao,
				         CodigoInterno.DataAlteracao
					from 
						 Loja,
					     CodigoInterno,
					     GrupoCodigoInterno
					where
						 CodigoInterno.IdLoja = $IdLoja and
						 CodigoInterno.IdLoja = Loja.IdLoja and	
					     GrupoCodigoInterno.IdGrupoCodigoInterno = CodigoInterno.IdGrupoCodigoInterno $where $order $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoCodigoInterno><![CDATA[$lin[IdGrupoCodigoInterno]]]></IdGrupoCodigoInterno>";
			$dados	.=	"\n<DescricaoGrupoCodigoInterno><![CDATA[$lin[DescricaoGrupoCodigoInterno]]]></DescricaoGrupoCodigoInterno>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<IdCodigoInterno><![CDATA[$lin[IdCodigoInterno]]]></IdCodigoInterno>";
			$dados	.=	"\n<DescricaoCodigoInterno><![CDATA[$lin[DescricaoCodigoInterno]]]></DescricaoCodigoInterno>";
			$dados	.=	"\n<ValorCodigoInterno><![CDATA[$lin[ValorCodigoInterno]]]></ValorCodigoInterno>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_codigo_interno();
?>
