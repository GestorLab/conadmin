<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_DatasEspeciais(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$Data					= $_GET['Data'];
		$DescricaoData			= $_GET['DescricaoData'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
	
		if($DescricaoData !=''){
			$where  .= " and DescricaoData like '%$DescricaoData%'";	 
		}

		if($Data!= ''){
			$where	.=	" and Data = '".$Data."'";
		}
		
		$sql	=	"SELECT  
					    Data,
						TipoData,
						DescricaoData,
						DataAlteracao,
				      	LoginAlteracao,
				      	DataCriacao,
				      	LoginCriacao 
					from
					    DatasEspeciais 
					where
						IdLoja = $IdLoja $where $Limit ";
						
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=52 and IdParametroSistema=$lin[TipoData]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
											
			$lin2[ValorParametroSistema]	=	explode("\n",$lin2[ValorParametroSistema]);
			$lin[Cor]						=	$lin2[ValorParametroSistema][1];
		
			$dados	.=	"\n<Data><![CDATA[$lin[Data]]]></Data>";
			$dados	.=	"\n<TipoData><![CDATA[$lin[TipoData]]]></TipoData>";
			$dados	.=	"\n<DescricaoData><![CDATA[$lin[DescricaoData]]]></DescricaoData>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_DatasEspeciais();
?>
