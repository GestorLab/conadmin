<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_DataHora(){
		global $_GET;
		
		$Data				= $_GET['Data'];
		$Hora  				= $_GET['Hora'];		
		$Error	= 0;
		
		if($Data!=""){
			$Data	= dataConv("$Data", "d/m/Y", "Ymd");
		} else{
			$Data = date("Ymd");
		}
		
		$DataHoraTemp = date("YmdHis");
		
		if($Data<date("Ymd")){
			$IdTemp	 = "titData";
			$IdTemp2 = "titData2";
			$Error	 = 27;
		}
		
		if($Hora!=""){
			$Hora = str_replace(":", "", "$Hora:".date("s"));
		} else{
			$Hora = date("His")+1;
		}
		
		if($Hora<=date("His") && $Error==0){
			$IdTemp	 = "titHora";
			$IdTemp2 = "titHora2";
			$Error	 = 28;
		}
		
		$DataHora = $Data.$Hora;
		
		if($DataHora > $DataHoraTemp){
			return "true";
		} else{
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$dados	.=	"\n<Error>$Error</Error>";
			$dados	.=	"\n<IdTemp>$IdTemp</IdTemp>";
			$dados	.=	"\n<IdTemp2>$IdTemp2</IdTemp2>";
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_DataHora();
?>