<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{	
		$local_Erro = '';
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		$local_IdStatusNovoTemp = $local_IdStatusNovo;
		$local_ObsAvulsa		= $local_Obs_OS;
		
		if($local_IdStatusNovo > 199 && $local_IdStatusNovo < 300) {
			$local_DataConclusao = "'" . date("Y-m-d H:i:s") . "'";
			$local_LoginConclusao = "'" . $local_Login . "'";
		} elseif($local_IdStatusNovo > 99 && $local_IdStatusNovo < 200) {
			$local_DataConclusao = "NULL";
			$local_LoginConclusao = "NULL";
		} else {
			if($local_DataConclusao == '') {
				$local_DataConclusao = "NULL";
			} else {
				$local_DataConclusao = "'" . date("Y-m-d H:i:s") . "'";
			}
			
			if($local_LoginConclusao == '') {
				$local_LoginConclusao = "NULL";
			} else {
				$local_LoginConclusao = "'" . $local_Login . "'";
			}
		}
		
		if($local_IdMarcador == "") 
			$local_IdMarcador = 'NULL';
		
		if($local_IdPessoaEnderecoTemp == "") 
			$local_IdPessoaEnderecoTemp = 'NULL';
		
		$sql3	="	select 
						IdGrupoUsuarioAtendimento, 
						EmAtendimento,
						LoginAtendimento,
						LoginSupervisor,
						LoginCriacao,
						DataAgendamentoAtendimento,
						IdStatus,
						LoginAlteracao,
						IdTipoOrdemServico
					from 
						OrdemServico 
					where 
						OrdemServico.IdLoja = $local_IdLoja and 
						OrdemServico.IdOrdemServico = $local_IdOrdemServico";
		$res3	=	mysql_query($sql3,$con);
		$lin3	=	mysql_fetch_array($res3);
		
		if($local_LoginSupervisor != ""){
			$LoginSupervisor = $local_LoginSupervisor;
		}else{
			$LoginSupervisor = $lin3[LoginSupervisor];
		}
		$local_LoginCriacaoAtual				=	$lin3[LoginCriacao];
		$local_IdGrupoUsuarioAtendimentoAtual	=	$lin3[IdGrupoUsuarioAtendimento];
		$local_DataAgendamentoAtendimentoAtual	=	$lin3[DataAgendamentoAtendimento];
		
		if($lin3[LoginAtendimento] != ''){
			$local_LoginAtendimentoAtual		=	$lin3[LoginAtendimento];
		}
		
		if($local_IdTipoOrdemServicoTemp == 2){
			$local_DescricaoOS	=	$local_DescricaoOSInterna;
			$local_Valor		= 	0.00;
			$local_ValorOutros	=	0.00;
		}else{
			$local_Valor		=	str_replace(".", "", $local_Valor);	
			$local_Valor		= 	str_replace(",", ".", $local_Valor);
			
			$local_ValorOutros	=	str_replace(".", "", $local_ValorOutros);	
			$local_ValorOutros	= 	str_replace(",", ".", $local_ValorOutros);
		}
		//if($local_DescricaoOSInterna != ""){
			
		//}
		
		if($local_IdServico != ""){
			$i = 1;
			$sql2	=	"select
							ServicoParametro.IdParametroServico,
							ServicoParametro.Editavel,
							ServicoParametro.ValorDefault,
							ServicoParametro.Unico,
							ServicoParametro.IdTipoAcesso
						from 
							Servico,
							ServicoParametro
						where
							Servico.IdLoja	  = $local_IdLoja and
							Servico.IdLoja	  = ServicoParametro.IdLoja and
							Servico.IdServico = ServicoParametro.IdServico and
							Servico.IdServico = $local_IdServico and
							ServicoParametro.IdStatus = 1
						";
			$res2	=	mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				switch($lin2[IdTipoAcesso]){
					case "1": # Administrativo
						$lin2[Permissao] = permissaoSubOperacao(1, 198, "V");
						break;
						
					case "2": # Técnico
						$lin2[Permissao] = permissaoSubOperacao(1, 199, "V");
						break;
						
					default:
						$lin2[Permissao] = false;
				}
				
				if($lin2[Permissao]){
					$sql3	=	"select IdParametroServico from OrdemServicoParametro where IdLoja = $local_IdLoja and IdOrdemServico = $local_IdOrdemServico and IdServico	= $local_IdServico and IdParametroServico = $lin2[IdParametroServico];";
					$res3	=	mysql_query($sql3,$con);
					
					if($lin2[Editavel] == 2 && $lin2[ValorDefault] != ""){
						$_POST['Valor_'.$lin2[IdParametroServico]]	= $lin2[ValorDefault];	
					}
					
					if($lin2[Unico] == 1){
						$sqlUnico = "select 
										count(*) Qtd
									from 
										Contrato,
										ContratoParametro
									where 
										Contrato.IdLoja = $local_IdLoja and 
										Contrato.IdLoja = ContratoParametro.IdLoja and 
										Contrato.IdContrato = ContratoParametro.IdContrato and 
										Contrato.IdServico = ContratoParametro.IdServico and 
										Contrato.IdStatus != 1 and
										ContratoParametro.Valor = '".trim($_POST['Valor_'.$lin2[IdParametroServico]])."';";
						$resUnico = @mysql_query($sqlUnico, $con);
						$linUnico = @mysql_fetch_array($resUnico);
						
						if($linUnico[Qtd] > 0) {
							$local_Erro = 142;
							$local_transaction[$tr_i] = false;
							$tr_i++;
						}
					}
					
					if(mysql_num_rows($res3) >= 1){	
						$sql	=	"
							UPDATE OrdemServicoParametro SET 
								Valor					='".$_POST['Valor_'.$lin2[IdParametroServico]]."'
							WHERE
								IdLoja					= $local_IdLoja and
								IdOrdemServico			= $local_IdOrdemServico and
								IdServico				= $local_IdServico and
								IdParametroServico		= $lin2[IdParametroServico];";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);				
						$tr_i++;
					} else{
						$sql	=	"
							INSERT INTO OrdemServicoParametro SET 
								Valor					='".$_POST['Valor_'.$lin2[IdParametroServico]]."',
								IdLoja					= $local_IdLoja,
								IdOrdemServico			= $local_IdOrdemServico,
								IdServico				= $local_IdServico,
								IdParametroServico		= $lin2[IdParametroServico];";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}
				}
			}
		}
		
		if($local_Data!=''){
			$local_DataHoraAgendamento	=	"'".dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora."'";
		}else{
			$local_DataHoraAgendamento	=	'NULL';
		}
		
		$sql4	=	"select Obs,IdStatus,FormaCobranca,IdLocalCobranca,ValorDespesaLocalCobranca from OrdemServico where IdLoja = $local_IdLoja and IdOrdemServico = $local_IdOrdemServico";
		$res4	=	@mysql_query($sql4,$con);
		$lin4	=	@mysql_fetch_array($res4);
		
		if($local_Hora != ""){
			$local_Hora	=	$local_Hora.":00";
		}
		if($local_IdStatusNovo != ""){
			$sql = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema = $local_IdStatusNovo";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);

			$local_HistoricoObs	.= 	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para: ".$lin[ValorParametroSistema];
			
			if($local_IdStatusNovo != $lin4[IdStatus]){
				$local_IdMarcador = 'NULL';
			}
			if($local_IdStatusNovo == 200){
				if($lin3[IdTipoOrdemServico] == 2){
					$sqlStatusConclusao = "select
												MudarStatusContratoConcluirOS,
												BaseDataStatusContratoOS
											from
												Servico
											where
												IdLoja = $local_IdLoja";
					$resStatusConclusao = mysql_query($sqlStatusConclusao,$con);
					$linStatusConclusao = mysql_fetch_array($resStatusConclusao);
				}else{
					$sqlStatusConclusao = "select
												MudarStatusContratoConcluirOS,
												BaseDataStatusContratoOS
											from
												Servico
											where
												IdLoja = $local_IdLoja and
												IdServico = $local_IdServico";
					$resStatusConclusao = mysql_query($sqlStatusConclusao,$con);
					$linStatusConclusao = mysql_fetch_array($resStatusConclusao);
				}
				if($linStatusConclusao[MudarStatusContratoConcluirOS] != ''){
					$local_IdStatus = $linStatusConclusao[MudarStatusContratoConcluirOS];

					if($local_IdStatus <= 199){
						$local_DataTerminoStatus		= date("d/m/Y");
						$local_DataUltimaCobrancaStatus = date("d/m/Y");
					}
					
					if($linStatusConclusao[MudarStatusContratoConcluirOS] == 201 || $linStatusConclusao[MudarStatusContratoConcluirOS] == 306){
						$local_DataBloqueioStatus = incrementaData(date("Y-m-d"), $linStatusConclusao[BaseDataStatusContratoOS]);
						$local_DataBloqueioStatus = dataConv($local_DataBloqueioStatus, "Y-m-d", "d/m/Y");
					}
					
					$local_ObsTemp = $local_Obs_OS;
					$local_Obs_OS	   = "";
					
					include('files/editar/editar_contrato_status.php');
					
					$local_HistoricoObs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Status Contrato Nº $local_IdContrato de acordo com agendamento.";
					$Obs = date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: De acordo com agendamento da OS Nº $local_IdOrdemServico.\n".$Obs;
					
					$sql = "UPDATE Contrato SET
								Obs					= '$Obs',	
								DataAlteracao		= (concat(curdate(),' ',curtime())),
								LoginAlteracao		= '$local_Login'
							WHERE 
								IdLoja				= $local_IdLoja and
								IdContrato			= $local_IdContrato;";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
					$tr_i++;
					
					$local_Obs_OS		= $local_ObsTemp;
					$local_ObsTemp	= "";
				}
			}
			$LoginSupervisor = $local_LoginSupervisor;
		}else{
			$sql7	=	"select 
							IdGrupoUsuarioAtendimento, 
							LoginAtendimento,
							LoginSupervisor,
							LoginCriacao,
							DataAgendamentoAtendimento,
							IdStatus,
							LoginAlteracao,
							ValorOutros,
							IdSubTipoOrdemServico,
							Valor,
							DescricaoOutros
						from 
							OrdemServico 
						where 
							OrdemServico.IdLoja = $local_IdLoja and 
							OrdemServico.IdOrdemServico = $local_IdOrdemServico";
			$res7	=	mysql_query($sql7,$con);
			$lin7	=	mysql_fetch_array($res7);
			
			if($local_LoginSupervisor != ""){
				$LoginSupervisor = $local_LoginSupervisor;
			}else{
				$LoginSupervisor = $lin7[LoginSupervisor];
			}
		}
		if($local_NovaDescricaoOsCDA != ""){
			if($local_DescricaoCDA != ""){
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				switch($local_NovaDescricaoOsCDA){
					case 1:
						$local_NovaDescricaoOsCDA = "Sim";
						break;
				}
				
				$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Nova Descrição OS CDA: ".$local_NovaDescricaoOsCDA;
			}
		}
		
		if($local_IdGrupoUsuarioAtendimento != ""){
			$sql2	=	"select DescricaoGrupoUsuario from GrupoUsuario where IdLoja = $local_IdLoja and IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			$sql6	="	select 
							LoginSupervisor 
						from
							GrupoUsuario 
						where
							IdLoja = $local_IdLoja and
							IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento";
			$res6	=	@mysql_query($sql6,$con);
			$lin6	=	@mysql_fetch_array($res6);
			
			if($local_LoginSupervisor == ""){
				if($lin6[LoginSupervisor] != ""){
					if($local_HistoricoObs != ""){
						$local_HistoricoObs	.=	"\n";	
					}
					$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Supervisor: ".$lin6[LoginSupervisor];
				}
			}else{
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Supervisor: ".$LoginSupervisor;
			}
			if($lin2[DescricaoGrupoUsuario]!=""){
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Grupo: ".$lin2[DescricaoGrupoUsuario];
			}
			if($local_LoginAtendimento!=""){
				$local_HistoricoObs	.= 	"\n".date("d/m/Y H:i:s")." [".$local_Login."] - Usuário: ".$local_LoginAtendimento;
			}
		}
		
		if($local_IdGrupoUsuarioAtendimento	==	''){
			if($local_IdGrupoUsuarioAtendimentoAtual != ""){
				$local_IdGrupoUsuarioAtendimento	=	$local_IdGrupoUsuarioAtendimentoAtual;
				
				if($local_LoginAtendimentoAtual != ""){
					$local_LoginAtendimento		=	"'$local_LoginAtendimentoAtual'";
					
					if($local_DataAgendamentoAtendimentoAtual!= ''){
						$local_DataHoraAgendamento	=	"'$local_DataAgendamentoAtendimentoAtual'";
					}else{
						$local_DataHoraAgendamento	= 'NULL';
					}
				} else{
					$local_LoginAtendimento = 'NULL';
				}
			} else{
				$local_IdGrupoUsuarioAtendimento = 'NULL';
			}
			
			if($local_DataHoraAgendamento == "NULL" && $local_DataAgendamentoAtendimentoAtual != '' && $local_IdStatusNovo == ''){
				$local_DataHoraAgendamento	=	"'$local_DataAgendamentoAtendimentoAtual'";
			}
		} else{
			$local_IdGrupoUsuarioAtendimento = "'$local_IdGrupoUsuarioAtendimento'";
			
			if($local_LoginAtendimento == ""){
				$local_LoginAtendimento = 'NULL';
			} else{
				$local_LoginAtendimento		=	"'$local_LoginAtendimento'";
			}
		}
		
		$sql12	=	"select 
							IdGrupoUsuarioAtendimento, 
							LoginAtendimento,
							LoginSupervisor,
							LoginCriacao,
							DataAgendamentoAtendimento,
							IdStatus,
							LoginAlteracao,
							ValorOutros,
							IdSubTipoOrdemServico,
							Valor,
							DescricaoOutros
						from 
							OrdemServico 
						where 
							OrdemServico.IdLoja = $local_IdLoja and 
							OrdemServico.IdOrdemServico = $local_IdOrdemServico";
		$res12	=	mysql_query($sql12,$con);
		$lin12	=	mysql_fetch_array($res12);
		
		
		if($local_ValorOutros != $lin12[ValorOutros]){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";
			}
			if($local_ValorOutros == ""){
				$local_ValorOutros = '0.00';
			}
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Outros Valores (R$) alterado  [".str_replace(".",",",$lin12[ValorOutros])." > ".str_replace(".",",",$local_ValorOutros)."]";
		}
		
		if($local_Justificativa != $lin12[DescricaoOutros]){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";
			}		
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Justificativa Outros Valores (R$) alterado [".$lin12[DescricaoOutros]." > ".$local_Justificativa."]";
		}
		
		if($local_Valor != $lin12[Valor]){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";
			}
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Valor (R$) alterado  [".str_replace(".",",",$lin12[Valor])." > ".str_replace(".",",",$local_Valor)."]";
		}
		
		if($local_IdSubTipoOrdemServicoTemp != $lin12[IdSubTipoOrdemServico] && $local_IdSubTipoOrdemServicoTemp != ""){
			if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";
				}
			$sqlSubTipoOSAntigo = "SELECT 
								DescricaoSubTipoOrdemServico 
							FROM
								SubTipoOrdemServico 
							WHERE
								IdSubTipoOrdemServico = $lin12[IdSubTipoOrdemServico]";
			$resSubTipoOsAntigo = mysql_query($sqlSubTipoOSAntigo, $con);
			$linSubTipoOsAntigo = mysql_fetch_array($resSubTipoOsAntigo);
			
			$sqlSubTipoOS = "SELECT 
								DescricaoSubTipoOrdemServico 
							FROM
								SubTipoOrdemServico 
							WHERE
								IdSubTipoOrdemServico = $local_IdSubTipoOrdemServicoTemp";
			$resSubTipoOs = mysql_query($sqlSubTipoOS, $con);
			$linSubTipoOs = mysql_fetch_array($resSubTipoOs);

			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - SubTipo OS alterado [".$linSubTipoOsAntigo[DescricaoSubTipoOrdemServico]." > ".$linSubTipoOs[DescricaoSubTipoOrdemServico]."]";
		}

		if($local_Data != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";
			}
			
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Agendado para: ".$local_Data." ".$local_Hora;
		}
		
		if($local_Obs_OS != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".$local_Obs_OS;
		}
		
		if($local_DescricaoOS !=""){
			$sql5 = "SELECT
						DescricaoOS
					FROM
						OrdemServico
					WHERE
						IdLoja = '$local_IdLoja' AND
						IdOrdemServico = '$local_IdOrdemServico';";
			$res5 = @mysql_query($sql5,$con);
			$lin5 = @mysql_fetch_array($res5);
			
			if($local_DescricaoOS != $lin5['DescricaoOS']){
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				
				$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Descrição Ordem de Serviço - (Descrição OS alterado [".$lin5['DescricaoOS']." > ".$local_DescricaoOS."])";
			}
		}
		
		/*if($local_Obs_OS != ""){;;//Leonardo - 19-04-13 - Desabilitado por estar causando redundancia de informação!
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".$local_Obs_OS;
		}*/
		
		if($local_FormaCobrancaTemp == ""){		
			if($lin4[FormaCobranca]!=""){
				$local_FormaCobrancaTemp = $lin4[FormaCobranca];
			} else{
				$local_FormaCobrancaTemp = "NULL";
			}			
		}
		
		$local_ValorFinal	=	str_replace(".", "", $local_ValorFinal);	
		$local_ValorFinal	= 	str_replace(",", ".", $local_ValorFinal);
		
		if($local_ValorFinal > 0){
			if($local_IdStatusNovo >= 200 &&  $local_IdStatusNovo <= 299){  //Concluido
				$local_IdStatusNovo	=	400; //Enc. Faturamento
			}
		}
		
		if($local_HistoricoObs	!= ""){
			$local_HistoricoObs	.= "\n".$lin4[Obs];
		} else{
			$local_HistoricoObs	=	$lin4[Obs];	
		}
		
		if($local_IdStatusNovo==""){
			$local_IdStatusNovo	=	$lin4[IdStatus];
		}
		
		if($local_LoginAtendimento == "")    $local_LoginAtendimento	= "NULL";
		
		if($local_Justificativa == "") {
			$local_Justificativa = NULL;
		} else {
			$local_Justificativa = "$local_Justificativa";
		}
		
		if($local_IdTipoOrdemServicoTemp == 2){
			$local_FormaCobrancaTemp   			= 	'NULL';
		}

		$local_HistoricoObs = str_replace('"', "'", $local_HistoricoObs);
		if($local_IdAgenteAutorizado == ""){
			$local_IdAgenteAutorizado = 'NULL';
		}
		if($local_IdCarteira == "" || $local_IdCarteira == 0){
			$local_IdCarteira = 'NULL';
		}
		if($local_CancelarOrdemServico == 1){
			$sql = "
			SELECT
				COUNT(*) QtdParcelaQuitada,
				Parcela.QtdParcelaAtiva,
				((
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados 
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$local_IdLoja' AND
						LancamentoFinanceiroDados.IdOrdemServico = $local_IdOrdemServico
					GROUP BY 
						LancamentoFinanceiroDados.IdOrdemServico
				) = (
					SELECT 
						COUNT(*) 
					FROM
						LancamentoFinanceiroDados 
					WHERE 
						LancamentoFinanceiroDados.IdLoja = '$local_IdLoja' AND 
						LancamentoFinanceiroDados.IdStatus = '0' AND
						LancamentoFinanceiroDados.IdOrdemServico = $local_IdOrdemServico
					GROUP BY 
						LancamentoFinanceiroDados.IdOrdemServico
				)) LancamentosCancelados 
			FROM
				(
					SELECT
						LancamentoFinanceiroDados.IdLoja,
						LancamentoFinanceiroDados.IdOrdemServico,
						COUNT(LancamentoFinanceiroDados.IdOrdemServico) QtdParcelaAtiva,
						LancamentoFinanceiroDados.IdStatus
					FROM
						LancamentoFinanceiroDados
					WHERE
						LancamentoFinanceiroDados.IdLoja = '$local_IdLoja' AND
						(
							LancamentoFinanceiroDados.IdStatus = '1' OR
							LancamentoFinanceiroDados.IdStatus = '2'
						) AND
						LancamentoFinanceiroDados.IdOrdemServico = $local_IdOrdemServico
					GROUP BY
						LancamentoFinanceiroDados.IdOrdemServico
				)Parcela,
				LancamentoFinanceiroDados
			WHERE
				LancamentoFinanceiroDados.IdLoja = Parcela.IdLoja AND
				LancamentoFinanceiroDados.IdOrdemServico = Parcela.IdOrdemServico AND
				LancamentoFinanceiroDados.IdStatus = Parcela.IdStatus AND
				LancamentoFinanceiroDados.IdStatusContaReceber = '2'";
			$res = @mysql_query($sql,$con);
			if($lin = @mysql_fetch_array($res)){
				if($lin[LancamentosCancelados] == 1) {
					$sql = "UPDATE OrdemServico SET
							IdStatus					= 0,
							Obs							= \"$local_Obs\",
							DataAlteracao				= (concat(curdate(),' ',curtime())),
							LoginAlteracao				= '$local_Login'
						WHERE
							IdLoja = $local_IdLoja and
							IdOrdemServico = $local_IdOrdemServico;";
					
					$local_transaction[$tr_i] = mysql_query($sql,$con);	
					$tr_i++;
				}
			}
		}else{
			$sql = "UPDATE OrdemServico SET 
						DescricaoOS					= \"$local_DescricaoOS\",
						DescricaoOutros				= \"$local_Justificativa\",
						ValorOutros					= \"$local_ValorOutros\",
						Valor						= $local_Valor,
						ValorTotal					= $local_Valor,
						LoginAtendimento			= $local_LoginAtendimento,
						IdSubTipoOrdemServico		= '$local_IdSubTipoOrdemServicoTemp',
						IdGrupoUsuarioAtendimento	= $local_IdGrupoUsuarioAtendimento,
						DataAgendamentoAtendimento	= $local_DataHoraAgendamento,
						Obs							= \"$local_HistoricoObs\",
						IdStatus					= $local_IdStatusNovo,
						IdMarcador					= $local_IdMarcador,
						FormaCobranca				= $local_FormaCobrancaTemp,
						IdPessoaEndereco			= '$local_IdPessoaEnderecoTemp',
						IdPessoaEnderecoCobranca	= NULL,	
						IdAgenteAutorizado			= $local_IdAgenteAutorizado,
						IdCarteira					= $local_IdCarteira,
						DataConclusao				= $local_DataConclusao,	
						LoginConclusao				= $local_LoginConclusao,
						LoginSupervisor				= '$LoginSupervisor',	
						DataAlteracao				= (concat(curdate(),' ',curtime())),
						LoginAlteracao				= '$local_Login',
						DescricaoCDA 				= \"$local_DescricaoCDA\"
					WHERE
						IdLoja = $local_IdLoja and
						IdOrdemServico = $local_IdOrdemServico;";
			$local_transaction[$tr_i] = mysql_query($sql,$con);	
			$tr_i++;
		}
		$IdEcluirAnexos = explode(',', $local_EcluirAnexos);
		
		for($i = 1; $i < count($IdEcluirAnexos); $i++){
			$sql = "
				SELECT
					NomeOriginal
				FROM
					OrdemServicoAnexo
				WHERE
					IdLoja = '$local_IdLoja' AND
					IdOrdemServico = '$local_IdOrdemServico' AND
					IdAnexo = '$IdEcluirAnexos[$i]';";
			$res = @mysql_query($sql,$con);
			if($lin = @mysql_fetch_array($res)){
				$ext = endArray(explode(".", $lin[NomeOriginal]));
				$url = "./anexos/ordem_servico/".$local_IdOrdemServico."/".$IdEcluirAnexos[$i].".".$ext;
				
				$sql = "
					DELETE FROM 
						OrdemServicoAnexo 
					WHERE 
						IdLoja = '$local_IdLoja' AND
						IdOrdemServico = '$local_IdOrdemServico' AND
						IdAnexo = '$IdEcluirAnexos[$i]';";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				if($local_transaction[$tr_i] == true){
					@unlink($url);
				}
				
				$tr_i++;
			}
		}
		
		for($i = 1; $i <= $_POST["MaxUploads"]; $i++){
			if($_POST['fakeupload_'.$i] != '' && $_POST['DescricaoArquivo_'.$i] != ''){
				$sql = "
					SELECT 
						(MAX(IdAnexo)+1) IdAnexo
					FROM 
						OrdemServicoAnexo
					WHERE 
						IdLoja = '$local_IdLoja' AND
						IdOrdemServico = '$local_IdOrdemServico';
				";
				$res = @mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if($lin['IdAnexo'] == ''){
					$lin['IdAnexo'] = 1;
				}
				
				$local_NomeOriginal	= $_FILES['EndArquivo_'.$i]['name'];
				$local_ExtArquivo	= endArray(explode(".", $local_NomeOriginal));
				$local_MD5			= md5($local_IdLoja.$local_IdOrdemServico.$lin[IdAnexo]);
				
				if(in_array(strtolower($local_ExtArquivo), $extensao_anexo)){
					$sql = "
						INSERT INTO
							OrdemServicoAnexo
						SET
							IdLoja			= '$local_IdLoja',
							IdOrdemServico	= '$local_IdOrdemServico',
							IdAnexo			= '$lin[IdAnexo]',
							DescricaoAnexo	= '".$_POST['DescricaoArquivo_'.$i]."',
							NomeOriginal	= '".$local_NomeOriginal."',
							MD5				= '$local_MD5';";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
					
					if($local_transaction[$tr_i]){
						if($local_ExtArquivo != ''){
							$local_ExtArquivo	= '.'.$local_ExtArquivo;
						}
						
						@mkdir("./anexos/ordem_servico/".$local_IdOrdemServico,0770);
						
						$EnderecoArquivo = "./anexos/ordem_servico/".$local_IdOrdemServico.'/'.$lin[IdAnexo].$local_ExtArquivo;
						
						if(!@copy($_FILES['EndArquivo_'.$i]['tmp_name'], $EnderecoArquivo)){
							@rmdir("./anexos/ordem_servico/".$local_IdOrdemServico);
							
							$local_transaction[$tr_i] = false;
						}
					}
				} else{
					$local_transaction[$tr_i] = false;
				}
				
				$tr_i++;
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){
			$local_LoginAtendimento = str_replace("'","",$local_LoginAtendimento);
			
			# SMS
			# 1) Envia quando a OS está em atendimento		
			
			if($local_IdStatusNovo >= 0 && $local_IdStatusNovo <= 399 && $lin3[EmAtendimento] == 1 && $local_ObsAvulsa != ""){
				# 2) Envia quando tem um atendente selecionado ou já preencido na OS e é diferente do usuário de alteração
				if($local_LoginAtendimento != '' && $local_LoginAtendimento != 'NULL' && $local_LoginAtendimento != NULL && $local_LoginAtendimento != $lin3[LoginAlteracao]){

					enviarSMSAtendenteMudancaStatusOrdemServico($local_IdLoja, $local_IdOrdemServico);
				}
			}
			
			# E-MAIL
			if(($local_LoginAtendimento != "" && $local_UsuarioAtendimentoAntigo != "") && ($local_LoginAtendimento != "NULL" && $local_UsuarioAtendimentoAntigo != "NULL")){
				if($local_LoginAtendimento != $local_UsuarioAtendimentoAntigo){
					enviarEmailOrdemServicoAtendente($local_IdLoja, $local_IdOrdemServico);
				}
			}
			
			# E-MAIL
			if($local_IdStatusNovoTemp > 199 && $local_IdStatusNovoTemp < 300){
				enviarEmailConclusaoOrdemServico($local_IdLoja, $local_IdOrdemServico);
			}
			$sql = "COMMIT;";
			$local_Erro = 4;
		} else{
			$sql = "ROLLBACK;";
			
			if($local_Erro == ''){
				$local_Erro = 5;	
			}
		}
		mysql_query($sql,$con);
	}
?>
