<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_FormaAvisoCobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$Login					= $_SESSION["Login"];
		$local_IdLoja			= $_SESSION["IdLoja"];
		
		$local_IdFormaAvisoCobranca	= $_GET['IdFormaAvisoCobranca'];	
		$where						=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($local_IdFormaAvisoCobranca != '')	{	
			$where .= " and FormaAvisoCobranca.IdFormaAvisoCobranca = $local_IdFormaAvisoCobranca"; 	
		}	
				
		$sql	=	"select
						FormaAvisoCobranca.IdFormaAvisoCobranca, 
						FormaAvisoCobranca.DescricaoFormaAvisoCobranca,
						FormaAvisoCobranca.ViaEmail,
						FormaAvisoCobranca.ViaImpressa,
						FormaAvisoCobranca.MarcadorEstrela,
						FormaAvisoCobranca.MarcadorQuadrado,
						FormaAvisoCobranca.MarcadorCirculo,
						FormaAvisoCobranca.MarcadorPositivo,
						FormaAvisoCobranca.IdGrupoUsuarioMonitor,																		
						FormaAvisoCobranca.DataCriacao,
						FormaAvisoCobranca.LoginCriacao,
						FormaAvisoCobranca.DataAlteracao,
						FormaAvisoCobranca.LoginAlteracao,
						GrupoUsuario.DescricaoGrupoUsuario
					from 
						FormaAvisoCobranca
							LEFT JOIN GrupoUsuario ON (FormaAvisoCobranca.IdGrupoUsuarioMonitor = GrupoUsuario.IdGrupoUsuario)						
					where
						FormaAvisoCobranca.IdLoja = $local_IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		if($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdFormaAvisoCobranca>$lin[IdFormaAvisoCobranca]</IdFormaAvisoCobranca>";
			$dados	.=	"\n<IdGrupoUsuarioMonitor>$lin[IdGrupoUsuarioMonitor]</IdGrupoUsuarioMonitor>";
			$dados	.=	"\n<DescricaoFormaAvisoCobranca><![CDATA[$lin[DescricaoFormaAvisoCobranca]]]></DescricaoFormaAvisoCobranca>";
			$dados	.=	"\n<ViaEmail><![CDATA[$lin[ViaEmail]]]></ViaEmail>";
			$dados	.=	"\n<ViaImpressa><![CDATA[$lin[ViaImpressa]]]></ViaImpressa>";
			$dados	.=  "\n<MarcadorEstrela><![CDATA[$lin[MarcadorEstrela]]]></MarcadorEstrela>";
			$dados	.=  "\n<MarcadorQuadrado><![CDATA[$lin[MarcadorQuadrado]]]></MarcadorQuadrado>";
			$dados	.=  "\n<MarcadorCirculo><![CDATA[$lin[MarcadorCirculo]]]></MarcadorCirculo>";
			$dados	.=  "\n<MarcadorPositivo><![CDATA[$lin[MarcadorPositivo]]]></MarcadorPositivo>";
			$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_FormaAvisoCobranca();
?>
