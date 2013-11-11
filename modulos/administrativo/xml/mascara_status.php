<?
	$localModulo = 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MascaraStatus(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION["IdLoja"];
		$local_IdServico	= $_GET["IdServico"];
		$local_IdStatus		= $_GET["IdStatus"];
		$where				= '';
		
		if($local_IdServico != '') {
			$where .= " AND ServicoMascaraStatus.IdServico = '$local_IdServico'"; 	
		}
		
		if($local_IdStatus != '') {
			$where .= " AND ServicoMascaraStatus.IdStatus = '$local_IdStatus'"; 	
		}
		
		$sql = "SELECT
					Servico.DescricaoServico,
					Servico.IdTipoServico,
					ServicoMascaraStatus.IdLoja,
					ServicoMascaraStatus.IdServico,
					ServicoMascaraStatus.IdStatus,
					ServicoMascaraStatus.PercentualDesconto,
					ServicoMascaraStatus.TaxaMudancaStatus,
					ServicoMascaraStatus.QtdMinimaDia,
					ServicoMascaraStatus.LoginCriacao,
					ServicoMascaraStatus.DataCriacao,
					ServicoMascaraStatus.LoginAlteracao,
					ServicoMascaraStatus.DataAlteracao
					
				FROM 
					Servico,
					ServicoMascaraStatus
				WHERE
					Servico.IdLoja = '$local_IdLoja' AND
					Servico.IdLoja = ServicoMascaraStatus.IdLoja AND
					Servico.IdServico = ServicoMascaraStatus.IdServico
					$where 
				ORDER BY
					ServicoMascaraStatus.IdServico ASC 
				$Limit;";
		$res = mysql_query($sql, $con);
		if(@mysql_num_rows($res) > 0) {
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else {
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)) {
			$lin[Status] = getParametroSistema(69, $lin[IdStatus]);
			
			$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<PercentualDesconto><![CDATA[$lin[PercentualDesconto]]]></PercentualDesconto>";
			$dados	.=	"\n<TaxaMudancaStatus><![CDATA[$lin[TaxaMudancaStatus]]]></TaxaMudancaStatus>";
			$dados	.=	"\n<QtdMinimaDia><![CDATA[$lin[QtdMinimaDia]]]></QtdMinimaDia>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
		}
		
		if(mysql_num_rows($res) > 0) {
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_MascaraStatus();
?>