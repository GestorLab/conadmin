<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ArquivoRemessaLocalCobranca(){
		global $con;
		global $_GET;
		
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdLocalCobranca	 	= $_GET['IdLocalCobranca'];		
		$IdStatus	 			= $_GET['IdStatus'];		
		$where					= "";
		
		if($IdLocalCobranca != ''){
			$where .= " and IdLocalCobranca = $IdLocalCobranca";
		}
		
		if($IdStatus != ''){
			$where .= " and IdStatus = $IdStatus";
		}
		
		$sql	=	"select 
						IdLocalCobranca, 
						DescricaoLocalCobranca,
						IdStatus
					from 
						LocalCobranca 
					where 
						IdLoja=$local_IdLoja and 
						(
							IdTipoLocalCobranca = 3 or 
							IdTipoLocalCobranca = 4 or
							IdTipoLocalCobranca = 6 
						) and
						IdLocalCobrancaLayout != '' $where
					order by 
						DescricaoLocalCobranca;";
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
	echo get_ArquivoRemessaLocalCobranca();
?>
