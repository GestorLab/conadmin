<?
	function get_Logomarca_resetar_default(){
	
		$logo_cab		=	"../../../img/personalizacao/logo_cab.gif";
		$logo_princ		=	"../../../img/personalizacao/logo_princ.gif";
		$logo_cab_bkp	=	"../../../img/personalizacao/logo_cab_bkp.gif";
		$logo_princ_bkp	=	"../../../img/personalizacao/logo_princ_bkp.gif";
		
		if(file_exists($logo_cab_bkp) == true && file_exists($logo_princ_bkp) == true){
			@unlink($logo_cab);
			@unlink($logo_princ);
			
			$local_transaction = (@rename($logo_cab_bkp, $logo_cab) && @rename($logo_princ_bkp, $logo_princ));
		}
		
		if($local_transaction == true){
			echo 4;			// Mensagem de Alteraчуo Positiva
		}else{
			echo 4;			// Mensagem de Alteraчуo Negativa
		}
	}
	
	echo get_Logomarca_resetar_default();
?>