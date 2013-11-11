<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_operacao(){
		
		global $con;
		global $_GET;
		
		$IdLoja		= $_GET['IdLoja'];
		$IdModulo	= $_GET['IdModulo'];
		
		$i=0;

		$sql	=	"select
					    Operacao.IdOperacao,
					    Operacao.NomeOperacao
					from
					    Operacao
					where
					    Operacao.IdModulo=$IdModulo and
					    Operacao.Restringir = 2
					order by
						Operacao.NomeOperacao";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$IdOperacao[$i] 	= $lin[IdOperacao];
			$NomeOperacao[$i] 	= $lin[NomeOperacao];
			$i++;	
		}	
		
		if($i>0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		for($ii=0;$ii<$i;$ii++){	
			$dados	.=	"\n<IdOperacao>$IdOperacao[$ii]</IdOperacao>";
			$dados	.=	"\n<NomeOperacao><![CDATA[$NomeOperacao[$ii]]]></NomeOperacao>";
		}
		
		if($i>0){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_operacao();
?>
