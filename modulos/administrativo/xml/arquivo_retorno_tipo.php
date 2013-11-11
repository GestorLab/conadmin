<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Arquivo_Retorno_Tipo(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdArquivoRetornoTipo		= $_GET['IdArquivoRetornoTipo'];
		$IdLocalRecebimento			= $_GET['IdLocalRecebimento'];
		$Nome						= $_GET['Nome'];
		$where			= "";
		
		if($IdArquivoRetornoTipo != '')		$where	=	" and ArquivoRetornoTipo.IdArquivoRetornoTipo = $IdArquivoRetornoTipo";
		if($IdLocalRecebimento != '')		$where	=	" and LocalCobranca.IdLocalCobranca = $IdLocalRecebimento";
		if($Nome != '')						$where	=	" and ArquivoRetornoTipo.DescricaoArquivoRetornoTipo like '$Nome%'";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql	=	"select
					     ArquivoRetornoTipo.IdArquivoRetornoTipo,
					     ArquivoRetornoTipo.DescricaoArquivoRetornoTipo
					from 
					     ArquivoRetornoTipo LEFT JOIN LocalCobranca ON (LocalCobranca.IdArquivoRetornoTipo = ArquivoRetornoTipo.IdArquivoRetornoTipo and LocalCobranca.IdLoja = $IdLoja)
					where
						ArquivoRetornoTipo.IdArquivoRetornoTipo != ''
						 $where 
					group by
						ArquivoRetornoTipo.IdArquivoRetornoTipo $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdArquivoRetornoTipo>$lin[IdArquivoRetornoTipo]</IdArquivoRetornoTipo>";
			$dados	.=	"\n<DescricaoArquivoRetornoTipo><![CDATA[$lin[DescricaoArquivoRetornoTipo]]]></DescricaoArquivoRetornoTipo>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Arquivo_Retorno_Tipo();
?>
