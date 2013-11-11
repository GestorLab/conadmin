<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_agendamento(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdOrdemServico'] != ''){		
			$where .= " and AgendamentoOrdemServico.IdOrdemServico = ".$_GET['IdOrdemServico'];
		}
		if($_GET['DataHoraAgendamento'] != ''){		
			$where .= " and AgendamentoOrdemServico.DataHoraAgendamento = '".$_GET['DataHoraAgendamento']."'";
		}
		

		$sql	=	"SELECT  
					     DataHoraAgendamento,
					     IdOrdemServico,
					     AgendamentoOrdemServico.LoginResponsavel,
					     Usuario.NomeUsuario,
					     AgendamentoOrdemServico.DataCriacao,
					     AgendamentoOrdemServico.LoginCriacao
					from
					    AgendamentoOrdemServico,
					    Usuario
					where
					    AgendamentoOrdemServico.IdLoja = $IdLoja and
						AgendamentoOrdemServico.LoginResponsavel = Usuario.Login $where order by DataHoraAgendamento DESC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Obs]	=	formTexto($lin[Obs]);
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<DataHoraAgendamento><![CDATA[$lin[DataHoraAgendamento]]]></DataHoraAgendamento>";
			$dados	.=	"\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
			$dados	.=	"\n<NomeUsuario><![CDATA[$lin[NomeUsuario]]]></NomeUsuario>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_agendamento();
?>
