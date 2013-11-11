<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	function get_parametro_sistema(){
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdGrupoParametroSistema		= $_GET['IdGrupoParametroSistema'];
		$IdParametroSistema 			= $_GET['IdParametroSistema'];
		$IdParametroSistemaFalse		= $_GET['IdParametroSistemaFalse'];
		$DescricaoParametroSistema  	= $_GET['DescricaoParametroSistema'];
		$VarStatus					  	= $_GET['VarStatus'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoParametroSistema != ''){				$where .= " and GrupoParametroSistema.IdGrupoParametroSistema=$IdGrupoParametroSistema";	}
		if($IdParametroSistema != ''){					$where .= " and ParametroSistema.IdParametroSistema=$IdParametroSistema";	}
		if($DescricaoParametroSistema !=''){			$where .= " and ParametroSistema.DescricaoParametroSistema like '$DescricaoParametroSistema%'";	}
		if($IdParametroSistemaFalse != ''){				$where .= " and ParametroSistema.IdParametroSistema < $IdParametroSistemaFalse";	}

		$sql	=	"select
					     ParametroSistema.IdGrupoParametroSistema,
					     GrupoParametroSistema.DescricaoGrupoParametroSistema,
					     GrupoParametroSistema.Editavel,
					     ParametroSistema.IdParametroSistema,
					     ParametroSistema.DescricaoParametroSistema,
						 ParametroSistema.ValorParametroSistema,
						 ParametroSistema.LoginCriacao,
				      	 ParametroSistema.DataCriacao,
				         ParametroSistema.LoginAlteracao,
				         ParametroSistema.DataAlteracao
					from 
					     ParametroSistema,
					     GrupoParametroSistema
					where
					     GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema $where $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoParametroSistema>$lin[IdGrupoParametroSistema]</IdGrupoParametroSistema>";
			$dados	.=	"\n<DescricaoGrupoParametroSistema><![CDATA[$lin[DescricaoGrupoParametroSistema]]]></DescricaoGrupoParametroSistema>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<IdParametroSistema>$lin[IdParametroSistema]</IdParametroSistema>";
			$dados	.=	"\n<DescricaoParametroSistema><![CDATA[$lin[DescricaoParametroSistema]]]></DescricaoParametroSistema>";
			$dados	.=	"\n<ValorParametroSistema><![CDATA[$lin[ValorParametroSistema]]]></ValorParametroSistema>";
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
	echo get_parametro_sistema();
?>
