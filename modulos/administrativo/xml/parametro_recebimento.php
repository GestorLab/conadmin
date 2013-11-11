<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_conta_receber_parametro(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdLocalRecebimento 	= $_GET['IdLocalRecebimento'];
		$where					= "";
		
		if($IdLocalRecebimento !=''){		$where .= " and ParametroRecebimento.IdLocalCobranca=$IdLocalRecebimento";	}
		
		$sql	=	"select
					     ParametroRecebimento.IdLoja,
					     ParametroRecebimento.IdLocalCobranca,
					     ParametroRecebimento.IdParametroRecebimento,
					     DescricaoParametroRecebimento,
						 Listar,
						 Obrigatorio,
						 ObsParametroRecebimento
					from 
					     ParametroRecebimento 
					where
					     ParametroRecebimento.IdLoja=$IdLoja 
					     $where";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
			$dados	.=	"\n<IdParametroRecebimento><![CDATA[$lin[IdParametroRecebimento]]]></IdParametroRecebimento>";
			$dados	.=	"\n<DescricaoParametroRecebimento><![CDATA[$lin[DescricaoParametroRecebimento]]]></DescricaoParametroRecebimento>";
			$dados	.=	"\n<Listar><![CDATA[$lin[Listar]]]></Listar>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			$dados	.=	"\n<ObsParametroRecebimento><![CDATA[$lin[ObsParametroRecebimento]]]></ObsParametroRecebimento>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_conta_receber_parametro();
?>
