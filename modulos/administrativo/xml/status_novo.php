<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_status_novo(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja							= $_SESSION['IdLoja'];
		$IdGrupoParametroSistema	  	= $_GET['IdGrupoParametroSistema'];
		$IdStatus					  	= $_GET['IdStatus'];
		$where						  	= "";
			
		if($IdGrupoParametroSistema !=''){   $where .= " where IdGrupoParametroSistema = '$IdGrupoParametroSistema'";		}	
		
		if($IdStatus >=200 && $IdStatus <=299){
			$where .= "and IdParametroSistema = '100'";
		}
		
		$sql	=	"select 
						IdParametroSistema,
						ValorParametroSistema
					from 
						ParametroSistema
						$where 
					order by
						IdParametroSistema $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdStatusNovo><![CDATA[$lin[IdParametroSistema]]]></IdStatusNovo>";
			$dados	.=	"\n<SelectStatusNovo><![CDATA[$lin[ValorParametroSistema]]]></SelectStatusNovo>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_status_novo();
?>
