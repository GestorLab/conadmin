<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_tipo_help_desk(){
		global $conCNT;
		global $_GET;
		
		$Limit 			= $_GET['Limit'];
		$IdTipo			= $_GET['IdTipo'];	
		
		$sql	=	"select
						IdTipoHelpDesk,
						DescricaoTipoHelpDesk,
						IdStatus,
						LoginCriacao,
						DataCriacao,
						LoginAlteracao,
						DataAlteracao
					from
						HelpDeskTipo
					where
						IdTipoHelpDesk = $IdTipo
					order by
						IdTipoHelpDesk;";
		$res	= @mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	= @mysql_fetch_array($res)){
			$dados	.=	"\n<IdTipo>$lin[IdTipoHelpDesk]</IdTipo>";
			$dados	.=	"\n<DescricaoTipo><![CDATA[$lin[DescricaoTipoHelpDesk]]]></DescricaoTipo>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
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
	echo get_tipo_help_desk();
?>
