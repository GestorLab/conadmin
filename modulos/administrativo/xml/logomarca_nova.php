<?
	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;
	
	if($local_Largura != ""){
		$width = $local_Largura."px";
	}
	if($local_MargemEsquerda != ""){
		$marginLeft = $local_MargemEsquerda."px";
	}
	if($local_MargemSuperior != ""){
		$marginTop = $local_MargemSuperior."px";
	}
	$ValorParametroSistema = "width: $width; float: Left; margin-left: $marginLeft; margin-top: $marginTop";
	
	$sql = "update ParametroSistema set 
				 ValorParametroSistema = '$ValorParametroSistema'
			where 
				IdGrupoParametroSistema = 4 and 
				IdParametroSistema = 2";
	$local_transaction[$tr_i]	=	mysql_query($sql,$con);
	$tr_i++;
			
	$temp_EndArquivo		=	$_FILES['EndArquivo']['name'];
	$temp_tempEndArquivo	=	$_POST['tempEndArquivo'];
	$temp_ExtArquivo		=	@strtolower(end(explode(".",$temp_tempEndArquivo)));
	$local_NomeArquivo		=	@end(explode("\\",$temp_tempEndArquivo));
	
	$logo_url 				= 	explode("logo_cab.gif",getParametroSistema(130,4));
	
	$logo_cab				=	$logo_url[0]."logo_cab.".$temp_ExtArquivo;
	$logo_princ				=	$logo_url[0]."logo_princ.".$temp_ExtArquivo;
	$logo_cab_bkp			=	$logo_url[0]."logo_cab_bkp.".$temp_ExtArquivo;
	$logo_princ_bkp			=	$logo_url[0]."logo_princ_bkp.".$temp_ExtArquivo;
	
	if($local_fakeupload != ""){
		$tam = getimagesize($_FILES['EndArquivo']['tmp_name']);
		$Largura = $tam[0];
		$Altura  = $tam[1];
		
		if($Largura <= 200 && $Altura <= 40){
			if(file_exists($logo_cab_bkp) == true && file_exists($logo_princ_bkp) == true){
				$local_transaction[$tr_i] = (copy($_FILES['EndArquivo']['tmp_name'],$logo_cab) && copy($_FILES['EndArquivo']['tmp_name'],$logo_princ));
				$tr_i++;
			} else{
				$local_transaction[$tr_i] =	(rename($logo_cab, $logo_cab_bkp) && rename($logo_princ, $logo_princ_bkp));
				//$tr_i++;
				
				//if($local_transaction[$tr_i]){
					$local_transaction[$tr_i] = (copy($_FILES['EndArquivo']['tmp_name'],$logo_cab) && copy($_FILES['EndArquivo']['tmp_name'],$logo_princ));
					$tr_i++;
				//}
			}
		} else{
			$local_transaction = false;
			$erro = 179;
		}
	}
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
			if($erro ==""){
				$erro = 5;
			}
		}
	}
	
	if($local_transaction == true){
		$sql = "COMMIT;";
		$local_Erro = 4;			// Mensagem de Alteraчуo Positiva
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = $erro;			// Mensagem de Alteraчуo Negativa
	}
	mysql_query($sql,$con);
?>