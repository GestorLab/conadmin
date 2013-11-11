<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	function get_servico_periodicidade(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_GET['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdPeriodicidade		= $_GET["IdPeriodicidade"];
		$IdServico				= $_GET["IdServico"];
		$QtdParcela				= $_GET["QtdParcela"];
		$TipoContrato			= $_GET["TipoContrato"];
		$IdLocalCobranca		= $_GET["IdLocalCobranca"];
		$MesFechado				= $_GET["MesFechado"];
		$QtdMesesFidelidade		= $_GET["QtdMesesFidelidade"];
		$where					= "";
		$groupby				= "";
		$order 					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPeriodicidade != '')		$where	.=	" and ServicoPeriodicidade.IdPeriodicidade = $IdPeriodicidade";
		if($IdServico != '')			$where	.=	" and ServicoPeriodicidade.IdServico = $IdServico";
		if($QtdParcela != '')			$where	.=	" and ServicoPeriodicidade.QtdParcela = $QtdParcela";
		if($TipoContrato != '')			$where	.=	" and ServicoPeriodicidade.TipoContrato = $TipoContrato";
		if($IdLocalCobranca != '')		$where	.=	" and ServicoPeriodicidade.IdLocalCobranca = $IdLocalCobranca";
		if($MesFechado != '')			$where	.=	" and ServicoPeriodicidade.MesFechado = $MesFechado";
		if($QtdMesesFidelidade != '')	$where	.=	" and ServicoPeriodicidade.QtdMesesFidelidade = $QtdMesesFidelidade";
		
		if($IdServico != ''){
			if($IdPeriodicidade=='' && $QtdParcela == '' && $TipoContrato== '' && $IdLocalCobranca == '' && $MesFechado == ''){
				$groupby	=	"group by Periodicidade.IdPeriodicidade";
			}
			if($IdPeriodicidade!='' && $QtdParcela == '' && $TipoContrato== '' && $IdLocalCobranca == '' && $MesFechado == ''){
				$groupby	= "group by ServicoPeriodicidade.QtdParcela";
				
				$order	=	"ServicoPeriodicidade.QtdParcela ASC";
			}
			if($IdPeriodicidade!='' && $QtdParcela != '' && $TipoContrato== '' && $IdLocalCobranca == '' && $MesFechado == ''){
				$groupby	= "group by ServicoPeriodicidade.TipoContrato";
			}
			if($IdPeriodicidade!='' && $QtdParcela != '' && $TipoContrato!= '' && $IdLocalCobranca == '' && $MesFechado == ''){
				$groupby	= "group by ServicoPeriodicidade.IdLocalCobranca";
			}
			if($IdPeriodicidade!='' && $QtdParcela != '' && $TipoContrato!= '' && $IdLocalCobranca != '' && $MesFechado == ''){
				$groupby	= "group by ServicoPeriodicidade.MesFechado";
			}
		}
		
		if($order == ""){
			$order	=	"LocalCobranca.AbreviacaoNomeLocalCobranca ASC, Periodicidade.DescricaoPeriodicidade ASC, ServicoPeriodicidade.TipoContrato ASC";
		}
		
		$sql = "select  
					ServicoPeriodicidade.IdServico,	
					ServicoPeriodicidade.IdPeriodicidade,
					Periodicidade.DescricaoPeriodicidade,
					Periodicidade.QtdParcelaMaximo,
					ServicoPeriodicidade.QtdParcela,
					ServicoPeriodicidade.IdLocalCobranca,
					LocalCobranca.DescricaoLocalCobranca,
					LocalCobranca.IdTipoLocalCobranca,
					ServicoPeriodicidade.TipoContrato,
					ServicoPeriodicidade.MesFechado,
					ServicoPeriodicidade.QtdMesesFidelidade
				from
					Periodicidade,
					ServicoPeriodicidade,
					LocalCobranca
				where
					ServicoPeriodicidade.IdLoja = $IdLoja and
					ServicoPeriodicidade.IdLoja = Periodicidade.IdLoja and
					ServicoPeriodicidade.IdLoja = LocalCobranca.IdLoja and
					ServicoPeriodicidade.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ServicoPeriodicidade.IdPeriodicidade = Periodicidade.IdPeriodicidade $where $groupby
				order by 
					$order";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 and IdParametroSistema = $lin[TipoContrato]";
				$res2 = @mysql_query($sql2,$con);
				$lin2 = @mysql_fetch_array($res2);
				
				$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=70 and IdParametroSistema = $lin[MesFechado]";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
				$dados	.=	"\n<IdPeriodicidade><![CDATA[$lin[IdPeriodicidade]]]></IdPeriodicidade>";
				$dados	.=	"\n<QtdParcelaMaximo><![CDATA[$lin[QtdParcelaMaximo]]]></QtdParcelaMaximo>";
				$dados	.=	"\n<DescricaoPeriodicidade><![CDATA[$lin[DescricaoPeriodicidade]]]></DescricaoPeriodicidade>";
				$dados	.=	"\n<QtdParcela><![CDATA[$lin[QtdParcela]]]></QtdParcela>";
				$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
				$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
				$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
				$dados	.=	"\n<TipoContrato><![CDATA[$lin[TipoContrato]]]></TipoContrato>";
				$dados	.=	"\n<DescTipoContrato><![CDATA[$lin2[ValorParametroSistema]]]></DescTipoContrato>";
				$dados	.=	"\n<MesFechado><![CDATA[$lin[MesFechado]]]></MesFechado>";
				$dados	.=	"\n<QtdMesesFidelidade><![CDATA[$lin[QtdMesesFidelidade]]]></QtdMesesFidelidade>";
				$dados	.=	"\n<DescricaoMesFechado><![CDATA[$lin3[ValorParametroSistema]]]></DescricaoMesFechado>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_servico_periodicidade();
?>