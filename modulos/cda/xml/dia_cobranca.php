<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	function get_dia_cobranca(){
		global $con;
		global $_GET;
		
		$IdLoja = $_GET["IdLoja"];
		$sql = "select 
					ValorCodigoInterno 
				from 
					(
						select 
							convert(ValorCodigoInterno,UNSIGNED) ValorCodigoInterno 
						from 
							CodigoInterno 
						where 
							IdLoja = $IdLoja and 
							IdGrupoCodigoInterno = 1
					) CodigoInterno 
				group by 
					ValorCodigoInterno
				order by 
					ValorCodigoInterno
					";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[DescricaoCodigoInterno] = visualizarNumber($lin[ValorCodigoInterno]);
				
				$dados	.=	"\n<ValorCodigoInterno><![CDATA[$lin[ValorCodigoInterno]]]></ValorCodigoInterno>";
				$dados	.=	"\n<DescricaoCodigoInterno><![CDATA[$lin[DescricaoCodigoInterno]]]></DescricaoCodigoInterno>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_dia_cobranca();
?>