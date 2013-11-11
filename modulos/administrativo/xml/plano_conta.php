<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_PlanoConta(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPlanoConta	 		= $_GET['IdPlanoConta'];
		$DescricaoPlanoConta  	= $_GET['DescricaoPlanoConta'];
		$TipoPlanoConta		  	= $_GET['TipoPlanoConta'];
		$IdAcessoRapido			= $_GET['IdAcessoRapido'];
		
		$AcessoRapido			= $_GET['AcessoRapido'];
		$Auxiliar				= $_GET['Auxiliar'];		
		
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPlanoConta != ''){
			$where .= " and IdPlanoConta like '$IdPlanoConta%'";	
		}
		if($AcessoRapido == 'S'){
			$where .= " and IdAcessoRapido = '$IdPlanoConta'";	
		}
		if($DescricaoPlanoConta !=''){	
			$where .= " and DescricaoPlanoConta like '$DescricaoPlanoConta%'";	
		}
		if($IdAcessoRapido !=''){	
			$where .= " and IdAcessoRapido like '$IdAcessoRapido%'";	
		}
		
		$sql	=	"select 
						IdPlanoConta,
						DescricaoPlanoConta,
						IdAcessoRapido,
						TipoPlanoConta,
						AcaoContabil,
						DataCriacao,
						LoginCriacao,
						DataAlteracao,
						LoginAlteracao
					 from 
					 	PlanoConta 
					where IdLoja = $IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		$qtdCols=	mysql_num_fields($res);
		if(@mysql_num_rows($res) >=1){	
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=33 and IdParametroSistema=$lin[TipoPlanoConta]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$dados	.=	"\n<IdPlanoConta><![CDATA[$lin[IdPlanoConta]]]></IdPlanoConta>";
			$dados	.=	"\n<DescricaoPlanoConta><![CDATA[$lin[DescricaoPlanoConta]]]></DescricaoPlanoConta>";
			$dados	.=	"\n<IdAcessoRapido><![CDATA[$lin[IdAcessoRapido]]]></IdAcessoRapido>";
			$dados	.=	"\n<Tipo><![CDATA[$lin[TipoPlanoConta]]]></Tipo>";
			$dados	.=	"\n<DescricaoTipo><![CDATA[$lin2[ValorParametroSistema]]]></DescricaoTipo>";
			$dados	.=	"\n<AcaoContabil><![CDATA[$lin[AcaoContabil]]]></AcaoContabil>";
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
	echo get_PlanoConta();
?>
