<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_OperadoraSMSParametro(){
		global $con;
		global $_GET;
		
		$IdLoja			= $_SESSION['IdLoja'];
		$IdOperadora	= $_GET['IdOperadora'];
		$IdContaSMS		= $_GET['IdContaSMS'];
		$where			= "";
		
		if($IdContaSMS != ''){
			$where = " AND ContaSMSParametro.IdContaSMS = $IdContaSMS";
		}
		
		$sql = "SELECT 
					OperadoraSMSParametro.IdParametroOperadoraSMS,
					OperadoraSMSParametro.DescricaoParametroOperadoraSMS,
					OperadoraSMSParametro.ValorParametroOperadoraSMS ValorParametroSMSDefault,
					OperadoraSMSParametro.Obrigatorio,
					ContaSMSParametro.ValorParametroSMS
				FROM
					OperadoraSMSParametro LEFT JOIN ContaSMSParametro ON(
						ContaSMSParametro.IdLoja = $IdLoja AND
						ContaSMSParametro.IdOperadora = OperadoraSMSParametro.IdOperadora AND
						ContaSMSParametro.IdParametroOperadoraSMS = OperadoraSMSParametro.IdParametroOperadoraSMS 
						$where
					)
				WHERE
					OperadoraSMSParametro.IdOperadora = $IdOperadora";
		
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			@header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados 	.= "\n<IdOperadora>$lin[IdOperadora]</IdOperadora>";
				$dados 	.= "\n<IdParametroOperadoraSMS>$lin[IdParametroOperadoraSMS]</IdParametroOperadoraSMS>";
				$dados	.= "\n<DescricaoParametroOperadoraSMS><![CDATA[$lin[DescricaoParametroOperadoraSMS]]]></DescricaoParametroOperadoraSMS>";
				$dados	.= "\n<ValorParametroSMSDefault><![CDATA[$lin[ValorParametroSMSDefault]]]></ValorParametroSMSDefault>";
				$dados	.= "\n<ValorParametroSMS><![CDATA[$lin[ValorParametroSMS]]]></ValorParametroSMS>";
				$dados	.= "\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";
			}
			
			$dados .= "\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_OperadoraSMSParametro();
?>