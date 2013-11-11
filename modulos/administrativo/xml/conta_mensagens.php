<?
	$localModulo	=	1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	@include("../../../rotinas/verifica.php");
	
	function getContaMensagens(){
		global $con;
		global $_GET;
		
		$IdLoja 	= $_SESSION["IdLoja"];
		$Template 	= $_GET['IdTemplate'];
		$sql = "";

		switch($Template){
			case 4:
				$sql = "Select
							ContaSMS.IdContaSMS Conta,
							ContaSMS.DescricaoContaSMS Descricao						
						From
							ContaSMS
						Where
						IdLoja = $IdLoja 
						ORDER BY DescricaoContaSMS ";
			break;
			case '':
				break;
			default:
				$sql = "SELECT 
							IdContaEmail Conta,
							  CONCAT(
							DescricaoContaEmail,
							' (',
							Usuario,
							')'
							  ) Descricao 
						FROM
							ContaEmail 
						WHERE IdLoja = $IdLoja 
						ORDER BY DescricaoContaEmail ";
			break;
		}		
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			@header ("content-type: text/xml");
			$dados	.=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			echo "false";
		}
		while($lin = @mysql_fetch_array($res)){
			$dados 	.= "\n<IdConta><![CDATA[$lin[Conta]]]></IdConta>";
			$dados	.=	"\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";			
		}
		if(@mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
		}
		echo $dados;		
	}
	getContaMensagens();
 ?>