<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_cabo(){
		global $con;
		
		$IdCaboTipo			= $_GET['IdCaboTipo'];	
		$where = "";
		
		if($IdCaboTipo != ""){
			$where = "IdCaboTipo = $IdCaboTipo";		
			
			//Info Tipo de Poste
			$sql = "SELECT
						IdCaboTipo,
						DescricaoCaboTipo,
						SiglaCaboTipo,
						Oculto,
						LoginCriacao,
						DataCriacao,
						LoginAlteracao,
						DataAlteracao
					FROM 
						CaboTipo
					WHERE
						$where";
			$res 	= mysql_query($sql,$con);		
		

			//Montar XML
			if(mysql_num_rows($res) > 0){	
				header ("content-type: text/xml");					
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";	
				while($lin	= mysql_fetch_array($res)){						
					$dados	.=	"\n<IdCaboTipo><![CDATA[$lin[IdCaboTipo]]]></IdCaboTipo>";	
					$dados	.=	"\n<DescricaoCaboTipo><![CDATA[$lin[DescricaoCaboTipo]]]></DescricaoCaboTipo>";	
					$dados	.=	"\n<SiglaCaboTipo><![CDATA[$lin[SiglaCaboTipo]]]></SiglaCaboTipo>";	
					$dados	.=	"\n<Oculto><![CDATA[$lin[Oculto]]]></Oculto>";	
					
					
					$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";			
					$lin[DataCriacao] = dataConv($lin[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s');				
					$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";		
					$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";				
					if($lin[DataAlteracao] == "" || $lin[DataAlteracao] == "0000-00-00 00:00:00"){
						$lin[DataAlteracao] = "";
					}				
					$lin[DataAlteracao] = dataConv($lin[DataAlteracao],'Y-m-d H:i:s','d/m/Y H:i:s');				
					$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";						
				}
				
				if(mysql_num_rows($res) >=1){
					$dados	.=	"\n</reg>";					
				}
			}else{
				$dados = "false";
			}		
			
			return $dados;			
		}
	}

	echo get_cabo();

?>