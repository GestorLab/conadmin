<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_PessoaAnexo(){
		global $con;
		global $_GET;
		
		$Limit 		= $_GET['Limit'];
		$IdLoja	 	= $_SESSION['IdLoja'];
		$IdPessoa	= $_GET['IdPessoa'];
		
		if($Limit != ''){
			$Limit = " limit 0,$Limit";
		}
		
		$sql = "
			SELECT
				IdPessoa,
				IdAnexo,
				DescricaoAnexo,
				NomeOriginal,
				MD5,
				LoginCriacao, 
				DataCriacao
			FROM
				PessoaAnexo
			WHERE
				IdPessoa = '$IdPessoa'
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
			$Tamanho = calculaTamanhoArquivo("../anexos/pessoa/".$lin[IdPessoa]."/".$lin[IdAnexo].".".$ext);
			
			$dados	.= "\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.= "\n<IdAnexo><![CDATA[$lin[IdAnexo]]]></IdAnexo>";
			$dados	.= "\n<IMG><![CDATA[$IMG]]></IMG>";
			$dados	.= "\n<Tamanho><![CDATA[$Tamanho]]></Tamanho>";
			$dados	.= "\n<DescricaoAnexo><![CDATA[$lin[DescricaoAnexo]]]></DescricaoAnexo>";
			$dados	.= "\n<NomeOriginal><![CDATA[$lin[NomeOriginal]]]></NomeOriginal>";
			$dados	.= "\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
			$dados	.= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_PessoaAnexo();
?>