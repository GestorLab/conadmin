<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_cabo(){
		global $con;
		
		$IdCabo			= $_GET['IdCabo'];	
		$where = "";
		
		if($IdCabo != ""){
			$where = "IdCabo = $IdCabo";
		
			
			//Info Tipo de Poste
			$sql = "SELECT
						IdCabo,
						IdTipoCabo,
						NomeCabo,
						Especificacao,
						Cor,
						EspessuraVisual,
						Opacidade,
						QtdFibra,
						Oculto,
						DataInstalacao,
						LoginCriacao,
						DataCriacao,
						LoginAlteracao,
						DataAlteracao
					FROM
						Cabo 
					WHERE 
						$where";
			$res 	= mysql_query($sql,$con);		
		

			//Montar XML
			if(mysql_num_rows($res) > 0){	
				header ("content-type: text/xml");					
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";	
				while($lin	= mysql_fetch_array($res)){						
					$dados	.=	"\n<IdCabo><![CDATA[$lin[IdCabo]]]></IdCabo>";	
					$dados	.=	"\n<IdTipoCabo><![CDATA[$lin[IdTipoCabo]]]></IdTipoCabo>";	
					$dados	.=	"\n<NomeCabo><![CDATA[$lin[NomeCabo]]]></NomeCabo>";	
					$dados	.=	"\n<Especificacao><![CDATA[$lin[Especificacao]]]></Especificacao>";	
					$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";	
					$dados	.=	"\n<EspessuraVisual><![CDATA[$lin[EspessuraVisual]]]></EspessuraVisual>";	
					$dados	.=	"\n<Opacidade><![CDATA[$lin[Opacidade]]]></Opacidade>";	
					$dados	.=	"\n<Oculto><![CDATA[$lin[Oculto]]]></Oculto>";	
					$dados	.=	"\n<QtdFibra><![CDATA[$lin[QtdFibra]]]></QtdFibra>";	
					$dados	.=	"\n<QtdFibra><![CDATA[$lin[QtdFibra]]]></QtdFibra>";	
					
					$lin[DataInstalacao] = dataConv($lin[DataInstalacao],'Y-m-d','d/m/Y');	
					$dados	.=	"\n<DataInstalacao><![CDATA[$lin[DataInstalacao]]]></DataInstalacao>";	
					
					
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