<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdOrdemServico	= $_POST['IdOrdemServico'];
	
	$sqlPrestadora = "select
						Pessoa.IdPessoa,
						Pessoa.TipoPessoa,
						UCASE(Pessoa.RazaoSocial) RazaoSocial,
						UCASE(Pessoa.Nome) Nome,
						Pessoa.CPF_CNPJ,
						Pessoa.RG_IE,
						Pessoa.InscricaoMunicipal,
						PessoaEndereco.Endereco,
						PessoaEndereco.Numero,
						PessoaEndereco.Bairro,
						PessoaEndereco.Complemento,
						PessoaEndereco.CEP,
						Cidade.NomeCidade,
						Estado.NomeEstado,
						Estado.SiglaEstado,
						Pessoa.Telefone1,
						Pessoa.Telefone2,
						Pessoa.Telefone3,
						Pessoa.Celular,
						Pessoa.Fax,
						Pessoa.Email,
						Pessoa.NomeRepresentante,
						Pessoa.DataNascimento,
						Pessoa.CampoExtra1 NomeMae,
						Pessoa.CampoExtra2 Profissao,
						Pessoa.EstadoCivil
					from
						Pessoa,
						PessoaEndereco,
						Cidade,
						Estado
					where
						Pessoa.IdPessoa IN(
							SELECT
								IdPessoa
							FROM
								Loja
							WHERE 
							IdLoja = '$local_IdLoja'
						) and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
						Estado.IdPais = PessoaEndereco.IdPais and
						Cidade.IdPais = PessoaEndereco.IdPais and
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdCidade = PessoaEndereco.IdCidade and
						Cidade.IdEstado = Estado.IdEstado";
	@$resPrestadora = mysql_query($sqlPrestadora,$con);
	@$linPrestadora = mysql_fetch_array($resPrestadora);
	
	$PrestadoraNomeFantasia	= $linPrestadora[Nome];
	$PrestadoraRazaoSocial	= $linPrestadora[RazaoSocial];
	$PrestadoraEndereco		= $linPrestadora[Endereco];
	$PrestadoraCEP			= $linPrestadora[CEP];
	$PrestadoraCNPJ			= $linPrestadora[CPF_CNPJ];
	$PrestadoraCidadeEstado	= $linPrestadora[NomeCidade]." / ".$linPrestadora[SiglaEstado];
	if($linPrestadora[Numero] != ""){
		$PrestadoraNumero	= $linPrestadora[Numero];
	}
	if($linPrestadora[Bairro] != ""){
		$PrestadoraBairro	= $linPrestadora[Bairro];
	}
	if($linPrestadora[Telefone1] != ""){
		$PrestadoraTelefone	= $linPrestadora[Telefone1];
	}else if($linPrestadora[Telefone2] !=""){
		$PrestadoraTelefone	= $linPrestadora[Telefone2];
	}else{
		$PrestadoraTelefone	= $linPrestadora[Telefone3];
	}
	
	$sqlOS 	="	select 
					Pessoa.IdPessoa,
					OrdemServico.IdOrdemServico,
					OrdemServico.DataAgendamentoAtendimento,
					OrdemServico.DataCriacao,
					OrdemServico.IdContrato,
					OrdemServico.DescricaoOS,
					OrdemServico.Valor + OrdemServico.ValorOutros TaxaInstalacao,					
					Pessoa.TipoPessoa,
					UCASE(Pessoa.RazaoSocial) RazaoSocial,
					UCASE(Pessoa.Nome) Nome,
					Pessoa.CPF_CNPJ,
					Pessoa.RG_IE,
					Pessoa.InscricaoMunicipal,
					PessoaEndereco.Endereco,
					PessoaEndereco.Numero,
					PessoaEndereco.Bairro,
					PessoaEndereco.Complemento,
					PessoaEndereco.CEP,
					Cidade.NomeCidade,
					Estado.NomeEstado,
					Estado.SiglaEstado,
					Pessoa.Telefone1,
					Pessoa.Telefone2,
					Pessoa.Telefone3,
					Pessoa.Celular,
					Pessoa.Fax,
					Pessoa.Email,
					Pessoa.NomeRepresentante,
					Pessoa.NomeRepresentante,
					Pessoa.DataNascimento,
					Pessoa.CampoExtra1 CodigoAssinatura,
					Pessoa.EstadoCivil 
				from
					OrdemServico,
					Pessoa,
					PessoaEndereco,
					Cidade,
					Estado					
				where
					OrdemServico.IdLoja = $local_IdLoja and
					OrdemServico.IdOrdemServico = $local_IdOrdemServico and
					OrdemServico.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
					Estado.IdPais = PessoaEndereco.IdPais and
					Cidade.IdPais = PessoaEndereco.IdPais and
					Estado.IdEstado = PessoaEndereco.IdEstado and
					Cidade.IdCidade = PessoaEndereco.IdCidade and
					Cidade.IdEstado = Estado.IdEstado";
	$resOS = @mysql_query($sqlOS,$con);
	$linOS = @mysql_fetch_array($resOS);
	
	$BairroClienteOS			= $linOS[Bairro];
	$CelularClienteOS			= $linOS[Celular];
	$CepClienteOS				= $linOS[CEP];
	$CpfCnpjClienteOS			= $linOS[CPF_CNPJ];
	$DataAgendamento			= $linOS[DataAgendamentoAtendimento];
	$DataAgendamento			= explode(" ",$DataAgendamento);
	$DataAgendamentoOS			= dataConv($DataAgendamento[0],'Y-m-d','d/m/y');
	$DataOrigemSolicitacaoOS	= dataConv($linOS[DataCriacao],'Y-m-d','d/m/Y');
	$EnderecoClienteOS			= $linOS[Endereco];
	$ComplemntoEnderecoClienteOS= $linOS[Complemento];
	$HoraAgendamentoOS			= $DataAgendamento[1];
	$HoraAgendamentoOS			= explode(":",$HoraAgendamentoOS);
	$HoraAgendamentoOS			= $HoraAgendamentoOS[0].":".$HoraAgendamentoOS[1];
	$NomeClienteOS				= $linOS[Nome];
	$NomeClienteOS				= strtolower($NomeClienteOS);
	$NomeClienteOS				= ucwords($NomeClienteOS);
	$NumeroEnderecoClienteOS	= trim($linOS[Numero]);
	$NumeroOS					= $linOS[IdOrdemServico];
	$NumeroAtendimento			= $linOS[IdContrato];
	$ObservaçõesOS				= $linOS[DescricaoOS];
	$TelefoneClienteOS			= $linOS[Telefone1];
	$Moeda						= getParametroSistema(5,1);
	$CodigoAssinatura			= $linOS[CodigoAssinatura];

	if(($linOS[Telefone2] != "") && ($linOS[Telefone3] != "")){
		if($linOS[Telefone2] != ""){
			$TelefoneComercial	= $linOS[Telefone2];
		}else{
			$TelefoneComercial	= $linOS[Telefone3];
		}
	}
	if($NumeroEnderecoClienteOS !=""){
		$sepNumeroCliente		= ", "; 
	}
	if($CidadeUF !=""){
		$CidadeUF				= $linOS[NomeCidade]."/".$linOS[SiglaEstado]; 
	}
	if($linOS[Telefone1] != ""){
		$separador1				= " / ";
	}else{
		$separador1				= "";
	}
	if($linOS[Telefone2] != ""){
		$separador2				= " / ";
	}else{ 
		$separador2				= "";
	}

	if($ComplemntoEnderecoClienteOS != ""){
		$sepEnderecoCliente = ", ";
	}
	
	if($linOS[IdContrato] != ""){
		$sql ="select	
					Contrato.DiaCobranca,
					Servico.DescricaoServico
				from
					Contrato,
					Servico
				where
					Contrato.IdLoja = $local_IdLoja and
					Contrato.IdLoja = Servico.IdLoja and
					Contrato.IdServico = Servico.IdServico and
					Contrato.IdContrato = $linOS[IdContrato]";
		$resContrato	= mysql_query($sql,$con);
		$linContrato	= mysql_fetch_array($resContrato);
		
		$DiaCobranca	= "Dia ".$linContrato[DiaCobranca];
		$PlanoAcesso	=  $linContrato[DescricaoServico];
		
		$sqlParametroContrato = "select
									ContratoParametro.IdParametroServico,
									ContratoParametro.Valor,
									ServicoParametro.VisivelOS
								from
									ContratoParametro,
									ServicoParametro
								where
									ContratoParametro.IdLoja = $local_IdLoja and
									ContratoParametro.IdLoja = ServicoParametro.IdLoja and
									ContratoParametro.IdServico = ServicoParametro.IdServico and
									ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico and
									ContratoParametro.IdContrato = $linOS[IdContrato]";
		$resParametroContrato = mysql_query($sqlParametroContrato,$con);
		while($linParametroContrato = mysql_fetch_array($resParametroContrato)){
			if($linParametroContrato[VisivelOS] == 1){
				$ParametroContrato[$linParametroContrato[IdParametroServico]] = $linParametroContrato[Valor];
			}
		}

		$VelocidadeConexao = $ParametroContrato[5];

		$sqlContratoOS	= "	select
								(Valor - ValorDesconto) as Valor
							from
								ContratoVigencia
							where
								DataInicio <= curdate() and
								(
									DataTermino is Null or
									DataTermino >= curdate()
								) and
								IdLoja = $local_IdLoja and
								IdContrato = $linOS[IdContrato]"; 
		$resContratoOS = @mysql_query($sqlContratoOS,$con);
		$linContratoOS = @mysql_fetch_array($resContratoOS);
		
		$ValorMensalidade = str_replace('.',',',$linContratoOS[Valor]);	
		
		if($ValorMensalidade != ''){
			$ValorMensalidade = $Moeda." ".$ValorMensalidade;
		}
	}	
	
	$sqlLogin ="select
					UsuarioGrupoUsuario.IdGrupoUsuario,
					GrupoUsuario.DescricaoGrupoUsuario,
					Usuario.Login,
					Pessoa.Nome,
					UsuarioGrupoUsuario.DataCriacao,
					UsuarioGrupoUsuario.LoginCriacao
				from
					UsuarioGrupoUsuario,
					GrupoUsuario,
					Usuario,
					Pessoa,
					OrdemServico
				where
					UsuarioGrupoUsuario.IdLoja = $local_IdLoja and
					UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and
					UsuarioGrupoUsuario.Login = Usuario.Login and
					OrdemServico.LoginAtendimento = UsuarioGrupoUsuario.Login and
					OrdemServico.IdOrdemServico = $local_IdOrdemServico and
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and
					Usuario.IdPessoa = Pessoa.IdPessoa and
					Pessoa.TipoUsuario = 1 and
					Usuario.IdStatus = 1";
	$resLogin = @mysql_query($sqlLogin,$con);
	$linLogin = @mysql_fetch_array($resLogin);
	
	$Atendente = $linLogin[Nome];

	$sql	= "	select
					Valor
				from
					ServicoValor
				where	
					IdLoja = $local_IdLoja and
					IdServico = 7"; 
	$resServicoInstalacao = @mysql_query($sql,$con);
	$linServicoInstalacao = @mysql_fetch_array($resServicoInstalacao);
	
	$TaxaInstalacao = str_replace('.',',',$linServicoInstalacao[Valor]);

	if($TaxaInstalacao != ''){
		$TaxaInstalacao = $Moeda." ".$TaxaInstalacao;
	}
?>
