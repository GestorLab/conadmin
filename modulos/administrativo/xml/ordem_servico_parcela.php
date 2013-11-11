<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ordem_servico_parcela(){
		
		global $con;
		global $_GET;
		
		$local_IdLoja					= $_SESSION["IdLoja"];	
		$Limit 							= $_GET['Limit'];
		$IdOrdemServico					= $_GET['IdOrdemServico'];
		$IdOrdemServicoParcela		  	= $_GET['IdOrdemServicoParcela'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdOrdemServico!= ''){
			$where	.=	" and OrdemServicoParcela.IdOrdemServico = ".$IdOrdemServico;
		}
		if($IdOrdemServicoParcela !=''){	 				 
			$where  .= " and OrdemServicoParcela.IdOrdemServicoParcela = '$IdOrdemServicoParcela%'";	 
		}
		
		
		$sql	=	"select
				      OrdemServicoParcela.IdOrdemServico,
				      IdOrdemServicoParcela,
				      OrdemServicoParcela.Valor,
				      OrdemServicoParcela.ValorDespesaLocalCobranca,
				      OrdemServicoParcela.Vencimento,
				      OrdemServicoParcela.MesReferencia,
				      OrdemServico.ValorTotal,
				      OrdemServico.FormaCobranca
				from
					  OrdemServico,	
				      OrdemServicoParcela
				where	
					  OrdemServico.IdLoja = $local_IdLoja and
					  OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and
					  OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[FormaCobranca]==1){
				$lin[Vencimento]	=	$lin[MesReferencia];
			}
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<IdOrdemServicoParcela><![CDATA[$lin[IdOrdemServicoParcela]]]></IdOrdemServicoParcela>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
			$dados	.=	"\n<Vencimento><![CDATA[$lin[Vencimento]]]></Vencimento>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<FormaCobranca><![CDATA[$lin[FormaCobranca]]]></FormaCobranca>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ordem_servico_parcela();
?>