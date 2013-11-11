<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Arquivo_Remessa_Tipo(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdArquivoRemessaTipo		= $_GET['IdArquivoRemessaTipo'];
		$IdLocalCobranca			= $_GET['IdLocalCobranca'];
		$Nome						= $_GET['Nome'];
		$where			= "";
		
		if($IdArquivoRemessaTipo != '')		$where	=	" and ArquivoRemessaTipo.IdArquivoRemessaTipo = $IdArquivoRemessaTipo";
		if($Nome != '')						$where	=	" and ArquivoRemessaTipo.DescricaoArquivoRemessaTipo like '$Nome%'";
		if($IdLocalCobranca != '')			$where	=	" and LocalCobranca.IdLocalCobranca = $IdLocalCobranca";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		$sql	=	"select
					     ArquivoRemessaTipo.IdArquivoRemessaTipo,
					     ArquivoRemessaTipo.DescricaoArquivoRemessaTipo
					from 
					     ArquivoRemessaTipo LEFT JOIN LocalCobranca ON (LocalCobranca.IdArquivoRemessaTipo = ArquivoRemessaTipo.IdArquivoRemessaTipo and LocalCobranca.IdLoja = $IdLoja)
                    where
						1 $where 
					group by
						ArquivoRemessaTipo.IdArquivoRemessaTipo $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdArquivoRemessaTipo>$lin[IdArquivoRemessaTipo]</IdArquivoRemessaTipo>";
			$dados	.=	"\n<DescricaoArquivoRemessaTipo><![CDATA[$lin[DescricaoArquivoRemessaTipo]]]></DescricaoArquivoRemessaTipo>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Arquivo_Remessa_Tipo();
?>
