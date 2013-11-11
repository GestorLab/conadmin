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
		$IdContaReceber				= $_GET['IdContaReceber'];
		$Nome						= $_GET['Nome'];
		$where			= "";
		
		if($IdArquivoRemessaTipo != '')		$where	=	" and ArquivoRemessaTipo.IdArquivoRemessaTipo = $IdArquivoRemessaTipo";
		if($IdContaReceber != '')			$where	=	" and ContaReceber.IdContaReceber = $IdContaReceber";
		if($Nome != '')						$where	=	" and ArquivoRemessaTipo.DescricaoArquivoRemessaTipo like '$Nome%'";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		
		$sql	=	"select
					     ArquivoRemessaTipo.IdArquivoRemessaTipo,
					     ArquivoRemessaTipo.DescricaoArquivoRemessaTipo,
					     OcorrenciaRemessa,
					     OcorrenciaRetorno,
					     StatusRetorno,
					     ContaReceber.IdOcorrenciaRemessa,
					     ContaReceber.IdOcorrenciaRetorno,
					     ContaReceber.IdStatusRetorno
					from 
					     ArquivoRemessaTipo 
                         LEFT JOIN LocalCobranca ON (ArquivoRemessaTipo.IdArquivoRemessaTipo = LocalCobranca.IdArquivoRemessaTipo) 
                         LEFT JOIN ContaReceber ON (LocalCobranca.IdLocalCobranca = ContaReceber.IdLocalCobranca)
					where
						ArquivoRemessaTipo.IdArquivoRemessaTipo != '' $where
					GROUP by
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
			$dados	.=	"\n<OcorrenciaRemessa><![CDATA[$lin[OcorrenciaRemessa]]]></OcorrenciaRemessa>";
			$dados	.=	"\n<OcorrenciaRetorno><![CDATA[$lin[OcorrenciaRetorno]]]></OcorrenciaRetorno>";
			$dados	.=	"\n<StatusRetorno><![CDATA[$lin[StatusRetorno]]]></StatusRetorno>";	
			$dados	.=	"\n<IdOcorrenciaRemessa><![CDATA[$lin[IdOcorrenciaRemessa]]]></IdOcorrenciaRemessa>";
			$dados	.=	"\n<IdOcorrenciaRetorno><![CDATA[$lin[IdOcorrenciaRetorno]]]></IdOcorrenciaRetorno>";
			$dados	.=	"\n<IdStatusRetorno><![CDATA[$lin[IdStatusRetorno]]]></IdStatusRetorno>";	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Arquivo_Remessa_Tipo();
?>
