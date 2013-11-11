<?
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../rotinas/verifica.php');
	
	function get_pessoa_loja(){
		global $conCNT;
		global $_GET;
	
		$local_IdLoja	= $_SESSION['IdLojaHD'];
		
		$sql ="	select 
					IdPessoa 
				from
					Loja 
				where
					IdLoja = $local_IdLoja";
		$res	= @mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	= @mysql_fetch_array($res)){
			if(getParametroSistema(229,1) == 1){
				$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			}else{
				$dados	.=	"\n<IdPessoa></IdPessoa>";
			}
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_pessoa_loja();
?>