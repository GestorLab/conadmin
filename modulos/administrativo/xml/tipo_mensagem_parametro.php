<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_tipo_mensagem_parametro(){
		
		global $con;
		global $_GET;
		
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdTipoMensagem					= $_GET['IdTipoMensagem'];
		$where							= "";
					
		if($IdTipoMensagem != ''){
			$where	.=	" and IdTipoMensagem = ".$IdTipoMensagem;
		}	
		
		$sql	=	"select	
						IdTipoMensagem,
						IdTipoMensagemParametro,
						DescricaoTipoMensagemParametro,
						ValorTipoMensagemParametro		      	
	 				 from	 				 	
	 				 	TipoMensagemParametro						  
					 where	
					 	IdLoja = $local_IdLoja											
	   				    $where";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdTipoMensagem>$lin[IdTipoMensagem]</IdTipoMensagem>";
			$dados	.=	"\n<IdTipoMensagemParametro><![CDATA[$lin[IdTipoMensagemParametro]]]></IdTipoMensagemParametro>";
			$dados	.=	"\n<DescricaoTipoMensagemParametro><![CDATA[$lin[DescricaoTipoMensagemParametro]]]></DescricaoTipoMensagemParametro>";
			$dados	.=	"\n<ValorTipoMensagemParametro><![CDATA[$lin[ValorTipoMensagemParametro]]]></ValorTipoMensagemParametro>";
		}		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_tipo_mensagem_parametro();
?>
