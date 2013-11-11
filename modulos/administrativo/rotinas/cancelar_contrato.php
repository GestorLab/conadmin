<?
	$localModulo		=	1;
	$localOperacao		=	2;	

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	$local_IdLoja	=	$_SESSION["IdLoja"];
	$local_Login	= 	$_SESSION["Login"];
	
	$local_DataTermino			= formatText($_POST['DataTermino'],NULL);
	$local_DataUltimaCobranca	= formatText($_POST['DataUltimaCobranca'],NULL);
	$local_Obs					= formatText($_POST['Obs'],NULL);
	$local_IdContrato			= formatText($_POST['IdContrato'],NULL);
	$local_Acao					= $_POST['Acao'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"C") == false){
		$local_Erro = 2;
		header("Location: ../cadastro_cancelar_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro");
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;		
		
		$status	=	"";
		
		if($local_DataTermino == ""){ 			
			$local_DataTermino = 'NULL';  		
		}else{
			$local_DataTermino = dataConv($local_DataTermino,'d/m/Y','Ymd'); 
		}

		if($local_DataUltimaCobranca == ""){
			$local_DataUltimaCobranca	=	'NULL';
		}else{ 	
			$local_DataUltimaCobranca	= 	"'".dataConv($local_DataUltimaCobranca,'d/m/Y','Y-m-d')."'"; 
		}
		
		//Status -> Cancelado
		if($local_DataTermino <= date("Ymd")){
			$status		= ",IdStatus	=	1";
			$cancelar	= true;
		}else{
			$cancelar	= false;
		}		
		
		$sql2	=	"select Obs from Contrato where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato;";
		$res2	=	mysql_query($sql2,$con);
		$lin2	=	mysql_fetch_array($res2);
		
		if($lin2[Obs]!=""){
			$lin2[Obs]	=	"\n".trim($lin2[Obs]);
		}
		
		if($local_Obs != ""){
			$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - ".trim($local_Obs).$lin2[Obs];
		}else{
			$local_Obs	=	trim($lin2[Obs]);
		}
		
		$local_DataTermino = dataConv($local_DataTermino,'Ymd','Y-m-d'); 
				
		$sql	=	"UPDATE Contrato SET
						DataUltimaCobranca			= $local_DataUltimaCobranca,
						DataTermino					= '$local_DataTermino',
						Obs							= '$local_Obs',
						DataAlteracao				= (concat(curdate(),' ',curtime())),
						LoginAlteracao				= '$local_Login' $status
					WHERE 
						IdLoja						= $local_IdLoja and
						IdContrato					= $local_IdContrato;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		
		if($cancelar == true){			
			$sql = "select
						Servico.UrlRotinaCancelamento
					from
						Contrato,
						Servico
					where
						Contrato.IdLoja = $local_IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdContrato = $local_IdContrato and
						Contrato.IdServico = Servico.IdServico";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			if($lin[UrlRotinaCancelamento] != ''){
				include("../".$lin[UrlRotinaCancelamento]);
			}
			$local_Erro = 67;	
		}
		
		
		$local_IdContratoPai	=	$local_IdContrato;
		
		$sql5	=	"select ContratoAutomatico.IdContratoAutomatico IdContrato,Contrato.IdServico from (select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from ContratoAutomatico where ContratoAutomatico.IdLoja = $local_IdLoja and ContratoAutomatico.IdContrato = $local_IdContratoPai) ContratoAutomatico, Contrato where Contrato.IdLoja = $local_IdLoja and Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
		$res5	=	mysql_query($sql5,$con);
		while($lin5 = mysql_fetch_array($res5)){
			$local_IdContrato	=	$lin5[IdContrato];
			
			if($cancelar == true){			
				$sql2 = "select
							Servico.UrlRotinaCancelamento
						from
							Servico
						where
							IdLoja = $local_IdLoja and
							IdServico = $lin5[IdServico]";
				$res2 = mysql_query($sql2,$con);
				$lin2 = mysql_fetch_array($res2);
				
				if($lin[UrlRotinaCancelamento] != ''){
					include("../".$lin[UrlRotinaCancelamento]);
				}
				$local_Erro = 67;	
			}
			
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			mysql_query($sql,$con);	
			
			if($local_Erro == ""){ $local_Erro = 82; }
			
			header("Location: ../cadastro_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro");		
		}else{
			$sql = "ROLLBACK;";
			if($_POST['DataTermino'] < date('Y-m-d')){
				$local_Erro = 68;	
			}else{
				$local_Erro = 83;	
			}
			
			mysql_query($sql,$con);
			
			header("Location: ../cadastro_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro");		
		}
	}
?>
