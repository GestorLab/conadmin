<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoGrupo(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdServicoGrupo	 		= $_GET['IdServicoGrupo'];
		$DescricaoServicoGrupo  = $_GET['DescricaoServicoGrupo'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdServicoGrupo != ''){	
			$where .= " and IdServicoGrupo=$IdServicoGrupo";	
		}
		if($DescricaoServicoGrupo !=''){	
			$where .= " and DescricaoServicoGrupo like '$DescricaoServicoGrupo%'";	
		}
		
		$sql	=	"select
						IdLoja,
						IdServicoGrupo, 
						DescricaoServicoGrupo, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						ServicoGrupo
					where
						IdLoja	=	$local_IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdServicoGrupo>$lin[IdServicoGrupo]</IdServicoGrupo>";
			$dados	.=	"\n<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ServicoGrupo();
?>
