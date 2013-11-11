<?
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MascaraVigencia() {
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_SESSION['IdLoja'];
		$local_IdServico		= $_GET['IdServico'];
		$local_IdTipoServico	= $_GET['IdTipoServico'];
		$local_Mes				= $_GET['Mes'];
		$where					=	"";
		
		if($Limit != '') {
			$Limit = " limit 0,$Limit";
		}
		
		if($local_IdServico != '') {	
			$where .= " and ServicoMascaraVigencia.IdServico = $local_IdServico"; 	
		}
		
		if($local_Mes != '') {
			$where .= " and ServicoMascaraVigencia.Mes = $local_Mes"; 	
		}
		
		if($local_IdTipoServico != '') {
			$where .= " and Servico.IdTipoServico in ($local_IdTipoServico)"; 	
		}
		
		$sql = "select
					ServicoMascaraVigencia.IdLoja,
					ServicoMascaraVigencia.IdServico,
					Servico.DescricaoServico,
					Servico.IdTipoServico, 
					ServicoMascaraVigencia.Mes,
					ServicoMascaraVigencia.Fator,
					ServicoMascaraVigencia.IdTipoDesconto,
					ServicoMascaraVigencia.IdContratoTipoVigencia,
					ServicoMascaraVigencia.LimiteDesconto,
					ServicoMascaraVigencia.ValorRepasseTerceiro,
					ServicoMascaraVigencia.PercentualRepasseTerceiro,
					ServicoMascaraVigencia.VigenciaDefinitiva,
					ServicoMascaraVigencia.LoginCriacao,
					ServicoMascaraVigencia.DataCriacao,
					ServicoMascaraVigencia.LoginAlteracao,
					ServicoMascaraVigencia.DataAlteracao
				from 
					Servico,
					ServicoMascaraVigencia
				where
					ServicoMascaraVigencia.IdLoja = $local_IdLoja and
					ServicoMascaraVigencia.IdLoja = Servico.IdLoja and
					ServicoMascaraVigencia.IdServico = Servico.IdServico 
					$where 
				order by
					ServicoMascaraVigencia.Mes ASC 
				$Limit;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0) {
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)) {
				$sql0 = "select 
								Valor
							from 
								ServicoValor 
							where 
								DataInicio <= curdate() and 
								(DataTermino is Null  or DataTermino >= curdate()) and 
								IdLoja = $lin[IdLoja] and 
								IdServico = $lin[IdServico] 
							order by
								DataInicio DESC 
							limit 0,1"; 
				$res0 = mysql_query($sql0,$con);
				$lin0 = @mysql_fetch_array($res0);
				$lin[Valor] = $lin0[Valor];
				
				$sql0 = "select 
							count(*) Mes 
						from 
							ServicoMascaraVigencia 
						where 
							IdLoja = $local_IdLoja and 
							IdServico = $lin[IdServico];";
				$res0 = mysql_query($sql0,$con);
				$lin0 = @mysql_fetch_array($res0);
				$lin[QtdMes] = $lin0[Mes];
				$lin[DescricaoVigenciaDefinitiva] = getParametroSistema(144,$lin[VigenciaDefinitiva]);
			
				$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
				$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
				$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
				$dados	.=	"\n<Mes><![CDATA[$lin[Mes]]]></Mes>";
				$dados	.=	"\n<QtdMes><![CDATA[$lin[QtdMes]]]></QtdMes>";
				$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
				$dados	.=	"\n<PercentualRepasseTerceiro><![CDATA[$lin[PercentualRepasseTerceiro]]]></PercentualRepasseTerceiro>";
				$dados	.=	"\n<Fator><![CDATA[$lin[Fator]]]></Fator>";
				$dados	.=	"\n<IdTipoDesconto><![CDATA[$lin[IdTipoDesconto]]]></IdTipoDesconto>";
				$dados	.=	"\n<IdContratoTipoVigencia><![CDATA[$lin[IdContratoTipoVigencia]]]></IdContratoTipoVigencia>";
				$dados	.=	"\n<LimiteDesconto><![CDATA[$lin[LimiteDesconto]]]></LimiteDesconto>";
				$dados	.=	"\n<VigenciaDefinitiva><![CDATA[$lin[VigenciaDefinitiva]]]></VigenciaDefinitiva>";
				$dados	.=	"\n<DescricaoVigenciaDefinitiva><![CDATA[$lin[DescricaoVigenciaDefinitiva]]]></DescricaoVigenciaDefinitiva>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else {
			return "false";
		}
	}
	
	echo get_MascaraVigencia();
?>