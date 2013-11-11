<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_historicoOS(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdOrdemServico'] != ''){		
			$where .= " and HistoricoOrdemServico.IdOrdemServico = ".$_GET['IdOrdemServico'];
		}

		$sql	=	"SELECT  
					     IdHistorico,
					     IdOrdemServico,
					     DataHora,
					     LoginResponsavel,
					     Obs
					from
					    HistoricoOrdemServico
					where
					     HistoricoOrdemServico.IdLoja = $IdLoja $where order by DataHora DESC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Obs]	=	formTexto($lin[Obs]);
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<IdHistorico>$lin[IdHistorico]</IdHistorico>";
			$dados	.=	"\n<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
			$dados	.=	"\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_historicoOS();
?>
