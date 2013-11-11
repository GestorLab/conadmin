<?
	$localModulo = 1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include("../../../rotinas/verifica.php");
	
	function get_servico_terceiro() {
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION["IdLoja"];
		$local_IdTerceiro	= $_GET["IdTerceiro"];
		$local_IdServico	= $_GET["IdServico"];
		$where				= '';
		
		if($local_IdTerceiro != '') {
			$where .= " AND Terceiro.IdPessoa = $local_IdTerceiro";
		}
		
		if($local_IdServico != '') {
			$where .= " AND ServicoTerceiro.IdServico = $local_IdServico";
		}
		
		$sql = "SELECT 
					ServicoTerceiro.IdServico,
					ServicoTerceiro.ValorRepasseTerceiro,
					ServicoTerceiro.PercentualRepasseTerceiro,
					ServicoTerceiro.PercentualRepasseTerceiroOutros,
					Terceiro.IdPessoa IdTerceiro,
					Terceiro.LoginCriacao,
					Terceiro.DataCriacao,
					Terceiro.LoginAlteracao,
					Terceiro.DataAlteracao,
					Pessoa.Nome
				FROM
					Terceiro LEFT JOIN (
						ServicoTerceiro
					) ON (
						ServicoTerceiro.IdLoja = Terceiro.IdLoja AND 
						ServicoTerceiro.IdPessoa = Terceiro.IdPessoa 
					),
					Pessoa
				WHERE
					Terceiro.IdLoja = $local_IdLoja AND
					Terceiro.IdPessoa = Pessoa.IdPessoa
					$where
				GROUP BY
					ServicoTerceiro.IdPessoa;";
		$res = mysql_query($sql, $con);
		
		if(@mysql_num_rows($res) > 0) {
			header ("content-type: text/xml");
			$dados	 = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)) {
				$dados	.= "\n\t<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
				$dados	.= "\n\t<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
				$dados	.= "\n\t<PercentualRepasseTerceiro><![CDATA[$lin[PercentualRepasseTerceiro]]]></PercentualRepasseTerceiro>";
				$dados	.= "\n\t<PercentualRepasseTerceiroOutros><![CDATA[$lin[PercentualRepasseTerceiroOutros]]]></PercentualRepasseTerceiroOutros>";
				$dados	.= "\n\t<IdTerceiro>$lin[IdTerceiro]</IdTerceiro>";
				$dados	.= "\n\t<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.= "\n\t<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.= "\n\t<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.= "\n\t<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.= "\n\t<Nome><![CDATA[$lin[Nome]]]></Nome>";
			}
			
			$dados	.= "\n</reg>";
			return $dados;
		}
		
		return "false";
	}
	
	echo get_servico_terceiro();
?>