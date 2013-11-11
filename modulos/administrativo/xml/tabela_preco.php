<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_tabela_preco(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdTabelaPreco	 		= $_GET['IdTabelaPreco'];
		$DescricaoTabelaPreco  	= $_GET['DescricaoTabelaPreco'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdTabelaPreco != ''){	
			$where .= " and IdTabelaPreco=$IdTabelaPreco";	
		}
		if($DescricaoTabelaPreco !=''){	
			$where .= " and DescricaoTabelaPreco like '$DescricaoTabelaPreco%'";	
		}
		
		$sql	=	"select
						IdTabelaPreco, 
						DescricaoTabelaPreco, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						TabelaPreco
					where
						IdLoja = $IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdTabelaPreco>$lin[IdTabelaPreco]</IdTabelaPreco>";
			$dados	.=	"\n<DescricaoTabelaPreco><![CDATA[$lin[DescricaoTabelaPreco]]]></DescricaoTabelaPreco>";
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
	echo get_tabela_preco();
?>
