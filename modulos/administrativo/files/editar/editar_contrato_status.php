<?	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		$cancelar			= true;
		$BackupParametro	= false;
		$local_IdStatusTemp	= $local_IdStatus;
		$local_TipoEmail = '';
		$local_ErroEmail = '';
		
		$sqlContrato = "select 
							IdPessoa,
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
		
		if($linContrato[IdStatus] == "205" && $linContrato[DataTermino] != ""){
			$temp2 = dataConv($linContrato[DataTermino],'Y-m-d','d/m/Y');
			
			if($linContrato[Obs] != ""){
				$linContrato[Obs] = date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > ]\n".trim($linContrato[Obs]);
			} else{
				$linContrato[Obs] = date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > ]";
			}
			
			$linContrato[Obs] = str_replace("'", "", $linContrato[Obs]);
			
			$sql = "UPDATE Contrato SET
						DataTermino			= NULL,
						Obs					= '$linContrato[Obs]',	
						DataAlteracao		= (concat(curdate(),' ',curtime())),
						LoginAlteracao		= '$local_Login'
					WHERE 	
						IdLoja				= $local_IdLoja and
						IdContrato			= $local_IdContrato;";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
			$tr_i++;
			
			$linContrato[DataTermino] = '';
		}
		
		switch($local_IdStatus){
			case '1': //Status -> Cancelado
				$local_DataTermino			=	dataConv($local_DataTerminoStatus,"d/m/Y","Y-m-d");
				$local_DataUltimaCobranca	=	dataConv($local_DataUltimaCobrancaStatus,"d/m/Y","Y-m-d");
				
				if(dataConv($local_DataTermino,"Y-m-d","Ymd") <= date("Ymd")){
					$BackupParametro = $cancelar = true;
				}else{
					$cancelar	= false;
				}
				break;
			case '200':	// Status -> Ativo
				if($local_IdStatusAnteriorTemp == 1){
					if($local_DataBloqueioStatus == "" or $local_DataBloqueioStatus == NULL){
						$sqlDPC ="	select 
										DataPrimeiraCobranca
									from 
										Contrato 
									where 
										IdLoja 		= $local_IdLoja and
										IdContrato	= $local_IdContrato";
						$resDPC	=	@mysql_query($sqlDPC,$con);
						if($linDPC	=	@mysql_fetch_array($resDPC)){
							$local_DataBloqueioStatus = dataConv($linDPC[DataPrimeiraCobranca],'Y-m-d','d/m/Y');
						}
					}
					$local_VarStatus				=	dataConv($local_DataBloqueioStatus,'d/m/Y','Y-m-d');
					$local_DataReativacao			=	dataConv($local_DataBloqueioStatus,'d/m/Y','Y-m-d');
					
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
										ContratoVigencia 
									where 
										IdLoja 		= $local_IdLoja and
										IdContrato	= $local_IdContrato and
										DataTermino is NULL
									order by
										DataInicio desc";
						$res2	=	@mysql_query($sql2,$con);
						if($lin2	=	@mysql_fetch_array($res2)){
							if($local_DataReativacao != $lin2[DataInicio] || $local_DataReativacao == ''){
								#Criar nova vigência com valor atual a partir da data de reativação com os valores normais
								$local_DataInicioContrato 	= incrementaData($linContrato[DataTermino],1);
								$local_DataReativacaoAux	= $local_DataReativacao;
								$local_DataReativacao 		= incrementaData($local_DataReativacao,-1);
								
								if($local_DataInicioContrato < $local_DataReativacao){
									$sql	=	"
										INSERT INTO ContratoVigencia SET 
											IdLoja					= $local_IdLoja,
											IdContrato				= $local_IdContrato, 
											IdTipoDesconto			= 3, 
											IdContratoTipoVigencia	= '2',
											ValorDesconto			= '0.00', 
											DataInicio				= '$local_DataInicioContrato',
											DataTermino				= '$local_DataReativacao',
											Valor					= '0.00',
											ValorRepasseTerceiro	= '0.00',
											LimiteDesconto			= NULL,
											DataCriacao				= (concat(curdate(),' ',curtime())),
											LoginCriacao			= '$local_Login';";
									$local_transaction[$tr_i]	=	mysql_query($sql,$con);
									$tr_i++;
								}
								$local_DataReativacao	= $local_DataReativacaoAux;
								
								$sql	=	"
									INSERT INTO ContratoVigencia SET 
										IdLoja					= $local_IdLoja,
										IdContrato				= $local_IdContrato, 
										IdTipoDesconto			= '$lin2[IdTipoDesconto]', 
										IdContratoTipoVigencia	= '$lin2[IdContratoTipoVigencia]',
										ValorDesconto			= '$lin2[ValorDesconto]', 
										DataInicio				= '$local_DataReativacao',
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
											DataTermino			= '$linContrato[DataTermino]',
											DataAlteracao		= (concat(curdate(),' ',curtime())),
											LoginAlteracao		= '$local_Login'
										WHERE 
											IdLoja				= $local_IdLoja and
											IdContrato			= $local_IdContrato and
											DataInicio			= '$lin2[DataInicio]';";
								$local_transaction[$tr_i]	=	mysql_query($sql,$con);
								$tr_i++;
							}
						}
						///////////////////////////////////////Contrato Automatico////////////////////////////////////////
						$sql3	=	"select 
											ContratoAutomatico.IdContratoAutomatico,
											Contrato.IdServico,
											Contrato.IdStatus,
											Contrato.DataTermino,
											Contrato.DataUltimaCobranca,
											Contrato.DataBaseCalculo 
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
						$res3 	= 	@mysql_query($sql3,$con);
						while($lin3 = @mysql_fetch_array($res3)){
												
							$sql4	=	"select 
											DataInicio,
											DataTermino,
											IdTipoDesconto,
											IdContratoTipoVigencia,
											ValorDesconto,
											Valor,
											ValorRepasseTerceiro,
											LimiteDesconto
										from 
											ContratoVigencia 
										where 
											IdLoja 		= $local_IdLoja and
											IdContrato	= $lin3[IdContratoAutomatico] and
											DataTermino is NULL
										order by
											DataInicio desc";
							$res4	=	@mysql_query($sql4,$con);
							if($lin4	=	@mysql_fetch_array($res4)){
								if($local_DataReativacao != $lin4[DataInicio] || $local_DataReativacao == ''){
									$local_DataInicioContrato 	= incrementaData($lin3[DataTermino],1);
									$local_DataReativacaoAux	= $local_DataReativacao;
									$local_DataReativacao 		= incrementaData($local_DataReativacao,-1);	
									
									if($local_DataInicioContrato < $local_DataReativacao){
										$sql	=	"
											INSERT INTO ContratoVigencia SET 
												IdLoja					= $local_IdLoja,
												IdContrato				= $lin3[IdContratoAutomatico], 
												IdTipoDesconto			= 3, 
												IdContratoTipoVigencia	= '2',
												ValorDesconto			= '0.00', 
												DataInicio				= '$local_DataInicioContrato',
												DataTermino				= '$local_DataReativacao',
												Valor					= '0.00',
												ValorRepasseTerceiro	= '0.00',
												LimiteDesconto			= NULL,
												DataCriacao				= (concat(curdate(),' ',curtime())),
												LoginCriacao			= '$local_Login';";
										$local_transaction[$tr_i]	=	mysql_query($sql,$con);
										$tr_i++;
									}
									$local_DataReativacao	= $local_DataReativacaoAux;
									
									$sql	=	"
										INSERT INTO ContratoVigencia SET 
											IdLoja					= $local_IdLoja,
											IdContrato				= $lin3[IdContratoAutomatico], 
											IdTipoDesconto			= '$lin4[IdTipoDesconto]', 
											IdContratoTipoVigencia	= '$lin4[IdContratoTipoVigencia]',
											ValorDesconto			= '$lin4[ValorDesconto]', 
											DataInicio				= '$local_DataReativacao',
											DataTermino				= NULL,
											Valor					= '$lin4[Valor]',
											ValorRepasseTerceiro	= '$lin4[ValorRepasseTerceiro]',
											LimiteDesconto			= '$lin4[LimiteDesconto]',
											DataCriacao				= (concat(curdate(),' ',curtime())),
											LoginCriacao			= '$local_Login';";
									$local_transaction[$tr_i]	=	mysql_query($sql,$con);							
									$tr_i++;
									
									#colocar data de término igual a data base para a vigência que estiver em aberto
									$sql	=	"
											UPDATE ContratoVigencia SET 
												DataTermino			= '$lin3[DataTermino]',
												DataAlteracao		= (concat(curdate(),' ',curtime())),
												LoginAlteracao		= '$local_Login'
											WHERE 
												IdLoja				= $local_IdLoja and
												IdContrato			= $lin3[IdContratoAutomatico] and
												DataInicio			= '$lin4[DataInicio]';";
									$local_transaction[$tr_i]	=	mysql_query($sql,$con);							
									$tr_i++;
								}
							}
						} 
					}					
				}else{
					$sqlInicio ="	select 
										DataPrimeiraCobranca
									from 
										Contrato 
									where 
										IdLoja 		= $local_IdLoja and
										IdContrato	= $local_IdContrato";
					$resInicio	=	@mysql_query($sqlInicio,$con);
					if($linInicio	=	@mysql_fetch_array($resInicio)){
						$local_DataReativacao			=	$linInicio[DataPrimeiraCobranca];
					}
				}
				// Não deixa que a Data de Termino e Data de Ultima Cobrança do contrato se perda quando muda para o status ativo
