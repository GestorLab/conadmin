<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Link(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdLink					= $_GET['IdLink'];
		$DescricaoLink			= $_GET['DescricaoLink'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($DescricaoLink!='' || $IdLink!=''){
			$where	.=	" where";
		}	
		
		if($DescricaoLink !=''){
			$where  .= " DescricaoLink like '$DescricaoLink%'";	 
		}
		if($DescricaoLink!='' && $IdLink!=''){
			$where	.=	" and ";
		}
		
		if($IdLink!= ''){
			$where	.=	" IdLink = ".$IdLink;
		}
		$sql	=	"SELECT  
					    IdLink,
						DescricaoLink,
						Link,
						DataAlteracao,
				      	LoginAlteracao,
				      	DataCriacao,
				      	LoginCriacao 
					from
					    Link $where $Limit";
						
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLink>$lin[IdLink]</IdLink>";
			$dados	.=	"\n<DescricaoLink><![CDATA[$lin[DescricaoLink]]]></DescricaoLink>";
			$dados	.=	"\n<Link><![CDATA[$lin[Link]]]></Link>";
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
	echo get_Link();
?>
