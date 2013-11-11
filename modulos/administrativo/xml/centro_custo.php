<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Centro_Custo(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdCentroCusto 			= $_GET['IdCentroCusto'];
		$DescricaoCentroCusto  	= $_GET['DescricaoCentroCusto'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdCentroCusto != ''){			$where .= " and IdCentroCusto=$IdCentroCusto";	}
		if($DescricaoCentroCusto !=''){		$where .= " and CentroCusto.DescricaoCentroCusto like '$DescricaoCentroCusto%'";	}

		$sql	=	"select
					     IdLoja,
					     IdCentroCusto,
					     CentroCusto.DescricaoCentroCusto,
						 CentroCusto.DataCriacao,
					 	 CentroCusto.LoginCriacao,
 						 CentroCusto.DataAlteracao,
						 CentroCusto.LoginAlteracao,
						 CentroCusto.IdStatus
					from 
					     CentroCusto
					where
					     IdLoja=$IdLoja $where $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdCentroCusto>$lin[IdCentroCusto]</IdCentroCusto>";
			$dados	.=	"\n<DescricaoCentroCusto><![CDATA[$lin[DescricaoCentroCusto]]]></DescricaoCentroCusto>";
			$dados	.=	"\n<IdStatus>$lin[IdStatus]</IdStatus>";
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
	echo get_Centro_Custo();
?>
