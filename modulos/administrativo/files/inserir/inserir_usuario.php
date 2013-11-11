<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$PasswordTemp	= $local_Password;
		$local_Password = md5($local_Password);
		$local_Login	= strtolower($local_Login);
	
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
		
		// Se a interação com Mikrotik for sim
		if($local_InteracaoMikrotik == 1){
			$IdLicenca	= $_SESSION["IdLicenca"];
			$ii			= 0;
			
			$Servidores = explode('_', $local_ServidorRadius);
			for($i=1; $i<count($Servidores); $i++){
				$sql = "select ValorCodigoInterno from CodigoInterno where IdLoja = '$IdLoja' and IdGrupoCodigoInterno = 10000 and IdCodigoInterno = '".$Servidores[$i]."';";
				$res = @mysql_query($sql,$con);
				if($lin = @mysql_fetch_array($res)){
					$aux = explode("\n",$lin[ValorCodigoInterno]);
					
					$bd[server][$ii]	= trim($aux[0]); //Host
					$bd[login][$ii]		= trim($aux[1]); //Login
					$bd[senha][$ii]		= trim($aux[2]); //Senha
					$bd[banco][$ii]		= trim($aux[3]); //DB
					
					$ii++;
				}
			}
			
			@mysql_close($con);
			
			for($i=0; $i<$ii; $i++){
				$conRadius	= @mysql_connect($bd[server][$i],$bd[login][$i],$bd[senha][$i]);
				@mysql_select_db($bd[banco][$i],$conRadius);
				
				$sql = "select (max(Id)+1) Id from usergroup;";
				$res = @mysql_query($sql,$conRadius);
				$lin = @mysql_fetch_array($res);
				
				if($lin[Id] < 100000){ $lin[Id] = 100000; }
				
				$sql	= "START TRANSACTION;";
				@mysql_query($sql,$conRadius);
				$tr_i	= 0;
					
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
		} 
		
		include ('../../files/conecta.php');
		
		// Sql de Inserção de Codigo Interno
		$sql	=	"
				INSERT INTO Usuario SET
						Login	 				='$local_Login',
						IdPessoa				='$local_IdPessoa',
						Password				='$local_Password',
						LimiteVisualizacao		= $local_LimiteVisualizacao, 
						AcessoSimultaneo		='$local_IdAcessoSimultaneo', 
						IdStatus				='$local_IdStatus', 
						IpAcesso				='$local_IpAcesso',
						DataExpiraSenha			= $local_DataExpiraSenha,
						ForcarAlteracaoSenha	='$local_ForcaAlterarSenha',					
						LoginCriacao			='$local_Login_Sistema', 
						DataCriacao				=(concat(curdate(),' ',curtime())),
						LoginAlteracao			= NULL,
						DataAlteracao			= NULL;";
		// Executa a Sql de Inserção de Usuario
		if(@mysql_query($sql,$con) == true){
			// Muda a ação para Alterar
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		} else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
	}
?>
