<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_OrdemServicoAnexo(){
		global $con;
		global $_GET;
		
		$Limit 			= $_GET['Limit'];
		$IdLoja	 		= $_SESSION['IdLoja'];
		$IdOrdemServico	= $_GET['IdOrdemServico'];
		
		if($Limit != ''){
			$Limit = " limit 0,$Limit";
		}
		
		$sql = "
			SELECT
				IdLoja,
				IdOrdemServico,
				IdAnexo,
				DescricaoAnexo,
				NomeOriginal,
				MD5
			FROM
				OrdemServicoAnexo
			WHERE
				IdLoja = '$IdLoja' AND
				IdOrdemServico = '$IdOrdemServico'
			$Limit;
		";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header("content-type: text/xml");
			$dados	 = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.= "\n<reg>";
		}else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$ext = endArray(explode('.', $lin[NomeOriginal]));
			$IMG = "<img src='".getIcone($ext)."' style='margin-bottom:-3px;'>";
			$Tamanho = calculaTamanhoArquivo("../anexos/ordem_servico/".$lin[IdOrdemServico]."/".$lin[IdAnexo].".".$ext);
			
			$dados	.= "\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.= "\n<IdOrdemServico><![CDATA[$lin[IdOrdemServico]]]></IdOrdemServico>";
			$dados	.= "\n<IdAnexo><![CDATA[$lin[IdAnexo]]]></IdAnexo>";
			$dados	.= "\n<IMG><![CDATA[$IMG]]></IMG>";
			$dados	.= "\n<Tamanho><![CDATA[$Tamanho]]></Tamanho>";
			$dados	.= "\n<DescricaoAnexo><![CDATA[$lin[DescricaoAnexo]]]></DescricaoAnexo>";
			$dados	.= "\n<NomeOriginal><![CDATA[$lin[NomeOriginal]]]></NomeOriginal>";
			$dados	.= "\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_OrdemServicoAnexo();
?>