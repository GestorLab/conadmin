<?
	$localModulo	=	1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_monitor(){
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION["IdLoja"];
		$IdServico			= $_GET['IdServico'];
		$Where				= "";
		
		if($IdServico != '') {
			$Where .= " and ServicoMonitor.IdServico = $IdServico";	
		}
		
		$sql = "select
					ServicoMonitor.IdServico,
					ServicoMonitor.IdServicoMonitor,
					ServicoMonitor.ComandoSSH,
					ServicoMonitor.CodigoSNMP,
					ServicoMonitor.Historico,
					ServicoMonitor.IdConsulta,
					ServicoMonitor.IdParametroServico,
					ServicoMonitor.FiltroContratoParametro,
					ServicoMonitor.IdFormatoResultado,
					ServicoTipoMonitor.IdTipoMonitor,
					ServicoTipoMonitor.TipoMonitor,
					ServicoSNMP.IdSNMP,
					ServicoSNMP.SNMP
				from 
					ServicoMonitor left join (
						select 
							IdParametroSistema IdTipoMonitor,
							ValorParametroSistema TipoMonitor
						from
							ParametroSistema
						where
							IdGrupoParametroSistema = 212
					) ServicoTipoMonitor on (
						ServicoMonitor.TipoMonitor = ServicoTipoMonitor.IdTipoMonitor
					) left join (
						select 
							IdParametroSistema IdSNMP,
							ValorParametroSistema SNMP
						from
							ParametroSistema
						where
							IdGrupoParametroSistema = 210
					) ServicoSNMP on (
						ServicoMonitor.IdSNMP = ServicoSNMP.IdSNMP
					)
				where
					ServicoMonitor.IdLoja = $IdLoja
					$Where;";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0) {
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)) {
				$dados	.=	"\n<IdLoja>$lin[IdServico]</IdLoja>";
				$dados	.=	"\n<IdServicoMonitor>$lin[IdServicoMonitor]</IdServicoMonitor>";
				$dados	.=	"\n<IdTipoMonitor><![CDATA[$lin[IdTipoMonitor]]]></IdTipoMonitor>";
				$dados	.=	"\n<TipoMonitor><![CDATA[$lin[TipoMonitor]]]></TipoMonitor>";
				$dados	.=	"\n<IdConsulta><![CDATA[$lin[IdConsulta]]]></IdConsulta>";
				$dados	.=	"\n<IdParametroServico><![CDATA[$lin[IdParametroServico]]]></IdParametroServico>";
				$dados	.=	"\n<FiltroContratoParametro><![CDATA[$lin[FiltroContratoParametro]]]></FiltroContratoParametro>";
				$dados	.=	"\n<ComandoSSH><![CDATA[$lin[ComandoSSH]]]></ComandoSSH>";
				$dados	.=	"\n<CodigoSNMP><![CDATA[$lin[CodigoSNMP]]]></CodigoSNMP>";
				$dados	.=	"\n<Historico><![CDATA[$lin[Historico]]]></Historico>";
				$dados	.=	"\n<IdFormatoResultado><![CDATA[$lin[IdFormatoResultado]]]></IdFormatoResultado>";
				$dados	.=	"\n<IdSNMP><![CDATA[$lin[IdSNMP]]]></IdSNMP>";
				$dados	.=	"\n<SNMP><![CDATA[$lin[SNMP]]]></SNMP>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else {
			return "false";
		}
	}
	
	echo get_monitor();
?>