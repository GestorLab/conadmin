<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Agente_Comissao(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		
		$IdAgenteAutorizado	= $_GET['IdAgenteAutorizado'];
		$IdServico			= $_GET['IdServico'];
		$Parcela			= $_GET['Parcela'];
		
		if($IdAgenteAutorizado	!= '')	{	$where .= " and ComissionamentoAgenteAutorizado.IdAgenteAutorizado = $IdAgenteAutorizado"; 	}
		if($IdServico!=''){					$where .= " and ComissionamentoAgenteAutorizado.IdServico=$IdServico";	}
		if($Parcela!=''){					$where .= " and ComissionamentoAgenteAutorizado.Parcela = '$Parcela'";	}
		
		$sql	=	"SELECT  
					     ComissionamentoAgenteAutorizado.IdAgenteAutorizado,
					     Pessoa.RazaoSocial,
					     Pessoa.Nome,
					     ComissionamentoAgenteAutorizado.IdServico,
					     Servico.DescricaoServico,
					     Parcela,
					     Percentual,
					     ComissionamentoAgenteAutorizado.DataCriacao,
					     ComissionamentoAgenteAutorizado.LoginCriacao,
					     ComissionamentoAgenteAutorizado.DataAlteracao,
					     ComissionamentoAgenteAutorizado.LoginAlteracao
					from
						ComissionamentoAgenteAutorizado,
					    AgenteAutorizado,
					    Pessoa,
					    Servico
					where
					     ComissionamentoAgenteAutorizado.IdLoja = $IdLoja and
					     ComissionamentoAgenteAutorizado.IdLoja = Servico.IdLoja and
					     ComissionamentoAgenteAutorizado.IdLoja = AgenteAutorizado.IdLoja and
					     ComissionamentoAgenteAutorizado.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado and
					     AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
						 ComissionamentoAgenteAutorizado.IdServico = Servico.IdServico $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<Parcela><![CDATA[$lin[Parcela]]]></Parcela>";
			$dados	.=	"\n<Percentual><![CDATA[$lin[Percentual]]]></Percentual>";
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
	echo get_Agente_Comissao();
?>
