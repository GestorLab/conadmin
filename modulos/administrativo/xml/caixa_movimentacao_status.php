<?
	$localModulo = 1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include("../../../rotinas/verifica.php");
	
	function get_CaixaMovimentacaoStatus(){
		global $con;
		global $_GET;
		
		$local_IdLoja				= $_SESSION["IdLoja"];
		$local_IdStatus				= $_GET["IdStatus"];
		$where						= "";
		
		if($local_IdStatus != ''){
			$where .= " AND ParametroSistema.IdParametroSistema = '$local_IdStatus'";
		}
		
		$sql = "SELECT 
					ParametroSistema.IdParametroSistema IdStatus,
					ParametroSistema.ValorParametroSistema Status,
					CodigoInterno.ValorCodigoInterno CorStatus
				FROM 
					ParametroSistema,
					CodigoInterno
				WHERE 
					ParametroSistema.IdGrupoParametroSistema = '259' AND 
					SUBSTRING(ParametroSistema.IdParametroSistema, 1, 1) = CodigoInterno.IdCodigoInterno AND 
					CodigoInterno.IdGrupoCodigoInterno = '64'
					$where;";
		$res = mysql_query($sql, $con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			if($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<CorStatus><![CDATA[$lin[CorStatus]]]></CorStatus>";
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_CaixaMovimentacaoStatus();
?>