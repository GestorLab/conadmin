<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Pessoa(){
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja	 				= $_SESSION['IdLoja'];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$Nome 					= $_GET['Nome'];
		$IdPais					= $_GET['IdPais'];
		$IdEstado				= $_GET['IdEstado'];
		$NomeCidade				= $_GET['NomeCidade'];
		$TipoAgenteAutorizado	= $_GET['TipoAgenteAutorizado'];
		$TipoVendedor			= $_GET['TipoVendedor'];
		$TipoUsuario			= $_GET['TipoUsuario'];
		$TipoFornecedor			= $_GET['TipoFornecedor'];
		$CPF_CNPJ				= $_GET['CPF_CNPJ'];		
		$TipoPessoa				= $_GET['TipoPessoa'];	
		$IdFornecedor			= $_GET['IdFornecedor'];
		$Local					= $_GET['Local'];
		$AnoDeclaracaoPagamento	= $_GET['AnoDeclaracaoPagamento'];
		
		$from			= "";
		$where			= "";
		$groupBy		= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdPessoa != ''){				$where .= " and Pessoa.IdPessoa=$IdPessoa";	}
		if($Nome !=''){						$where .= " and (Pessoa.Nome like '$Nome%' or Pessoa.RazaoSocial like '$Nome%')";	}
		if($IdPais != ''){					$where .= " and PessoaEndereco.IdPais=$IdPais";	}
		if($IdEstado != ''){				$where .= " and PessoaEndereco.IdEstado ='$IdEstado'";	}
		if($NomeCidade != ''){				$where .= " and Cidade.NomeCidade like '$NomeCidade%'";	}
		if($CPF_CNPJ != ''){				$where .= " and Pessoa.CPF_CNPJ like '$CPF_CNPJ%'";	}	
		if($TipoAgenteAutorizado != ''){	$where .= " and Pessoa.TipoAgenteAutorizado = '$TipoAgenteAutorizado'";	}		
		if($TipoVendedor != ''){			$where .= " and Pessoa.TipoVendedor = '$TipoVendedor'";	}			
		if($TipoFornecedor != ''){			$where .= " and Pessoa.TipoFornecedor = '$TipoFornecedor'";	}			
		if($TipoPessoa != ''){				$where .= " and Pessoa.TipoPessoa = '$TipoPessoa'";	}			
		if($TipoUsuario != ''){				$where .= " and Pessoa.TipoUsuario = '$TipoUsuario'";		}
		
		if($AnoDeclaracaoPagamento != ''){
			$from	.= ", ContaReceber, ContaReceberRecebimento";
			$where	.= " and 
					Pessoa.IdPessoa = ContaReceber.IdPessoa and
		            ContaReceber.IdLoja = $IdLoja and
		            ContaReceber.IdLoja = ContaReceberRecebimento.IdLoja and
		            ContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
		            ContaReceberRecebimento.IdStatus = 1 and
		            substring(ContaReceberRecebimento.DataRecebimento, 1, 4) = '$AnoDeclaracaoPagamento'";
		    $groupBy.= " group by ContaReceber.IdPessoa";
		}
		
		if($IdFornecedor != ''){
			$from	.= ",Fornecedor";
			$where	.= " and Pessoa.IdPessoa = Fornecedor.IdFornecedor";	
		}	
		
		if($IdPessoa != ''){
			$sql	=	"select count(*) Qtd from Loja where IdPessoa=$IdPessoa";
			$res	=	@mysql_query($sql,$con);
			if($lin	=	@mysql_fetch_array($res)){
				$Qtd_Loja	=	$lin[Qtd];
			}
		}
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								Carteira.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}

		$sqlRestringirPessoa = "select
									*
								from
									Loja
								where
									IdLoja = $IdLoja and
									RestringirPessoa = 1";
		$resRestringirPessoa = @mysql_query($sqlRestringirPessoa,$con);
		if($linRestringirPessoa = @mysql_fetch_array($resRestringirPessoa)){
			$where .=	" and (Pessoa.IdLoja = $IdLoja or Pessoa.IdLoja is NULL)";
		}
		
		$sql = "select distinct
					Pessoa.IdPessoa,					
					Pessoa.TipoPessoa,
					Pessoa.Nome,	
					Pessoa.NomePai,
					Pessoa.NomeMae,
					Pessoa.NomeRepresentante,					
					Pessoa.RazaoSocial,
					Pessoa.DataNascimento,			
					Pessoa.Sexo,
					Pessoa.RG_IE,
					Pessoa.CPF_CNPJ,					
					Pessoa.EstadoCivil,			
					Pessoa.InscricaoMunicipal,
					Pessoa.Telefone1,
					Pessoa.Telefone2,
					Pessoa.Telefone3,					
					Pessoa.Celular,
					Pessoa.Fax,	
					Pessoa.ComplementoTelefone,					
					Pessoa.Email,
					Pessoa.Site,						
					Pessoa.Obs,
					Pessoa.Senha,
					Pessoa.AgruparContratos,
					Pessoa.Cob_CobrarDespesaBoleto,
					Pessoa.MonitorFinanceiro,
					Pessoa.ForcarAtualizarDadosCDA,
					Pessoa.Cob_FormaCorreio,
					Pessoa.Cob_FormaEmail,
					Pessoa.Cob_FormaOutro,
					GrupoPessoa.IdGrupoPessoa,
					GrupoPessoa.DescricaoGrupoPessoa,
					Pessoa.TipoUsuario,
					Pessoa.TipoAgenteAutorizado,
					Pessoa.TipoFornecedor,
					Pessoa.TipoVendedor,
					Pessoa.CampoExtra1,
					Pessoa.CampoExtra2,
					Pessoa.CampoExtra3,
					Pessoa.CampoExtra4,
					Pessoa.OrgaoExpedidor,
					Pessoa.NomeConjugue,
					Pessoa.IdEnderecoDefault,					
					Pessoa.DataCriacao,				
					Pessoa.LoginCriacao,
					Pessoa.DataAlteracao,			
					Pessoa.LoginAlteracao,
					Contrato.IdContrato,
					PessoaEndereco.Endereco,
					PessoaEndereco.CEP,
					PessoaEndereco.Numero,
					PessoaEndereco.Bairro,
					PessoaEndereco.IdPais,
					Pais.NomePais,
					PessoaEndereco.IdEstado,
					Estado.NomeEstado,
					Estado.SiglaEstado,
					PessoaEndereco.IdCidade,
					Cidade.NomeCidade,
					PessoaEndereco.Telefone,
					PessoaEndereco.Celular CelularEndereco,
					PessoaEndereco.Fax FaxEndereco,
					PessoaEndereco.EmailEndereco
				from 
					Pessoa LEFT JOIN (
						(
							select 
								IdContrato,
								IdPessoa 
							from 
								Contrato 
							where 
								Contrato.IdLoja = $IdLoja and 
								Contrato.IdStatus >= 200 
							group by 
								IdPessoa
						) Contrato
					) ON (
						Pessoa.IdPessoa = Contrato.IdPessoa
					) LEFT JOIN (
						PessoaGrupoPessoa,
						GrupoPessoa
					) ON (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					),
					PessoaEndereco LEFT JOIN (
						Pais,
						Estado,
						Cidade
					) ON (
						PessoaEndereco.IdPais = Pais.IdPais and
						Pais.IdPais = Estado.IdPais and 
						PessoaEndereco.IdEstado = Estado.IdEstado and
						Estado.IdPais = Cidade.IdPais and 
						Estado.IdEstado = Cidade.IdEstado and
						PessoaEndereco.IdCidade = Cidade.IdCidade
					) 
					$from
				where
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
					PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault 
					$where 
					$groupBy 
					$Limit";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[CPF_CNPJ]!=''){
				$temp	=	explode('.',$lin[CPF_CNPJ]);
				if($temp[1] == ''){
					if($lin[TipoPessoa] == 2){
						$lin[CPF_CNPJ] = substr($lin[CPF_CNPJ],0,3).'.'.substr($lin[CPF_CNPJ],3,3).'.'.substr($lin[CPF_CNPJ],6,3).'-'.substr($lin[CPF_CNPJ],9,2);		
					}else{
				 		$lin[CPF_CNPJ] = substr($lin[CPF_CNPJ],0,2).'.'.substr($lin[CPF_CNPJ],2,3).'.'.substr($lin[CPF_CNPJ],5,3).'/'.substr($lin[CPF_CNPJ],8,4).'-'.substr($lin[CPF_CNPJ],12,2);
					}
					$sql	=	"UPDATE Pessoa SET CPF_CNPJ = '$lin[CPF_CNPJ]' WHERE IdPessoa = $lin[IdPessoa]";
					@mysql_query($sql,$con);
	    		}
	    	}
	    	
	    	if($lin[IdContrato] == ''){
				$Color	  =	getParametroSistema(15,2);
			}else{
				$Color	  =	'';
			}
			
			if($lin[TipoPessoa] == 1){
				$lin[NomeDefault]	=	$lin[getCodigoInterno(3,24)];
			}else{
				$lin[NomeDefault]	=	$lin[Nome];
			}
			
			$sql2	=	"select count(*) QTD from PessoaEndereco where IdPessoa=$lin[IdPessoa]";
			$res2	=	mysql_query($sql2,$con);
			$lin2	=	mysql_fetch_array($res2);
			
			$sql3	=	"select COUNT(*) QTD from LocalCobranca where IdLoja = 1 and IdTipoLocalCobranca = 3 and IdStatus = 1;";
			$res3	=	mysql_query($sql3, $con);
			$lin3	=	mysql_fetch_array($res3);
			
			$sql4	=	"select IdOrdemServico from OrdemServico where IdLoja = $IdLoja and IdPessoa=$lin[IdPessoa] and OrdemServico.IdStatus > 99 limit 0,1;";
			$res4	=	mysql_query($sql4,$con);
			$lin4	=	mysql_fetch_array($res4);
			
			$sql5 = "select 
						* 
					from
						(select 
							concat(count(*),'&nbsp;Contrato') ContratoAtivo,
							count(*) QtdContratoAtivo 
						from
							Contrato 
						where 
							Contrato.Idloja = $IdLoja and 
							Contrato.IdPessoa = $lin[IdPessoa] and 
							Contrato.IdStatus > 199
						) ContratoAtivo,
						(select 
							count(*) QtdContratoCancelado 
						from
							Contrato 
						where 
							Contrato.Idloja = $IdLoja and 
							Contrato.IdPessoa = $lin[IdPessoa] and 
							Contrato.IdStatus >= 0 and
							Contrato.IdStatus < 200
						) ContratoCancelado,
						(select 
							concat(count(*),'&nbsp;Ordem&nbsp;Serviço') OrdemServico, 
							count(*) QtdOrdemServico 
						from
							OrdemServico 
						where 
							OrdemServico.IdLoja = $IdLoja and 
							OrdemServico.Idpessoa = $lin[IdPessoa] and (
								(
									OrdemServico.IdStatus > 99 and 
									OrdemServico.IdStatus < 200
								) or (
									OrdemServico.IdStatus > 299 and 
									OrdemServico.IdStatus < 499
								)
							)
						) OrdemServico;";
			$res5 = mysql_query($sql5,$con);
			$lin5 = @mysql_fetch_array($res5);
			$lin[CorStatus] = "#c10000";
			
			if($lin5[QtdContratoAtivo] > 0){
				if($lin5[QtdContratoAtivo] > 1){
					$lin5[ContratoAtivo] .= 's';
				}
				
				$lin[TitStatus] = $MouseTit = '';
				$sql_co = "select distinct
								substring(Contrato.IdStatus,1,1) IdStatus
							from
								Contrato 
							where 
								Contrato.Idloja = $IdLoja and 
								Contrato.IdPessoa = $lin[IdPessoa] and 
								Contrato.IdStatus > 199";
				$res_co = mysql_query($sql_co,$con);
				
				if(@mysql_num_rows($res_co) == 1){
					$lin_co = @mysql_fetch_array($res_co);
					$ColorTit = "color:".getCodigoInterno(15, $lin_co[IdStatus]).";";
				}
				
				$StatusContrato = array();
				$i = 0;
				$sql_co = "select
								Contrato.IdContrato,
								Contrato.IdStatus,
								concat('[CO ',Contrato.IdContrato,'] ',ParametroSistema.ValorParametroSistema) Contrato
							from
								Contrato, 
								ParametroSistema
							where 
								Contrato.Idloja = $IdLoja and 
								Contrato.IdPessoa = $lin[IdPessoa] and 
								Contrato.IdStatus > 199 and
								ParametroSistema.IdGrupoParametroSistema = 69 and
								Contrato.IdStatus = ParametroSistema.IdParametroSistema;";
				$res_co = mysql_query($sql_co,$con);
				$Local_IdContratoAux = "";
				while($lin_co = @mysql_fetch_array($res_co)){
					$lin[TitStatus] .= $lin_co[Contrato]."<br />";
					$Local_IdContratoAux = $lin_co[IdContrato];
					if(!in_array($lin_co[IdStatus],$StatusContrato)){
						$StatusContrato[$i] = $lin_co[IdStatus];
						$i++;
					}
				}
				
				if($lin[TitStatus] != ''){
					$MouseTit = " onmousemove='quadro_alt(event, this, \"".$lin[TitStatus]."\");'";
					if($lin5[QtdContratoAtivo] > 1){
						$lin5[ContratoAtivo] = "<span style='cursor:pointer;".$ColorTit."'".$MouseTit." onclick='window.open(\"listar_contrato.php?IdStatus=".implode(',',$StatusContrato)."&IdPessoa=".$lin[IdPessoa]."&filtro_limit=".$lin5[QtdContratoAtivo]."\");'>".$lin5[ContratoAtivo]."</span>";
					}else{
						$lin5[ContratoAtivo] = "<span style='cursor:pointer;".$ColorTit."'".$MouseTit." onclick=\"window.location='cadastro_contrato.php?IdContrato=".$Local_IdContratoAux."'\">".$lin5[ContratoAtivo]."</span>";
					}
				}				
			
				$lin[Status] = $lin5[ContratoAtivo];
				
				if($lin5[QtdOrdemServico] > 0){
					if($lin5[QtdOrdemServico] > 1){
						$lin5[OrdemServico] .= 's';
					}
					
					$lin[TitStatus] = $MouseTit = '';
					$sql_os = "select distinct
									substring(OrdemServico.IdStatus,1,1) IdStatus
								from
									OrdemServico 
								where 
									OrdemServico.IdLoja = $IdLoja and 
									OrdemServico.Idpessoa = $lin[IdPessoa] and (
										(
											OrdemServico.IdStatus > 99 and 
											OrdemServico.IdStatus < 200
										) or (
											OrdemServico.IdStatus > 299 and 
											OrdemServico.IdStatus < 500
										)
									)";
					$res_os = mysql_query($sql_os,$con);
					
					if(@mysql_num_rows($res_os) == 1){
						$lin_os = @mysql_fetch_array($res_os);
						$ColorTit = "color:".getCodigoInterno(16, $lin_os[IdStatus]).";";
					}
					
					$StatusOrdemServico = array();
					$i = 0;
					$sql_os = "select
									OrdemServico.IdOrdemServico,
									OrdemServico.IdStatus,
									concat('[OS ',OrdemServico.IdOrdemServico,'] ',ParametroSistema.ValorParametroSistema) OrdemServico
								from
									OrdemServico, 
									ParametroSistema
								where 
									OrdemServico.IdLoja = $IdLoja and 
									OrdemServico.Idpessoa = $lin[IdPessoa] and (
										(
											OrdemServico.IdStatus > 99 and 
											OrdemServico.IdStatus < 200
										) or (
											OrdemServico.IdStatus > 299 and 
											OrdemServico.IdStatus < 500
										)
									) and
									ParametroSistema.IdGrupoParametroSistema = 40 and
									OrdemServico.IdStatus = ParametroSistema.IdParametroSistema";
					$res_os = mysql_query($sql_os,$con);
					$Local_IdOrdemServicoAux = "";
					while($lin_os = @mysql_fetch_array($res_os)){
						$lin[TitStatus] .= $lin_os[OrdemServico]."<br />";
						$Local_IdOrdemServicoAux = $lin_os[IdOrdemServico];
						if(!in_array($lin_os[IdStatus],$StatusOrdemServico)){
							$StatusOrdemServico[$i] = $lin_os[IdStatus];
							$i++;
						}
					}
					
					if($lin[TitStatus] != ''){
						$MouseTit = " onmousemove='quadro_alt(event, this, \"".$lin[TitStatus]."\");'";
					}
					if($lin5[QtdOrdemServico] > 1){
						$lin5[OrdemServico] = "<span style='cursor:pointer;".$ColorTit."'".$MouseTit." onClick='window.open(\"listar_ordem_servico.php?IdStatus=".implode(',',$StatusOrdemServico)."&IdPessoa=".$lin[IdPessoa]."&filtro_limit=".$lin5[QtdOrdemServico]."\");'>".$lin5[OrdemServico]."</span>";
					}else{
						$lin5[OrdemServico] = "<span style='cursor:pointer;".$ColorTit."'".$MouseTit." onclick=\"window.location='cadastro_ordem_servico.php?IdOrdemServico=".$Local_IdOrdemServicoAux."'\">".$lin5[OrdemServico]."</span>";
					
					}
					$lin[Status] .= '&nbsp;/&nbsp;'.$lin5[OrdemServico];
				}
			} elseif($lin5[QtdContratoCancelado] > 0 && $lin5[QtdOrdemServico] == 0){
				$lin[Status] = "Inativo";
			} else{
				if($lin5[QtdContratoCancelado] > 0){
					$lin[Status] = "Inativo";
				}else{
					$lin[Status] = "Não&nbsp;possui&nbsp;contrato";
				}
				
				if($lin5[QtdOrdemServico] > 0){
					if($lin5[QtdOrdemServico] > 1){
						$lin5[OrdemServico] .= 's';
					}
					
					$lin[TitStatus] = $MouseTit = '';
					$sql_os = "select distinct
									substring(OrdemServico.IdStatus,1,1) IdStatus
								from
									OrdemServico 
								where 
									OrdemServico.IdLoja = $IdLoja and 
									OrdemServico.Idpessoa = $lin[IdPessoa] and (
										(
											OrdemServico.IdStatus > 99 and 
											OrdemServico.IdStatus < 200
										) or (
											OrdemServico.IdStatus > 299 and 
											OrdemServico.IdStatus < 500
										)
									)";
					$res_os = mysql_query($sql_os,$con);
					
					if(@mysql_num_rows($res_os) == 1){
						$lin_os = @mysql_fetch_array($res_os);
						$ColorTit = "color:".getCodigoInterno(16, $lin_os[IdStatus]).";";
					}
					
					$StatusOrdemServico = array();
					$i = 0;
					$sql_os = "select 
									OrdemServico.IdStatus,
									concat('[OS ',OrdemServico.IdOrdemServico,'] ',ParametroSistema.ValorParametroSistema) OrdemServico
								from
									OrdemServico, 
									ParametroSistema
								where 
									OrdemServico.IdLoja = $IdLoja and 
									OrdemServico.Idpessoa = $lin[IdPessoa] and (
										(
											OrdemServico.IdStatus > 99 and 
											OrdemServico.IdStatus < 200
										) or (
											OrdemServico.IdStatus > 299 and 
											OrdemServico.IdStatus < 500
										)
									) and
									ParametroSistema.IdGrupoParametroSistema = 40 and
									OrdemServico.IdStatus = ParametroSistema.IdParametroSistema";
					$res_os = mysql_query($sql_os,$con);
					
					while($lin_os = @mysql_fetch_array($res_os)){
						$lin[TitStatus] .= $lin_os[OrdemServico]."<br />";
						
						if(!in_array($lin_os[IdStatus],$StatusOrdemServico)){
							$StatusOrdemServico[$i] = $lin_os[IdStatus];
							$i++;
						}
					}
					
					if($lin[TitStatus] != ''){
						$MouseTit = " onmousemove='quadro_alt(event, this, \"".$lin[TitStatus]."\");'";
					}
					
					$lin5[OrdemServico] = "<span style='cursor:pointer;".$ColorTit."'".$MouseTit." onClick='window.open(\"listar_ordem_servico.php?IdStatus=".implode(',',$StatusOrdemServico)."&IdPessoa=".$lin[IdPessoa]."&filtro_limit=".$lin5[QtdOrdemServico]."\");'>".$lin5[OrdemServico]."</span>";
					$lin[Status] .= '&nbsp;/&nbsp;'.$lin5[OrdemServico];
				}
			}
			
			$sql6	=	"select COUNT(*) QTD from LocalCobranca where IdLoja = 1 and IdTipoLocalCobranca = 6 and IdStatus = 1;";
			$res6	=	mysql_query($sql6, $con);
			$lin6	=	mysql_fetch_array($res6);
			/*
			$sql7	=	"select 
							count(*) Qtd 
						from
							Usuario 
						where
							IdPessoa = $lin[IdPessoa] and
							IdStatus = 1";
			$res7	=	mysql_query($sql7, $con);
			$lin7	=	mysql_fetch_array($res7);
			if($lin7[Qtd] >= 1){
				$Disabled = "true";
			}else{
				$Disabled = "false";
			}
			*/
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<OrgaoExpedidor><![CDATA[$lin[OrgaoExpedidor]]]></OrgaoExpedidor>";
			$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<CorStatus><![CDATA[$lin[CorStatus]]]></CorStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<NomeDefault><![CDATA[$lin[NomeDefault]]]></NomeDefault>";
			$dados	.=	"\n<NomeRepresentante><![CDATA[$lin[NomeRepresentante]]]></NomeRepresentante>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<DataNascimento><![CDATA[$lin[DataNascimento]]]></DataNascimento>";
			$dados	.=	"\n<Sexo><![CDATA[$lin[Sexo]]]></Sexo>";
			$dados	.=	"\n<IdPais><![CDATA[$lin[IdPais]]]></IdPais>";
			$dados	.=	"\n<NomePais><![CDATA[$lin[NomePais]]]></NomePais>";
			$dados	.=	"\n<IdEstado><![CDATA[$lin[IdEstado]]]></IdEstado>";
			$dados	.=	"\n<NomeEstado><![CDATA[$lin[NomeEstado]]]></NomeEstado>";
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
			$dados	.=	"\n<IdCidade><![CDATA[$lin[IdCidade]]]></IdCidade>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
			$dados	.=	"\n<RG_IE><![CDATA[$lin[RG_IE]]]></RG_IE>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			$dados	.=	"\n<EstadoCivil><![CDATA[$lin[EstadoCivil]]]></EstadoCivil>";
			$dados	.=	"\n<InscricaoMunicipal><![CDATA[$lin[InscricaoMunicipal]]]></InscricaoMunicipal>";
			$dados	.=	"\n<CEP><![CDATA[$lin3[CEP]]]></CEP>";
			$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";
			$dados	.=	"\n<Complemento><![CDATA[$lin[Complemento]]]></Complemento>";
			$dados	.=	"\n<Numero><![CDATA[$lin[Numero]]]></Numero>";
			$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";
			$dados	.=	"\n<Telefone1><![CDATA[$lin[Telefone1]]]></Telefone1>";
			$dados	.=	"\n<Telefone2><![CDATA[$lin[Telefone2]]]></Telefone2>";
			$dados	.=	"\n<Telefone3><![CDATA[$lin[Telefone3]]]></Telefone3>";
			$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
			$dados	.=	"\n<Fax><![CDATA[$lin[Fax]]]></Fax>";
			$dados	.=	"\n<ComplementoTelefone><![CDATA[$lin[ComplementoTelefone]]]></ComplementoTelefone>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<Site><![CDATA[$lin[Site]]]></Site>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Cob_FormaCorreio><![CDATA[$lin[Cob_FormaCorreio]]]></Cob_FormaCorreio>";
			$dados	.=	"\n<Cob_FormaEmail><![CDATA[$lin[Cob_FormaEmail]]]></Cob_FormaEmail>";
			$dados	.=	"\n<Cob_FormaOutro><![CDATA[$lin[Cob_FormaOutro]]]></Cob_FormaOutro>";
			$dados	.=	"\n<IdGrupoPessoa><![CDATA[$lin[IdGrupoPessoa]]]></IdGrupoPessoa>";
			$dados	.=	"\n<Telefone><![CDATA[$lin[Telefone]]]></Telefone>";
			$dados	.=	"\n<CelularEndereco><![CDATA[$lin[CelularEndereco]]]></CelularEndereco>";
			$dados	.=	"\n<FaxEndereco><![CDATA[$lin[FaxEndereco]]]></FaxEndereco>";
			$dados	.=	"\n<EmailEndereco><![CDATA[$lin[EmailEndereco]]]></EmailEndereco>";
			$dados	.=	"\n<DescricaoGrupoPessoa><![CDATA[$lin[DescricaoGrupoPessoa]]]></DescricaoGrupoPessoa>";
			$dados	.=	"\n<Senha><![CDATA[$lin[Senha]]]></Senha>";
			$dados	.=	"\n<QtdEndereco><![CDATA[$lin2[QTD]]]></QtdEndereco>";
			$dados	.=	"\n<Cob_CobrarDespesaBoleto><![CDATA[$lin[Cob_CobrarDespesaBoleto]]]></Cob_CobrarDespesaBoleto>";
			$dados	.=	"\n<AgruparContratos><![CDATA[$lin[AgruparContratos]]]></AgruparContratos>";
			$dados	.=	"\n<TipoUsuario><![CDATA[$lin[TipoUsuario]]]></TipoUsuario>";
			$dados	.=	"\n<TipoAgenteAutorizado><![CDATA[$lin[TipoAgenteAutorizado]]]></TipoAgenteAutorizado>";
			$dados	.=	"\n<TipoFornecedor><![CDATA[$lin[TipoFornecedor]]]></TipoFornecedor>";
			$dados	.=	"\n<TipoVendedor><![CDATA[$lin[TipoVendedor]]]></TipoVendedor>";
			$dados	.=	"\n<CampoExtra1><![CDATA[$lin[CampoExtra1]]]></CampoExtra1>";
			$dados	.=	"\n<CampoExtra2><![CDATA[$lin[CampoExtra2]]]></CampoExtra2>";
			$dados	.=	"\n<CampoExtra3><![CDATA[$lin[CampoExtra3]]]></CampoExtra3>";
			$dados	.=	"\n<CampoExtra4><![CDATA[$lin[CampoExtra4]]]></CampoExtra4>";
			$dados	.=	"\n<NomePai><![CDATA[$lin[NomePai]]]></NomePai>";
			$dados	.=	"\n<NomeMae><![CDATA[$lin[NomeMae]]]></NomeMae>";
			$dados	.=	"\n<NomeConjugue><![CDATA[$lin[NomeConjugue]]]></NomeConjugue>";
			$dados	.=	"\n<IdEnderecoDefault><![CDATA[$lin[IdEnderecoDefault]]]></IdEnderecoDefault>";
			$dados	.=	"\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
			$dados	.=	"\n<Qtd_Loja><![CDATA[$Qtd_Loja]]></Qtd_Loja>";
			$dados	.=	"\n<IdOrdemServico><![CDATA[$lin4[IdOrdemServico]]]></IdOrdemServico>";
			$dados	.=	"\n<ContaDebito><![CDATA[$lin3[QTD]]]></ContaDebito>";
			$dados	.=	"\n<CartaoCredito><![CDATA[$lin6[QTD]]]></CartaoCredito>";
			$dados	.=	"\n<MonitorFinanceiro><![CDATA[$lin[MonitorFinanceiro]]]></MonitorFinanceiro>";
			$dados	.=	"\n<ForcarAtualizarDadosCDA><![CDATA[$lin[ForcarAtualizarDadosCDA]]]></ForcarAtualizarDadosCDA>";
			//$dados	.=	"\n<Disabled><![CDATA[$Disabled]]></Disabled>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Pessoa();
?>