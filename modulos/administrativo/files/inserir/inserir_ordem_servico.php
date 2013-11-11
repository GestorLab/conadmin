<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de OrdemServico
		$sql	=	"select (max(IdOrdemServico)+1) IdOrdemServico from OrdemServico where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdOrdemServico]!=NULL){
			$local_IdOrdemServico	=	$lin[IdOrdemServico];
		}else{
			$local_IdOrdemServico	=	1;
		}
		
		$local_IdStatusNovoTemp = $local_IdStatusNovo;
		
		if($local_IdStatusNovo > 199 && $local_IdStatusNovo < 300) {
			$local_DataConclusao = "'".date("Y-m-d H:i:s")."'";
			$local_LoginConclusao = "'".$local_Login."'";
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
		
		if($local_IdContrato == "")
			$local_IdContrato = 'NULL';
		
		if($local_IdPessoa == "")
			$local_IdPessoa = 'NULL';
			
		if($local_IdPessoaEndereco == '')
			$local_IdPessoaEndereco = 'NULL';
		
		if($local_IdAgenteAutorizado == "")
			$local_IdAgenteAutorizado = 'NULL';
		
		if($local_IdCarteira == "")
			$local_IdCarteira = 'NULL';
		
		if($local_IdServico == ""){
			 $local_IdServico	= 'NULL';
			 $local_Valor		= "0.00";
			 $local_ValorOutros	= "0.00";
		}else{
			$local_Valor		=	str_replace(".", "", $local_Valor);	
			$local_Valor		= 	"'".str_replace(",", ".", $local_Valor)."'";
			
			$local_ValorOutros	=	str_replace(".", "", $local_ValorOutros);	
			$local_ValorOutros	= 	"'".str_replace(",", ".", $local_ValorOutros)."'";
		}
		
		if($local_Data!=''){
			$local_DataHoraAgendamento	=	"'".dataConv($local_Data,'d/m/Y','Y-m-d')." ".$local_Hora.":00'";
		}else{
			$local_DataHoraAgendamento	=	'NULL';
		}
		
		if($local_IdGrupoUsuarioAtendimento == ''){
			$local_IdGrupoUsuarioAtendimento	=	'NULL';
		}
		
		$sql2	=	"select DescricaoGrupoUsuario from GrupoUsuario where IdLoja = $local_IdLoja and IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento";
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);
		
		$sql4	="	select 
						LoginSupervisor 
					from
						GrupoUsuario 
					where
						IdLoja = $local_IdLoja and
						IdGrupoUsuario = $local_IdGrupoUsuarioAtendimento";
		$res4	=	@mysql_query($sql4,$con);
		$lin4	=	@mysql_fetch_array($res4);
		
		if($local_Hora != ""){
			$local_Hora	=	$local_Hora.":00";
		}
		
		if($local_LoginSupervisor == ""){
			if($lin4[LoginSupervisor] != ""){
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Supervisor: ".$lin4[LoginSupervisor];
			}
		}else{
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Supervisor: ".$local_LoginSupervisor;
		}
		if($lin2[DescricaoGrupoUsuario] != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			$local_HistoricoObs	.=	date("d/m/Y H:i:s")." [".$local_Login."] - Grupo: ".$lin2[DescricaoGrupoUsuario];
		}
		if($local_LoginAtendimento != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			$local_HistoricoObs	.= 	date("d/m/Y H:i:s")." [".$local_Login."] - Usuário: ".$local_LoginAtendimento;
		}
		
		if($local_Data != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Agendado para: ".$local_Data." ".$local_Hora;
		}
		
		if($local_Obs != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".$local_Obs;
		}
		
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$local_IdStatusNovo";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		if($local_HistoricoObs != "" || $local_IdStatusNovo == 200){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			if($local_IdStatusNovo >= 200 && $local_IdStatusNovo <= 299)
				$local_HistoricoObs .=	date("d/m/Y H:i:s")." [".$local_Login."] - Ordem de Serviço cadastrada com o status: $lin3[ValorParametroSistema]";
			else
				$local_HistoricoObs .=	date("d/m/Y H:i:s")." [".$local_Login."] - Mudou status para: $lin3[ValorParametroSistema]";
		}
		
		if($local_NovaDescricaoOsCDA != ""){
			if($local_HistoricoObs != ""){
				$local_HistoricoObs	.=	"\n";	
			}
			switch($local_NovaDescricaoOsCDA){
				case 1:
					$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Nova Descrição OS CDA: Sim";
					break;
			}
		}
		
		if($local_IdStatusNovo == 200){
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
				
				$local_ObsTemp = $local_Obs;
				$local_Obs	   = "";
				
				include('files/editar/editar_contrato_status.php');
				
				if($local_HistoricoObs != ""){
					$local_HistoricoObs	.=	"\n";	
				}
				
				$local_HistoricoObs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração Status Contrato Nº $local_IdContrato de acordo com agendamento.";
				$Obs = date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: De acordo com agendamento da OS Nº $local_IdOrdemServico.\n".$Obs;
				
				$sql = "UPDATE Contrato SET
							Obs					= \"$Obs\",	
							DataAlteracao		= (concat(curdate(),' ',curtime())),
							LoginAlteracao		= '$local_Login'
						WHERE 
							IdLoja				= $local_IdLoja and
							IdContrato			= $local_IdContrato;";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);		
				$tr_i++;
				
				$local_Obs		= $local_ObsTemp;
				$local_ObsTemp	= "";
			}
		}
		
		if($local_LoginAtendimento == ""){
			$local_LoginAtendimento	=	"NULL";
		}else{
			$local_LoginAtendimento	=	"'$local_LoginAtendimento'";
		}
		
		$local_Erro = '';
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		
		$local_ValorFinal	=	str_replace(".", "", $local_ValorFinal);	
		$local_ValorFinal	= 	str_replace(",", ".", $local_ValorFinal);
		
		if($local_ValorFinal > 0){
			if($local_IdStatusNovo >= 200 && $local_IdStatusNovo <= 299){  //Concluido
				$local_IdStatusNovo	=	400; //Enc. Faturamento
			}
		}
		
		if($local_FormaCobrancaTemp == ""){
			$local_FormaCobrancaTemp   = 'NULL';
		}
		
		if($local_Justificativa == ""){
			$local_Justificativa = 'NULL';
		} else {
			$local_Justificativa = "'$local_Justificativa'";
		}
		
		if($local_IdTipoOrdemServico == 2){
			$local_DescricaoOS					=	$local_DescricaoOSInterna;
			$local_IdPessoa						=	'NULL';
			$local_IdContrato					=	'NULL';
			$local_IdServico					=	'NULL';
			$local_Valor						= 	'0.00';
			$local_FormaCobrancaTemp   			= 	'NULL';
		}
		
		if($local_IdMarcador == '' || $local_IdMarcador == 0 || ($local_IdStatusNovo <= 99 || $local_IdStatusNovo >= 200)){ //$local_IdStatusNovo != 1
			$local_IdMarcador	=	'NULL';
		}
		
		$local_DescricaoOS		= str_replace('"','\"',$local_DescricaoOS);
		$local_HistoricoObs		= str_replace('"','\"',$local_HistoricoObs);
		$local_Justificativa	= str_replace('"','\"',$local_Justificativa);
		$local_DescricaoCDA		= str_replace('"','\"',$local_DescricaoCDA);
		
		$sql = "INSERT INTO OrdemServico SET 
					IdLoja						= $local_IdLoja,
					IdOrdemServico				= $local_IdOrdemServico,
					IdTipoOrdemServico			= '$local_IdTipoOrdemServico',	
					IdSubTipoOrdemServico		= '$local_IdSubTipoOrdemServico',
					IdPessoa					= $local_IdPessoa,
					IdServico					= $local_IdServico,  
					IdContrato					= $local_IdContrato,
					DescricaoOS					= \"$local_DescricaoOS\",
					ValorOutros					= $local_ValorOutros,
					Valor						= $local_Valor,
					ValorTotal					= $local_Valor,
					ValorDespesaLocalCobranca	= '0.00',
					Obs							= \"$local_HistoricoObs\",
					DescricaoOutros				= $local_Justificativa,
					IdStatus					= $local_IdStatusNovo,
					IdGrupoUsuarioAtendimento	= $local_IdGrupoUsuarioAtendimento,
					LoginAtendimento			= $local_LoginAtendimento,
					LoginSupervisor				= '$local_LoginSupervisor',
					DataAgendamentoAtendimento	= $local_DataHoraAgendamento,
					DataConclusao				= $local_DataConclusao,
					LoginConclusao				= $local_LoginConclusao,
					FormaCobranca				= $local_FormaCobrancaTemp,
					IdPessoaEndereco			= $local_IdPessoaEndereco,
					IdPessoaEnderecoCobranca	= NULL,
					IdAgenteAutorizado			= $local_IdAgenteAutorizado,
					IdCarteira					= $local_IdCarteira,
					IdMarcador					= $local_IdMarcador,
					EmAtendimento				= 2,
					MD5							= md5(concat($local_IdLoja, $local_IdOrdemServico)),
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login',
					DescricaoCDA 				= \"$local_DescricaoCDA\";";
		$local_transaction[$tr_i] = mysql_query($sql,$con);	
		$tr_i++;
		
		$i = 1;
		$sql2	=	"select
						ServicoParametro.IdParametroServico,
						ServicoParametro.Editavel,
						ServicoParametro.ValorDefault,
						ServicoParametro.Unico
					from 
						Loja,
						Servico,
						ServicoParametro
					where
						Servico.IdLoja	  = $local_IdLoja and
						Servico.IdLoja = Loja.IdLoja and
						Servico.IdLoja = ServicoParametro.IdLoja and
						Servico.IdServico = ServicoParametro.IdServico and
						Servico.IdServico = $local_IdServico and
						ServicoParametro.IdStatus = 1";
		$res2	=	mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			if($lin2[Editavel]==2 && $lin2[ValorDefault]!=""){
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
			
			$sql	=	"
				INSERT INTO OrdemServicoParametro SET 
					IdLoja 					= $local_IdLoja,
					IdOrdemServico			= $local_IdOrdemServico,
					IdServico				= $local_IdServico,
					IdParametroServico		= $lin2[IdParametroServico],
					Valor					='".$_POST['Valor_'.$lin2[IdParametroServico]]."';";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
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
				$local_ExtArquivo	= endArray(explode(".",$local_NomeOriginal));
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
							MD5				= '$local_MD5';
					";
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
			$sql = "COMMIT;";
			mysql_query($sql,$con);

			$local_LoginAtendimento = str_replace("'","",$local_LoginAtendimento);
	
			if($local_IdStatusNovo >= 100 && $local_IdStatusNovo <= 199){
				if($local_LoginAtendimento != '' && $local_LoginAtendimento != 'NULL' && $local_LoginAtendimento != NULL && $local_LoginAtendimento != $local_Login){		
					if($local_IdTipoOrdemServico != 2)						
						enviarSMSAtendenteMudancaStatusOrdemServico($local_IdLoja, $local_IdOrdemServico);
				}
			}
			
			enviarEmailAberturaOrdemServico($local_IdLoja, $local_IdOrdemServico);
			
			if($local_LoginAtendimento != "" && $local_LoginAtendimento != "NULL"){				
				enviarEmailOrdemServicoAtendente($local_IdLoja, $local_IdOrdemServico);
			}

			if($local_IdStatusNovoTemp > 199 && $local_IdStatusNovoTemp < 300){				
				enviarEmailConclusaoOrdemServico($local_IdLoja, $local_IdOrdemServico);
			}

			// Muda a ação para Inserir
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		} else{
			$sql = "ROLLBACK;";
			mysql_query($sql,$con);

			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			
			if($local_Erro == ''){
				$local_Erro = 8;			// Mensagem de Inserção Negativa
			}
		}		
	}
?>