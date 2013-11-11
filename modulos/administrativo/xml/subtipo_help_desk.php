<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_subtipo_help_desk(){
		global $conCNT;
		global $_GET;
		
		$Limit 			= $_GET['Limit'];
		$IdTipo			= $_GET['IdTipo'];	
		$IdSubTipo		= $_GET['IdSubTipo'];	
		
		$sql	=	"select
						IdTipoHelpDesk,
						IdSubTipoHelpDesk,
						DescricaoSubTipoHelpDesk,
						IdStatus,
						LoginCriacao,
						DataCriacao,
						LoginAlteracao,
						DataAlteracao
					from
						HelpDeskSubTipo
					where
						IdTipoHelpDesk = $IdTipo and
						IdSubTipoHelpDesk = $IdSubTipo
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
			$dados	.=	"\n<IdSubTipo>$lin[IdSubTipoHelpDesk]</IdSubTipo>";
			$dados	.=	"\n<DescricaoSubTipo><![CDATA[$lin[DescricaoSubTipoHelpDesk]]]></DescricaoSubTipo>";
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
	echo get_subtipo_help_desk();
?>
