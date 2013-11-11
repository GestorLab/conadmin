<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		
		if((permissaoSubOperacao($localModulo, 198, "V") && permissaoSubOperacao($localModulo, 198, "U")) || (permissaoSubOperacao($localModulo, 199, "V") && permissaoSubOperacao($localModulo, 199, "U"))){
			$y	=	0;
			$local_IdServicoTemp[$y]	=	$local_IdServico;
			$tr_i = 0;
			
			$sqlObs = "SELECT
						Obs
					FROM
						Contrato
					WHERE
						IdContrato = $local_IdContrato";
			$resObs 	= mysql_query($sqlObs,$con);
			$Obs		= mysql_fetch_array($resObs);
			
			$local_Obs 	= ""; 
			
			$sql2 = "select
							ServicoParametro.IdGrupoUsuario,
							ServicoParametro.IdParametroServico,
							ServicoParametro.DescricaoParametroServico,
							ServicoParametro.Editavel,
							ServicoParametro.ValorDefault,
							ServicoParametro.IdTipoTexto,
							ServicoParametro.ExibirSenha,
							ServicoParametro.Unico,
							ServicoParametro.IdTipoAcesso,
							ServicoParametro.SalvarHistorico
						from 
							Servico,
							ServicoParametro
						where
							Servico.IdLoja	  = $local_IdLoja and
							Servico.IdLoja = ServicoParametro.IdLoja and 
							Servico.IdServico = ServicoParametro.IdServico and
							Servico.IdServico = $local_IdServico and
							ServicoParametro.IdStatus = 1";
			$res2 = mysql_query($sql2,$con);
			while($lin2 = @mysql_fetch_array($res2)){
				switch($lin2[IdTipoAcesso]){
					case "1": # Administrativo
						$lin2[Permissao] = (permissaoSubOperacao($localModulo, 198, "V") && permissaoSubOperacao($localModulo, 198, "U"));
						break;
						
					case "2": # Técnico
						$lin2[Permissao] = (permissaoSubOperacao($localModulo, 199, "V") && permissaoSubOperacao($localModulo, 199, "U"));
						break;
						
					default:
						$lin2[Permissao] = false;
				}
				
				if($lin2[Permissao]){
					$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato and IdServico = $local_IdServico and IdParametroServico = $lin2[IdParametroServico];";
					$res3	=	mysql_query($sql3,$con);
					$lin3	=	mysql_fetch_array($res3);

					// Carrega em uma variável os parametros para ser usado em rotinas
					$ParametroValor[$lin2[IdParametroServico]] = $_POST['Valor_'.$lin2[IdParametroServico]];
					
					if($lin2[Editavel] == 2 && $lin2[ValorDefault]!="" && $_POST['Valor_'.$lin2[IdParametroServico]] == ''){
						$_POST['Valor_'.$lin2[IdParametroServico]] =	$lin2[ValorDefault];	
					}
					
					$sqlUnico = "select 
									count(*) Qtd
								from 
									Contrato,
									ContratoParametro
								where 
									Contrato.IdLoja = ContratoParametro.IdLoja and 
									Contrato.IdContrato != $local_IdContrato and
									Contrato.IdContrato = ContratoParametro.IdContrato and 
									Contrato.IdServico = ContratoParametro.IdServico and 
									Contrato.IdStatus != 1 and
									ContratoParametro.Valor = '".trim($_POST['Valor_'.$lin2[IdParametroServico]])."';";
					$resUnico = @mysql_query($sqlUnico, $con);
					$linUnico = @mysql_fetch_array($resUnico);
				
					if($lin2[IdGrupoUsuario] != ''){
						$sql7	=	"select
										(COUNT(*)>0) Qtd
									from 
										UsuarioGrupoUsuario
									where 
										IdLoja = '$local_IdLoja' and 
										IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
										Login = '$local_Login';";
						$res7	=	@mysql_query($sql7,$con);
						$lin7	=	@mysql_fetch_array($res7);
					} else {
						$lin7[Qtd] = 1;
					}
						
					if($lin2[Unico] == 1 && (int)$linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($_POST['Valor_'.$lin2[IdParametroServico]]) != '')) {
						$local_Erro2 = 142;
						$local_desc_param = $lin2[DescricaoParametroServico].",Valor_".$lin2[IdParametroServico].",".trim($_POST['Valor_'.$lin2[IdParametroServico]]);

						$ValorParametroMsg = trim($_POST['Valor_'.$lin2[IdParametroServico]]);

						$local_Obs = date("d/m/Y H:i:s")." [$local_Login] - Parâmetro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" já utilizado. Digite outro novamente.".$local_Obs;
					} else{
						if(mysql_num_rows($res3) >= 1){	
							$sql	=	"
								UPDATE ContratoParametro SET 
									Valor					='".trim($_POST['Valor_'.$lin2[IdParametroServico]])."'
								WHERE
									IdLoja					= $local_IdLoja and
									IdContrato				= $local_IdContrato and
									IdServico				= $local_IdServico and
									IdParametroServico		= $lin2[IdParametroServico];";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}else{
							$sql	=	"
								INSERT INTO ContratoParametro SET 
									Valor					='".$_POST['Valor_'.$lin2[IdParametroServico]]."',
									IdLoja					= $local_IdLoja,
									IdContrato				= $local_IdContrato,
									IdServico				= $local_IdServico,
									IdParametroServico		= $lin2[IdParametroServico];";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}
						$local_Obs  = str_replace("'",'"',$local_Obs);
						$_POST['Valor_'.$lin2[IdParametroServico]] = str_replace("\n","",trim($_POST['Valor_'.$lin2[IdParametroServico]]));
						if($lin3[Valor] != $_POST['Valor_'.$lin2[IdParametroServico]]){
							if($lin2[SalvarHistorico] == 1){
								if($lin2[IdTipoTexto] == 2){
									if($lin2[ExibirSenha] == 1){
										if($local_Obs!="")	$local_Obs	.= "\n";
										$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";
									}else{
										if($local_Obs!="")	$local_Obs	.= "\n";
										$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico]";
									}
								}else{
									if($local_Obs!="")	$local_Obs	.= "\n";
									$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";				
								}
							}
						}
					}
				}
			}
			
			if($local_Obs != ""){
				$local_Obs .= "\n".$Obs[Obs];
			}else{
				$local_Obs = $Obs[Obs];
			}
			
			$sql	=	"UPDATE Contrato SET
							Obs							= '$local_Obs',
							DataAlteracao				= (concat(curdate(),' ',curtime())),
							LoginAlteracao				= '$local_Login'
						WHERE 
							IdLoja						= $local_IdLoja and
							IdContrato					= $local_IdContrato";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$auto	=	1;
			$local_ObsAux	=	"";
			//############################ Contrato Automatico #################################
			
			$sql5	=	"select 
							ContratoAutomatico.IdContratoAutomatico IdContrato,
							Contrato.IdServico 
						from 
							(select	
								ContratoAutomatico.IdContrato,	
								ContratoAutomatico.IdContratoAutomatico 
							from 
								ContratoAutomatico 
							where
								ContratoAutomatico.IdLoja = $local_IdLoja and 
								ContratoAutomatico.IdContrato = $local_IdContrato) ContratoAutomatico, 
							Contrato 
						where 
							Contrato.IdLoja = $local_IdLoja and 
							Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
			$res5	=	mysql_query($sql5,$con);
			while($lin5 = mysql_fetch_array($res5)){
				$local_IdServicoTemp[$y]	=	$local_IdServico;	
				$y++;
				
				$local_NotaFiscalCDAAutomatico = $_POST['NotaFiscalCDAAutomatico_'.$lin5[IdServico]];

				if($local_NotaFiscalCDAAutomatico == 0){ 	$local_NotaFiscalCDAAutomatico = 'NULL';	}
																		
				$sql	=	"UPDATE Contrato SET
								NotaFiscalCDA				= $local_NotaFiscalCDAAutomatico
							WHERE 
								IdLoja						= $local_IdLoja and
								IdContrato = $lin5[IdContrato]";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;

				$ii 	= 	1;
				$sql2	=	"select
								ServicoParametro.IdGrupoUsuario,
								ServicoParametro.IdParametroServico,
								ServicoParametro.DescricaoParametroServico,
								ServicoParametro.Editavel,
								ServicoParametro.ValorDefault,
								ServicoParametro.IdTipoTexto,
								ServicoParametro.ExibirSenha,
								ServicoParametro.Unico,
								ServicoParametro.IdTipoAcesso,
								ServicoParametro.SalvarHistorico
							from 
								Loja,
								Servico,
								ServicoParametro
							where
								Servico.IdLoja	  = $local_IdLoja and
								Servico.IdLoja = Loja.IdLoja and
								Servico.IdLoja = ServicoParametro.IdLoja and
								Servico.IdServico = ServicoParametro.IdServico and
								Servico.IdServico = $lin5[IdServico] and
								ServicoParametro.IdStatus = 1";
				$res2	=	mysql_query($sql2,$con);
				while($lin2 = mysql_fetch_array($res2)){
					switch($lin2[IdTipoAcesso]){
						case "1": # Administrativo
							$lin2[Permissao] = (permissaoSubOperacao($localModulo, 198, "V") && permissaoSubOperacao($localModulo, 198, "U"));
							break;
							
						case "2": # Técnico
							$lin2[Permissao] = (permissaoSubOperacao($localModulo, 199, "V") && permissaoSubOperacao($localModulo, 199, "U"));
							break;
							
						default:
							$lin2[Permissao] = false;
					}
					
					if($lin2[Permissao]){
						$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $lin5[IdContrato] and IdServico = $lin5[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
						$res3	=	mysql_query($sql3,$con);
						$lin3	=	mysql_fetch_array($res3);
						
						$local_Valor[$ii]	=	$_POST["ValorAutomatico_".$lin5[IdServico]."_".$lin2[IdParametroServico]];
						
						if($lin2[Editavel]==2 && $lin2[ValorDefault]!="" && $local_Valor[$ii] == ""){
							$local_Valor[$ii]	=	$lin2[ValorDefault];
						}
					
						$sqlUnico = "select 
										count(*) Qtd
									from 
										Contrato,
										ContratoParametro
									where 
										Contrato.IdLoja = ContratoParametro.IdLoja and 
										Contrato.IdContrato != $lin5[IdContrato] and
										Contrato.IdContrato = ContratoParametro.IdContrato and 
										Contrato.IdServico = ContratoParametro.IdServico and 
										Contrato.IdStatus != 1 and
										ContratoParametro.Valor = '".trim($local_Valor[$ii])."';";
						$resUnico = @mysql_query($sqlUnico, $con);
						$linUnico = @mysql_fetch_array($resUnico);
					
						if($lin2[IdGrupoUsuario] != ''){
							$sql7	=	"select
											(COUNT(*)>0) Qtd
										from 
											UsuarioGrupoUsuario
										where 
											IdLoja = '$local_IdLoja' and 
											IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
											Login = '$local_Login';";
							$res7	=	@mysql_query($sql7,$con);
							$lin7	=	@mysql_fetch_array($res7);
						} else {
							$lin7[Qtd] = 1;
						}
						
						if($lin2[Unico] == 1 && (int) $linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($local_Valor[$ii]) !='')){
							$local_Erro2 = 142;
							$local_desc_param = $lin2[DescricaoParametroServico].",".$local_Valor[$ii].",".trim($local_Valor[$ii]);

							$ValorParametroMsg = trim($local_Valor[$ii]);

							$local_ObsAux = date("d/m/Y H:i:s")." [$local_Login] - Parâmetro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" já utilizado. Digite outro novamente.".$local_ObsAux;
						} else{
							$sql4	=	"select * from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $lin5[IdContrato] and IdServico = $lin5[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
							$res4	=	mysql_query($sql4,$con);
							if(mysql_num_rows($res4) >= 1){
								$sql	=	"
									UPDATE ContratoParametro SET 
										Valor					='".trim($local_Valor[$ii])."'
									WHERE
										IdLoja 					= $local_IdLoja and
										IdContrato				= $lin5[IdContrato] and
										IdServico				= $lin5[IdServico] and
										IdParametroServico		= $lin2[IdParametroServico];";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
							}else{
								$sql	=	"
									INSERT INTO ContratoParametro SET 
										IdLoja 					= $local_IdLoja,
										IdContrato				= $lin5[IdContrato],
										IdServico				= $lin5[IdServico],
										IdParametroServico		= $lin2[IdParametroServico],
										Valor					='".$local_Valor[$ii]."';";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
							}
							
							$local_ObsAux  = str_replace("'",'"',$local_ObsAux);
							
							if($lin3[Valor] != $local_Valor[$ii]){
								if($lin2[SalvarHistorico] == 1){
									if($lin2[IdTipoTexto] == 2){							
										if($lin2[ExibirSenha]==1){
											if($local_ObsAux!="")	$local_ObsAux	.= "\n";
											$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
										}else{
											if($local_ObsAux!="")	$local_ObsAux	.= "\n";
											$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico]";
										}
									}else{
										if($local_ObsAux!="")	$local_ObsAux	.= "\n";
										$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
									}
								}
							}
						}
					}
					
					$ii++;
				}
			}
			
			if($local_ObsMsnErroParametro != ''){
				$local_ObsMsnErroParametro = str_replace("\\'",'"',$local_ObsMsnErroParametro);
				$local_ObsMsnErroParametro = str_replace("\'",'"',$local_ObsMsnErroParametro);
				$local_ObsMsnErroParametro = str_replace("'",'"',$local_ObsMsnErroParametro);
				$local_ObsAux = date("d/m/Y H:i:s")." [".$local_Login."] - ".$local_ObsMsnErroParametro.$local_ObsAux;
			}
			
			if($local_ObsAux!=""){
				$sql	=	"UPDATE Contrato SET
								Obs							= '".$local_ObsAux.'\n'.$local_Obs."',
								DataAlteracao				= (concat(curdate(),' ',curtime())),
								LoginAlteracao				= '$local_Login'
							WHERE 
								IdLoja						= $local_IdLoja and
								IdContrato					= $local_IdContrato";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
			
			$sql = "select
						UrlRotinaAlteracao
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $local_IdServico";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			if($lin[UrlRotinaAlteracao] != ''){
				include($lin[UrlRotinaAlteracao]);
			}
			
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
				$local_Erro = 4;			// Mensagem de Alteração Positiva
			}else{
				$sql = "ROLLBACK;";
				$local_Erro = 5;			// Mensagem de Alteração Negativa
			}
			mysql_query($sql,$con);
		}else{
			$local_Erro = 2;
		}
	}else{
		if($local_IdCarteiraTemp!="" && $local_IdCarteira==""){
			$local_IdCarteira	=	$local_IdCarteiraTemp;
		}
		
		$local_AlterarVigencia					= 	$_POST['AlterarVigencia'];
		if($local_IdContratoAgrupador == 0){ 		$local_IdContratoAgrupador 	= 'NULL';}
		if($local_IdAgenteAutorizado == ""){ 		$local_IdAgenteAutorizado 	= 'NULL';}
		if($local_IdCarteira == ""){ 				$local_IdCarteira 			= 'NULL';}
		if($local_AdequarLeisOrgaoPublico == 0){ 	$local_AdequarLeisOrgaoPublico = getCodigoInterno(3,28);}
		if($local_NotaFiscalCDA == 0){ 				$local_NotaFiscalCDA = 'NULL';	}
		if($local_QtdMesesFidelidade == ""){		$local_QtdMesesFidelidade = 0; }
	
		
		if($local_IdContratoAgrupador != 'NULL'){
			$local_DiaCobranca	=	$local_DiaCobrancaTemp;	
		}
		
		$local_Erro2 = '';
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$sqlObs	=  "select 
						Contrato.Obs,
						Contrato.DiaCobranca,
						Contrato.DataInicio,
						Contrato.DataTermino,
						Contrato.DataPrimeiraCobranca,
						Contrato.DataUltimaCobranca,
						Contrato.QtdMesesFidelidade,
						Contrato.QtdParcela, 
						Contrato.TipoContrato,
						Contrato.MesFechado,
						Contrato.IdContratoAgrupador,
						Contrato.IdPeriodicidade,
						Periodicidade.DescricaoPeriodicidade,
						Contrato.IdStatus,
						Contrato.CFOP,
						Contrato.NotaFiscalCDA,
						Contrato.IdLocalCobranca,
						Contrato.AssinaturaContrato,
						LocalCobranca.DescricaoLocalCobranca
					from 
						Contrato, 
						Periodicidade,
						LocalCobranca
					where 
						Contrato.IdLoja = $local_IdLoja and 
						Contrato.IdContrato = $local_IdContrato and
						Contrato.IdLoja = Periodicidade.IdLoja and
						Contrato.IdLoja = LocalCobranca.IdLoja and
						Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
						Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade";
		$resObs	=	mysql_query($sqlObs,$con);
		$linObs	=	mysql_fetch_array($resObs);
		
		if($linObs[Obs]!=""){
			$linObs[Obs]	=	trim($linObs[Obs]);
		}
		
		$Obs	=	$local_Obs;
		
		if($Obs == "\n" || $Obs == "<BR>") $Obs = "";
		
		$local_Obs						=	"";
		$local_IdContratoPai			=	$local_IdContrato;
		$local_DataInicio				=	dataConv($local_DataInicio,'d/m/Y','Y-m-d');
		$local_DataPrimeiraCobranca		=	dataConv($local_DataPrimeiraCobranca,'d/m/Y','Y-m-d');
		
		if($linObs[IdPeriodicidade]!=$local_IdPeriodicidade){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$sql1	=  "select DescricaoPeriodicidade from Periodicidade where IdLoja = $local_IdLoja and IdPeriodicidade = $local_IdPeriodicidade";
			$res1	=	mysql_query($sql1,$con);
			$lin1	=	mysql_fetch_array($res1);
			
			$local_Obs	=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Periodicidade. [$linObs[DescricaoPeriodicidade] > $lin1[DescricaoPeriodicidade]]";
		}
		
		if($linObs[QtdParcela]!=$local_QtdParcela){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de QTD. Parcelas. [$linObs[QtdParcela] > $local_QtdParcela]";
		}
		
		if($linObs[TipoContrato]!=$local_TipoContrato){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Tipo Contrato. [".getParametroSistema(28,$linObs[TipoContrato])." > ".getParametroSistema(28,$local_TipoContrato)."]";
		}
		//Inicio Historico CFOP
		$sqlCFOP = "select
						CFOP,
						NaturezaOperacao
					from 
						CFOP
					where 
						CFOP = '$linObs[CFOP]'";
		$resCFOP = mysql_query($sqlCFOP, $con);
		$linCFOP = mysql_fetch_array($resCFOP);
		if($local_CFOPServico == "" || $local_CFOPServico == "(NULL)" || $local_CFOPServico == NULL){
			$local_CFOPServico = "0.00000";
		}
		if($linObs[CFOP]!=$local_CFOPServico && $linObs[CFOP] !=""){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$sqlCFOPAtual = "select
								CFOP,
								NaturezaOperacao
							from 
								CFOP
							where 
								CFOP = $local_CFOPServico";
			$resCFOPAtual = mysql_query($sqlCFOPAtual, $con);
			$linCFOPAtual = mysql_fetch_array($resCFOPAtual);

			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] -  Mudou CFOP [".$linCFOP[CFOP]." - ".$linCFOP[NaturezaOperacao]." > ".$linCFOPAtual[CFOP]." - ".$linCFOPAtual[NaturezaOperacao]."]";
		}
		
		//Fim Historico CFOP
		
		//Inicio inicio Nota Fiscal CDA
		if($linObs[NotaFiscalCDA] == ""){
			$linObs[NotaFiscalCDA] = 'NULL';
		}
		
		$sqlNotaFicalCDA = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=133 AND IdParametroSistema = $linObs[NotaFiscalCDA]";
		$resNotaFicalCDA = @mysql_query($sqlNotaFicalCDA,$con);
		$linNotaFicalCDA = mysql_fetch_array($resNotaFicalCDA);
		if($linObs[NotaFiscalCDA] != $local_NotaFiscalCDA){
			if($local_Obs!="")	$local_Obs	.= "\n";	
			
			$sqlNotaFicalCDAAtual = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=133 AND IdParametroSistema = $local_NotaFiscalCDA";
			$resNotaFicalCDAAtual = @mysql_query($sqlNotaFicalCDAAtual,$con);
			$linNotaFicalCDAAtual = mysql_fetch_array($resNotaFicalCDAAtual);
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] -  Mudou Visualizar Nota Fiscal CDA [".$linNotaFicalCDA[ValorParametroSistema]." > ".$linNotaFicalCDAAtual[ValorParametroSistema]."]";
		}
		//Final Nota Fiscal CDA
		if($linObs[IdLocalCobranca]!=$local_IdLocalCobranca){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$sql1	=  "select DescricaoLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca";
			$res1	=	mysql_query($sql1,$con);
			$lin1	=	mysql_fetch_array($res1);
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Local de Cobrança. [$linObs[DescricaoLocalCobranca] > $lin1[DescricaoLocalCobranca]]";
		}
		
		if($linObs[MesFechado]!=$local_MesFechado){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Mês Fechado. [".getParametroSistema(70,$linObs[MesFechado])." > ".getParametroSistema(70,$local_MesFechado)."]";
		}
		
		if($local_IdContratoAgrupador=="NULL"){
			$local_IdContratoAgrupadorTemp = "";
		}else{
			$local_IdContratoAgrupadorTemp = $local_IdContratoAgrupador;
		}
		if($linObs[IdContratoAgrupador]!=$local_IdContratoAgrupadorTemp){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Contrato Agrupador. [$linObs[IdContratoAgrupador] > $local_IdContratoAgrupadorTemp]";
		}
		
		if($linObs[DiaCobranca]!=$local_DiaCobranca){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Vencimento. [$linObs[DiaCobranca] > $local_DiaCobranca]";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($linObs[DataInicio]!=$local_DataInicio){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$temp		=	dataConv($local_DataInicio,'Y-m-d','d/m/Y');	
			$temp2		=	dataConv($linObs[DataInicio],'Y-m-d','d/m/Y');	
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Início Cont. [$temp2 > $temp]";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($linObs[DataPrimeiraCobranca]!=$local_DataPrimeiraCobranca){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$temp		=	dataConv($local_DataPrimeiraCobranca,'Y-m-d','d/m/Y');	
			$temp2		=	dataConv($linObs[DataPrimeiraCobranca],'Y-m-d','d/m/Y');	
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Primeira Cob. [$temp2 > $temp]";
			
			if($local_AlterarVigencia == 1){
				$sqlaux	=	"select  
								DataInicio
							from 
								ContratoVigencia  
							where 
								IdLoja = $local_IdLoja and
								(IdContrato = $local_IdContrato or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato)) and
								DataTermino is null";
				$resaux	=	mysql_query($sqlaux,$con);
				while($linaux = mysql_fetch_array($resaux)){
					$sql	=	"UPDATE ContratoVigencia SET 
									DataInicio 					= '".$local_DataPrimeiraCobranca."',
									DataAlteracao				= (concat(curdate(),' ',curtime())),
									LoginAlteracao				= '$local_Login'
								WHERE 
									IdLoja = $local_IdLoja and
									(IdContrato = $local_IdContrato or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato)) and
									DataTermino is null";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
					
					$temp		=	dataConv($local_DataPrimeiraCobranca,'Y-m-d','d/m/Y');	
					$temp2		=	dataConv($linaux[DataInicio],'Y-m-d','d/m/Y');	
					if($local_Obs!="")	$local_Obs	.= "\n";
					$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] -  Alteração automática da Data Inicio da vigência via Data Primeira Cob. [$temp2 > $temp]";
				}	
			}
		}
		
		if($local_DataTermino != "" && $local_DataTermino != "NULL"){
			$local_DataTermino	=	dataConv($local_DataTermino,'d/m/Y','Y-m-d');	
		}
		if($local_DataUltimaCobranca != "" && $local_DataUltimaCobranca != "NULL"){
			$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobranca,'d/m/Y','Y-m-d');	
		}
		
		#echo "linObsDataTermino:".$linObs[DataTermino]."<BR>";
		#echo "DataTermino:".$local_DataTermino."<BR><BR>";
		#echo "linObsDataUltimaCobranca:".$linObs[DataUltimaCobranca]."<BR>";
		#echo "DataUltimaCobranca:".$local_DataUltimaCobranca;
		
				
		/*Update Data Termino (1 = Sim)*/
		if(getCodigoInterno(3,54)==2){
			$local_DataTermino 		  = $linObs[DataTermino];
			$local_DataUltimaCobranca = $linObs[DataUltimaCobranca];
		}else{
			$temp	=	"";
			$temp2	=	"";
			
			if($linObs[DataTermino]!=$local_DataTermino){
				if($local_Obs != "") $local_Obs .= "\n";
				
				$temp	=	dataConv($local_DataTermino,'Y-m-d','d/m/Y');
				$temp2	=	dataConv($linObs[DataTermino],'Y-m-d','d/m/Y');
				
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > $temp]";
				
				if($local_DataTermino != "" && $local_DataTermino != "NULL"){
					if($linObs[IdStatus] == "200"){
						if((int) dataConv($local_DataTermino,'Y-m-d','Ymd') > (int) date("Ymd")){
							$IdStatusTemp = "205";
						} else {
							$IdStatusTemp = "1";
						}
					} elseif((int) dataConv($local_DataTermino,'Y-m-d','Ymd') <= (int) date("Ymd")){
						$IdStatusTemp = "1";
					}
					
					$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = $IdStatusTemp";
					$res1 = @mysql_query($sql1,$con);
					$lin1 = @mysql_fetch_array($res1);
					
					if($local_Obs != "") $local_Obs .= "\n";
					
					$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin1[ValorParametroSistema]";
					
					$sql = "update Contrato set 
								IdStatus = '$IdStatusTemp'
							where
								IdLoja = $local_IdLoja and
								IdContrato = $local_IdContrato and 
								IdStatus = '$linObs[IdStatus]';";
					$local_transaction[$tr_i] = mysql_query($sql,$con);
					$tr_i++;
				} else {
					if($linObs[IdStatus] == "205"){
						$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = 200";
						$res1 = @mysql_query($sql1,$con);
						$lin1 = @mysql_fetch_array($res1);
						
						if($local_Obs != "") $local_Obs .= "\n";
						
						$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin1[ValorParametroSistema]";
						
						$sql = "update Contrato set 
									IdStatus = '200'
								where
									IdLoja = $local_IdLoja and
									IdContrato = $local_IdContrato and 
									IdStatus = '$linObs[IdStatus]';";
						$local_transaction[$tr_i] = mysql_query($sql,$con);
						$tr_i++;
					}
				}
			}
			
			$temp	=	"";
			$temp2	=	"";
			if($linObs[DataUltimaCobranca]!=$local_DataUltimaCobranca){
				if($local_Obs != "") $local_Obs .= "\n";
				
				$temp2	=	dataConv($linObs[DataUltimaCobranca],'Y-m-d','d/m/Y');
				$temp	=	dataConv($local_DataUltimaCobranca,'Y-m-d','d/m/Y');
				
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Última Cob. [$temp2 > $temp]";
			}
		}
		
		if($linObs[AssinaturaContrato] != $local_AssinaturaContrato){
			$temp = $temp2 = "";
			
			if((int) $local_AssinaturaContrato > 0){
				$temp = getCodigoInterno(9, $local_AssinaturaContrato);
			}
			
			if((int) $linObs[AssinaturaContrato] > 0){
				$temp2 = getCodigoInterno(9, $linObs[AssinaturaContrato]);
			}
			
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Contrato Ass. [$temp2 > $temp]";
		}
		/*Update Data Vigencia (Sim)*/
		/*if($local_AlterarVigencia == 1){
			$local_DataVigencia 		 = $linObs[DataTermino];
			$local_DataUltimaCobranca = $linObs[DataUltimaCobranca];
		}else{
			$temp	=	"";
			$temp2	=	"";
			
			if($linObs[DataTermino]!=$local_DataTermino){
				if($local_Obs != "") $local_Obs .= "\n";
				
				$temp	=	dataConv($local_DataTermino,'Y-m-d','d/m/Y');
				$temp2	=	dataConv($linObs[DataTermino],'Y-m-d','d/m/Y');
				
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > $temp]";
			}
			
			$temp	=	"";
			$temp2	=	"";
			if($linObs[DataUltimaCobranca]!=$local_DataUltimaCobranca){
				if($local_Obs != "") $local_Obs .= "\n";
				
				$temp2	=	dataConv($linObs[DataUltimaCobranca],'Y-m-d','d/m/Y');
				$temp	=	dataConv($local_DataUltimaCobranca,'Y-m-d','d/m/Y');
				
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Última Cob. [$temp2 > $temp]";
			}
		}*/
		
		$temp	=	"";
		$temp2	=	"";
		if($linObs[QtdMesesFidelidade]!=$local_QtdMesesFidelidade){
			if($local_Obs!="")	$local_Obs	.= "\n";
			
			$temp2	=	$linObs[QtdMesesFidelidade];	
			$temp	=	$local_QtdMesesFidelidade;					
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de QTD. Meses Fidelidade. [$temp2 > $temp]";
		}
		
		
		if($local_DataTermino == ""){
			$local_DataTermino	=	'NULL';
		}
		if($local_DataUltimaCobranca == ""){
			$local_DataUltimaCobranca	=	'NULL';
		}
		
		if($Obs != ""){
			$QtdAspas = substr_count($Obs,"'"); // Busca a quantidade de aspas simples dentro da string
			if($QtdAspas%2 == 0){
				$Obs = str_replace("'",'"',$Obs);
			}else{
				$Obs = str_replace("'",'',$Obs);
			}
			
			if($local_Obs!="")	$local_Obs	.= "\n";
			$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".trim($Obs);
		}
		
		$y	=	0;
		$local_IdServicoTemp[$y]	=	$local_IdServico;	
		
		$sql2 = "select
						ServicoParametro.IdGrupoUsuario,
						ServicoParametro.IdParametroServico,
						ServicoParametro.DescricaoParametroServico,
						ServicoParametro.Editavel,
						ServicoParametro.ValorDefault,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha,
						ServicoParametro.Unico,
						ServicoParametro.IdTipoAcesso,
						ServicoParametro.SalvarHistorico
					from 
						Servico,
						ServicoParametro
					where
						Servico.IdLoja	  = $local_IdLoja and
						Servico.IdLoja = ServicoParametro.IdLoja and 
						Servico.IdServico = ServicoParametro.IdServico and
						Servico.IdServico = $local_IdServico and
						ServicoParametro.IdStatus = 1
					";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = @mysql_fetch_array($res2)){
			switch($lin2[IdTipoAcesso]){
				case "1": # Administrativo
					$lin2[Permissao] = (permissaoSubOperacao($localModulo, 198, "V") && permissaoSubOperacao($localModulo, 198, "U"));
					break;
					
				case "2": # Técnico
					$lin2[Permissao] = (permissaoSubOperacao($localModulo, 199, "V") && permissaoSubOperacao($localModulo, 199, "U"));
					break;
					
				default:
					$lin2[Permissao] = false;
			}
			
			if($lin2[Permissao]){
				$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato and IdServico = $local_IdServico and IdParametroServico = $lin2[IdParametroServico];";
				$res3	=	mysql_query($sql3,$con);
				$lin3	=	mysql_fetch_array($res3);

				// Carrega em uma variável os parametros para ser usado em rotinas
				$ParametroValor[$lin2[IdParametroServico]] = $_POST['Valor_'.$lin2[IdParametroServico]];
				
				if($lin2[Editavel] == 2 && $lin2[ValorDefault]!="" && $_POST['Valor_'.$lin2[IdParametroServico]] == ''){
					$_POST['Valor_'.$lin2[IdParametroServico]] =	$lin2[ValorDefault];	
				}
				
				$sqlUnico = "select 
								count(*) Qtd
							from 
								Contrato,
								ContratoParametro
							where 
								Contrato.IdLoja = ContratoParametro.IdLoja and 
								Contrato.IdContrato != $local_IdContrato and
								Contrato.IdContrato = ContratoParametro.IdContrato and 
								Contrato.IdServico = ContratoParametro.IdServico and 
								Contrato.IdStatus != 1 and
								ContratoParametro.Valor = '".trim($_POST['Valor_'.$lin2[IdParametroServico]])."';";
				$resUnico = @mysql_query($sqlUnico, $con);
				$linUnico = @mysql_fetch_array($resUnico);
			
				if($lin2[IdGrupoUsuario] != ''){
					$sql7	=	"select
									(COUNT(*)>0) Qtd
								from 
									UsuarioGrupoUsuario
								where 
									IdLoja = '$local_IdLoja' and 
									IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
									Login = '$local_Login';";
					$res7	=	@mysql_query($sql7,$con);
					$lin7	=	@mysql_fetch_array($res7);
				} else {
					$lin7[Qtd] = 1;
				}
					
				if($lin2[Unico] == 1 && (int)$linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($_POST['Valor_'.$lin2[IdParametroServico]]) != '')) {
					$local_Erro2 = 142;
					$local_desc_param = $lin2[DescricaoParametroServico].",Valor_".$lin2[IdParametroServico].",".trim($_POST['Valor_'.$lin2[IdParametroServico]]);

					$ValorParametroMsg = trim($_POST['Valor_'.$lin2[IdParametroServico]]);

					$local_Obs = date("d/m/Y H:i:s")." [$local_Login] - Parâmetro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" já utilizado. Digite outro novamente.".$local_Obs;
				} else{
					if(mysql_num_rows($res3) >= 1){	
						$sql	=	"
							UPDATE ContratoParametro SET 
								Valor					='".trim($_POST['Valor_'.$lin2[IdParametroServico]])."'
							WHERE
								IdLoja					= $local_IdLoja and
								IdContrato				= $local_IdContrato and
								IdServico				= $local_IdServico and
								IdParametroServico		= $lin2[IdParametroServico];";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}else{
						$sql	=	"
							INSERT INTO ContratoParametro SET 
								Valor					='".$_POST['Valor_'.$lin2[IdParametroServico]]."',
								IdLoja					= $local_IdLoja,
								IdContrato				= $local_IdContrato,
								IdServico				= $local_IdServico,
								IdParametroServico		= $lin2[IdParametroServico];";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
					$local_Obs  = str_replace("'",'"',$local_Obs);
					$_POST['Valor_'.$lin2[IdParametroServico]] = str_replace("\n","",trim($_POST['Valor_'.$lin2[IdParametroServico]]));
					if($lin3[Valor] != $_POST['Valor_'.$lin2[IdParametroServico]]){
						if($lin2[SalvarHistorico] == 1){
							if($lin2[IdTipoTexto] == 2){
								if($lin2[ExibirSenha] == 1){
									if($local_Obs!="")	$local_Obs	.= "\n";
									$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";
								}else{
									if($local_Obs!="")	$local_Obs	.= "\n";
									$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico]";
								}
							}else{
								if($local_Obs!="")	$local_Obs	.= "\n";
								$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$_POST['Valor_'.$lin2[IdParametroServico]]."]";				
							}
						}
					}
				}
			}
		}
		
		
		
		$sql2	=	"select
						LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato,
						LocalCobrancaParametroContrato.DescricaoParametroContrato
					from 
						LocalCobranca,
						LocalCobrancaParametroContrato
					where
						LocalCobranca.IdLoja = $local_IdLoja and
						LocalCobranca.IdLoja = LocalCobrancaParametroContrato.IdLoja and 
						LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca and
						LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca and
						LocalCobrancaParametroContrato.IdStatus = 1
					";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$sql3	=	"select 
							IdLocalCobrancaParametroContrato,
							Valor 
						from 
							ContratoParametroLocalCobranca 
						where 
							IdLoja = $local_IdLoja and 
							IdContrato = $local_IdContrato and 
							IdLocalCobranca = $local_IdLocalCobranca and 
							IdLocalCobrancaParametroContrato = $lin2[IdLocalCobrancaParametroContrato];";
			$res3	=	mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
			
			if(mysql_num_rows($res3) >= 1){	
				$sql	=	"
					UPDATE ContratoParametroLocalCobranca SET 
						Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."'
					WHERE
						IdLoja								= $local_IdLoja and
						IdContrato							= $local_IdContrato and
						IdLocalCobranca						= $local_IdLocalCobranca and
						IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato];";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}else{
				$sql	=	"
					INSERT INTO ContratoParametroLocalCobranca SET 
						Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."',
						IdLoja								= $local_IdLoja,
						IdContrato							= $local_IdContrato,
						IdLocalCobranca						= $local_IdLocalCobranca,
						IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato];";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
			
			if($lin3[Valor] != $_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]){
				if($local_Obs!="")	$local_Obs	.= "\n";
				$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Local. Cob. - $lin2[DescricaoParametroContrato] [$lin3[Valor] > ".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."]";
			}
		}
		
		$select = "";
		if($local_SeletorContaCartao != ""){
			$sql = "SELECT
						IdContrato,
						IdContaDebito,
						IdCartao,
						IdPessoa
					FROM
						Contrato
					WHERE
						IdContrato = $local_IdContrato AND
						IdLoja = $local_IdLoja";
			$res = mysql_query($sql,$con);
			$dados = mysql_fetch_array($res);
			
			if($local_SeletorContaCartao == "IdContaDebito"){
				if($dados[IdContaDebito] != $local_IdContaDebitoCartao){
					$sql = "select 
								NumeroAgencia,
								DigitoAgencia,
								NumeroConta,
								DigitoConta
							from
								PessoaContaDebito
							where
								IdLoja = '$local_IdLoja' and
								IdPessoa = '$dados[IdPessoa]' and
								IdContaDebito = '$dados[IdContaDebito]'";
					
					$resContaDebito = mysql_query($sql,$con);
					$linContaDebito = mysql_fetch_array($resContaDebito);
					
					if($linContaDebito[NumeroAgencia]  != "" && $linContaDebito[DigitoAgencia] != ""){
						$NumeroContaFinal .= $linContaDebito[NumeroAgencia]."-".$linContaDebito[DigitoAgencia]." ";
					}else{
						$NumeroContaFinal .= $linContaDebito[NumeroAgencia]." ";
					}
					
					if($linContaDebito[NumeroConta] != "" && $linContaDebito[DigitoConta] != ""){
						$NumeroContaFinal .= $linContaDebito[NumeroConta]."-"+$linContaDebito[DigitoConta];
					}else{
						$NumeroContaFinal .= $linContaDebito[NumeroConta];
					}
					if($local_Obs!="")	$local_Obs	.= "\n";
					$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alterou a Número da Conta Débito [$NumeroContaFinal > $local_NumeroContaDebitoCartao]";
				
				}
				if($dados[IdCartao] != ""){
					$sqlDelete = "UPDATE Contrato SET 
										IdCartao = NULL
								  WHERE
										IdContrato = $local_IdContrato AND
										IdLoja = $local_IdLoja";
										
					$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
					$tr_i++;
				}
			}
			
			if($local_SeletorContaCartao == "IdCartao"){
					if($dados[IdCartao] != $local_IdContaDebitoCartao){
						$sql = "SELECT
									NumeroCartao
								FROM
									PessoaCartao
								WHERE
									IdCartao = '$dados[IdCartao]' AND
									IdPessoa = '$dados[IdPessoa]' AND
									IdLoja = $local_IdLoja";
						$resCartao = mysql_query($sql,$con);
						$numeroCartao = mysql_fetch_array($resCartao);
						if($dados[IdCartao] == "" || $dados[IdCartao] == NULL){
							$numeroCartaoFinal = "";
						}else{
							$numeroCartaoFinal = substr($numeroCartao[NumeroCartao],0,3) ." **** **** ".substr($numeroCartao[NumeroCartao],15,18);
						}
						if($local_Obs!="")	$local_Obs	.= "\n";
						$local_Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Número do Cartão de Crédito [$numeroCartaoFinal > $local_NumeroContaDebitoCartao].";
				}
				if($dados[IdContaDebito] != ""){
					$sqlDelete = "UPDATE Contrato SET 
										IdContaDebito = NULL
								  WHERE
										IdContrato = $local_IdContrato AND
										IdLoja = $local_IdLoja";
										
					$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
					$tr_i++;
				}
			}
			
			if($local_SeletorContaCartao != "" && $local_IdContaDebitoCartao != ""){
				$select =  "$local_SeletorContaCartao = '$local_IdContaDebitoCartao',"; 
			}
		}else{
			$sqlDelete = "UPDATE Contrato SET 
										IdContaDebito = NULL,
										IdCartao	  = NULL
								  WHERE
										IdContrato = $local_IdContrato AND
										IdLoja = $local_IdLoja";
										
			$local_transaction[$tr_i]	=	mysql_query($sqlDelete,$con);
			$tr_i++;
		}
		
		if($linObs[Obs] != ""){
			if($local_Obs!="")	$local_Obs	.= "\n";
			$local_Obs	.=	$linObs[Obs];
		}
		
		$local_Obs = str_replace("'", "",$local_Obs);
		
		if($local_DataTermino != "" && $local_DataTermino != "NULL"){
			$local_DataTermino	=	"'".$local_DataTermino."'";	
		}
		if($local_DataUltimaCobranca != "" && $local_DataUltimaCobranca != "NULL"){
			$local_DataUltimaCobranca	=	"'".$local_DataUltimaCobranca."'";	
		}
		if($local_IdTerceiro == ""){ $local_IdTerceiro = "NULL"; } 		
		if($local_IdCarteira == "" || $local_IdCarteira == 0){ $local_IdCarteira = "NULL"; }
		
		$sql	=	"UPDATE Contrato SET
						IdAgenteAutorizado			= $local_IdAgenteAutorizado,
						IdCarteira					= $local_IdCarteira,
						IdTerceiro					= $local_IdTerceiro, 
						Obs							= '$local_Obs',
						IdContratoAgrupador			= $local_IdContratoAgrupador
					WHERE 
						IdLoja = $local_IdLoja and
						IdContrato = $local_IdContrato";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		$y++;			
		
		$sql	=	"UPDATE Contrato SET
						DataInicio					= '$local_DataInicio', 
						DataPrimeiraCobranca		= '$local_DataPrimeiraCobranca',
						DataTermino					= $local_DataTermino,
						DataUltimaCobranca			= $local_DataUltimaCobranca, 
						AssinaturaContrato			= $local_AssinaturaContrato,
						IdLocalCobranca				= $local_IdLocalCobranca,
						IdPeriodicidade				= $local_IdPeriodicidade,
						$select						
						QtdParcela					= $local_QtdParcela,
						TipoContrato				= $local_TipoContrato,
						QtdMesesFidelidade			= '$local_QtdMesesFidelidade',	
						MesFechado					= $local_MesFechado,
						IdPessoaEndereco			= '$local_IdPessoaEndereco',
						IdPessoaEnderecoCobranca	= '$local_IdPessoaEnderecoCobranca',
						CFOP						= '$local_CFOPServico',
						DiaCobranca					= '$local_DiaCobranca',
						AdequarLeisOrgaoPublico		= $local_AdequarLeisOrgaoPublico,
						DataAlteracao				= (concat(curdate(),' ',curtime())),
						LoginAlteracao				= '$local_Login'
					WHERE 
						IdLoja = $local_IdLoja and
						(IdContrato = $local_IdContrato or IdContrato in (select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato))";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
										
		$sql	=	"UPDATE Contrato SET
						NotaFiscalCDA				= $local_NotaFiscalCDA
					WHERE 
						IdLoja						= $local_IdLoja and
						IdContrato = $local_IdContrato";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		$sql = "select
					UrlRotinaAlteracao
				from
					Servico
				where
					IdLoja = $local_IdLoja and
					IdServico = $local_IdServico";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[UrlRotinaAlteracao] != ''){
			include($lin[UrlRotinaAlteracao]);
		}
		
		if($IdStatusTemp == 1){
			$sql = "select
						UrlRotinaCancelamento
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $local_IdServico";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			if($lin[UrlRotinaCancelamento] != ''){
				include($lin[UrlRotinaCancelamento]);
			}		
		}
		
		$auto	=	1;
		$local_ObsAux	=	"";
		//############################ Contrato Automatico #################################
		
		$sql5	=	"select 
						ContratoAutomatico.IdContratoAutomatico IdContrato,
						Contrato.IdServico 
					from 
						(select	
							ContratoAutomatico.IdContrato,	
							ContratoAutomatico.IdContratoAutomatico 
						from 
							ContratoAutomatico 
						where
							ContratoAutomatico.IdLoja = $local_IdLoja and 
							ContratoAutomatico.IdContrato = $local_IdContrato) ContratoAutomatico, 
						Contrato 
					where 
						Contrato.IdLoja = $local_IdLoja and 
						Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
		$res5	=	mysql_query($sql5,$con);
		while($lin5 = mysql_fetch_array($res5)){
			$local_IdServicoTemp[$y]	=	$local_IdServico;	
			$y++;
			
			$local_NotaFiscalCDAAutomatico = $_POST['NotaFiscalCDAAutomatico_'.$lin5[IdServico]];

			if($local_NotaFiscalCDAAutomatico == 0){ 	$local_NotaFiscalCDAAutomatico = 'NULL';	}
																	
			$sql	=	"UPDATE Contrato SET
							NotaFiscalCDA				= $local_NotaFiscalCDAAutomatico
						WHERE 
							IdLoja						= $local_IdLoja and
							IdContrato = $lin5[IdContrato]";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			$ii 	= 	1;
			$sql2	=	"select
							ServicoParametro.IdGrupoUsuario,
							ServicoParametro.IdParametroServico,
							ServicoParametro.DescricaoParametroServico,
							ServicoParametro.Editavel,
							ServicoParametro.ValorDefault,
							ServicoParametro.IdTipoTexto,
							ServicoParametro.ExibirSenha,
							ServicoParametro.Unico,
							ServicoParametro.IdTipoAcesso,
							ServicoParametro.SalvarHistorico
						from 
							Loja,
							Servico,
							ServicoParametro
						where
							Servico.IdLoja	  = $local_IdLoja and
							Servico.IdLoja = Loja.IdLoja and
							Servico.IdLoja = ServicoParametro.IdLoja and
							Servico.IdServico = ServicoParametro.IdServico and
							Servico.IdServico = $lin5[IdServico] and
							ServicoParametro.IdStatus = 1";
			$res2	=	mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				switch($lin2[IdTipoAcesso]){
					case "1": # Administrativo
						$lin2[Permissao] = (permissaoSubOperacao($localModulo, 198, "V") && permissaoSubOperacao($localModulo, 198, "U"));
						break;
						
					case "2": # Técnico
						$lin2[Permissao] = (permissaoSubOperacao($localModulo, 199, "V") && permissaoSubOperacao($localModulo, 199, "U"));
						break;
						
					default:
						$lin2[Permissao] = false;
				}
				
				if($lin2[Permissao]){
					$sql3	=	"select IdParametroServico,Valor from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $lin5[IdContrato] and IdServico = $lin5[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
					$res3	=	mysql_query($sql3,$con);
					$lin3	=	mysql_fetch_array($res3);
					
					$local_Valor[$ii]	=	$_POST["ValorAutomatico_".$lin5[IdServico]."_".$lin2[IdParametroServico]];
					
					if($lin2[Editavel]==2 && $lin2[ValorDefault]!="" && $local_Valor[$ii] == ""){
						$local_Valor[$ii]	=	$lin2[ValorDefault];
					}
				
					$sqlUnico = "select 
									count(*) Qtd
								from 
									Contrato,
									ContratoParametro
								where 
									Contrato.IdLoja = ContratoParametro.IdLoja and 
									Contrato.IdContrato != $lin5[IdContrato] and
									Contrato.IdContrato = ContratoParametro.IdContrato and 
									Contrato.IdServico = ContratoParametro.IdServico and 
									Contrato.IdStatus != 1 and
									ContratoParametro.Valor = '".trim($local_Valor[$ii])."';";
					$resUnico = @mysql_query($sqlUnico, $con);
					$linUnico = @mysql_fetch_array($resUnico);
				
					if($lin2[IdGrupoUsuario] != ''){
						$sql7	=	"select
										(COUNT(*)>0) Qtd
									from 
										UsuarioGrupoUsuario
									where 
										IdLoja = '$local_IdLoja' and 
										IdGrupoUsuario in ($lin2[IdGrupoUsuario]) and 
										Login = '$local_Login';";
						$res7	=	@mysql_query($sql7,$con);
						$lin7	=	@mysql_fetch_array($res7);
					} else {
						$lin7[Qtd] = 1;
					}
					
					if($lin2[Unico] == 1 && (int) $linUnico[Qtd] > 0 && $lin7[Qtd] == 1 && (trim($local_Valor[$ii]) !='')){
						$local_Erro2 = 142;
						$local_desc_param = $lin2[DescricaoParametroServico].",".$local_Valor[$ii].",".trim($local_Valor[$ii]);

						$ValorParametroMsg = trim($local_Valor[$ii]);

						$local_ObsAux = date("d/m/Y H:i:s")." [$local_Login] - Parâmetro $lin2[DescricaoParametroServico] \\\"$ValorParametroMsg\\\" já utilizado. Digite outro novamente.".$local_ObsAux;
					} else{
						$sql4	=	"select * from ContratoParametro where IdLoja = $local_IdLoja and IdContrato = $lin5[IdContrato] and IdServico = $lin5[IdServico] and IdParametroServico = $lin2[IdParametroServico];";
						$res4	=	mysql_query($sql4,$con);
						if(mysql_num_rows($res4) >= 1){
							$sql	=	"
								UPDATE ContratoParametro SET 
									Valor					='".trim($local_Valor[$ii])."'
								WHERE
									IdLoja 					= $local_IdLoja and
									IdContrato				= $lin5[IdContrato] and
									IdServico				= $lin5[IdServico] and
									IdParametroServico		= $lin2[IdParametroServico];";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}else{
							$sql	=	"
								INSERT INTO ContratoParametro SET 
									IdLoja 					= $local_IdLoja,
									IdContrato				= $lin5[IdContrato],
									IdServico				= $lin5[IdServico],
									IdParametroServico		= $lin2[IdParametroServico],
									Valor					='".$local_Valor[$ii]."';";
							$local_transaction[$tr_i]	=	mysql_query($sql,$con);
							$tr_i++;
						}
						
						$local_ObsAux  = str_replace("'",'"',$local_ObsAux);
						
						if($lin3[Valor] != $local_Valor[$ii]){
							if($lin2[SalvarHistorico] == 1){
								if($lin2[IdTipoTexto] == 2){							
									if($lin2[ExibirSenha]==1){
										if($local_ObsAux!="")	$local_ObsAux	.= "\n";
										$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
									}else{
										if($local_ObsAux!="")	$local_ObsAux	.= "\n";
										$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico]";
									}
								}else{
									if($local_ObsAux!="")	$local_ObsAux	.= "\n";
									$local_ObsAux	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Parâmetro Serviço Auto. ($lin5[IdServico]) - $lin2[DescricaoParametroServico] [$lin3[Valor] > ".$local_Valor[$ii]."]";
								}
							}
						}
					}
				}
				
				$ii++;
			}
		
			$sql4	=	"select Valor from ServicoValor where DataInicio <= curdate() and (DataTermino is Null  or DataTermino >= curdate()) and IdLoja =$local_IdLoja and IdServico = $lin5[IdServico] order BY DataInicio DESC LIMIT 0,1"; 
			$res4	=	@mysql_query($sql4,$con);
			$lin4	=	@mysql_fetch_array($res4);
			
			$sql2	=	"select
							LocalCobrancaParametroContrato.IdLocalCobrancaParametroContrato,
							LocalCobrancaParametroContrato.DescricaoParametroContrato
						from 
							Loja,
							LocalCobranca,
							LocalCobrancaParametroContrato
						where
							LocalCobranca.IdLoja = $local_IdLoja and
							LocalCobranca.IdLoja = Loja.IdLoja and
							LocalCobranca.IdLoja = LocalCobrancaParametroContrato.IdLoja and
							LocalCobranca.IdLocalCobranca = LocalCobrancaParametroContrato.IdLocalCobranca and
							LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca and
							LocalCobrancaParametroContrato.IdStatus = 1";
			$res2	=	mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				
				$sql4	=	"select IdLocalCobrancaParametroContrato,Valor from ContratoParametroLocalCobranca where IdLoja = $local_IdLoja and IdContrato = $lin5[IdContrato] and IdLocalCobranca	= $local_IdLocalCobranca and IdLocalCobrancaParametroContrato = $lin2[IdLocalCobrancaParametroContrato];";
				$res4	=	mysql_query($sql4,$con);
				$lin4	=	mysql_fetch_array($res4);
				
				if(mysql_num_rows($res4) >= 1){
					$sql	=	"
						UPDATE ContratoParametroLocalCobranca SET 
							Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."'
						WHERE
							IdLoja 								= $local_IdLoja and
							IdContrato							= $lin5[IdContrato] and
							IdLocalCobranca						= $local_IdLocalCobranca and
							IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato];";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}else{
					$sql	=	"
						INSERT INTO ContratoParametroLocalCobranca SET 
							IdLoja 								= $local_IdLoja,
							IdContrato							= $lin5[IdContrato],
							IdLocalCobranca						= $local_IdLocalCobranca,
							IdLocalCobrancaParametroContrato	= $lin2[IdLocalCobrancaParametroContrato],
							Valor								='".$_POST['LocalCobranca_Valor_'.$lin2[IdLocalCobrancaParametroContrato]]."';";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}
			
			$sql = "select
						UrlRotinaAlteracao
					from
						Servico
					where
						IdLoja = $local_IdLoja and
						IdServico = $lin5[IdServico]";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			if($lin[UrlRotinaAlteracao] != ''){
				include($lin[UrlRotinaAlteracao]);
			}
			
			$auto++;
			$local_NotaFiscalCDAAutomatico = $_POST['NotaFiscalCDAAutomatico_'.$lin5[IdServico]];
			$local_IdAgenteAutorizadoAutomatico = $_POST['IdAgenteAutorizado_'.$lin5[IdServico]];
			$local_IdCarteiraAutomatico = $_POST['IdCarteira_'.$lin5[IdServico]];
			$local_IdTerceiroAutomatico = $_POST['IdTerceiro_'.$lin5[IdServico]];
			
			if($local_IdAgenteAutorizadoAutomatico == ''){
				$local_IdAgenteAutorizadoAutomatico = "NULL";
			}
			if($local_IdCarteiraAutomatico == '' || $local_IdCarteiraAutomatico == 0){
				$local_IdCarteiraAutomatico = "NULL";
			}
			if($local_IdTerceiroAutomatico == ''){
				$local_IdTerceiroAutomatico = "NULL";
			}
			$sql	=	"UPDATE Contrato SET
							IdAgenteAutorizado			= $local_IdAgenteAutorizadoAutomatico,
							IdCarteira					= $local_IdCarteiraAutomatico,
							IdTerceiro					= $local_IdTerceiroAutomatico
						WHERE 
							IdLoja = $local_IdLoja and
							IdContrato = $lin5[IdContrato]";
			
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		//#########################################################
		
		if($local_ObsMsnErroParametro != ''){
			$local_ObsMsnErroParametro = str_replace("\\'",'"',$local_ObsMsnErroParametro);
			$local_ObsMsnErroParametro = str_replace("\'",'"',$local_ObsMsnErroParametro);
			$local_ObsMsnErroParametro = str_replace("'",'"',$local_ObsMsnErroParametro);
			$local_ObsAux = date("d/m/Y H:i:s")." [".$local_Login."] - ".$local_ObsMsnErroParametro.$local_ObsAux;
		}
		
		if($local_ObsAux!=""){
			$sql	=	"UPDATE Contrato SET
							Obs							= '".$local_ObsAux.'\n'.$local_Obs."',
							DataAlteracao				= (concat(curdate(),' ',curtime())),
							LoginAlteracao				= '$local_Login'
						WHERE 
							IdLoja						= $local_IdLoja and
							IdContrato					= $local_IdContrato";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 4;			// Mensagem de Alteração Positiva
		}else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Alteração Negativa
		}
		mysql_query($sql,$con);
	}
?>
