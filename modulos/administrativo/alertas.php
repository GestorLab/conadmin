<?
	$alerta = '';
	$i		= 0;
	$j		= 0;
	
	$sqlLoja = "select
					RestringirPessoa
				from
					Loja
				where
					IdLoja = $local_IdLoja";
	$resLoja = mysql_query($sqlLoja,$con);
	$linLoja = mysql_fetch_array($resLoja);
	
	if($linLoja[RestringirPessoa] == 1){
		$where = " and ( 
			IdLoja = $local_IdLoja or 
			IdLoja is null 
		)";
	}

	##### Verifica se a quantidade de contratos está acabando;
	if(ContratoFree() == true){
		if(QtdContrato() >= (QtdContratoFree()-(QtdContratoFree()/10)) && QtdContratoFree() != 0){
			$alerta[$i] = "Atenção: A quantidade de contratos liberada para sua licença está acabando. Você ainda pode cadastrar ".(QtdContratoFree()-QtdContrato())." contrato(s).";
			$i++;
		}
	}else{
		$alerta[$i] = "Atenção: A quantidade de contratos liberada para sua licença expirou.<BR>Você possui ".QtdContrato()." contratos cadastrados.";
		$i++;
	}
	#Verifica se existem e-mail's não enviados	
	$sqlEmail = "SELECT
						HistoricoMensagem.IdHistoricoMensagem,
						HistoricoMensagem.Assunto,
						HistoricoMensagem.DataCriacao,
						HistoricoMensagem.DataEnvio,
						HistoricoMensagem.IdStatus,
						TipoMensagem.DelayDisparo
					FROM
						HistoricoMensagem,
						TipoMensagem
					WHERE
					TipoMensagem.IdTipoMensagem = HistoricoMensagem.IdTipoMensagem AND
					SUBSTRING(HistoricoMensagem.DataEnvio,12,20) < SUBSTRING(HistoricoMensagem.DataCriacao,12,20) + TipoMensagem.DelayDisparo AND
					SUBSTRING(HistoricoMensagem.DataEnvio,1,10) = '".date("Y-m-d")."' AND 
					HistoricoMensagem.IdStatus <> 2 AND
					HistoricoMensagem.IdStatus <> 4 AND
					HistoricoMensagem.IdStatus <> 6 AND
					HistoricoMensagem.IdStatus <> 5";						
	$resEmail = mysql_query($sqlEmail, $con);
	$linEmail = mysql_num_rows($resEmail);
	
	$email = "";
	if($linEmail > 1 ){
		$email = "e-mails";
	}else{
		$email = "e-mail";
	}
	if($linEmail > 0 ){
		$alerta[$i] = "Atenção:  O sistema não está conseguindo enviar $linEmail $email por favor verifique.";
		$i++;
	}
	##### Verifica existência de configuração de recurso de backup
	if(trim(getParametroSistema(83, 20)) == ''){
		$alerta[$i] = "Atenção:  O sistema de backup não está configurado. Para segurança de suas informações, entre em contato com o suporte.";
		$i++;
	}else{
		##### Verifica último backup
		$sqlLogBackup = "select
							DataHoraInicio,
							DataHoraConclusao,
							Log,
							Erro
						from
							Backup
						order by
							DataHoraInicio DESC
						limit 0,1";
		$resLogBackup = mysql_query($sqlLogBackup,$con);
		if(mysql_num_rows($resLogBackup) >= 1){
			
			$linLogBackup = mysql_fetch_array($resLogBackup);

			if($linLogBackup[DataHoraConclusao] == '' && $linLogBackup[Erro] == 0){
				$alerta2[$j] = "Atenção: Backup em execução. Seu sistema pode ficar lento durante este período.<br>Backup iniciou em: ".dataConv($linLogBackup[DataHoraInicio],"Y-m-d H:i:s","d/m/Y H:i:s");
				$j++;
			}else{
				if(stripos($linLogBackup[Log],"erro") || $linLogBackup[Erro] == 1){

					$sqlUltimoBackup = "SELECT
											DataHoraInicio
										FROM
											Backup
										WHERE
											Erro = 0 AND
											DataHoraConclusao IS NOT NULL
										ORDER BY
											DataHoraInicio DESC
										LIMIT 0,1";
					$resUltimoBackup = mysql_query($sqlUltimoBackup,$con);
					$linUltimoBackup = mysql_fetch_array($resUltimoBackup);

					$IntervaloUltimoBackup = IntervaloMKTime(date("Y-m-d H:i:s"),$linUltimoBackup[DataHoraInicio]);

					if($IntervaloUltimoBackup[d] < 1){
						// Horas
						$alerta[$i] = $IntervaloUltimoBackup[H]." horas"; // converter para horas...
					}else{
						// Dias
						$alerta[$i] = $IntervaloUltimoBackup[d]." dias";
					}
					
					$alerta[$i] = "Atenção: Há $alerta[$i] que não é feito backup. Favor entrar em contato com o suporte.";
					$i++;

					$alerta[$i] = "Atenção: Erro ao executar o backup!";

					$linLogBackup[Log] = str_replace("\n","<br>",$linLogBackup[Log]);

					if($linLogBackup[Log] != ''){
						$alerta[$i] .= " Veja os erros abaixo: <br><br>";
						$alerta[$i] .= $linLogBackup[Log];
					}
					$alerta[$i] .= "<BR><BR>Favor entrar em contato com o suporte.";
					$i++;
				}
			}
		}else{
			$alerta[$i] = "Atenção:  Não há registros de backup. Favor entrar em contato com o suporte.";
			$i++;
		}
	}
	##############################################################

	##### Verifica se o sistema de envio de e-mails está configurado
	$sqlc1 = "select count(*) Qtd	from ContaEmail where IdLoja=$local_IdLoja and IdContaEmail != 0";
	$resc1 = @mysql_query($sqlc1,$con);
	$linc1 = @mysql_fetch_array($resc1);
	if($linc1[Qtd] == 1){
		$sql = "select 
					count(*) Qtd 
				from
					ContaEmail 
				where
					DescricaoContaEmail != '' and
					NomeRemetente != '' and
					EmailRemetente != '' and
					ServidorSMTP != '' and
					Porta != '' and
					RequerAutenticacao != '' and
					Usuario != '' and
					Senha != '' and
					NomeResposta != '' and
					EmailResposta != '' and
					IntervaloEnvio != '' and
					QtdTentativaEnvio != '' and
					IdContaEmail != 0";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[Qtd] == 0){
			$alerta[$i] = "Atenção:  O sistema de envio de e-mails não está configurado. Para ativar, entre em contato com o suporte.";
		}
	}else{
		if($linc1[Qtd] > 1){
			$sql="	select 
						count(*) Qtd 
					from
						ContaEmail 
					where
						Usuario != '' and
						Senha != ''";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[Qtd] == 0){
				$alerta[$i] = "Atenção:  O sistema de envio de e-mails não está configurado. Para ativar, entre em contato com o suporte.";
			}			
		}
	}

	$sql  = " select 
				 ContaEmail.IdContaEmail,
				 TipoMensagem.IdTipoMensagem
			  from
				 ContaEmail,				
				 TipoMensagem
			  where 
				 ContaEmail.IdLoja = $local_IdLoja and
				 ContaEmail.IdLoja = TipoMensagem.IdLoja and
				 TipoMensagem.IdTipoMensagem != 38 and
				 ContaEmail.IdContaEmail > 0 and
				 ContaEmail.IdContaEmail = TipoMensagem.IdContaEmail and				
				 TipoMensagem.Titulo = 'Teste de Conta de Email'";
	$resContaEmail = @mysql_query($sql,$con);
	while($linContaEmail = @mysql_fetch_array($resContaEmail)){
		
		$sql = "select 
					 count(*) Qtd
				from
					 HistoricoMensagem					 
				where 
					 HistoricoMensagem.IdLoja = $local_IdLoja and					
					 HistoricoMensagem.IdTipoMensagem = $linContaEmail[IdTipoMensagem] and
					 substring(DataEnvio,1,10) = CURDATE() and
					 IdStatus in (2,4)";
		$resHistoricoMensagem = @mysql_query($sql,$con);
		$linHistoricoMensagem = @mysql_fetch_array($resHistoricoMensagem);

		$sql = "select 
					 count(*) Qtd
				from
					 HistoricoMensagem,
					 TipoMensagem
				where 
					 HistoricoMensagem.IdLoja = $local_IdLoja and
					 HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
					 HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and
					 TipoMensagem.IdContaEmail = $linContaEmail[IdContaEmail] and
					 substring(DataEnvio,1,10) = CURDATE() and
					 HistoricoMensagem.IdStatus in (2,4)";
		$resHistoricoMensagem2 = @mysql_query($sql,$con);
		$linHistoricoMensagem2 = @mysql_fetch_array($resHistoricoMensagem2);

		$sql = "select 
					 count(*) Qtd
				from
					 HistoricoMensagem,
					 TipoMensagem
				where 
					 HistoricoMensagem.IdLoja = $local_IdLoja and
					 HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and
					 HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem and
					 TipoMensagem.IdContaEmail = $linContaEmail[IdContaEmail] and
					 ADDTIME(HistoricoMensagem.DataCriacao, TipoMensagem.DelayDisparo) < DATE_ADD(concat(CURDATE(),' ',CURTIME()),INTERVAL - 1 HOUR) and
					 HistoricoMensagem.IdStatus in (1,3,5)";
		$resHistoricoMensagem3 = @mysql_query($sql,$con);
		$linHistoricoMensagem3 = @mysql_fetch_array($resHistoricoMensagem3);	

		if(($linHistoricoMensagem[Qtd] == 0 && $linHistoricoMensagem2[Qtd] == 0) || $linHistoricoMensagem3[Qtd] > 0){
			$alerta[$i] = "<a href='cadastro_conta_email.php?IdContaEmail=$linContaEmail[IdContaEmail]'>Atenção:  A conta de e-mail número $linContaEmail[IdContaEmail] não está funcionando.</a>";
			$i++;
		}
	}


	##### Verifica se estão preenchidas as configurações de Nome/Telefone/E-mail na tela de avisos
	if(trim(getParametroSistema(95,3)) == ''){
		$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=95&IdParametroSistema=3'>Atenção:  Não está preenchido o nome da empresa na tela de avisos do cliente.</a>";
		$i++;
	}
	if(trim(getParametroSistema(95,4)) == ''){
		$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=95&IdParametroSistema=4'>Atenção:  Não está preenchido o telefone de contato da empresa na tela de avisos do cliente.</a>";
		$i++;
	}
	if(trim(getParametroSistema(95,5)) == ''){
		$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=95&IdParametroSistema=5'>Atenção:  Não está preenchido o e-mail de contato da empresa na tela de avisos do cliente.</a>";
		$i++;
	}
	##############################################################

	##### Verifica se existe configurações do radius e se elas estão corretas
	if(trim(getCodigoInterno(10000,1)) != ''){
		$parametroRadius = explode("\n",getCodigoInterno(10000,1));
		
		$parametroRadius[0] = trim($parametroRadius[0]);
		$parametroRadius[1] = trim($parametroRadius[1]);
		$parametroRadius[2] = trim($parametroRadius[2]);
		$parametroRadius[3] = trim($parametroRadius[3]);
		
		$con_temp = @mysql_connect($parametroRadius[0], $parametroRadius[1], $parametroRadius[2]);
		if($con_temp == false){
			$alerta[$i] = "Atenção:  Não foi possível conectar a base de dados do servidor radius configurado.";
		}
		$i++;
	}
	##############################################################

	##### Verifica se existe contratos com datas inválidas
	$sql = "select
				count(*) Qtd
			from
				Contrato
			where
				IdLoja = $local_IdLoja and (
				DataInicio = '0000-00-00' or
				DataTermino = '0000-00-00' or
				DataBaseCalculo = '0000-00-00' or
				DataPrimeiraCobranca = '0000-00-00' or
				DataUltimaCobranca = '0000-00-00' or
				DataUltimoBloqueio = '0000-00-00' or
				DiaCobranca = 0 or
				DiaCobranca = '' or
				DiaCobranca is null or
				DataBaseCalculo like '0000-%' or
				DataPrimeiraCobranca like '0000-%' or
				DataUltimaCobranca like '0000-%' or
				DataUltimoBloqueio like '0000-%')";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	if($lin[Qtd] > 0){
		$alerta[$i] = "<a href='listar_contrato_data_irregular.php?filtro_limit=$lin[Qtd]'>Atenção:  Há contrato(s) com datas irregulares. Clique para acessar o relatório.</a>";
		$i++;
	}
	##############################################################

	##### Verifica se existe contratos com datas inválidas
	$sql = "select 
				IdParametroSistema IdQuadroAviso
			from 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
				IdGrupoParametroSistema = 56 and 
				IdParametroSistema = 14 and
				IdGrupoUsuario in (
					select 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					from	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					group by	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res)>=1){
		$sql = "select
					count(*) Qtd
				from
					ContratoVigencia
				where
					IdLoja = $local_IdLoja and (
					DataInicio = '0000-00-00' or
					DataTermino = '0000-00-00' or
					DataInicio like '0000-%' or
					DataTermino like '0000-%')";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		if($lin[Qtd] > 0){
			$alerta[$i] = "<a href='listar_contrato_vigencia_irregular.php?filtro_limit=$lin[Qtd]'>Atenção:  Há contrato(s) com vigências irregulares. Clique para acessar o relatório.</a> ";
			$i++;
		}
	}
	##############################################################

	##### Verifica se está configurado o IdPessoa CNT Sistemas
	if(getParametroSistema(4,7) == '' && $local_login == 'root'){
		$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=4&IdParametroSistema=7'>Atenção:  IdPessoa CNT Sistemas está em branco.</a>";
		$i++;
	}
	##############################################################

	##### Verifica se está configurado o IdPessoa CNT Sistemas
	if(getParametroSistema(4,8) == '' && $local_login == 'root'){
		$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=4&IdParametroSistema=8'>Atenção:  IdContrato CNT Sistemas está em branco.</a>";
		$i++;
	}
	##############################################################

	##### Verifica se está a configuração inicial para rotacionamento do radius
	if(getCodigoInterno(10000,21) == '2000' && $local_login == 'root'){
		$alerta[$i] = "<a href='cadastro_codigo_interno.php?IdGrupoCodigoInterno=10000&IdCodigoInterno=21'>Atenção:  Verifique a configuração inicial para rotacionamento do arquivo de log do FreeRadius.</a>";
		$i++;
	}
	##############################################################

	

	##### Verifica se a data e hora do PHP e MySQL estão sincronizados
	if($local_login == 'root'){
		$sql = "select curdate() 'Data', curtime() 'Hora'";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$DataMySQL = $lin[Data]." ".$lin[Hora];
		$DataPHP   = date("Y-m-d H:i:s");

		if($DataMySQL != $DataPHP){
			$alerta[$i] = "Atenção:  As datas do servidor não estão sincronizadas.<br>PHP: $DataPHP | MySQL: $DataMySQL";
			$i++;
		}
	}
	##############################################################
	
	##### Verifica se existe códigos internos sendo solicitados, porem não encontrados
	if($local_login == 'root' || $_SESSION[IdLicenca] == '2007A000'){
		$sql = "select
					IdCodigoInterno,
					ValorCodigoInterno
				from
					CodigoInterno
				where
					IdGrupoCodigoInterno = 31 and
					ValorCodigoInterno != ''";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$alerta[$i] = "<a href='cadastro_codigo_interno.php?IdGrupoCodigoInterno=31&IdCodigoInterno=$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</a>";
			$i++;
		}
	}
	##############################################################
	
	##### Verifica se o SNMP está configurado no servidor
	if($local_login == 'root' && !function_exists('snmpwalkoid')){
		$alerta[$i] = "SNMP não está configurado neste servidor.<BR>Caso seja provedor e utilize Mikrotik, entre em contato com o suporte.";
		$i++;
	}
	##############################################################

	##### Verifica se existe parametros do sistema sendo solicitados, porem não encontrados
	if($local_login == 'root' || $_SESSION[IdLicenca] == '2007A000'){
		$sql = "select
					IdParametroSistema,
					ValorParametroSistema
				from
					ParametroSistema
				where
					IdGrupoParametroSistema = 135 and
					ValorParametroSistema != ''";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$alerta[$i] = "<a href='cadastro_parametro_sistema.php?IdGrupoParametroSistema=135&IdParametroSistema=$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</a>";
			$i++;
		}
	}
	##############################################################

	##### Verifica se existe contratos sem Vigência
	$sql = "SELECT DISTINCT
				COUNT(*) Qtd
			FROM 
				Contrato
			WHERE 
				IdLoja = $local_IdLoja AND 
				IdContrato NOT IN(
					SELECT DISTINCT
						IdContrato
					FROM 
						ContratoVigencia
					WHERE 
						IdLoja = $local_IdLoja
				);
	";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);
	if($lin[Qtd] > 0){
		$alerta[$i]			= "<a href='listar_contrato_sem_vigencia.php?filtro_limit=$lin[Qtd]'>Atenção: Há contratos que não possuem vigências. Clique para acessar o relatório.</a>";		
		$i++;
	}
	##############################################################
	
	##### Verifica se foi possivel conectar ao servidor da CNTSistemas
	if(!$conCNT){
		$alerta[$i] = "Não foi possível conectar no servidor de Help Desk. Por favor, tente novamente mais tarde.";
		$i++;
	}
	
	##############################################################

	/*$sql =	"
		SELECT		
			COUNT(IdStatus) QTD
		FROM
			HistoricoMensagem			
		WHERE
			SUBSTRING(DataEnvio, 1, 10) = CURDATE() AND 
			IdStatus = 2
		GROUP BY
			IdStatus";
	$res =	@mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);

	$Enviados = $lin[QTD];

	$sql =	"
		SELECT		
			COUNT(IdStatus) QTD
		FROM
			HistoricoMensagem			
		WHERE
			SUBSTRING(DataCriacao, 1, 10) = CURDATE() AND 
			IdStatus = 3
		GROUP BY
			IdStatus";
	$res =	@mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);

	$Falharam = $lin[QTD];
	
	if($Falharam > 0){
		$percentual = (($Falharam*100)/($Enviados+$Falharam));
		$alerta[$i] = "Atenção: Dos e-mails disparados hoje pelo sistema, " . round($percentual) . "% ($Falharam) não foram enviados.";
		$i++;
	}*/
	##############################################################

	##### Verifica Tabelas Conrropidas	
	#Barrado temporariamente até que seja feito um teste no sistema.
	/*$sql = "show table status from $con_bd[banco] where comment != 'view'";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
			
		$sql = "select count(*) Qtd	from $lin[Name] limit 0,1";
		$res2 = @mysql_query($sql,$con);
		$lin2 = @mysql_fetch_array($res2);

		if($lin2[Qtd] == ""){
			$alerta[$i] = "<a onClick='call_ajax(\"rotinas/rotina_reparar_tabela.php?NomeTabela=$lin[Name]\")' style='cursor:pointer'>Atenção: A seguinte tabela está conrropida ($lin[Name]). Clique aqui para reparar.</a>";
			$i++;
		}
	}*/
	##############################################################
	for($ii=0; $ii<$i; $ii++){		
		$alertaOrdenado[$ii] = strip_tags($alerta[$ii]);
	}
	
	if(count($alertaOrdenado) > 0){
		sort($alertaOrdenado);
	}

	##### Exibe os alertas
	for($ii=0; $ii<$i; $ii++){		
		for($jj=0; $jj<$i; $jj++){		
			if($alertaOrdenado[$ii] != '' && $alertaOrdenado[$ii] == strip_tags($alerta[$jj])){
				echo "<div class='alerta'>$alerta[$jj]</div>";	
				break;
			}
		}
	}
	/*for($ii=0; $ii<$i; $ii++){		
		if($alerta[$ii] != ''){
			echo "<div class='alerta'>$alerta[$ii]</div>";
		}
	}*/
	##############################################################
	
	##### Contratos com a Data Base inferior a data de hoje
	$sql = "select 
				IdParametroSistema IdQuadroAviso
			from 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
				IdGrupoParametroSistema = 56 and 
				IdParametroSistema = 13 and
				IdGrupoUsuario in (
					select 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					from	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					group by	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res)>=1){
		$data = incrementaData(date("Y-m-d"), getCodigoInterno(3, 125));
		$filtro_data = dataConv(incrementaData($data, -1), "Y-m-d", "d/m/Y");
		$status = str_replace(array("\r","\n"), array("",","),getCodigoInterno(3, 170));
		
		$sql = "select
					count(Contrato.IdContrato) QTDContrato,
					Contrato.DataBaseCalculo
				from 
					Contrato
				where
					Contrato.IdLoja = $local_IdLoja and (
						Contrato.DataBaseCalculo < '$data' or (
							Contrato.DataBaseCalculo IS NULL and
							Contrato.DataPrimeiraCobranca < '$data'
						)
					) and 
					Contrato.IdStatus in ($status)
				order by
					Contrato.DataBaseCalculo;";				
		$res = @mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			if($lin[QTDContrato] > 0){
				if($lin[QTDContrato] > 1){
					$tag = "contratos";
				} else{
					$tag = "contrato";
				}
				
				$alerta2[$j] = "<a href='listar_contrato_datas.php?filtro_limit=$lin[QTDContrato]&Campo=DataBase&DataFim=".$filtro_data."&IdStatus=$status'>Atenção: Há $lin[QTDContrato] $tag com a Data Base inferior ao dia ".dataConv($data, "Y-m-d", "d/m/Y").". Clique aqui para acessar o relatório.</a>";
				$j++;
			}
		}
	}
	##############################################################
	
	##### Alerta de Qtd. de Contratos para Cada Status
	$sql = "
		SELECT 
			ParametroSistema.IdParametroSistema IdStatus,
			ParametroSistema.ValorParametroSistema Status
		FROM 
			ParametroSistema
		WHERE 
			ParametroSistema.IdGrupoParametroSistema = 69
		GROUP BY 
			ParametroSistema.ValorParametroSistema;
	";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		$sql0 = "
			SELECT 
				(ParametroSistema.IdParametroSistema+1000) IdQuadroAviso
			FROM 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			WHERE   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja AND
				(GrupoUsuarioQuadroAviso.IdQuadroAviso-1000) = ParametroSistema.IdParametroSistema AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso >= 1000 AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso <= 1999 AND
				ParametroSistema.IdGrupoParametroSistema = 69 AND 
				ParametroSistema.IdParametroSistema = $lin[IdStatus] AND
				GrupoUsuarioQuadroAviso.IdGrupoUsuario IN (
					SELECT 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					FROM	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					WHERE
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja AND 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja AND 
						UsuarioGrupoUsuario.Login = Usuario.Login AND 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario AND 
						Usuario.IdPessoa = Pessoa.IdPessoa AND Pessoa.TipoUsuario = 1 AND 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					GROUP BY	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
		$res0 = @mysql_query($sql0, $con);
		if($lin0 = @mysql_fetch_array($res0)){
			$sql1 = "
				SELECT
					COUNT(IdLoja) QTDContrato
				FROM
					Contrato
				WHERE
					Contrato.IdLoja = $local_IdLoja AND
					Contrato.IdStatus = $lin[IdStatus]
				ORDER BY
					Contrato.IdLoja;";
			$res1 = @mysql_query($sql1,$con);
			if($lin1 = @mysql_fetch_array($res1)){
				if($lin1[QTDContrato] > 0){
					$alerta2[$j] = "<a href='listar_contrato.php?filtro_limit=$lin1[QTDContrato]&filtro_status=$lin[IdStatus]'>Atenção:  Há $lin1[QTDContrato] contratos com o status \"$lin[Status]\".</a>";
					$j++;
				}
			}
		}
	}
	##############################################################
	
	##### Alerta de Qtd. de Ordem de Serviço para Cada Grupo
	$sql = "
		SELECT 
			UsuarioGrupoUsuario.IdGrupoUsuario, 
			GrupoUsuario.DescricaoGrupoUsuario 
		FROM 
			UsuarioGrupoUsuario, 
			GrupoUsuario, 
			Usuario, 
			Pessoa 
		WHERE 
			UsuarioGrupoUsuario.IdLoja = 1  AND 
			UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja AND 
			UsuarioGrupoUsuario.Login = Usuario.Login AND 
			UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario AND 
			Usuario.IdPessoa = Pessoa.IdPessoa AND 
			Pessoa.TipoUsuario = 1 AND 
			Usuario.IdStatus = 1 AND
			GrupoUsuario.OrdemServico = 1
		GROUP BY 
			UsuarioGrupoUsuario.IdGrupoUsuario;
	";
	$res = @mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){
		$sql0 = "
			SELECT 
				(GrupoUsuario.IdGrupoUsuario+2000) IdQuadroAviso
			FROM 
				GrupoUsuario,
				GrupoUsuarioQuadroAviso
			WHERE   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja AND
				(GrupoUsuarioQuadroAviso.IdQuadroAviso-2000) = GrupoUsuario.IdGrupoUsuario AND
				GrupoUsuarioQuadroAviso.IdQuadroAviso >= 2000 AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso <= 2999 AND
				GrupoUsuario.IdGrupoUsuario = $lin[IdGrupoUsuario] AND
				GrupoUsuarioQuadroAviso.IdGrupoUsuario IN (
					SELECT 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					FROM	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					WHERE
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja AND 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja AND 
						UsuarioGrupoUsuario.Login = Usuario.Login AND 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario AND 
						Usuario.IdPessoa = Pessoa.IdPessoa AND Pessoa.TipoUsuario = 1 AND 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					GROUP BY	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
		$res0 = @mysql_query($sql0, $con);
		if($lin0 = @mysql_fetch_array($res0)){
			$sql1 = "select
						count(IdLoja) as QTDOrdemServico
					from
						OrdemServico
					where
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdGrupoUsuarioAtendimento = $lin[IdGrupoUsuario] and
						OrdemServico.IdStatus > 99 and
						OrdemServico.IdStatus < 200
					order by
						OrdemServico.IdLoja;
			";
			$res1 = @mysql_query($sql1,$con);
			if($lin1 = @mysql_fetch_array($res1)){
				if($lin1[QTDOrdemServico] > 0){
					$alerta2[$j] = "<a href='listar_ordem_servico_avancado.php?filtro_limit=$lin1[QTDOrdemServico]&filtro_grupo=$lin[IdGrupoUsuario]&filtro_idstatus=100'>Atenção:  Há $lin1[QTDOrdemServico] Ordem de Serviço \"Em Aberto\" para o grupo \"$lin[DescricaoGrupoUsuario]\".</a>";
					$j++;
				}
			}
		}
	}
	##############################################################
	
	##### Verifica se existe solicitação de alteração de dados
	$sql = "select 
				IdParametroSistema IdQuadroAviso
			from 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
				IdGrupoParametroSistema = 56 and 
				IdParametroSistema = 15 and
				IdGrupoUsuario in (
					select 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					from	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					group by	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
	$res = mysql_query($sql, $con);
	
	if(mysql_num_rows($res) > 0){
		if(permissaoSubOperacao($localModulo, 70, 'V') != false){
			$sql ="	select 
						count(*) Qtd 
					from 
						Pessoa,
						PessoaSolicitacao,
						PessoaSolicitacaoEndereco,
						Pais,
						Estado,
						Cidade 
					where
						Pessoa.IdPessoa = PessoaSolicitacao.IdPessoa and
						PessoaSolicitacao.IdPessoa = PessoaSolicitacaoEndereco.IdPessoa and
						PessoaSolicitacao.IdPessoaSolicitacao = PessoaSolicitacaoEndereco.IdPessoaSolicitacao and
						PessoaSolicitacao.IdEnderecoDefault = PessoaSolicitacaoEndereco.IdPessoaEndereco and
						Pais.IdPais = PessoaSolicitacaoEndereco.IdPais and
						Estado.IdPais = Pais.IdPais and
						PessoaSolicitacaoEndereco.IdEstado = Estado.IdEstado and
						Cidade.IdEstado = Estado.IdEstado and
						Cidade.IdCidade = PessoaSolicitacaoEndereco.IdCidade and
						PessoaSolicitacao.IdStatus = '1'
						$where";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[Qtd] >=1){
				if($lin[Qtd]==1){	$var	=	'ão';	}
				else{				$var	=	'ões';	}
				$alerta2[$j] = "<a href='listar_pessoa_solicitacao.php?filtro_limit=$lin[Qtd]&IdStatus=1'>Atenção: Há $lin[Qtd] solicitaç$var de atualização de dados cadastrais. Clique para 
				acessar o relatório.</a>";
				$j++;
			}
		}
	}
	##############################################################
	
	##### Avisa sobre o feriado de hoje
	$DataHoje	= date("Y-m-d");
	$DataAmanha	= incrementaData($DataHoje,1);

	$sql =	"select
				Data,
				TipoData,
				DescricaoData
			from
				DatasEspeciais
			where
				IdLoja = $local_IdLoja and
				Data >= '$DataHoje' and
				Data <= '$DataAmanha'";
	$res =	@mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)){

		if($lin[Data] == $DataHoje){
			$Hoje = "Hoje";
		}else{
			$Hoje = "Amanhã";
		}

		$lin[Data] = dataConv($lin[Data],"Y-m-d","d/m/Y");

		$lin[TipoData] = explode("\n",getParametroSistema(52,$lin[TipoData]));
		$lin[TipoData] = trim($lin[TipoData][0]);

		$alerta2[$j] = "$Hoje ($lin[Data]) - $lin[TipoData]: $lin[DescricaoData].";
		$j++;
	}
	##############################################################
	
	##### Verifica se existe Contratos que não foram assinados
	$sql = "select 
				IdParametroSistema IdQuadroAviso
			from 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
				IdGrupoParametroSistema = 56 and 
				IdParametroSistema = 16 and
				IdGrupoUsuario in (
					select 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					from	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					group by	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res) > 0){
		if(permissaoSubOperacao($localModulo, 70, 'V') != false){
			$sql = "select 
						count(*) Qtd 
					from 
						Contrato 
					where 
						IdLoja = $local_IdLoja and 
						AssinaturaContrato = '2' and 
						IdStatus >= 200;";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[Qtd] > 0){
				if($lin[Qtd] == 1){
					$txt = 'contrato que não foi assinado';
				} else{
					$txt = 'contratos que não foram assinados';
				}
				
				$alerta2[$j] = "<a href='listar_contrato_tipo.php?filtro_limit=$lin[Qtd]&filtro_contrato_assinado=2&filtro_contrato_cancelado=2'>Atenção: Há $lin[Qtd] $txt. Clique para acessar o relatório.</a>";
				$j++;
			}
		}
	}
	##############################################################
	
	##### Há x contratos inadimplentes até o dia xx/xx/xxxx
	$sql = "select 
				IdParametroSistema IdQuadroAviso
			from 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
				IdGrupoParametroSistema = 56 and 
				IdParametroSistema = 21 and
				IdGrupoUsuario in (
					select 
						UsuarioGrupoUsuario.IdGrupoUsuario 
					from	
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where
						UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
						UsuarioGrupoUsuario.Login = '$local_Login' 
					group by	
						UsuarioGrupoUsuario.IdGrupoUsuario
				);";
	$res = mysql_query($sql,$con);
	if(mysql_num_rows($res) > 0){
		$Status = getCodigoInterno(3, 232);
		$Status = str_replace(array("\r", "\n"), array("", ","), $Status);
		
		$sqlDiaCompe = "SELECT 
							MAX(LocalCobranca.DiasCompensacao) dia 
						FROM
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber,
							LocalCobranca 
						WHERE
							Contrato.IdLoja = '$local_IdLoja' AND
							Contrato.IdStatus IN ($Status) AND
							Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND
							Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND
							LocalCobranca.IdLoja = Contrato.IdLoja AND
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND
							ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
							LocalCobranca.IdLocalCobranca = Contrato.IdLocalCobranca AND
							ContaReceber.IdStatus IN (1, 3, 6, 8)";
		$resDiaCompe = @mysql_query($sqlDiaCompe,$con);
		$linDiaCompe = @mysql_fetch_array($resDiaCompe);
		
		$Dias = getCodigoInterno(3, 231);
		$Dias = $Dias+$linDiaCompe[dia];
		
		$sqlData = "SELECT 
							SUBSTRING(ADDDATE(NOW(),INTERVAL $Dias DAY),1,10) data";
		$resData = mysql_query($sqlData,$con);
		$linData = mysql_fetch_array($resData);
		$dataUtil = dia_util($linData[data]);
		
		$sql = "SELECT 
					COUNT(*) Qtd 
				FROM 
					(
						SELECT
							Contrato.IdContrato
						FROM
							Contrato,
							LancamentoFinanceiro,
							LancamentoFinanceiroContaReceber,
							ContaReceber
						WHERE 
							Contrato.IdLoja = '$local_IdLoja' AND 
							Contrato.IdStatus IN ($Status) AND 
							Contrato.IdLoja = LancamentoFinanceiro.IdLoja AND 
							Contrato.IdContrato = LancamentoFinanceiro.IdContrato AND 
							LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
							LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro AND 
							LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja AND 
							LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber AND 
							ContaReceber.IdStatus IN (1, 3, 6, 8) AND 
							ContaReceber.DataVencimento <= '$dataUtil'
						GROUP BY 
							Contrato.IdContrato
					) Temp;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[Qtd] > 0){
			$lin[Data] = dataConv($dataUtil, "Y-m-d", "d/m/Y");
			$alerta2[$j] = "<a href='listar_contrato_periodo_pago.php?filtro_limit=$lin[Qtd]&filtro_campo=Inadimplente&filtro_data_termino=$lin[Data]&filtro_id_status=$Status'>Atenção: Há $lin[Qtd] contratos inadimplentes até o dia $lin[Data]. Clique aqui para acessar o relatório.</a>";
			$j++;
		}
	}
	##############################################################
	
	@include("alertas_personalizado.php");
	
	for($ii=0; $ii<$j; $ii++){		
		$alertaOrdenado2[$ii] = strip_tags($alerta2[$ii]);
	}
	
	if(count($alertaOrdenado2) > 0){
		sort($alertaOrdenado2);
	}

	##### Exibe os alertas
	for($ii=0; $ii<$j; $ii++){		
		for($jj=0; $jj<$j; $jj++){		
			if($alertaOrdenado2[$ii] != '' && $alertaOrdenado2[$ii] == strip_tags($alerta2[$jj])){
				echo "<div class='quadroAvisoPessoa'><p><B>$alerta2[$jj]</p></B></div>";	
				break;
			}
		}
	}	
?>
