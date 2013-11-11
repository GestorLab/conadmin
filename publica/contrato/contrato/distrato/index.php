<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";	

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	 	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Login		= $_SESSION["Login"];
	$local_IdContrato	= $_GET['IdContrato'];
	global $Servico_Contrato_Parametro;
	$sql="select	
				Pessoa.IdPessoa,
				Pessoa.RazaoSocial,
				Pessoa.CPF_CNPJ,
				Pessoa.RG_IE,
				Pessoa.InscricaoMunicipal,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				Pessoa.Site,
				Pessoa.NomeRepresentante,
				Pessoa.IdEnderecoDefault
		  from
				Pessoa,
				Loja
		  where
				Pessoa.IdPessoa = 1";
	$resEmpresa = mysql_query($sql,$con);
	$linEmpresa = mysql_fetch_array($resEmpresa);

	 $sqlPessoaEndereco = "select
						PessoaEndereco.IdPessoaEndereco,
						PessoaEndereco.Endereco,
						PessoaEndereco.Numero,
						PessoaEndereco.Bairro,
						PessoaEndereco.Complemento,
						PessoaEndereco.CEP,
						Cidade.NomeCidade,
						Estado.SiglaEstado,
						PessoaEndereco.NomeResponsavelEndereco
					from
						PessoaEndereco,
						Cidade,
						Estado
					where
						PessoaEndereco.IdPessoa = $linEmpresa[IdPessoa] and
						Estado.IdPais = PessoaEndereco.IdPais and
						Cidade.IdPais = PessoaEndereco.IdPais and
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdCidade	= PessoaEndereco.IdCidade and
						Cidade.IdEstado	= Estado.IdEstado";
	$resPessoaEndereco = mysql_query($sqlPessoaEndereco,$con);
	while($linEmpresaEndereco = mysql_fetch_array($resPessoaEndereco)){
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Endereco]					= $linEmpresaEndereco[Endereco];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Numero]					= $linEmpresaEndereco[Numero];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Bairro]					= $linEmpresaEndereco[Bairro];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Complemento]				= $linEmpresaEndereco[Complemento];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][CEP]						= $linEmpresaEndereco[CEP];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][NomeCidade]				= $linEmpresaEndereco[NomeCidade];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][SiglaEstado]				= $linEmpresaEndereco[SiglaEstado];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Telefone]					= $linEmpresaEndereco[Telefone];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][Celular]					= $linEmpresaEndereco[Celular];
		$PessoaEnderecoEmpresa[$linEmpresaEndereco[IdPessoaEndereco]][NomeResponsavelEndereco]	= $linEmpresaEndereco[NomeResponsavelEndereco];
	
	}

	 $sqlContrato = "select
						Pessoa.IdPessoa,
						Contrato.DataInicio,
						Pessoa.TipoPessoa,
						Pessoa.RazaoSocial,
						Pessoa.Nome,
						Pessoa.CPF_CNPJ,
						Pessoa.RG_IE,
						Pessoa.InscricaoMunicipal,
						Pessoa.Telefone1,
						Pessoa.Telefone2,
						Pessoa.Telefone3,
						Pessoa.Celular,
						Pessoa.Fax,
						Pessoa.Email,
						Servico.DescricaoServico,
						ContratoVigenciaAtiva.Valor,
						ContratoVigenciaAtiva.LimiteDesconto,						
						Contrato.DiaCobranca,
						Pessoa.NomeRepresentante,
						Pessoa.DataNascimento,
						Pessoa.CampoExtra1 Rep_Cargo,
						Pessoa.CampoExtra2 Rep_CPF,
						Pessoa.CampoExtra3 Rep_RG,
						Pessoa.EstadoCivil,
						Contrato.QtdMesesFidelidade,
						ContratoVigenciaAtiva.ValorDesconto,
						Pessoa.Obs,
						Contrato.IdPessoaEndereco,
						Contrato.IdPessoaEnderecoCobranca
					from
						Contrato,
						Pessoa,
						Servico,
						ContratoVigenciaAtiva
					where
						Contrato.IdLoja = $local_IdLoja and	
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and		
						Contrato.IdContrato = $local_IdContrato and
						Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato and
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Contrato.IdServico = Servico.IdServico";
	$resContrato = mysql_query($sqlContrato,$con);
	$linContrato = mysql_fetch_array($resContrato);
		
	$DadosCliente[Nome]=$linContrato[Nome];
	$sqlPessoaEndereco = "select
						PessoaEndereco.IdPessoaEndereco,
						PessoaEndereco.Endereco,
						PessoaEndereco.Numero,
						PessoaEndereco.Bairro,
						PessoaEndereco.Complemento,
						PessoaEndereco.CEP,
						Cidade.NomeCidade,
						Estado.SiglaEstado,
						PessoaEndereco.NomeResponsavelEndereco
					from
						PessoaEndereco,
						Cidade,
						Estado
					where
						PessoaEndereco.IdPessoa = $linContrato[IdPessoa] and
						Estado.IdPais = PessoaEndereco.IdPais and
						Cidade.IdPais = PessoaEndereco.IdPais and
						Estado.IdEstado = PessoaEndereco.IdEstado and
						Cidade.IdCidade	= PessoaEndereco.IdCidade and
						Cidade.IdEstado	= Estado.IdEstado";
	$resPessoaEndereco = mysql_query($sqlPessoaEndereco,$con);
	while($linPessoaEndereco = mysql_fetch_array($resPessoaEndereco)){
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Endereco]					= $linPessoaEndereco[Endereco];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Numero]					= $linPessoaEndereco[Numero];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Bairro]					= $linPessoaEndereco[Bairro];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Complemento]				= $linPessoaEndereco[Complemento];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][CEP]						= $linPessoaEndereco[CEP];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][NomeCidade]				= $linPessoaEndereco[NomeCidade];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][SiglaEstado]				= $linPessoaEndereco[SiglaEstado];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Telefone]					= $linPessoaEndereco[Telefone];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][Celular]					= $linPessoaEndereco[Celular];
		$PessoaEndereco[$linPessoaEndereco[IdPessoaEndereco]][NomeResponsavelEndereco]	= $linPessoaEndereco[NomeResponsavelEndereco];
	}

	$linContrato[IdContrato]	= str_pad($local_IdContrato, 6, "0", STR_PAD_LEFT);	

	$linContrato[AnoInicio] = substr($linContrato[DataInicio],0,4);
	$linContrato[MesInicio] = substr($linContrato[DataInicio],5,2);
	$linContrato[DiaInicio] = substr($linContrato[DataInicio],8,2);
	$linContrato[Telefone]	= $linContrato[Telefone1];
	$linContrato[CidadeData] = $PessoaEndereco[$linContrato[IdPessoaEndereco]][NomeCidade]." - ".$PessoaEndereco[$linContrato[IdPessoaEndereco]][SiglaEstado].", $linContrato[DiaInicio] de ".mes($linContrato[MesInicio])." de $linContrato[AnoInicio].";

	$SeparadoTelefone = ' / ';
	
	$ValorMensal  = $linContrato[Valor];
	$ValorMensalExtenso = extenso($linContrato[Valor],"real", "reais", "centavo", "centavos");

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

	$Moeda = getParametroSistema(5,1);	

	if($linContrato[TipoPessoa] == 1){
		$linContrato[NomeFantasia] = $linContrato[Nome];		
		$linContrato[Nome] = $linContrato[RazaoSocial];	
	}else{
		$linContrato[NomeFantasia]		= "";
		$linContrato[RazaoSocial]		= "";
		$linContrato[NomeRepresentante] = "";
	}

	$linContrato[DataNascimento]	= dataConv($linContrato[DataNascimento],'Y-m-d','d/m/Y');
	$linContrato[DataInicio]		= dataConv($linContrato[DataInicio],'Y-m-d','d/m/Y');

	$sqlParametroContrato = "select
								IdParametroServico,
								Valor
							from
								ContratoParametro
							where
								IdLoja = $local_IdLoja and
								IdContrato = $local_IdContrato";
	$resParametroContrato = mysql_query($sqlParametroContrato,$con);
	while($linParametroContrato = mysql_fetch_array($resParametroContrato)){
		$ParametroContrato[$linParametroContrato[IdParametroServico]] = $linParametroContrato[Valor];
	}
	
	$KitWireless		 = $ParametroContrato[7];
	$ValorKitComodato	 = $ParametroContrato[10];
	$Valor				 = $ParametroContrato[9];
	$Tecnologia			 = $ParametroContrato[8];
	$Velocidade			 = $ParametroContrato[4];
	$Velocidade			 = str_replace("k","",$Velocidade);
	$Velocidade			 = str_replace("Full","",$Velocidade);
	$ValorAdesao		 = extenso($Valor,"real", "reais", "centavo", "centavos");
	if($ValorAdesao == ''){
		$ValorAdesao = 'zero';
	}

	if($ValorTaxaAdesao == ''){
		$ValorTaxaAdesao = "0,00";
	}

	$linContrato[IdContrato]		= str_pad($local_IdContrato, 6, "0", STR_PAD_LEFT);
	$linContrato[DiaCobranca]		= str_pad($linContrato[DiaCobranca], 2, "0", STR_PAD_LEFT);

	$linContrato[Valor]				= number_format($linContrato[Valor], 2, '.', '');
	$linContrato[Valor]				= str_replace(".",",",$linContrato[Valor]);		

	$linContrato[ValorDesconto]		= number_format($linContrato[ValorDesconto], 2, '.', '');
	$linContrato[ValorDesconto]		= str_replace(".",",",$linContrato[ValorDesconto]);		

	$linContrato[ValorMensal]		= $linContrato[Valor];
	
	if($PessoaEndereco[$linContrato[IdPessoaEndereco]][Numero] != '') $PessoaEndereco[$linContrato[IdPessoaEndereco]][Endereco]		.= ', nº '.$PessoaEndereco[$linContrato[IdPessoaEndereco]][Numero];

