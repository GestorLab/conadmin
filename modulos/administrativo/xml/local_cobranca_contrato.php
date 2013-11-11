<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_LocalCobrancaContrato(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoa			  	= $_GET['IdPessoa'];
		$IdTipoExc			  	= $_GET['IdTipoExc'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoa != ""){
			$where .= " and Contrato.IdPessoa = $IdPessoa";
		}
		
		if($IdTipoExc != ""){
			$where .= " and LocalCobrancaGeracao.IdTipoLocalCobranca != $IdTipoExc";
		}
		
		$sql	=	"select
						Contrato.IdLoja,
						Contrato.IdContrato,
						Contrato.IdPessoa,
						Contrato.IdServico, 
						Contrato.DataInicio,
						Contrato.DataTermino,
						Contrato.DataBaseCalculo,
						Contrato.DataPrimeiraCobranca,
						Contrato.DataUltimaCobranca,
						Contrato.AssinaturaContrato,
						Contrato.IdAgenteAutorizado,
						Contrato.IdCarteira,
						Contrato.IdPeriodicidade,
						Contrato.QtdParcela,
						Contrato.IdLocalCobranca,
						Contrato.DiaCobranca,
						LocalCobrancaGeracao.DescricaoLocalCobranca,
						LocalCobrancaGeracao.IdTipoLocalCobranca,
						LocalCobrancaGeracao.AbreviacaoNomeLocalCobranca,
						Contrato.IdContratoAgrupador,
						Contrato.AdequarLeisOrgaoPublico,
						Contrato.DataCriacao, 
						Contrato.LoginCriacao, 
						Contrato.DataAlteracao, 
						Contrato.LoginAlteracao,
						Contrato.TipoContrato, 
						Contrato.IdStatus,
						Contrato.VarStatus,
						Contrato.Obs,
						Contrato.MesFechado,
						Contrato.QtdMesesFidelidade,
						Contrato.MultaFidelidade
					from 
						Loja,
						Contrato,
						LocalCobrancaGeracao 
					where 
						Contrato.IdLoja = $IdLoja and
						Contrato.IdLoja = Loja.IdLoja and
						Contrato.IdLoja = LocalCobrancaGeracao.IdLoja and
						Contrato.IdLocalCobranca = LocalCobrancaGeracao.IdLocalCobranca
						$where 
					group by
						Contrato.IdLocalCobranca
					order by
						Contrato.IdContrato DESC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
			$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_LocalCobrancaContrato();
?>
