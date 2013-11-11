<?php
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include('../files/funcoes.php');
	
	$local_urlRetornoErro	= "../index.php";
	
	$formDados[IdLoja]					= getParametroSistema(95,6);

	$formDados[CPF_CNPJ]				= $_REQUEST['CPF_CNPJ'];
	$formDados[Senha]					= $_REQUEST['Senha'];
	$formDados[Pessoa]					= $_REQUEST['Pessoa'];
	$formDados[ValidarCPF_CNPJ]			= $_REQUEST['ValidarCPF_CNPJ'];

	$formDados[Login]				= 'cda';
	$formDados[CPF_CNPJOriginal]	= $formDados[CPF_CNPJ];
	$formDados[CPF_CNPJ]		= inserir_mascara($formDados[CPF_CNPJ]);
	
	if($formDados[CPF_CNPJ]!='' || $formDados[Pessoa]!=''){

		if($formDados[Pessoa] != ''){
			// Acesso pela variável "Pessoa"
			$select = "md5(concat(Pessoa.IdPessoa, Pessoa.CPF_CNPJ, IF(Pessoa.Senha IS NULL,'',Pessoa.Senha))) = '$formDados[Pessoa]'";
		}

		if($formDados[CPF_CNPJ] != ''){
			// Acesso pelo "CPF_CNPJ"
			if(getParametroSistema(95,2) == 1){
				// Quando está configurado para pedir senha
				$select = "md5(concat(Pessoa.CPF_CNPJ, IF(Pessoa.Senha IS NULL,'',Pessoa.Senha))) = '".md5($formDados[CPF_CNPJ].$formDados[Senha])."'";

				// Verifica se ele está acessando com parâmetros do contrato
				$sql = "SELECT
							Contrato.IdPessoa
						FROM
							Contrato,
							(SELECT
								*
							FROM
								ServicoParametro
							WHERE
								IdTipoTexto != 2) ServicoParametroUsuario,
							(SELECT
								*
							FROM
								ServicoParametro
							WHERE
								IdTipoTexto = 2) ServicoParametroSenha,
							ContratoParametro Usuario,
							ContratoParametro Senha 
						WHERE 
							Usuario.IdLoja = Senha.IdLoja AND 
							Usuario.IdLoja = ServicoParametroUsuario.IdLoja AND
							Senha.IdLoja = ServicoParametroSenha.IdLoja AND
							Usuario.IdContrato = Senha.IdContrato AND
							Usuario.IdLoja = Contrato.IdLoja AND
							Usuario.IdContrato = Contrato.IdContrato AND
							Usuario.IdServico = Senha.IdServico AND
							Usuario.IdServico = ServicoParametroUsuario.IdServico AND
							Senha.IdServico = ServicoParametroSenha.IdServico AND
							Usuario.IdParametroServico = ServicoParametroUsuario.IdParametroServico AND
							Senha.IdParametroServico = ServicoParametroSenha.IdParametroServico AND 
							ServicoParametroUsuario.AcessoCDA = 1 AND
							ServicoParametroSenha.AcessoCDA = 1 AND 
							Contrato.IdStatus >= 200 AND 
							MD5(Usuario.Valor) = MD5('$formDados[CPF_CNPJOriginal]') AND
							MD5(Senha.Valor) = MD5('$formDados[Senha]')";
					$res = mysql_query($sql,$con);
					if($lin = mysql_fetch_array($res)){
						$select = "Pessoa.IdPessoa = $lin[IdPessoa]";
					}
			}else{
				// Quando está configurado para NÃO pedir senha
				$select = "md5(Pessoa.CPF_CNPJ) = '".md5($formDados[CPF_CNPJ])."'";
			}
		}

		$sql = "select 
					IdPessoa
				from 
					Pessoa 
				where 
					$select";		
		$res = mysql_query($sql,$con);
		$num = @mysql_num_rows($res);
		$lin = @mysql_fetch_array($res);
		
		if($num == 1){
			session_cache_expire (720);
			session_start("ConAdmin_session_cda");

			$_SESSION["LoginCDA"]		=	$formDados[Login];
			$_SESSION["IdLojaCDA"]		=	$formDados[IdLoja];
			$_SESSION["IdPessoaCDA"]	=	$lin[IdPessoa];
			// Carrega as variáveis do config
			$Vars = Vars();
			$VarsKeys = array_keys($Vars);
			
			for($i=0; $i<count($VarsKeys); $i++){
				$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
			}
			// Fim - Carrega as variáveis do config
			$_SESSION["IdLogAcessoCDA"] = LogAcessoCDA();
			
			$IdStatusCr = getCodigoInternoCDA(3,238);
			$IdStatusCo = getCodigoInternoCDA(3,239);
			$IdStatusOs = getCodigoInternoCDA(3,240);
			
			$IdStatusCr = str_replace('\n',',',$IdStatusCr);
			$IdStatusCo = str_replace('\n',',',$IdStatusCo);
			$IdStatusOs = str_replace('\n',',',$IdStatusOs);
			
			$acesso = false;
			
			if($IdStatusCr != "" || $IdStatusCo != "" || $IdStatusOs != ""){
				if($IdStatusCr != ""){
					$sql = "SELECT
								IdContaReceber
							FROM
								ContaReceber
							WHERE
								IdPessoa = $lin[IdPessoa] AND
								IdStatus IN ($IdStatusCr)";
					$resCr = mysql_query($sql,$con);
					if(mysql_num_rows($resCr) > 0){
						$acesso = true;
					}
				}
				
				if($IdStatusCo != ""){
					$sql = "SELECT
								IdContrato
							FROM
								Contrato
							WHERE
								IdPessoa = $lin[IdPessoa] AND
								IdStatus IN ($IdStatusCo)";
					$resCo = mysql_query($sql,$con);
					if(mysql_num_rows($resCo) > 0){
						$acesso = true;
					}
				}
				
				if($IdStatusOs != ""){
					$sql = "SELECT
								IdOrdemServico
							FROM
								OrdemServico
							WHERE
								IdPessoa = $lin[IdPessoa] AND
								IdStatus IN ($IdStatusOs)";
					$resOs = mysql_query($sql,$con);
					if(mysql_num_rows($resOs) > 0){
						$acesso = true;
					}
				}
			}
			
			if($acesso){
				$sqlAtualiza = "select 
									ForcarAtualizarDadosCDA 
								from
									Pessoa 
								where
									CPF_CNPJ = '$formDados[CPF_CNPJ]' and
									ForcarAtualizarDadosCDA = 1";
				$resAtualiza = mysql_query($sqlAtualiza,$con);
				if(mysql_num_rows($resAtualiza) > 0) {
					header("Location: ../menu.php?ctt=tela_aviso_atualizar_cadastro.php&IdParametroSistema=15&Erro=72");
				} else{
					header("Location: ../menu.php");
				}	
			}else{
				header("Location: $local_urlRetornoErro?Erro=82");
			}
		} elseif($num > 1){
			header("Location: $local_urlRetornoErro?Pessoa=".md5($formDados[CPF_CNPJ]));
		} else{
			$sql = "select 
						Pessoa.IdPessoa
					from 
						Pessoa,
						Contrato,
						ContratoParametro,
						ServicoParametro 
					where 
						Pessoa.IdPessoa = Contrato.IdPessoa and
						Contrato.IdLoja = ContratoParametro.IdLoja and 
						Contrato.IdContrato = ContratoParametro.IdContrato and 
						Contrato.IdServico = ContratoParametro.IdServico and 
						ContratoParametro.IdLoja = ServicoParametro.IdLoja and 
						ContratoParametro.IdServico = ServicoParametro.IdServico and 
						ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and 
						(
							Pessoa.CPF_CNPJ = '".inserir_mascara($formDados[CPF_CNPJ])."' or
							(
								ServicoParametro.AcessoCDA = 1 and
								ContratoParametro.Valor = '$formDados[CPF_CNPJ]'
							)
						)";
			$res = mysql_query($sql,$con);
			if(mysql_num_rows($res) > 0) {
				$local_urlRetornoErro .= "?Erro=53";
			} else{
				if($formDados[ValidarCPF_CNPJ] == 1){
					$local_urlRetornoErro .= "?Erro=5";
				} else{
					$local_urlRetornoErro .= "?Erro=61";
				}
			}
			
			header ("Location: $local_urlRetornoErro");
		}
		
	} else{
		header("Location: $local_urlRetornoErro");
	}
?>