<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		$local_IdLoja	= $_SESSION["IdLoja"];
		
		$sql = "start transaction;"; 
		@mysql_query($sql, $con);
		$tr_i = 0;
		
		$local_DadosMonitor = explode("»", $local_DadosMonitor);
		$local_IdServicoMonitor = '0';
		
		for($i = 0; $i < count($local_DadosMonitor); $i++) {
			$dados = explode("¬", $local_DadosMonitor[$i]);
		
			$AccessKey 					= $dados[0]; 
			$IdServicoMonitor 			= $dados[1];
			$IdTipoMonitor 				= $dados[2];
			$TipoMonitor 				= $dados[3]; 
			$Consulta 					= $dados[4]; 
			$IdSNMP 					= $dados[5]; 
			$SNMP 						= $dados[6];
			$ComandoSSH 				= $dados[7];
			$Historico 					= $dados[8];
			$Resultado 					= $dados[9];
			$IdParametroServico			= $dados[10];
			$FiltroContratoParametro	= $dados[11];
			$CodigoSNMP					= $dados[12]; 
			
			if($AccessKey != NULL) {
				if($IdServicoMonitor == NULL) { 
					$sql = "select 
								(max(IdServicoMonitor)+1) IdServicoMonitor 
							from 
								ServicoMonitor 
							where 
								IdLoja = $local_IdLoja and 
								IdServico = $local_IdServico;";
					$res = mysql_query($sql, $con);
					$lin = @mysql_fetch_array($res);
					
					if($lin[IdServicoMonitor] != NULL) {
						$IdServicoMonitor	= $lin[IdServicoMonitor];
					} else {
						$IdServicoMonitor	= 1;
					}
					
					if($IdParametroServico == ""){
						$IdParametroServico = "NULL";
						$aux = ",IdParametroServico		= NULL,
								FiltroContratoParametro	= '$FiltroContratoParametro'";
					} else {
						$aux = ",IdParametroServico		= '$IdParametroServico',
								FiltroContratoParametro	= '$FiltroContratoParametro'";
					}
					
						$sql = "insert into ServicoMonitor set
								IdLoja					= '$local_IdLoja',
								IdServico				= '$local_IdServico',
								IdServicoMonitor		= '$IdServicoMonitor',
								TipoMonitor				= '$IdTipoMonitor',
								IdConsulta				= '$Consulta',
								IdSNMP					= '$IdSNMP',
								ComandoSSH				= '$ComandoSSH',
								CodigoSNMP				= '$CodigoSNMP',
								Historico				= '$Historico',
								IdFormatoResultado		= '$Resultado'
								$aux";
				} else {
						$sql = "update ServicoMonitor set
								TipoMonitor				= '$IdTipoMonitor',
								IdConsulta				= '$Consulta',
								IdSNMP					= '$IdSNMP',
								ComandoSSH				= '$ComandoSSH',
								CodigoSNMP				= '$CodigoSNMP',
								Historico				= '$Historico',
								IdFormatoResultado		= '$Resultado'
								$aux
							where
								IdLoja = '$local_IdLoja' and
								IdServico = '$local_IdServico' and
								IdServicoMonitor = '$IdServicoMonitor'";
				}
				
				$local_transaction[$tr_i] = mysql_query($sql, $con);
				$local_IdServicoMonitor .= ",$IdServicoMonitor";
			}
		}
		
		$sql = "delete from 
					ServicoMonitor 
				where 
					IdLoja = $local_IdLoja and 
					IdServico = $local_IdServico and 
					IdServicoMonitor not in ($local_IdServicoMonitor);";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		
		$sql = "update Servico set
					UrlContratoImpresso		= '$local_UrlContratoImpresso',
					UrlRotinaCriacao		= '$local_UrlRotinaCriacao',
					UrlRotinaCancelamento	= '$local_UrlRotinaCancelamento',
					UrlRotinaBloqueio		= '$local_UrlRotinaBloqueio',
					UrlRotinaDesbloqueio	= '$local_UrlRotinaDesbloqueio',
					UrlRotinaAlteracao		= '$local_UrlRotinaAlteracao',
					UrlDistratoImpresso		= '$local_UrlDistratoImpresso',
					DataAlteracao			= (concat(curdate(),' ',curtime())),
					LoginAlteracao			= '$local_Login',
					EmailListaBloqueados	= '$local_Email_lista_bloqueados',
					TermoCienciaCDA			= '$local_TermoCienciaCDA'	
				where
					IdLoja = $local_IdLoja and
					IdServico = $local_IdServico";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction) {
			$sql = "commit;";
			$local_Erro = 4;
		} else {
			$sql = "rollback;";
			$local_Erro = 5;
		}
		
		@mysql_query($sql, $con);
	}
?>