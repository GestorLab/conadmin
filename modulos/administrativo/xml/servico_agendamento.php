<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoAgendamento(){
		global $con;
		global $_GET;
		
		$IdLoja		= $_SESSION["IdLoja"];
		$IdServico	= $_GET['IdServico'];
		$QtdMes		= $_GET['QtdMes'];
		
		$where		= "";
		
		if($IdServico != ''){
			$where .= " and ServicoAgendamento.IdServico = $IdServico";
		}
		
		if($QtdMes != ''){
			$where .= " and ServicoAgendamento.QtdMes = $QtdMes";
		}
		
		$sql = "
			select 
				Servico.IdServico,
				Servico.IdTipoServico,
				Servico.BaseDataStatusContratoOS,
				ServicoAgendamento.QtdMes,
				ServicoAgendamento.IdStatus,
				ServicoAgendamento.IdNovoStatus,
				ServicoAgendamento.LoginCriacao,
				ServicoAgendamento.DataCriacao,
				ServicoAgendamento.LoginAlteracao,
				ServicoAgendamento.DataAlteracao
			from
				Servico,
				ServicoAgendamento
			where
				Servico.IdLoja = $IdLoja and
				Servico.IdLoja = ServicoAgendamento.IdLoja and
				Servico.IdServico = ServicoAgendamento.IdServico
				$where
			order by
				ServicoAgendamento.IdServico";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Status] = getParametroSistema(69, $lin[IdStatus]);
			$lin[NovoStatus] = getParametroSistema(69, $lin[IdNovoStatus]);
			$lin[TipoServico] = getParametroSistema(71, $lin[IdTipoServico]);
			
			$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
			$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
			$dados	.=	"\n<TipoServico><![CDATA[$lin[TipoServico]]]></TipoServico>";
			$dados	.=	"\n<BaseDataStatusContratoOS><![CDATA[$lin[BaseDataStatusContratoOS]]]></BaseDataStatusContratoOS>";
			$dados	.=	"\n<QtdMes><![CDATA[$lin[QtdMes]]]></QtdMes>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<IdNovoStatus><![CDATA[$lin[IdNovoStatus]]]></IdNovoStatus>";
			$dados	.=	"\n<NovoStatus><![CDATA[$lin[NovoStatus]]]></NovoStatus>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
		}
		if(mysql_num_rows($res) >= 1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_ServicoAgendamento();
?>
