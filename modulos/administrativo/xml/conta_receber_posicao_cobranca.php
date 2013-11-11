<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Conta_Receber_Posicao_Cobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja							= $_SESSION['IdLoja'];
		$IdGrupoParametroSistema		= $_GET['IdGrupoParametroSistema'];
		$IdParametroSistema 			= $_GET['IdParametroSistema'];
		$IdContaReceber		 			= $_GET['IdContaReceber'];
		
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoParametroSistema != ''){				$where .= " and ParametroSistema.IdGrupoParametroSistema=$IdGrupoParametroSistema";	}	
				
		$sql	=  "select
					     ParametroSistema.IdParametroSistema,
						 ParametroSistema.ValorParametroSistema					
					from 
					     ParametroSistema
					where
					    ParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema 
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
			$dados	.=	"\n<IdParametroSistema>$lin[IdParametroSistema]</IdParametroSistema>";
			$dados	.=	"\n<ValorParametroSistema><![CDATA[$lin[ValorParametroSistema]]]></ValorParametroSistema>";			
		}
		if(mysql_num_rows($res) >=1){
			$PosicaoCobranca = "";
			$PosicaoCobrancaTemp = "";
			$DataRemessa = "";
			
			$aux = "";

			$sql = "select
						distinct
						IdPosicaoCobranca,
						DataRemessa
					from
						ContaReceberPosicaoCobranca
					where
						IdLoja				= $IdLoja and
						IdContaReceber		= $IdContaReceber";
			$res2	=	mysql_query($sql,$con);
			while($lin2	=	@mysql_fetch_array($res2)){
				if($lin2[IdPosicaoCobranca] == 2){
					$aux = 1;
				}
				if($PosicaoCobranca == ""){
					$PosicaoCobranca = $lin2[IdPosicaoCobranca];
					$PosicaoCobrancaTemp = $lin2[IdPosicaoCobranca];
				}else{
					if($lin2[IdPosicaoCobranca] == 3 && $aux == 1){
						$PosicaoCobranca = str_replace("2", "_", $PosicaoCobranca);		
						$PosicaoCobranca .= '_'.$lin2[IdPosicaoCobranca];
						$PosicaoCobrancaTemp = $lin2[IdPosicaoCobranca];
					}else{
						$PosicaoCobranca .= "_".$lin2[IdPosicaoCobranca];
						$PosicaoCobrancaTemp = $lin2[IdPosicaoCobranca];
					}
				}
				$DataRemessa = $lin2[DataRemessa];
			}
			
			$dados	.=	"\n<PosicaoCobranca><![CDATA[$PosicaoCobranca]]></PosicaoCobranca>";
			$dados	.=	"\n<PosicaoCobrancaTemp><![CDATA[$PosicaoCobrancaTemp]]></PosicaoCobrancaTemp>";
			$dados	.=	"\n<DataRemessa><![CDATA[$DataRemessa]]></DataRemessa>";
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Conta_Receber_Posicao_Cobranca();
?>