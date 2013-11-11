<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servico_agrupado(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdServico				= $_GET["IdServico"];
		$where					= "";
		
		if($IdServico != ''){
			$where	.=	" and ServicoAgrupado.IdServico = $IdServico";
		}
		
		$sql	=	"SELECT  
						 IdServico,	
					     IdServicoAgrupador
					from
					    ServicoAgrupado
					where
					    ServicoAgrupado.IdLoja = $IdLoja $where";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
			$dados	.=	"\n<IdServicoAgrupador><![CDATA[$lin[IdServicoAgrupador]]]></IdServicoAgrupador>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_servico_agrupado();
?>
