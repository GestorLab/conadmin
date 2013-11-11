<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoValor() {
		global $con;
		global $_GET;
		
		$IdLoja		= $_SESSION["IdLoja"];
		$Limit 		= $_GET['Limit'];
		$IdServico	= $_GET['IdServico'];
		$DataInicio	= $_GET['DataInicio'];
		$where		= "";
		
		if($Limit != '') {
			$Limit = "limit 0,$Limit";
		}
		
		if($IdServico != '') {
			$where .= " and ServicoValor.IdServico = $IdServico";
		}
		
		if($DataInicio != '') {
			$where .= " and ServicoValor.DataInicio = '$DataInicio'";
		}
		
		$sqlQtdMes ="select
						ServicoValor.DataInicio
					from 
						Loja,
						Servico,
						ServicoValor
					where
						Servico.IdLoja = $IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						ServicoValor.IdLoja = Servico.IdLoja and
						Servico.IdServico = ServicoValor.IdServico and
						ServicoValor.IdServico = $IdServico
					order by 
						DataInicio DESC 
					$Limit";
		$resQtdMes = mysql_query($sqlQtdMes,$con);
		$linQtdMes = mysql_fetch_array($resQtdMes);
		
		$sql = "select
					ServicoValor.DataInicio,
					ServicoValor.DataTermino,
					ServicoValor.DescricaoServicoValor,
					ServicoValor.Valor,
					ServicoValor.MultaFidelidade,
					ServicoValor.IdContratoTipoVigencia,
					ServicoValor.DataCriacao,
					ServicoValor.LoginCriacao,
					ServicoValor.DataAlteracao,
					ServicoValor.LoginAlteracao,
					Servico.IdTipoServico
				from 
					Loja,
					Servico,
					ServicoValor
				where
					Servico.IdLoja = $IdLoja and
					Servico.IdLoja = Loja.IdLoja and
					ServicoValor.IdLoja = Servico.IdLoja and
					Servico.IdServico = ServicoValor.IdServico 
					$where 
				order by 
					DataInicio DESC 
				$Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)) {
				$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
				$dados	.=	"\n<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
				$dados	.=	"\n<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
				$dados	.=	"\n<DescricaoServicoValor><![CDATA[$lin[DescricaoServicoValor]]]></DescricaoServicoValor>";
				$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.=	"\n<DataInicioTemp><![CDATA[$linQtdMes[DataInicio]]]></DataInicioTemp>";
				$dados	.=	"\n<MultaFidelidade><![CDATA[$lin[MultaFidelidade]]]></MultaFidelidade>";
				$dados	.=	"\n<IdContratoTipoVigencia><![CDATA[$lin[IdContratoTipoVigencia]]]></IdContratoTipoVigencia>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else {
			return "false";
		}
	}
	
	echo get_ServicoValor();
?>