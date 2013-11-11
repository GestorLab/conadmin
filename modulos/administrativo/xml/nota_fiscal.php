<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal(){
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION["IdLoja"];	
		$IdContaReceber			= $_GET['IdContaReceber'];
		$PeriodoApuracao		= $_GET['PeriodoApuracao'];
		$IdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];

		$where				= '';
		$limit				= '';
		
		if($IdContaReceber != ''){
			$where .= " and NotaFiscal.IdContaReceber = $IdContaReceber";
		}
		
		if($PeriodoApuracao != ''){
			$PeriodoApuracao = dataConv($PeriodoApuracao, "m/Y", "Y-m");
			$where .= " and NotaFiscal.PeriodoApuracao = '$PeriodoApuracao'";
		}	
		
		if($IdNotaFiscalLayout != ''){
			$where .= " and NotaFiscal.Modelo = (
				select
					Modelo
				from
					NotaFiscalLayout
				where
					IdNotaFiscalLayout = '$IdNotaFiscalLayout' 
			)";
		}
		
		if($IdNotaFiscalLayout != ''){
			$where .= " and NotaFiscal.Modelo = (
												select
													Modelo
												from
													NotaFiscalLayout
												where
													IdNotaFiscalLayout = '$IdNotaFiscalLayout' 
											)";
		}
		
		if($_GET['Limit'] != ''){
			$limit = " limit ".$_GET['Limit'];
		}
		
		$sql = "	select 
					NotaFiscal.IdLoja,
					NotaFiscal.IdNotaFiscal,
					NotaFiscal.IdNotaFiscalLayout, 
					NotaFiscal.PeriodoApuracao,
					NotaFiscal.ObsVisivel
				from 
					NotaFiscal
				where
					NotaFiscal.IdLoja = $IdLoja
					$where
				$limit;";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdNotaFiscal><![CDATA[$lin[IdNotaFiscal]]]></IdNotaFiscal>";
			$dados	.=	"\n<IdNotaFiscalLayout><![CDATA[$lin[IdNotaFiscalLayout]]]></IdNotaFiscalLayout>";
			$dados	.=	"\n<PeriodoApuracao><![CDATA[$lin[PeriodoApuracao]]]></PeriodoApuracao>";
			$dados	.=	"\n<ObsNotaFiscal><![CDATA[$lin[ObsVisivel]]]></ObsNotaFiscal>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_nota_fiscal();
?>