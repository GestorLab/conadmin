<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"M") == false){
		$local_Erro = 2;
	}else{		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$contrato	=	explode(',',$local_Contratos);
		
		$cont	=	0;
		while($cont < sizeof($contrato)){
			$Obs = "";
			$local_IdContrato = $contrato[$cont];
			$cancelar	= true;

			//$local_IdStatusTemp			=	$local_IdStatus;
			
			$sqlContrato = "select 
								Obs,
								IdServico,
								IdStatus,
								DataTermino,
								DataUltimaCobranca,
								DataBaseCalculo
							from 
								Contrato 
							where 
								IdLoja = $local_IdLoja and 
								IdContrato = $local_IdContrato;";
	
			$resContrato = mysql_query($sqlContrato,$con);
			$linContrato = mysql_fetch_array($resContrato);
			
			if($linContrato[IdStatus] != $local_IdStatus){
				switch($local_IdStatus){
					case '1': //Status -> Cancelado
						$local_DataTermino			=	dataConv($local_DataTerminoStatus,"d/m/Y","Y-m-d");
						$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobrancaStatus,"d/m/Y","Y-m-d");
						
						if(dataConv($local_DataTermino,"Y-m-d","Ymd") <= date("Ymd")){
							$cancelar	= true;
							
							
							$local_DataTermino			=	dataConv($local_DataTerminoStatus,"d/m/Y","Y-m-d");
							$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobrancaStatus,"d/m/Y","Y-m-d");
						}else{
							$cancelar	= false;
							
							$local_IdStatus				= 	$linContrato[IdStatus];
							$local_DataTermino			=	dataConv($local_DataTerminoStatus,"d/m/Y","Y-m-d");
							$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobrancaStatus,"d/m/Y","Y-m-d");
						}
						break;
					case '200':
						$local_VarStatus				=	dataConv($local_DataBloqueioStatus,'d/m/Y','Y-m-d');
						
						if($local_VarStatus!= ""){
							$local_DataTerminoVigencia		=	incrementaData($local_VarStatus,'-1');
							$local_IdContratoTipoVigencia 	= 	getCodigoInterno(3,46);
							
							$sql2	=	"select 
											DataInicio,
											DataTermino,
											IdTipoDesconto,
											IdContratoTipoVigencia,
											ValorDesconto,
											Valor,
											ValorRepasseTerceiro,
											LimiteDesconto
										from 
											ContratoVigenciaAtiva 
										where 
											IdLoja 	= $local_IdLoja and
											IdContrato	= $local_IdContrato";
							$res2	=	@mysql_query($sql2,$con);
							while($lin2	=	@mysql_fetch_array($res2)){
								#Criar nova vigência com valor atual a partir da data de reativação com os valores normais
								$sql	=	"
									INSERT INTO ContratoVigencia SET 
										IdLoja					= $local_IdLoja,
										IdContrato				= $local_IdContrato, 
										IdTipoDesconto			= '$lin2[IdTipoDesconto]', 
										IdContratoTipoVigencia	= '$lin2[IdContratoTipoVigencia]',
										ValorDesconto			= '$lin2[ValorDesconto]', 
										DataInicio				= '$local_VarStatus',
										DataTermino				= NULL,
										Valor					= '$lin2[Valor]',
										ValorRepasseTerceiro	= '$lin2[ValorRepasseTerceiro]',
										LimiteDesconto			= '$lin2[LimiteDesconto]',
										DataCriacao				= (concat(curdate(),' ',curtime())),
										LoginCriacao			= '$local_Login';";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
								
								#colocar data de término igual a data base para a vigência que estiver em aberto
								$sql	=	"
										UPDATE ContratoVigencia SET 
											DataTermino			= '$local_DataTerminoVigencia',
											DataAlteracao		= (concat(curdate(),' ',curtime())),
											LoginAlteracao		= '$local_Login'
										WHERE 
											IdLoja				= $local_IdLoja and
											IdContrato			= $local_IdContrato and
											DataInicio			= '$lin2[DataInicio]';";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
							}
							
							if($linContrato[DataBaseCalculo]==""){
								$linContrato[DataBaseCalculo]	=	'NULL';
							}else{
								$linContrato[DataBaseCalculo]	=	"'".$linContrato[DataBaseCalculo]."'";
							}
							
							
							#insira uma vigência com data de início "data base" e "data término" um dia antes da data de reativação, com valor zerado.
							$sql	=	"
								INSERT INTO ContratoVigencia SET 
									IdLoja					= $local_IdLoja,
									IdContrato				= $local_IdContrato, 
									IdTipoDesconto			= 'NULL', 
									IdContratoTipoVigencia	= $local_IdContratoTipoVigencia,
									ValorDesconto			= '0.00', 
									DataInicio				= $linContrato[DataBaseCalculo],
									DataTermino				= '$local_DataTerminoVigencia',
									Valor					= '0.00',
									ValorRepasseTerceiro	= '0.00',
									LimiteDesconto			= NULL,
									DataCriacao				= (concat(curdate(),' ',curtime())),
									LoginCriacao			= '$local_Login';";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}
						break;
					case '306':
						$local_DataTermino			=	$linContrato[DataTermino];
						$local_DataUltimaCobranca	=	$linContrato[DataUltimaCobranca];
					
						$local_VarStatus 			= $local_DataUltimaCobrancaStatus."#".$local_DataTerminoStatus;	
						break;
					default:
						$local_DataTermino			=	$linContrato[DataTermino];
						$local_DataUltimaCobranca	=	$linContrato[DataUltimaCobranca];
						$local_VarStatus			=	$local_DataBloqueioStatus;
						break;
				}
				
				$sql = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema = $local_IdStatus";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
		
				if($cancelar == false){
					$local_IdStatus	=	$linContrato[IdStatus];	
				}
				
				
				if($local_IdStatus == 201){
					$lin[ValorParametroSistema]	=	str_replace('Temporariamente','até '.$local_DataBloqueioStatus,$lin[ValorParametroSistema]);
					
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema]";
				}else{
					if($cancelar == false){
						$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento agendado para $local_DataUltimaCobrancaStatus";
					}else{
						$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema]";
					
						if($local_DataBloqueioStatus != "" && $local_IdStatus == 200){
							$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Data Reativação: $local_DataBloqueioStatus";
						}
					}
				}
				
				$temp	=	"";
				$temp2	=	"";
				if($linContrato[DataTermino]!=$local_DataTermino){
					if($linContrato[DataTermino]!=""){ 
						$temp2		=	dataConv($linContrato[DataTermino],'Y-m-d','d/m/Y');	
					}
						
					if($local_DataTermino == ""){	  
						$temp		=	dataConv($local_DataTermino,'Y-m-d','d/m/Y');	
					}
				
					$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > $temp]";
				}
				
				$temp	=	"";
				$temp2	=	"";
				if($linContrato[DataUltimaCobranca]!=$local_DataUltimaCobranca){
					if($linContrato[DataUltimaCobranca]!=""){ 
						$temp2		=	dataConv($linContrato[DataUltimaCobranca],'Y-m-d','d/m/Y');	
					}
					if($local_DataUltimaCobranca != ""){	
						$temp		=	dataConv($local_DataUltimaCobranca,'Y-m-d','d/m/Y');	
					}
					
					$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Última Cob. [$temp2 > $temp]";
				}
				
				if($local_Obs!=""){
					$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: ".trim($local_Obs);
				}
				
				if($linContrato[Obs]!=""){
					$Obs	.=	"\n".trim($linContrato[Obs]);
				}
				
				if($local_DataTermino == "" || $local_DataTermino == "NULL"){		
					$local_DataTermino = "NULL";
				}else{
					$local_DataTermino	= "'".$local_DataTermino."'";
				}
				
				if($local_DataUltimaCobranca == "" || $local_DataUltimaCobranca == "NULL"){
					$local_DataUltimaCobranca = "NULL";	
				}else{
					$local_DataUltimaCobranca	= "'".$local_DataUltimaCobranca."'";
				}
				
				$Obs = str_replace("\'", "'", $Obs);
				$Obs = str_replace("'", "\'", $Obs);
				
				$sql	=	"UPDATE Contrato SET
								IdStatus			= '$local_IdStatus',
								VarStatus			= '$local_VarStatus',
								DataUltimaCobranca	= $local_DataUltimaCobranca,
								DataTermino			= $local_DataTermino,
								Obs					= '$Obs',	
								DataAlteracao		= (concat(curdate(),' ',curtime())),
								LoginAlteracao		= '$local_Login'
							WHERE 
								IdLoja				= $local_IdLoja and
								IdContrato			= $local_IdContrato;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				
				
				$y	=	0;
				$local_IdServicoTemp[$y]	=	$local_IdServico;	
				$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato";				
				$res2	=	mysql_query($sql2,$con);
				while($lin2	=	mysql_fetch_array($res2)){
					$sql3	=	"select IdServico from Contrato where IdLoja = $local_IdLoja and IdContrato	= $lin2[IdContratoAutomatico];";
					$res3	=	mysql_query($sql3,$con);
					$lin3	=	mysql_fetch_array($res3);
					
					$local_IdServicoTemp[$y]	=	$lin3[IdServico];
					
					$sql	=	"
							UPDATE Contrato SET
								IdStatus			= '$local_IdStatus',
								VarStatus			= '$local_VarStatus',
								DataUltimaCobranca	= $local_DataUltimaCobranca,
								DataTermino			= $local_DataTermino,
								Obs					= '$Obs',	
								DataAlteracao		= (concat(curdate(),' ',curtime())),
								LoginAlteracao		= '$local_Login'
							WHERE 	
								IdLoja					= $local_IdLoja and
								IdContrato				= $lin2[IdContratoAutomatico];";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					$y++;
				}
				
			}

			if($cancelar == true){			
				$sqlRotinas = "select
						UrlRotinaCancelamento,
						UrlRotinaBloqueio,
						UrlRotinaDesbloqueio
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $linContrato[IdServico]";
				$resRotinas = mysql_query($sqlRotinas,$con);
				$linRotinas = mysql_fetch_array($resRotinas);

				$IdLoja		= $local_IdLoja;
				$IdContrato	= $local_IdContrato;
	
				// Rotina de Cancelamento
				if($local_IdStatus >= 1 && $local_IdStatus < 200 && $linRotinas[UrlRotinaCancelamento] != ''){
					include($linRotinas[UrlRotinaCancelamento]);
				}
	
				// Rotina de Desbloqueio
				if($local_IdStatus >= 200 && $local_IdStatus < 300 && $linRotinas[UrlRotinaDesbloqueio] != ''){
					include($linRotinas[UrlRotinaDesbloqueio]);
				}
	
				// Rotina de Bloqueio
				if($local_IdStatus >= 300 && $local_IdStatus < 400 && $linRotinas[UrlRotinaBloqueio] != ''){
					include($linRotinas[UrlRotinaBloqueio]);
				}
				
				for($ii = 0; $ii < $y; $ii++){
					$sqlRotinas = "select
							UrlRotinaCancelamento,
							UrlRotinaBloqueio,
							UrlRotinaDesbloqueio
						from
							Servico
						where
							IdLoja = $local_IdLoja and
							IdServico = ".$local_IdServicoTemp[$ii];
					$resRotinas = mysql_query($sqlRotinas,$con);
					$linRotinas = mysql_fetch_array($resRotinas);
		
					// Rotina de Cancelamento
					if($local_IdStatus >= 1 && $local_IdStatus < 200 && $linRotinas[UrlRotinaCancelamento] != ''){
						include($linRotinas[UrlRotinaCancelamento]);
					}
		
					// Rotina de Desbloqueio
					if($local_IdStatus >= 200 && $local_IdStatus < 300 && $linRotinas[UrlRotinaDesbloqueio] != ''){
						include($linRotinas[UrlRotinaDesbloqueio]);
					}
		
					// Rotina de Bloqueio
					if($local_IdStatus >= 300 && $local_IdStatus < 400 && $linRotinas[UrlRotinaBloqueio] != ''){
						include($linRotinas[UrlRotinaBloqueio]);
					}
				}
			}

			$cont++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sqlTransacao = "COMMIT;";
				
			switch($local_IdStatusTemp){
				case '1':
					if($cancelar == true){			
						$local_Erro = 67;	
					}else{
						$local_Erro = 4;
					}
					break;
				case '200':
					$local_Erro = 82;
					break;
				case '201':
					$local_Erro = 82;
					break;		
				default:
					$local_Erro = 4;			// Mensagem de Edição Positiva
			}
			
			mysql_query($sqlTransacao,$con);
			header("Location: listar_operacao.php?Erro=$local_Erro");
		}else{
			$sqlTransacao = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Edição Negativa
			
			mysql_query($sqlTransacao,$con);
			header("Location: cadastro_contrato_mudar_status.php?Erro=$local_Erro");
		}
	}	
?>
