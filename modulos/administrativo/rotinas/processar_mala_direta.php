<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"P")){
		$local_Erro = 2;
	} else{
		$sql = "select 
					MalaDireta.ListaEmail,
					MalaDireta.Filtro_IdPessoa,
					MalaDireta.Filtro_IdGrupoPessoa,
					MalaDireta.Filtro_IdServico,
					MalaDireta.Filtro_IdContrato,
					MalaDireta.Filtro_IdStatusContrato,
					MalaDireta.Filtro_IdProcessoFinanceiro,
					MalaDireta.Filtro_IdPaisEstadoCidade
				from 
					MalaDireta 
				where 
					MalaDireta.IdLoja = '$local_IdLoja' and
					MalaDireta.IdMalaDireta = $local_IdMalaDireta;";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		$local_ListaEmail = array(
			"IdPessoa" => array(), 
			"Email" => array()
		);
		$cont = 0;
		$from = '';
		$where = '';
		
		if($lin[Filtro_IdPessoa] != ''){
			$where .= " and Pessoa.IdPessoa in ($lin[Filtro_IdPessoa])";
		}
		
		if($lin[Filtro_IdGrupoPessoa] != ''){
			$where .= " and PessoaGrupoPessoa.IdGrupoPessoa in ($lin[Filtro_IdGrupoPessoa])";
		}
		
		if($where != ''){
			if($lin[Filtro_IdPaisEstadoCidade] != ''){
				$from .= ",PessoaEndereco";
				$where .= " and Pessoa.IdPessoa = PessoaEndereco.IdPessoa and Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and CONCAT(PessoaEndereco.IdPais,',',PessoaEndereco.IdEstado,',',PessoaEndereco.IdCidade) in ('".implode("','", explode("^", $lin[Filtro_IdPaisEstadoCidade]))."')";
			}
			
			$sql_FiltroPessoa = "select 
									Pessoa.IdPessoa,
									Pessoa.Email
								from 
									Pessoa,
									PessoaGrupoPessoa
									$from
								where 
									(
										Pessoa.IdLoja = '$local_IdLoja' or 
										Pessoa.IdLoja = '' or 
										Pessoa.IdLoja is null
									) and 
									Pessoa.Email != '' and
									PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and
									Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa
									$where
								group by 
									Pessoa.Email;";
			$res_FiltroPessoa = mysql_query($sql_FiltroPessoa,$con);
			
			while($lin_FiltroPessoa = @mysql_fetch_array($res_FiltroPessoa)){
				$lin_FiltroPessoa[Email] = explode(";",$lin_FiltroPessoa[Email]);
				
				for($i = 0; $i < count($lin_FiltroPessoa[Email]); $i++){
					$lin_FiltroPessoa[Email][$i] = trim($lin_FiltroPessoa[Email][$i]);
					
					if(!in_array($lin_FiltroPessoa[Email][$i], $local_ListaEmail[Email]) && $lin_FiltroPessoa[Email][$i] != ''){
						$local_ListaEmail[IdPessoa][$cont] = trim($lin_FiltroPessoa[IdPessoa]);
						$local_ListaEmail[Email][$cont] = $lin_FiltroPessoa[Email][$i];
						$cont++;
					}
				}
			}
		} elseif($lin[Filtro_IdPaisEstadoCidade] != ''){
			$sql_FiltroPaisEstadoCidade = "select 
												Pessoa.IdPessoa,
												Pessoa.Email
											from 
												Pessoa, 
												PessoaEndereco 
											where 
												(
													Pessoa.IdLoja = '$local_IdLoja' or 
													Pessoa.IdLoja = '' or 
													Pessoa.IdLoja is null
												) and 
												Pessoa.Email != '' and
												Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
												Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and 
												CONCAT(PessoaEndereco.IdPais,',',PessoaEndereco.IdEstado,',',PessoaEndereco.IdCidade) in ('".implode("','", explode("^", $lin[Filtro_IdPaisEstadoCidade]))."')
											group by 
												Pessoa.Email";
			$res_FiltroPaisEstadoCidade = mysql_query($sql_FiltroPaisEstadoCidade,$con);
			
			while($lin_FiltroPaisEstadoCidade = @mysql_fetch_array($res_FiltroPaisEstadoCidade)){
				$lin_FiltroPaisEstadoCidade[Email] = explode(";",$lin_FiltroPaisEstadoCidade[Email]);
				
				for($i = 0; $i < count($lin_FiltroPaisEstadoCidade[Email]); $i++){
					$lin_FiltroPaisEstadoCidade[Email][$i] = trim($lin_FiltroPaisEstadoCidade[Email][$i]);
					
					if(!in_array($lin_FiltroPaisEstadoCidade[Email][$i], $local_ListaEmail[Email]) && $lin_FiltroPaisEstadoCidade[Email][$i] != ''){
						$local_ListaEmail[IdPessoa][$cont] = trim($lin_FiltroPaisEstadoCidade[IdPessoa]);
						$local_ListaEmail[Email][$cont] = $lin_FiltroPaisEstadoCidade[Email][$i];
						$cont++;
					}
				}
			}
		}
		
		$whe_FiltroPessoa = '';
		
		if($cont > 0){
			$whe_FiltroPessoa = " and Pessoa.IdPessoa in (". implode(",", $local_ListaEmail[IdPessoa]).")";
		}
		
		$where_co = '';
		
		if($lin[Filtro_IdContrato] != ''){
			$where_co .= " and Contrato.IdContrato in ($lin[Filtro_IdContrato])";
		}
		
		if($lin[Filtro_IdStatusContrato] != ''){
			$where_co .= " and Contrato.IdStatus in ($lin[Filtro_IdStatusContrato])";
		}
		
		$where = '';
		
		if($lin[Filtro_IdServico] != ''){
			$where .= " and Servico.IdServico in ($lin[Filtro_IdServico])";
		}
		
		if($where != '' || $where_co != '' || $whe_FiltroPessoa != ''){
			unset($local_ListaEmail["IdPessoa"], $local_ListaEmail["Email"]);
			
			$local_ListaEmail["IdPessoa"] = array();
			$local_ListaEmail["Email"] = array();
			$cont = 0;
			
			if($where != ''){
				$where_temp = " union (
									select 
										OrdemServico.IdPessoa
									from 
										Servico,
										OrdemServico
									where 
										Servico.IdLoja = '$local_IdLoja' and
										Servico.IdLoja = OrdemServico.IdLoja and
										Servico.IdServico = OrdemServico.IdServico
										$where
								)";
			}
			
			$sql_FiltroServico = "select 
									Pessoa.IdPessoa,
									Pessoa.Email
								from
									Pessoa,
									((
										select 
											Contrato.IdPessoa
										from 
											Servico,
											Contrato
										where 
											Servico.IdLoja = '$local_IdLoja' and
											Servico.IdLoja = Contrato.IdLoja and
											Servico.IdServico = Contrato.IdServico
											$where
											$where_co
									)$where_temp) ServicoTemp
								where
									Pessoa.IdPessoa = ServicoTemp.IdPessoa and
									Pessoa.Email != '' 
									$whe_FiltroPessoa
								group by 
									Pessoa.Email;";
			$res_FiltroServico = mysql_query($sql_FiltroServico,$con);
			
			while($lin_FiltroServico = @mysql_fetch_array($res_FiltroServico)){
				$lin_FiltroServico[Email] = explode(";",$lin_FiltroServico[Email]);
				
				for($i = 0; $i < count($lin_FiltroServico[Email]); $i++){
					$lin_FiltroServico[Email][$i] = trim($lin_FiltroServico[Email][$i]);
					
					if(!in_array($lin_FiltroServico[Email][$i], $local_ListaEmail[Email]) && $lin_FiltroServico[Email][$i] != ''){
						$local_ListaEmail[IdPessoa][$cont] = trim($lin_FiltroServico[IdPessoa]);
						$local_ListaEmail[Email][$cont] = $lin_FiltroServico[Email][$i];
						$cont++;
					}
				}
			}
		}
		
		if($lin[Filtro_IdProcessoFinanceiro] != ''){
			$where .= " and LancamentoFinanceiro.IdProcessoFinanceiro in ($lin[Filtro_IdProcessoFinanceiro])";
		}
		
		if($where != '' || $where_co != '' || $whe_FiltroPessoa != ''){
			unset($local_ListaEmail["IdPessoa"], $local_ListaEmail["Email"]);
			
			$local_ListaEmail["IdPessoa"] = array();
			$local_ListaEmail["Email"] = array();
			$cont = 0;
			
			if($where != ''){
				$where_temp = " union (
									select
										OrdemServico.IdPessoa
									from 
										LancamentoFinanceiro,
										OrdemServico,
										Servico
									where 
										LancamentoFinanceiro.IdLoja = '$local_IdLoja' and
										LancamentoFinanceiro.IdLoja = OrdemServico.IdLoja and
										LancamentoFinanceiro.IdOrdemServico = OrdemServico.IdOrdemServico and 
										Servico.IdLoja = OrdemServico.IdLoja and
										Servico.IdServico = OrdemServico.IdServico
										$where
								)";
			}
			
			$sql_FiltroProcessoFinanceiro = "select 
												Pessoa.IdPessoa,
												Pessoa.Email
											from
												((
													select
														Contrato.IdPessoa
													from 
														LancamentoFinanceiro,
														Contrato,
														Servico
													where 
														LancamentoFinanceiro.IdLoja = '$local_IdLoja' and
														LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
														LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
														Servico.IdLoja = Contrato.IdLoja and
														Servico.IdServico = Contrato.IdServico
														$where
														$where_co
												)$where_temp) ProcessoFinanceiroTemp,
												Pessoa
											where
												ProcessoFinanceiroTemp.IdPessoa = Pessoa.IdPessoa and
												Pessoa.Email != ''
												$whe_FiltroPessoa
											group by
												Pessoa.Email;";
			$res_FiltroProcessoFinanceiro = mysql_query($sql_FiltroProcessoFinanceiro,$con);
			
			while($lin_FiltroProcessoFinanceiro = @mysql_fetch_array($res_FiltroProcessoFinanceiro)){
				$lin_FiltroProcessoFinanceiro[Email] = explode(";",$lin_FiltroProcessoFinanceiro[Email]);
				
				for($i = 0; $i < count($lin_FiltroProcessoFinanceiro[Email]); $i++){
					$lin_FiltroProcessoFinanceiro[Email][$i] = trim($lin_FiltroProcessoFinanceiro[Email][$i]);
					
					if(!in_array($lin_FiltroProcessoFinanceiro[Email][$i], $local_ListaEmail[Email]) && $lin_FiltroProcessoFinanceiro[Email][$i] != ''){
						$local_ListaEmail[IdPessoa][$cont] = trim($lin_FiltroProcessoFinanceiro[IdPessoa]);
						$local_ListaEmail[Email][$cont] = $lin_FiltroProcessoFinanceiro[Email][$i];
						$cont++;
					}
				}
			}
		}
		
		$ListaEmail = preg_split("/\||,|:|;|\n/",$lin[ListaEmail]);
		
		for($i = 0; $i < count($ListaEmail); $i++){
			$ListaEmail[$i] = trim($ListaEmail[$i]);
			
			if(!in_array($ListaEmail[$i], $local_ListaEmail[Email]) && $ListaEmail[$i] != ''){
				$local_ListaEmail[IdPessoa][$cont] = null;
				$local_ListaEmail[Email][$cont] = $ListaEmail[$i];
				$cont++;
			}
		}
		
		$sql = "start transaction;";
		@mysql_query($sql,$con);
		$tr_i = 0;	
		
		for($i = 0; $i < $cont; $i++){
			if($local_ListaEmail[IdPessoa][$i] == ''){
				$local_ListaEmail[IdPessoa][$i] = "NULL";
			} else{
				$local_ListaEmail[IdPessoa][$i] = "'".$local_ListaEmail[IdPessoa][$i]."'";
			}
			
			if($local_ListaEmail[Email][$i] == ''){
				$local_ListaEmail[Email][$i] = "NULL";
			} else{
				$local_ListaEmail[Email][$i] = "'".$local_ListaEmail[Email][$i]."'";
			}
			
			$sql = "insert into MalaDiretaEmail set
						IdLoja			= $local_IdLoja,
						IdMalaDireta	= $local_IdMalaDireta,
						Email			= ".$local_ListaEmail[Email][$i].",
						IdPessoa		= ".$local_ListaEmail[IdPessoa][$i].";";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
		
		$sql = "update MalaDireta set 
					IdStatus			= 2,
					LoginProcessamento	= '$local_Login', 
					DataProcessamento	= (concat(curdate(),' ',curtime()))
				where
					IdLoja = '$local_IdLoja' and
					IdMalaDireta = '$local_IdMalaDireta';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction){
			$sql = "commit;";
			$local_Erro = 147;
		} else{
			$sql = "rollback;";
			$local_Erro = 86;
		}
		
		@mysql_query($sql,$con);
	}
?>