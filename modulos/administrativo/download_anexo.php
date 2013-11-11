<?
	$localModulo		=	1;
	$localOperacao		=	65;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_MD5 		= $_GET["Anexo"];	
	$local_Arquivo  = $_GET["Arquivo"];
	
	if($local_Arquivo == true){
		$sql = "select
					IdTicket,
					IdTicketHistorico,
					IdAnexo,
					MD5,
					NomeOriginal,
					FileAnexo
				from
					HelpDeskAnexo
				where
					MD5 = '$local_MD5'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$local_Arquivo  ="../helpdesk/anexo/".$lin[IdTicket]."/".$lin[MD5];
		$Ext = endArray(explode(".",$lin[NomeOriginal]));
		$Arquivo = "ArquivoDeskTemp.".$Ext;
		if(file_exists($local_Arquivo)){
			copy($local_Arquivo,$Arquivo);	
		}
		
		header('Content-Description: File Transfer');
		header("Content-type: application/$Ext");
		header("Content-Disposition: attachment; filename=$lin[NomeOriginal]");
		readfile($Arquivo);
		
		unlink($Arquivo);
	}else{
		$sql =  "select
					IdTicket,
					IdTicketHistorico,
					IdAnexo,
					NomeOriginal,
					FileAnexo
				from
					HelpDeskAnexo
				where
					MD5 = '$local_MD5'";
		$res = mysql_query($sql,$conCNT);
		if($lin = mysql_fetch_array($res)){
			$Ext = endArray(explode(".",$lin[NomeOriginal]));

			$lin[NomeOriginal] = str_replace(" ","_",$lin[NomeOriginal]);
			
			header("Content-type: application/$Ext");
			header("Content-Disposition: attachment; filename=$lin[NomeOriginal]");
			die($lin[FileAnexo]);
		}
	}
?>