/*
	if($PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Complemento] != '') $PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Complemento] = ', '.$PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Complemento];
	if($PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Bairro] != '') $PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Bairro]		= ', bairro '.$PessoaEndereco[$linContrato[IdPessoaEnderecoCobranca]][Bairro];
*/

	$linContrato[Email] = str_replace(";","<br>",$linContrato[Email]);
	
	/*if($_GET['ACC'] != ""){
		include("co_pj_com.php");
	}else{		
		if($linContrato[TipoPessoa] == 1){
			if($ValorKitComodato > 0){		
				include("co_pj_com.php");
			}else{
				include("co_pj.php");
			}
		}else{
			if($ValorKitComodato > 0){						
				include("co_pf_com.php");
			}else{
				include("co_pf.php");
			}
		}
	}*/
	
	$sql="SELECT 
		`ContratoParametro`.IdParametroServico,
		ContratoParametro.Valor,
		Contrato.DiaCobranca Vencimento
	FROM
		ContratoParametro,
		ServicoParametro, 
		Contrato
	WHERE 
		ContratoParametro.IdParametroServico = ServicoParametro.IdParametroServico 
		AND `ServicoParametro`.IdServico = ContratoParametro.IdServico
		AND ServicoParametro.IdLoja = $local_IdLoja
		AND ServicoParametro.IdLoja = ContratoParametro.IdLoja 
		AND ContratoParametro.IdContrato = $local_IdContrato
		AND ContratoParametro.IdContrato = Contrato.IdContrato";
	$res=mysql_query($sql,$con);
	$pos=0;
	while($ServicoComodato=mysql_fetch_array($res)){
		$Servico_Contrato_Parametro[$pos][IdParametroServico] 	= $ServicoComodato[IdParametroServico];
		$Servico_Contrato_Parametro[$pos][Valor] 				= $ServicoComodato[Valor];
		$Servico_Contrato_Parametro[$pos][Vencimento]			= $ServicoComodato[Vencimento];
		$pos++;
	}
	
	include('contrato_modelo_distrato.php');
		
	
?>