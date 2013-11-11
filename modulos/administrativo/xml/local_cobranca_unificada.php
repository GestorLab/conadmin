<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Local_Cobranca_Unificada(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];		
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdLojaUnificada		= $_GET['IdLojaUnificada'];
		$IdLocalCobranca		= $_GET['IdLocalCobranca'];
			
		$sql	=	"select 
						IdLocalCobranca, 
						DescricaoLocalCobranca 
					from 
						LocalCobranca 
					where 
						IdLoja = $IdLojaUnificada					 
					order by 
						DescricaoLocalCobranca asc ";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin	=	@mysql_fetch_array($res)){	
			if($IdLojaUnificada == $local_IdLoja && $IdLocalCobranca == $lin[IdLocalCobranca]){
			}else{
				$dados	.=	"\n<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";
				$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";						
			}
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Local_Cobranca_Unificada();
?>
