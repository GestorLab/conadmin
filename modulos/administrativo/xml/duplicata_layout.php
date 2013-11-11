<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Duplicata_Layout(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdDuplicataLayout			= $_GET['IdDuplicataLayout'];
		
		$sql ="	select 
					DuplicataLayout.IdDuplicataLayout,
					DuplicataLayout.DescricaoDuplicata 
				from
					DuplicataLayout 
					left join 
						LocalCobranca 
							on(
								LocalCobranca.IdDuplicataLayout = DuplicataLayout.IdDuplicataLayout and
								LocalCobranca.IdLoja = $IdLoja
							) 
				where 
					DuplicataLayout.IdDuplicataLayout = $IdDuplicataLayout
				group by
					IdDuplicataLayout";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin	=	@mysql_fetch_array($res)){
				$dados	.=	"\n<IdDuplicataLayout>$lin[IdDuplicataLayout]</IdDuplicataLayout>";
				$dados	.=	"\n<DescricaoDuplicata><![CDATA[$lin[DescricaoDuplicata]]]></DescricaoDuplicata>";
			}
			if(mysql_num_rows($res) >=1){
				$dados	.=	"\n</reg>";
				return $dados;
			}
		}else{
			return "false";
		}
	}
	echo get_Duplicata_Layout();
?>
