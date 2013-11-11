<?	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_DataBaseCalculo!= "" && $local_DataBaseCalculo!= "NULL"){
			$local_DataBaseCalculo	=	dataConv($local_DataBaseCalculo,'d/m/Y','Y-m-d');
		}
		$local_DataInicio			=	dataConv($local_DataInicio,'d/m/Y','Y-m-d');
		$local_DataPrimeiraCobranca	=	dataConv($local_DataPrimeiraCobranca,'d/m/Y','Y-m-d');
		
		if($local_DataTermino!= ""){
			$local_DataTermino	=	dataConv($local_DataTermino,'d/m/Y','Y-m-d');
		}
		if($local_DataUltimaCobranca!= "" && $local_DataUltimaCobranca!= "NULL"){
			$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobranca,'d/m/Y','Y-m-d');
		}
		
		$sql	=	"select	
						Obs,
						DataBaseCalculo,
						DataInicio,
						DataTermino,
						DataPrimeiraCobranca,
						DataUltimaCobranca 
					from 
						Contrato 
					where
						IdLoja = $local_IdLoja and 
						IdContrato = $local_IdContrato";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		$temp	=	"";
		$temp2	=	"";
		if($lin[DataPrimeiraCobranca]!=$local_DataPrimeiraCobranca){
			if($local_Obs != "") $local_Obs .= "\n";
			
			$temp2	=	dataConv($lin[DataPrimeiraCobranca],'Y-m-d','d/m/Y');
			$temp	=	dataConv($local_DataPrimeiraCobranca,'Y-m-d','d/m/Y');
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Primeira Cob. [$temp2 > $temp]";
			
			$sql3	=	"select 
								ContratoVigenciaAtiva.IdContrato,
								ContratoVigenciaAtiva.DataInicio
							from 
								ContratoVigenciaAtiva
							where 
								ContratoVigenciaAtiva.IdLoja = $local_IdLoja and 
								ContratoVigenciaAtiva.IdContrato = $local_IdContrato";
			$res3	=	mysql_query($sql3,$con);
			while($lin3	=	mysql_fetch_array($res3)){
				$sql	=	"UPDATE ContratoVigencia SET 
								DataInicio 					= '".$local_DataPrimeiraCobranca."',
								DataAlteracao				= (concat(curdate(),' ',curtime())),
								LoginAlteracao				= '$local_Login'
							WHERE 
								IdLoja						= $local_IdLoja and
								(IdContrato = $lin3[IdContrato] or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $lin3[IdContrato])) and
								DataInicio 					= '$lin3[DataInicio]';";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}	
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($lin[DataInicio]!=$local_DataInicio){
			$temp2	=	dataConv($lin[DataInicio],'Y-m-d','d/m/Y');
			$temp	=	dataConv($local_DataInicio,'Y-m-d','d/m/Y');
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Início Cont. [$temp2 > $temp]";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($lin[DataBaseCalculo]!=$local_DataBaseCalculo){
			if($local_Obs != "") $local_Obs .= "\n";
			
			$temp2	=	dataConv($lin[DataBaseCalculo],'Y-m-d','d/m/Y');
			$temp	=	dataConv($local_DataBaseCalculo,'Y-m-d','d/m/Y');
			
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Base [$temp2 > $temp]";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($lin[DataTermino]!=$local_DataTermino){
			if($local_Obs != "") $local_Obs .= "\n";
			
			$temp2	=	dataConv($lin[DataTermino],'Y-m-d','d/m/Y');
			$temp	=	dataConv($local_DataTermino,'Y-m-d','d/m/Y');
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > $temp]";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($lin[DataUltimaCobranca]!=$local_DataUltimaCobranca){
			if($local_Obs != "") $local_Obs .= "\n";
			
			$temp2	=	dataConv($lin[DataUltimaCobranca],'Y-m-d','d/m/Y');
			$temp	=	dataConv($local_DataUltimaCobranca,'Y-m-d','d/m/Y');
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Última Cob. [$temp2 > $temp]";
		}
		
		
		if($local_Obs!= ""){
			$local_Obs	.=	"\n".trim($lin[Obs]);
		}else{
			$local_Obs	=	trim($lin[Obs]);
		}
		
		if($lin[DataBaseCalculo] == ""){		$lin[DataBaseCalculo]		=	"NULL";		}
		if($lin[DataInicio] == ""){				$lin[DataInicio]			=	"NULL";		}
		if($lin[DataTermino] == ""){			$lin[DataTermino]			=	"NULL";		}
		if($lin[DataPrimeiraCobranca] == ""){	$lin[DataPrimeiraCobranca]	=	"NULL";		}
		if($lin[DataUltimaCobranca] == ""){		$lin[DataUltimaCobranca]	=	"NULL";		}
		
		if($local_DataBaseCalculo!= "NULL" && $local_DataBaseCalculo!= ""){				
			$local_DataBaseCalculo		=	"'".$local_DataBaseCalculo."'";				
		}else{
			$local_DataBaseCalculo		=	"NULL";
		}
		if($local_DataInicio!= "NULL" && $local_DataInicio!= ""){						
			$local_DataInicio			=	"'".$local_DataInicio."'";					
		}else{
			$local_DataInicio			=	"NULL";	
		}
		if($local_DataPrimeiraCobranca!= "NULL" && $local_DataPrimeiraCobranca!= ""){	
			$local_DataPrimeiraCobranca	=	"'".$local_DataPrimeiraCobranca."'";		
		}else{
			$local_DataPrimeiraCobranca	=	"NULL";
		}
		if($local_DataTermino!= "NULL" && $local_DataTermino!= ""){						
			$local_DataTermino			=	"'".$local_DataTermino."'";					
		}else{
			$local_DataTermino			=	"NULL";	
		}
		if($local_DataUltimaCobranca!= "NULL" && $local_DataUltimaCobranca!= ""){		
			$local_DataUltimaCobranca	=	"'".$local_DataUltimaCobranca."'";		
		}else{
			$local_DataUltimaCobranca	=	"NULL";
		}
		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sql	=	"UPDATE Contrato SET
						DataBaseCalculo			= $local_DataBaseCalculo,
						DataInicio				= $local_DataInicio,
						DataTermino				= $local_DataTermino,
						DataUltimaCobranca		= $local_DataUltimaCobranca,
						DataPrimeiraCobranca	= $local_DataPrimeiraCobranca,
						Obs						= '$local_Obs',
						DataAlteracao			= (concat(curdate(),' ',curtime())),
						LoginAlteracao			= '$local_Login'
					WHERE 	
						IdLoja					= $local_IdLoja and
						(IdContrato = $local_IdContrato or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato));";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
				
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			mysql_query($sql,$con);
			
			$local_Erro = 4;			// Mensagem de Inserção Positiva
			
			header("Location: cadastro_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro");
		}else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$con);
			
			$local_Erro = 5;			// Mensagem de Inserção Negativa
			
		}
	}
	
?>
