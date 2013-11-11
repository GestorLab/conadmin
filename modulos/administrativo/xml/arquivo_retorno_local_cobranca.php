<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ArquivoRetornoLocalCobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdArquivoRetorno	 	= $_GET['IdArquivoRetorno'];
		$IdLocalRecebimento	 	= $_GET['IdLocalRecebimento'];		
		$where					= "";
		
		if($IdArquivoRetorno == ''){
			$where .= " and IdArquivoRetornoTipo != ''";
		}
		
		if($IdLocalRecebimento != ''){
			$where .= " and IdLocalCobranca = $IdLocalRecebimento";
		}
		
		$sql	=	"select 
						IdLocalCobranca, 
						DescricaoLocalCobranca,
						IdStatus
					from 
						LocalCobranca
					where 
						IdLoja=$local_IdLoja $where
					order by 
						DescricaoLocalCobranca";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";
			$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";		
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ArquivoRetornoLocalCobranca();
?>
