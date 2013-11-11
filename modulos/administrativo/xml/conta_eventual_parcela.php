<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_conta_eventual(){
		
		global $con;
		global $_GET;
		
		$local_IdLoja					= $_SESSION["IdLoja"];	
		$Limit 							= $_GET['Limit'];
		$IdContaEventual				= $_GET['IdContaEventual'];
		$IdContaEventualParcela		  	= $_GET['IdContaEventualParcela'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdContaEventual!= ''){
			$where	.=	" and ContaEventualParcela.IdContaEventual = ".$IdContaEventual;
		}
		if($DescricaoContaEventual !=''){	 				 
			$where  .= " and ContaEventualParcela.IdContaEventualParcela = '$IdContaEventualParcela%'";	 
		}
		
		
		$sql	=	"select
				      ContaEventualParcela.IdContaEventual,
				      IdContaEventualParcela,
				      Valor,
				      ContaEventualParcela.ValorDespesaLocalCobranca,
				      ContaEventualParcela.Vencimento,
				      ContaEventualParcela.MesReferencia,
				      ContaEventual.ValorTotal,
				      ContaEventual.FormaCobranca
				from
					  ContaEventual,	
				      ContaEventualParcela
				where	
					  ContaEventual.IdLoja = $local_IdLoja and
					  ContaEventual.IdLoja = ContaEventualParcela.IdLoja and
					  ContaEventual.IdContaEventual = ContaEventualParcela.IdContaEventual $where $Limit";
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
			
			$dados	.=	"\n<IdContaEventual>$lin[IdContaEventual]</IdContaEventual>";
			$dados	.=	"\n<IdContaEventualParcela><![CDATA[$lin[IdContaEventualParcela]]]></IdContaEventualParcela>";
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
	echo get_conta_eventual();
?>

