<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoParametroOpcoes(){
		
		global $con;
		global $_GET;
		
		$rotina = 	$_GET['Rotina'];
		
		$resultado = opcoesServicoParametro($rotina);

		$count	=  	count($resultado);
		
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
			
		for($i=0; $i < $count; $i++){
			$Valor	=	trim($resultado[$i]);
		
			$dados	.=	"\n<Valor><![CDATA[$Valor]]></Valor>";
		}
		
		if($count >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_ServicoParametroOpcoes();
?>
