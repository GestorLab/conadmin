<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_Password != ''){
			$PasswordTemp	= $local_Password;
		}
		if($local_LimiteVisualizacao == ""){
			$local_LimiteVisualizacao = 'NULL';	
		}
		if($local_DataExpiraSenha != ""){
			$local_DataExpiraSenha = "'".dataConv($local_DataExpiraSenha,'d/m/Y','Y-m-d')."'";	
		}else{
			$local_DataExpiraSenha = 'NULL';
		}
		if($local_ForcaAlterarSenha == ""){
			$local_ForcaAlterarSenha = 'NULL';	
		}
		if($local_IdAcessoSimultaneo == ""){
			$local_IdAcessoSimultaneo = 'NULL';	
		}
		
		// Obtendo o Password para inserção do Mikrotik //
		if($PasswordTemp == ''){
			$sql = "select Password from Usuario where Login = '$local_Login';";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			$PasswordTemp = $lin[Password];
		}
		
		$local_Login_Sistema	= $_SESSION["Login"];
		$IdLicenca				= $_SESSION["IdLicenca"];
		$ii						= 0;
		
		$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $IdLoja and IdGrupoCodigoInterno = 10000 and IdCodigoInterno < 20 order by ValorCodigoInterno;";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$aux = explode("\n",$lin[ValorCodigoInterno]);
			
			$bd[server][$ii]	= trim($aux[0]); //Host
			$bd[login][$ii]		= trim($aux[1]); //Login
			$bd[senha][$ii]		= trim($aux[2]); //Senha
			$bd[banco][$ii]		= trim($aux[3]); //DB
			$bd[Id][$ii]		= $lin[IdCodigoInterno];
			
			$ii++;
		}
		
		@mysql_close($con);
		
		$inserido = 0;
		
		// Conectando ao Radius //
		for($i=0; $i<$ii; $i++){
			$conRadius	= @mysql_connect($bd[server][$i],$bd[login][$i],$bd[senha][$i]);
			@mysql_select_db($bd[banco][$i], $conRadius);
			
			$update = false;
			
			if($local_InteracaoMikrotik == 1){
				$Servidores = explode('_', $local_ServidorRadius);
				for($aux=1; $aux<count($Servidores); $aux++){
					if($bd[Id][$i] == $Servidores[$aux]){
						$update = true;
					}
				}
			}
			
			$sql	= "START TRANSACTION;";
			@mysql_query($sql,$conRadius);
			$tr_i	= 0;
			
			$sqlRadius	= "select 
								usergroup.Id, 
								radgroupreply.GroupName,
								usergroup.Senha
							from 
								radgroupreply, 
								(select
									radcheck.Value AS Senha,
									usergroup.id AS Id,
									usergroup.IdLicenca,
									usergroup.GroupName
								 from
									radcheck,
									usergroup
								 where
									usergroup.IdLoja = '$IdLoja' and
									usergroup.IdLicenca = '$IdLicenca' and
									usergroup.UserName = '$local_Login' and
									usergroup.Id > 99999 and
									usergroup.IdLoja = radcheck.IdLoja and
									usergroup.Id = radcheck.id and
									usergroup.IdLicenca = radcheck.IdLicenca
								) usergroup
							where 
								radgroupreply.GroupName = usergroup.GroupName and
								radgroupreply.IdLicenca = usergroup.IdLicenca and
								radgroupreply.Attribute = 'Mikrotik-Group';";
			$resRadius = @mysql_query($sqlRadius, $conRadius);
			if(@mysql_num_rows($resRadius) > 0){
				while($linRadius = @mysql_fetch_array($resRadius)){
					if($update){
						// Alterando o Password //
						$sqlRadius = "
							update radcheck set 
								Value = '$PasswordTemp',
								Referencia = 'Login-Mikrotik_Password_Senha' 
							where 
								IdLoja = '$IdLoja' and 
								IdLicenca = '$IdLicenca' and 
								UserName = '$local_Login' and 
								Attribute = 'Password';";
						$local_transaction[$tr_i] = @mysql_query($sqlRadius,$conRadius);
						$tr_i++;
						
						$inserido++;
					} else{
						// Deletando Radius que foram descartados //
						$sql1Radius	= "delete from 
											usergroup 
										where 
											IdLicenca = '$IdLicenca' and 
											IdLoja = '$IdLoja' and 
											Id = '$linRadius[Id]' and 
											UserName = '$local_Login';";
						$local_transaction[$tr_i] = @mysql_query($sql1Radius, $conRadius);
						$tr_i++;
							
						$sql1Radius	= "delete from 
											radcheck 
										where 
											IdLicenca = '$IdLicenca' and 
											IdLoja = '$IdLoja' and 
											Id = '$linRadius[Id]' and 
											Attribute = 'Password' and
											UserName = '$local_Login';";
						$local_transaction[$tr_i] = @mysql_query($sql1Radius, $conRadius);
						$tr_i++;
					}
				}
			} else{
				if($update){
					// Inserindo novas opções no Radius //
					$sql = "select (max(Id)+1) Id from usergroup;";
					$res = @mysql_query($sql,$conRadius);
					$lin = @mysql_fetch_array($res);
					
					if($lin[Id] < 100000){ $lin[Id] = 100000; }
					
					$sqlRadius	= "INSERT INTO radcheck SET
										IdLicenca	= '$IdLicenca',
										IdLoja		=  $IdLoja,
										id			=  $lin[Id],
										UserName	= '$local_Login',
										Attribute	= 'Password',
										Op			= ':=',
										Value		= '$PasswordTemp',
										Referencia	= 'Login-Mikrotik_Password_Senha';";
					$local_transaction[$tr_i] = @mysql_query($sqlRadius,$conRadius);
					$tr_i++;
					
					$sqlRadius	= "INSERT INTO usergroup SET
										IdLicenca	= '$IdLicenca',
										IdLoja		=  $IdLoja,
										Id			=  $lin[Id],
										UserName	= '$local_Login',
										GroupName	= '$local_IdGrupoAcesso';";
					$local_transaction[$tr_i] = @mysql_query($sqlRadius,$conRadius);
					$tr_i++;
					
					$inserido++;
				}
			}
			
			for($aux=0; $aux<$tr_i; $aux++){
				if($local_transaction[$aux] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
			}else{
				$sql = "ROLLBACK;";
			}
			
			@mysql_query($sql,$conRadius);
			@mysql_close($conRadius);
		}
		
		if($inserido == 0){
			$local_IdGrupoAcesso = '';
		}
		
		include ('../../files/conecta.php');

		if($local_ForcaAlterarSenha > 2){
			$SolicitaSenhaData = date('Y-m-d', strtotime("+".getCodigoInterno(3,241)." days"));		
		}
		$sql	=	"UPDATE Usuario SET 
							IdPessoa					='$local_IdPessoa',
							LimiteVisualizacao			= $local_LimiteVisualizacao,
							AcessoSimultaneo			='$local_IdAcessoSimultaneo', 
							IdStatus					='$local_IdStatus',
							IpAcesso					='$local_IpAcesso',
							DataExpiraSenha				= $local_DataExpiraSenha,
							ForcarAlteracaoSenha		='$local_ForcaAlterarSenha',
							SolicitacaoAlteracaoSenha	='$SolicitaSenhaData',
							LoginAlteracao				='$local_Login_Sistema',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							Login					='$local_Login';";
		if(@mysql_query($sql,$con) == true){
			if($local_Password != ""){
				if($local_Login_Sistema != $local_Login){
					if($local_Password!=""){
						$local_Password = md5($local_Password);
					
						$sql	=	"UPDATE Usuario SET Password = '$local_Password' WHERE Login = '$local_Login';";
						@mysql_query($sql,$con);
					}
				}else{
					$local_Erro = 81;
				}
			}		
			if($local_Erro == ''){
				$local_Erro = 4;
			}		
		}else{
			$local_Erro = 5;
		}
	}
?>
