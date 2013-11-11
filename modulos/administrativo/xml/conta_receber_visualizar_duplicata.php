<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_conta_receber_visualizar_duplicata(){
		global $con;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdContaReceber	= $_GET['IdContaReceber'];
		$local_IdContaReceber	= $_POST['IdContaReceber'];

		$sql = "select 
					 LocalCobranca.IdDuplicataLayout,
					 ContaReceber.MD5 
				from
					 ContaReceber,
					 LocalCobranca 
				where 
                    LocalCobranca.IdLoja = $IdLoja and 
					ContaReceber.IdLoja = LocalCobranca.IdLoja and
					ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ContaReceber.IdContaReceber = $IdContaReceber ;";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin = @mysql_fetch_array($res)){
			$file="../administrativo/duplicata/".$lin[IdDuplicataLayout]."/index.php";
			$file2="index.php";
			$fileurl = $file."?Duplicata=$lin[MD5]";
			
			if(file_exists("../duplicata/".$lin[IdDuplicataLayout]."/".$file2)){
				$Erro = 0;
			} else{
				$Erro = 58;
			}
			
			$dados	.=	"\n<Url><![CDATA[$fileurl]]></Url>";
			$dados	.=	"\n<Erro><![CDATA[$Erro]]></Erro>";
			$dados	.=	"\n<IdDuplicataLayout><![CDATA[".$lin['IdDuplicataLayout']."]]></IdDuplicataLayout>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_conta_receber_visualizar_duplicata();
?>
