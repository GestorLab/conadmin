<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_NotaFiscal2ViaEletronicaRemessaVerificaTransmissao(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdNotaFiscalLayout	 	= $_GET['IdNotaFiscalLayout'];
		$MesReferencia			= $_GET['MesReferencia'];		
		$Status					= $_GET['Status'];		

		$where			= "";
		
		if($IdNotaFiscalLayout != ''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout=$IdNotaFiscalLayout";	
		}
		if($MesReferencia !=''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.MesReferencia like '$MesReferencia'";	
		}
		if($Status !=''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.Status like '$Status'";	
		}	
		
		$sql	=  "select					
						count(*) Qtd
					from 
						NotaFiscal2ViaEletronicaArquivo
					where
						IdLoja = $local_IdLoja and
						IdNotaFiscalLayout=$IdNotaFiscalLayout and
						MesReferencia like '$MesReferencia' and
						StatusArquivoMestre like 'N' and
						IdStatus = 4";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
			
		if($lin	=	@mysql_fetch_array($res)){
			
			$VerificaTransmicao = 0;
			if($lin[Qtd] == 0 && $Status == 'S'){
				$VerificaTransmicao = 1;
			}		

			$dados	.=	"\n<VerificaTransmicao><![CDATA[$VerificaTransmicao]]></VerificaTransmicao>";			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_NotaFiscal2ViaEletronicaRemessaVerificaTransmissao();
?>