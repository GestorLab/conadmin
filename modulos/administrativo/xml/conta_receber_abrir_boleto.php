<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_conta_receber_visualizar_boleto(){
		global $con;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdContaReceber	= $_GET['IdContaReceber'];

		$sql = "select 
					LocalCobranca.IdLocalCobrancaLayout,
					ContaReceber.MD5,
					ContaReceber.IdStatus
				from 
					ContaReceber,
					LocalCobranca	
				where 
					LocalCobranca.IdLoja=$IdLoja and 
					ContaReceber.IdLoja = LocalCobranca.IdLoja and 
					ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
					ContaReceber.IdContaReceber =  $IdContaReceber;";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				switch($lin[IdStatus]){
					case 0:
						$file = "boleto.php";
						$fileurl = $file."?Tipo=pdf&ContaReceber=$lin[MD5]";
						break;
					case 2:
						$file = "boleto.php";
						$fileurl = $file."?Tipo=pdf&ContaReceber=$lin[MD5]";
						break;
					case 3:
						$file = "boleto.php";
						$fileurl = $file."?Tipo=pdf&ContaReceber=$lin[MD5]";
						break;
				}
				
				if(file_exists("../".$file)){
					$Erro = 0;
				} else{
					$Erro = 58;
				}
				
				$dados	.=	"\n<Url><![CDATA[$fileurl]]></Url>";
				$dados	.=	"\n<Erro><![CDATA[$Erro]]></Erro>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	echo get_conta_receber_visualizar_boleto();
?>