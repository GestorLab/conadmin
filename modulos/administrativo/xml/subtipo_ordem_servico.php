<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_SubTipoOrdemServico(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja							= $_SESSION["IdLoja"];
		$IdOrdemServico	 				= $_GET['IdOrdemServico'];
		$IdTipoOrdemServico	 			= $_GET['IdTipoOrdemServico'];
		$IdSubTipoOrdemServico	 		= $_GET['IdSubTipoOrdemServico'];
		$DescricaoTipoOrdemServico 		= $_GET['DescricaoTipoOrdemServico'];
		$DescricaoSubTipoOrdemServico 	= $_GET['DescricaoSubTipoOrdemServico'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdTipoOrdemServico != ''){	
			$where .= " and SubTipoOrdemServico.IdTipoOrdemServico=$IdTipoOrdemServico";	
		}
		if($IdSubTipoOrdemServico != ''){	
			$where .= " and SubTipoOrdemServico.IdSubTipoOrdemServico=$IdSubTipoOrdemServico";	
		}
		if($DescricaoTipoOrdemServico !=''){	
			$where .= " and DescricaoTipoOrdemServico like '$DescricaoTipoOrdemServico%'";	
		}
		if($DescricaoSubTipoOrdemServico !=''){	
			$where .= " and DescricaoSubTipoOrdemServico like '$DescricaoSubTipoOrdemServico%'";	
		}
		
		if($IdOrdemServico !=""){
			$from_ex  =", OrdemServico";
			$where_ex ="and 
						OrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
						OrdemServico.IdOrdemServico = $IdOrdemServico and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
						OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico";
		}
		
		$sql	=	"select
						SubTipoOrdemServico.IdTipoOrdemServico, 
						TipoOrdemServico.DescricaoTipoOrdemServico, 
						SubTipoOrdemServico.IdSubTipoOrdemServico,
						SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
						SubTipoOrdemServico.Cor,
						SubTipoOrdemServico.DataCriacao, 
						SubTipoOrdemServico.LoginCriacao, 
						SubTipoOrdemServico.DataAlteracao, 
						SubTipoOrdemServico.LoginAlteracao 
					from 
						TipoOrdemServico,
						SubTipoOrdemServico
						$from_ex
					where
						SubTipoOrdemServico.IdLoja = $IdLoja and
						SubTipoOrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						SubTipoOrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico $where $where_ex $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdTipoOrdemServico>$lin[IdTipoOrdemServico]</IdTipoOrdemServico>";
			$dados	.=	"\n<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
			$dados	.=	"\n<IdSubTipoOrdemServico>$lin[IdSubTipoOrdemServico]</IdSubTipoOrdemServico>";
			$dados	.=	"\n<DescricaoSubTipoOrdemServico><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipoOrdemServico>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
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
	echo get_SubTipoOrdemServico();
?>
