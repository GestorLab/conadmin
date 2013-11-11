<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdContrato	= $_GET['IdContrato'];
	$logo				= "img/logo.gif";
	$Moeda				= getParametroSistema(5,1);
	$comodato			= "";
	
	if($local_IdContrato != ""){
		//Dados Contrato
		$sqlContrato = "select
							Pessoa.IdPessoa,
							Contrato.DataInicio,
							Contrato.DataCriacao,
							Contrato.IdPessoaEndereco,
							Contrato.QtdParcela,
							Contrato.DiaCobranca,
							Pessoa.TipoPessoa,
							Pessoa.RazaoSocial,
							Pessoa.Nome,
							Pessoa.CPF_CNPJ,
							Pessoa.RG_IE,
							Pessoa.InscricaoMunicipal,
							PessoaEndereco.Endereco,
							PessoaEndereco.Numero,
							PessoaEndereco.Bairro,
							PessoaEndereco.Complemento,
							PessoaEndereco.CEP,
							Cidade.NomeCidade,
							Estado.SiglaEstado,
							Estado.NomeEstado,
							Pessoa.Telefone1,
							Pessoa.Telefone2,
							Pessoa.Telefone3,
							Pessoa.Celular,
							Pessoa.Fax,
							Pessoa.Email,
							Servico.IdServico,
							Servico.DescricaoServico,
							ContratoVigenciaAtiva.Valor,
							Contrato.DiaCobranca,
							Pessoa.DataNascimento,
							Pessoa.NomeRepresentante,
							Pessoa.CampoExtra1 Rep_CPF,
							Pessoa.CampoExtra2 Rep_RG,
							Pessoa.CampoExtra3 Rep_Email,
							Pessoa.CampoExtra4 Rep_Telefone,
							Pessoa.EstadoCivil,
							Contrato.QtdMesesFidelidade,
							ContratoVigenciaAtiva.ValorDesconto,
							Pessoa.Obs,
							LocalCobranca.PercentualMulta,
							LocalCobranca.PercentualJurosDiarios
						from
							Contrato,
							Pessoa,
							PessoaEndereco,
							Cidade,
							Estado,
							Servico,
							ContratoVigenciaAtiva,
							LocalCobranca
						where
							Contrato.IdLoja 			= $local_IdLoja and	
							Contrato.IdLoja				= Servico.IdLoja and
							Contrato.IdLoja 			= ContratoVigenciaAtiva.IdLoja and		
							Pessoa.IdPessoa 			= PessoaEndereco.IdPessoa and
							Pessoa.IdEnderecoDefault	= PessoaEndereco.IdPessoaEndereco and
							Contrato.IdContrato 		= $local_IdContrato and
							Contrato.IdContrato	 		= ContratoVigenciaAtiva.IdContrato and
							Contrato.IdPessoa 			= Pessoa.IdPessoa and
							Estado.IdPais 				= PessoaEndereco.IdPais and
							Cidade.IdPais 				= PessoaEndereco.IdPais and
							Estado.IdEstado				= PessoaEndereco.IdEstado and
							Cidade.IdCidade				= PessoaEndereco.IdCidade and
							Cidade.IdEstado				= Estado.IdEstado and
							Contrato.IdServico			= Servico.IdServico and
							Contrato.IdLoja				= LocalCobranca.IdLoja and
							Contrato.IdLocalCobranca	= LocalCobranca.IdLocalCobranca";
		$resContrato = mysql_query($sqlContrato,$con);
		$linContrato = mysql_fetch_array($resContrato);

		if($linContrato[TipoPessoa] == 1){
			$linContrato[CNPJ]	= $linContrato[CPF_CNPJ];
			$linContrato[IE]	= $linContrato[RG_IE];
		}else{
			$linContrato[CPF]	= $linContrato[CPF_CNPJ];
			$linContrato[RG]	= $linContrato[RG_IE];
		}
		
			
		if($linContrato[TipoPessoa] == 1){
			$linContrato[NomeRazaoSocial] = $linContrato[RazaoSocial];
		} else{
			$linContrato[NomeRazaoSocial] = $linContrato[Nome];
		}
		
		if($linContrato[PercentualMulta] != null){
			$linContrato[PercentualMulta] = number_format($linContrato[PercentualMulta], 3, ",", "")."%";
		}
		
		if($linContrato[PercentualJurosDiarios] != null){
			$linContrato[PercentualJurosDiarios] = number_format($linContrato[PercentualJurosDiarios], 3, ",", "")."%";
		}
		
		$SeparadoTelefone = ' / ';
		
		$linContrato[Telefone]	= $linContrato[Telefone1];
		
		if($linContrato[Telefone2] != ''){
			if($linContrato[Telefone] != ''){
				$linContrato[Telefone]	.= $SeparadoTelefone;
			}
			$linContrato[Telefone]	.= $linContrato[Telefone2];
		}

		if($linContrato[Telefone3] != ''){
			if($linContrato[Telefone] != ''){
				$linContrato[Telefone]	.= $SeparadoTelefone;
			}
			$linContrato[Telefone]	.= $linContrato[Telefone3];
		}

		if($linContrato[Telefone3] != ''){
			if($linContrato[Telefone] != ''){
				$linContrato[Telefone]	.= $SeparadoTelefone;
			}
			$linContrato[Telefone]	.= $linContrato[Telefone3];
		}

		if($linContrato[Fax] != ''){
			if($linContrato[Telefone] != ''){
				$linContrato[Telefone]	.= $SeparadoTelefone;
			}
			$linContrato[Telefone]	.= $linContrato[Fax];
		}
		
		if($linContrato[Celular] != ''){
			if($linContrato[Telefone] != ''){
				$linContrato[Telefone]	.= $SeparadoTelefone;
			}
			$linContrato[Telefone]	.= $linContrato[Celular];
		}
		
		$DataInicio = explode("-",$linContrato[DataInicio]);
		
		$linContrato[DiaInicio]	= $DataInicio[2];
		$linContrato[MesInicio]	= $DataInicio[1];
		$linContrato[AnoInicio]	= $DataInicio[0];
		
		$linContrato[CidadeData] = "$linContrato[NomeCidade]  -  ($linContrato[SiglaEstado]),  $linContrato[DiaInicio]  de  ".mes($linContrato[MesInicio])."  de  $linContrato[AnoInicio].";
		
		//Valores
		$sqlFinanceiro = "SELECT 
								Valor,
								MultaFidelidade 
							FROM
								ServicoValor 
							WHERE 
								IdServico = $linContrato[IdServico]
								AND IdLoja = $local_IdLoja ";
		$resFinanceiro = mysql_query($sqlFinanceiro, $con);
		$linFinanceiro = mysql_fetch_array($resFinanceiro);
	
		$linContrato[DataCriacao] = substr($linContrato[DataCriacao],0,4);
	
		// Endereço Instalação
		$sqlEndereco = "SELECT 
							PessoaEndereco.Endereco,
							PessoaEndereco.Endereco EnderecoSimples,
							PessoaEndereco.CEP,
							PessoaEndereco.IdPais,
							PessoaEndereco.IdEstado,
							PessoaEndereco.IdCidade,
							PessoaEndereco.Endereco,
							PessoaEndereco.Numero,
							PessoaEndereco.Complemento,
							PessoaEndereco.Bairro,
							Cidade.NomeCidade,
							Estado.SiglaEstado
						FROM
							PessoaEndereco,
							Cidade,
							Estado
						WHERE 
							IdPessoa = $linContrato[IdPessoa] 
							AND IdPessoaEndereco	= $linContrato[IdPessoaEndereco] 
							AND Cidade.IdCidade		= PessoaEndereco.IdCidade
							AND Estado.IdEstado		= PessoaEndereco.IdEstado";
		$resEndereco = mysql_query($sqlEndereco, $con);
		$linEndereco = mysql_fetch_array($resEndereco);
		
		
		//Informações Comodato
		$sqlComodato = "SELECT 
							(SELECT 
								Valor 
							FROM
								ContratoParametro 
							WHERE 
								IdLoja =  $local_IdLoja 
								AND IdContrato = $local_IdContrato 
								AND IdParametroServico = 5) Equipamento,
								
							(SELECT 
								Valor 
							FROM
								ContratoParametro 
							WHERE 
								IdLoja =  $local_IdLoja 
								AND IdContrato = $local_IdContrato 
								AND IdParametroServico = 10) TaxaAdesao,
							
							(SELECT 
								Valor 
							FROM
								ContratoParametro 
							WHERE 
								IdLoja =  $local_IdLoja 
								AND IdContrato = $local_IdContrato 
								AND IdParametroServico = 11) Desconto,
							
							(SELECT 
								Valor 
							FROM
								ContratoParametro 
							WHERE 
								IdLoja =  $local_IdLoja 
								AND IdContrato = $local_IdContrato 
								AND IdParametroServico = 12) Velocidade";
		$resComodato = mysql_query($sqlComodato,$con);
		$linComodato = mysql_fetch_array($resComodato);
		
		//Calculos
		if($linComodato[Desconto] >= 100){			
			$linComodato[Descontos]	= '0';
		}else{
			$linComodato[Descontos]	= "0.".$linComodato[Desconto];			
		}
		$linComodato[Total] 	= $linComodato[TaxaAdesao]*$linComodato[Descontos]; 
		

		//Volta a mascara de valor
		$linFinanceiro[Valor] 			 = $Moeda." ".number_format($linFinanceiro[Valor],2,',','.');	
		$linFinanceiro[Valor] 			 = str_replace('.',',',$linFinanceiro[Valor]);
		
		$linComodato[Descontos] 		 = number_format($linComodato[Descontos],2,',','.');	
		$linComodato[Descontos] 		 = str_replace('.',',',$linComodato[Descontos]);		
		
		$linComodato[Total] 			 = $Moeda." ".number_format($linComodato[Total],2,',','.');	
		$linComodato[Total] 			 = str_replace('.',',',$linComodato[Total]);
		
		$linComodato[TaxaAdesao] 		 = $Moeda." ".@number_format($linComodato[TaxaAdesao],2,',','.');	
		$linComodato[TaxaAdesao] 		 = str_replace('.',',',$linComodato[TaxaAdesao]);
		
		
		include ('contrato_modelo_distrato.php');
	}
?>