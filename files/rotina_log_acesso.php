<?
	$_SESSION["RestringirAgenteAutorizado"] = false;	
	$_SESSION["RestringirAgenteCarteira"]	= false;
	$_SESSION["RestringirCarteira"]			= false;

	if($_SESSION["Login"] != 'root'){
		$sqlAgenteAutorizado = "select
									AgenteAutorizado.IdAgenteAutorizado
								from
									Usuario,
									Pessoa,
									AgenteAutorizado
								where
									Usuario.Login = '".$_SESSION["Login"]."' and
									Usuario.IdPessoa = Pessoa.IdPessoa and
									AgenteAutorizado.IdLoja = ".$_SESSION["IdLoja"]." and
									AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
									AgenteAutorizado.Restringir = 1 and
									AgenteAutorizado.IdStatus = 1";
		$resAgenteAutorizado = mysql_query($sqlAgenteAutorizado, $con);
		if($linAgenteAutorizado = mysql_fetch_array($resAgenteAutorizado)){
			$_SESSION["RestringirAgenteAutorizado"] = true;
		}
		
		# Regra 2 ##
		$sqlCarteira = "select
							Carteira.IdAgenteAutorizado,
							Carteira.IdCarteira
						from
							Usuario,
							Pessoa,
							Carteira
						where
							Usuario.Login = '".$_SESSION["Login"]."' and
							Usuario.IdPessoa = Pessoa.IdPessoa and
							Carteira.IdLoja = ".$_SESSION["IdLoja"]." and
							Carteira.IdCarteira = Pessoa.IdPessoa and
							Carteira.Restringir = 1 and
							Carteira.IdStatus = 1";
		$resCarteira = mysql_query($sqlCarteira, $con);
		if($linCarteira = mysql_fetch_array($resCarteira)){
			$_SESSION["RestringirCarteira"] = true;
		}
		
		# Regra 3 ##
		$sqlAgenteAutorizado = "select
									AgenteAutorizado.IdAgenteAutorizado
								from
									Usuario,
									Pessoa,
									AgenteAutorizado,
									Carteira
								where
									Usuario.Login = '".$_SESSION["Login"]."' and
									Usuario.IdPessoa = Pessoa.IdPessoa and
									AgenteAutorizado.IdLoja = ".$_SESSION["IdLoja"]." and
									AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
									Carteira.IdCarteira = Pessoa.IdPessoa and
									AgenteAutorizado.Restringir = 1 and
									AgenteAutorizado.IdStatus = 1";
		$resAgenteAutorizado = mysql_query($sqlAgenteAutorizado, $con);
		if($linAgenteAutorizado = mysql_fetch_array($resAgenteAutorizado)){
			$_SESSION["RestringirAgenteCarteira"] = true;
		}

		# Regra 4
	#	$LimiteVisualizacao = $lin[LimiteVisualizacao]; ## Acho que está perdido
		$sqlLimiteVisualizacao = "select
										max(GrupoPermissao.LimiteVisualizacao) LimiteVisualizacao
									from
										UsuarioGrupoPermissao,
										GrupoPermissao
									where
										Login = '".$_SESSION["Login"]."' and
										UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissao.IdGrupoPermissao";
		$resLimiteVisualizacao = mysql_query($sqlLimiteVisualizacao,$con);
		$linLimiteVisualizacao = mysql_fetch_array($resLimiteVisualizacao);

		if($linLimiteVisualizacao[LimiteVisualizacao] > $LimiteVisualizacao){
			$LimiteVisualizacao = $linLimiteVisualizacao[LimiteVisualizacao];
		}
		$_SESSION["LimiteVisualizacao"] = $LimiteVisualizacao;
	}
?>