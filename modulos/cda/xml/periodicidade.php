<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	function get_periodicidade(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_GET["IdLoja"];
		$IdPeriodicidade		= $_GET["IdPeriodicidade"];
		$IdLocalCobranca		= $_GET["IdLocalCobranca"];
		$TipoContrato			= $_GET["TipoContrato"];
		$MesFechado				= $_GET["MesFechado"];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql	=	"SELECT  
					     IdPeriodicidade,
					     DescricaoPeriodicidade,
					     QtdParcelaMaximo,
					     Fator
					from
					    Periodicidade
					where
					    Periodicidade.IdLoja = $IdLoja and
						Periodicidade.IdPeriodicidade = $IdPeriodicidade
					order by IdPeriodicidade";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			if($IdLocalCobranca != ''){
				$sql3 = "select AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja=$IdLoja and IdLocalCobranca = $IdLocalCobranca";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);				
			}
			
			if($TipoContrato != ''){
				$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema=$TipoContrato";
				$res2 = @mysql_query($sql2,$con);
				$lin2 = @mysql_fetch_array($res2);
			}
			
			if($MesFechado != ''){
				$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=70 and IdParametroSistema=$MesFechado";
				$res4 = @mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
			}
			
			$dados	.=	"\n<IdPeriodicidade>$lin[IdPeriodicidade]</IdPeriodicidade>";
			$dados	.=	"\n<QtdParcelaMaximo><![CDATA[$lin[QtdParcelaMaximo]]]></QtdParcelaMaximo>";
			$dados	.=	"\n<DescricaoPeriodicidade><![CDATA[$lin[DescricaoPeriodicidade]]]></DescricaoPeriodicidade>";
			$dados	.=	"\n<Fator><![CDATA[$lin[Fator]]]></Fator>";
			$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin3[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
			$dados	.=	"\n<DescTipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></DescTipoContrato>";
			$dados	.=	"\n<DescMesFechado><![CDATA[$lin4[ValorParametroSistema]]]></DescMesFechado>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_periodicidade();
?>