/*				$local_DataTermino			= $linContrato[DataTermino];
				$local_DataUltimaCobranca	= $linContrato[DataUltimaCobranca];
*/				$local_DataTermino			= '';
				$local_DataUltimaCobranca	= '';
				break;	
			default:
				/* Se o status for do tipo Ativo ele limpa os campos 'Data Término Cont.' e 'Data Última Cob.'.
				 * Caso contrario ele não deixa a 'Data Término Cont.' e 'Data Última Cob.' se perde. */
				if($local_IdStatus > 200 && $local_IdStatus < 300){
					$local_DataTermino			= '';
					$local_DataUltimaCobranca	= '';
				} else{
					$local_DataTermino			= $linContrato[DataTermino];
					$local_DataUltimaCobranca	= $linContrato[DataUltimaCobranca];
				}
				
				$local_VarStatus = $local_DataBloqueioStatus;
				break;
		}
		
		$sql = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 and IdParametroSistema = $local_IdStatus";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($cancelar == false){
			#$local_IdStatus	=	$linContrato[IdStatus];	
			$local_IdStatus	= "205";	
		}
		
		switch($local_IdStatus){
			case '201':
				$lin[ValorParametroSistema]	=	str_replace('Temporariamente','até '.$local_DataBloqueioStatus,$lin[ValorParametroSistema]);
			
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema]";
				break;
			case '204':
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema] para $local_DataBloqueioStatus";
			break;
			case '306':
				$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema] para $local_DataBloqueioStatus";
				break;
				
			default:
				if($cancelar == false){
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para ".getParametroSistema(69,$local_IdStatus);
				}else{
					$Obs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para $lin[ValorParametroSistema]";
					#***-**** Obs Data reativação.
					$sqlDataReativacao 	= 	"SELECT 
												DataPrimeiraCobranca 
											FROM
												Contrato 
											WHERE 
												IdLoja = $local_IdLoja and 
												IdContrato = $local_IdContrato";
					$resDataReativacao 	= mysql_query($sqlDataReativacao, $con);
					$linDataReativacao	= mysql_fetch_array($resDataReativacao);
					
					if($local_IdStatusAnteriorTemp == 1 && $local_IdStatus == 200){
						if($linDataReativacao[DataPrimeiraCobranca] != $local_DataReativacao){
							$linDataReativacao[DataPrimeiraCobranca]	=	dataConv($linDataReativacao[DataPrimeiraCobranca],'Y-m-d','d/m/Y');
							$local_DataReativacaoObs					=	dataConv($local_DataReativacao,'Y-m-d','d/m/Y');
							$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Início Cob. [$linDataReativacao[DataPrimeiraCobranca] > $local_DataReativacaoObs]";
							$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Data Reativação: $local_DataBloqueioStatus";
						}
					}
				}
				break;
		}

		# GERAÇÃO DE PROTOCOLO PARA A OPERAÇÃO
		if($local_ProtocoloAssunto != '' && $local_ProtocoloObservacao != ''){
			$sql = "select (max(IdProtocolo) + 1) IdProtocolo from Protocolo;";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[IdProtocolo] != NULL){ 
				$local_IdProtocolo = $lin[IdProtocolo];
			} else{
				$local_IdProtocolo = 1;
			}
			
			$sql = "select 
						Pessoa.IdPessoa,
						Pessoa.CPF_CNPJ,
						Pessoa.Nome,
						Pessoa.Telefone1,
						Pessoa.Telefone2,
						Pessoa.Telefone3,
						Pessoa.Celular,
						Pessoa.Email
					from
						Contrato,
						Pessoa 
					where 
						Contrato.IdLoja = '$local_IdLoja' and 
						Contrato.IdContrato = '$local_IdContrato' and 
						Contrato.IdPessoa = Pessoa.IdPessoa;";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$local_IdPessoa = $lin[IdPessoa];
			
			if($lin[CPF_CNPJ] == ''){
				$lin[CPF_CNPJ] = "NULL";
			} else{
				$lin[CPF_CNPJ] = "'".$lin[CPF_CNPJ]."'";
			}
			
			if($lin[Telefone1] == ''){
				$lin[Telefone1] = "NULL";
			} else{
				$lin[Telefone1] = "'".$lin[Telefone1]."'";
			}
			
			if($lin[Telefone2] == ''){
				$lin[Telefone2] = "NULL";
			} else{
				$lin[Telefone2] = "'".$lin[Telefone2]."'";
			}
			
			if($lin[Telefone3] == ''){
				$lin[Telefone3] = "NULL";
			} else{
				$lin[Telefone3] = "'".$lin[Telefone3]."'";
			}
			
			if($lin[Celular] == ''){
				$lin[Celular] = "NULL";
			} else{
				$lin[Celular] = "'".$lin[Celular]."'";
			}
			
			if($lin[Email] == ''){
				$lin[Email] = "NULL";
			} else{
				$lin[Email] = "'".$lin[Email]."'";
			}
			
			$sql = "insert into Protocolo set
						IdLoja				= '$local_IdLoja',
						IdProtocolo			= '$local_IdProtocolo',
						LocalAbertura		= '2',
						IdProtocoloTipo		= '1',
						Assunto				= '$local_ProtocoloAssunto',
						IdContrato			= $local_IdContrato,
						IdPessoa			= '".$lin[IdPessoa]."',
						CPF_CNPJ			= ".$lin[CPF_CNPJ].",
						Nome				= '".$lin[Nome]."',
						Telefone1			= ".$lin[Telefone1].",
						Telefone2			= ".$lin[Telefone2].",
						Telefone3			= ".$lin[Telefone3].",
						Celular				= ".$lin[Celular].",
						Email				= ".$lin[Email].",
						IdStatus			= 200,
						LoginCriacao		= '$local_Login',
						DataCriacao			= (concat(curdate(),' ',curtime())),
						LoginConclusao		= '$local_Login', 
						DataConclusao		= (concat(curdate(),' ',curtime()));";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = $local_IdStatusAnteriorTemp";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$local_StatusAnterior = $lin[ValorParametroSistema];
			
			$sql = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = $local_IdStatus";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$local_StatusNovo = $lin[ValorParametroSistema];
			
			$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>Protocolo referente ao contrato N° $local_IdContrato.\r\nAlteração do status $local_StatusAnterior para o status $local_StatusNovo.</div>";
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Assunto:</b> Geração de protocolo via alteração de status do contrato.</div>$local_Mensagem";
			
			$sql = "select DescricaoProtocoloTipo from ProtocoloTipo where IdLoja = '$local_IdLoja' and IdProtocoloTipo = '1';";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Tipo Protocolo:</b> ".$lin[DescricaoProtocoloTipo]."</div>$local_Mensagem";
			
			$sql = "insert into
						ProtocoloHistorico
					set
						IdLoja					= '$local_IdLoja',
						IdProtocolo				= '$local_IdProtocolo',
						IdProtocoloHistorico	= '1',
						Mensagem				= \"$local_Mensagem\",
						IdStatus				= '100',
						LoginCriacao			= '$local_Login', 
						DataCriacao				= (concat(curdate(),' ',curtime()));";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			$local_Mensagem = "<b>Escrito por:</b> $local_Login <div style=\'margin-top:6px;\'>".str_replace('"',"'",$local_ProtocoloObservacao)."</div>";
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Assunto:</b> $local_ProtocoloAssunto</div>$local_Mensagem";
			$local_Mensagem = "<div style=\'margin:6px 0px 6px 0px;\' class=\'none\'><b>Tipo Protocolo:</b> ".$lin[DescricaoProtocoloTipo]."</div>$local_Mensagem";
			
			$sql = "insert into
						ProtocoloHistorico
					set
						IdLoja					= '$local_IdLoja',
						IdProtocolo				= '$local_IdProtocolo',
						IdProtocoloHistorico	= '2',
						Mensagem				= \"$local_Mensagem\",
						IdStatus				= '200',
						LoginCriacao			= '$local_Login', 
						DataCriacao				= (concat(curdate(),' ',curtime()));";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			if($Obs != ''){
				$Obs .= "\n";
			}
			
			$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Protocolo n° $local_IdProtocolo gerado a partir desta operação.";
		}
		
		$temp	=	"";
		$temp2	=	"";
		if($linContrato[DataTermino]!=$local_DataTermino){
			$temp		=	dataConv($local_DataTermino,'Y-m-d','d/m/Y');	
			$temp2		=	dataConv($linContrato[DataTermino],'Y-m-d','d/m/Y');
			
			$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Término Cont. [$temp2 > $temp]";
		}
		
		
		$temp	=	"";
		$temp2	=	"";
		if($linContrato[DataUltimaCobranca]!=$local_DataUltimaCobranca){
			$temp		=	dataConv($local_DataUltimaCobranca,'Y-m-d','d/m/Y');	
			$temp2		=	dataConv($linContrato[DataUltimaCobranca],'Y-m-d','d/m/Y');
			
			$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração de Data Última Cob. [$temp2 > $temp]";
		}
		
		if($local_Obs!=""){
			$local_Obs = str_replace("","",$local_Obs);
			$Obs	.=	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: ".trim($local_Obs);
		}
		
		if($BackupParametro == true){
			$BackupParametro = "";
			# BKP DE PARÂMETRO, SERVIÇO NORMAL
			$sql = "SELECT 
						ContratoParametro.IdServico,
						ContratoParametro.Valor,
						ServicoParametro.DescricaoParametroServico, 
						ServicoParametro.SalvarHistorico,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha
					FROM
						ContratoParametro,
						ServicoParametro 
					WHERE 
						ContratoParametro.IdLoja = '$local_IdLoja' AND 
						ContratoParametro.IdContrato = '$local_IdContrato' AND 
						ContratoParametro.IdLoja = ServicoParametro.IdLoja AND 
						ContratoParametro.IdServico = ServicoParametro.IdServico AND 
						ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico;";
			$res = @mysql_query($sql, $con);
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[SalvarHistorico] == 1){
					if($BackupParametro != "")
						$BackupParametro .= "\n";
					
					if($lin[IdTipoTexto] == 2){							
						if($lin[ExibirSenha] == 1){
							$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - $lin[DescricaoParametroServico]: $lin[Valor]";
						} else{
							$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - $lin[DescricaoParametroServico]";
						}
					} else{
						$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - $lin[DescricaoParametroServico]: $lin[Valor]";
					}
				}
			}
			# BKP DE PARÂMETRO, SERVIÇO AUTOMATICO
			$sql = "SELECT 
						ContratoParametro.IdServico,
						ContratoParametro.Valor,
						ServicoParametro.DescricaoParametroServico, 
						ServicoParametro.SalvarHistorico,
						ServicoParametro.IdTipoTexto,
						ServicoParametro.ExibirSenha
					FROM
						ContratoAutomatico,
						ContratoParametro,
						ServicoParametro 
					WHERE 
						ContratoAutomatico.IdLoja = '$local_IdLoja' AND 
						ContratoAutomatico.IdContrato = '$local_IdContrato' AND 
						ContratoParametro.IdLoja = ContratoAutomatico.IdLoja AND 
						ContratoParametro.IdContrato = ContratoAutomatico.IdContratoAutomatico AND 
						ContratoParametro.IdLoja = ServicoParametro.IdLoja AND 
						ContratoParametro.IdServico = ServicoParametro.IdServico AND 
						ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico;";
			$res = @mysql_query($sql, $con);
			
			while($lin = @mysql_fetch_array($res)){
				if($lin[SalvarHistorico] == 1){
					if($BackupParametro != "")
						$BackupParametro .= "\n";
					
					if($lin[IdTipoTexto] == 2){							
						if($lin[ExibirSenha] == 1){
							$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - Parâmetro Serviço Auto. ($lin[IdServico]) - $lin[DescricaoParametroServico]: $lin[Valor]";
						} else{
							$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - Parâmetro Serviço Auto. ($lin[IdServico]) - $lin[DescricaoParametroServico]";
						}
					} else{
						$BackupParametro .=	date("d/m/Y H:i:s")." [".$local_Login."] - Parâmetro Serviço Auto. ($lin[IdServico]) - $lin[DescricaoParametroServico]: $lin[Valor]";
					}
				}
			}
			
			if(!empty($BackupParametro)){
				if($Obs != "")
					$Obs .= "\n";
				
				$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Concluído backup dos parâmetros do contrato.";
				$Obs .= "\n".$BackupParametro;
				$Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Iniciando backup dos parâmetros do contrato.";
			}
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
		
		$Obs = str_replace("'","",$Obs);
		if($local_DataBloqueioStatus != "" || $local_DataBloqueioStatus == ""){			
			if($local_IdStatus == 200){						
				$sql	=	"UPDATE Contrato SET
								IdStatus			= '$local_IdStatus',
								VarStatus			= '$local_VarStatus',
								DataUltimaCobranca	= $local_DataUltimaCobranca,
								DataTermino			= $local_DataTermino,
								DataPrimeiraCobranca = '$local_DataReativacao',
								Obs					= '$Obs',	
								DataAlteracao		= (concat(curdate(),' ',curtime())),
								LoginAlteracao		= '$local_Login'
							WHERE 
								IdLoja				= $local_IdLoja and
								IdContrato			= $local_IdContrato;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
				$tr_i++;
			}
		}
		if($local_IdStatus != 200){
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
		}
		
		$y	=	0;
		$local_IdServicoTemp[$y]	=	$local_IdServico;	
		$sql2	=	"select IdContratoAutomatico from ContratoAutomatico where IdLoja = $local_IdLoja and IdContrato = $local_IdContrato";
		$res2	=	mysql_query($sql2,$con);
		while($lin2	=	mysql_fetch_array($res2)){
			$sql3	=	"select IdServico from Contrato where IdLoja = $local_IdLoja and IdContrato	= $lin2[IdContratoAutomatico];";
			$res3	=	mysql_query($sql3,$con);
			$lin3	=	mysql_fetch_array($res3);
					
			$local_IdServicoTemp[$y]	=	$lin3[IdServico];
			if($local_DataBloqueioStatus != "" || $local_DataBloqueioStatus == ""){
				if($local_IdStatus == 200){
					$sql	=	"
							UPDATE Contrato SET
								IdStatus			= '$local_IdStatus',
								VarStatus			= '$local_VarStatus',
								DataUltimaCobranca	= NULL,
								DataTermino			= $local_DataTermino,
								Obs					= '$Obs',	
								DataAlteracao		= (concat(curdate(),' ',curtime())),
								LoginAlteracao		= '$local_Login'
							WHERE 	
								IdLoja				= $local_IdLoja and
								IdContrato			= $lin2[IdContratoAutomatico];";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
					$tr_i++;
				}
			}
			if($local_IdStatus != 200){
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
							IdLoja				= $local_IdLoja and
							IdContrato			= $lin2[IdContratoAutomatico];";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);			
				$tr_i++;
			}
		}
		
		if($local_IdProtocolo != ''){
			$sql = "select
						IdMudancaStatus
					from
						ContratoStatus
					where 
						IdLoja = $local_IdLoja and
						IdContrato = $local_IdContrato
					order by 
						IdMudancaStatus desc 
					limit 1;";
			$res = mysql_query($sql, $con);
			$lin = mysql_fetch_array($res);
			
			$sql = "update ContratoStatus set
						IdProtocolo	= '$local_IdProtocolo'
					where 
						IdLoja = $local_IdLoja and
						IdContrato = $local_IdContrato and
						IdMudancaStatus = $lin[IdMudancaStatus];";
			$local_transaction[$tr_i] = mysql_query($sql,$con);		
			$tr_i++;
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
			
			
			if($cancelar == true){			
				$sqlRotinas = "select
									UrlRotinaCancelamento,
									UrlRotinaBloqueio,
									UrlRotinaDesbloqueio
								from
									Servico
								where
									IdLoja = $local_IdLoja and
									IdServico = $linContrato[IdServico] and
									(UrlRotinaCancelamento 	!= '' or UrlRotinaCancelamento is not NULL)  and
									(UrlRotinaBloqueio	!= '' or UrlRotinaBloqueio is not NULL) and
									(UrlRotinaDesbloqueio 	!= '' or UrlRotinaDesbloqueio is not NULL);";
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
				if(($local_IdStatus >= 300 && $local_IdStatus < 400 && $linRotinas[UrlRotinaBloqueio] != '' && $local_IdStatus != 306) || $local_IdStatus == 204){
					if($linRotinas[UrlRotinaBloqueio] !=""){
						include($linRotinas[UrlRotinaBloqueio]);
					}
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
			
			mysql_query($sqlTransacao,$con);

			derrubaConexaoRadius($local_IdLoja, $local_IdContrato);
			
			if($local_IdStatusTemp == 1 && $cancelar == true){			
				enviarEmailCancelamentoServico($local_IdLoja, $local_IdContrato, $local_EmailNotificacao, $local_IdProtocolo);
			}else{				
				enviarEmailAlteracaoStatusContrato($local_IdLoja, $local_IdContrato, $local_EmailNotificacao, $local_IdStatus, $local_DataBloqueioStatus, $local_IdProtocolo);
			}
		}else{
			$sqlTransacao = "ROLLBACK;";
			$local_Erro = 5;			// Mensagem de Edição Negativa
			
			mysql_query($sqlTransacao,$con);
		}
		
		if($localOperacao == 62){
			if($local_IdProtocolo != '' && $local_Erro != 5){
				header("Location: menu_protocolo_gerado.php?IdContrato=$local_IdContrato&IdPessoa=$local_IdPessoa&IdProtocolo=$local_IdProtocolo&Erro=$local_Erro");
			} else{
				header("Location: cadastro_contrato.php?IdContrato=$local_IdContrato&Erro=$local_Erro&EmailErro=$local_ErroEmail&TipoEmail=$local_TipoEmail");
			}
		}
	}
?>
