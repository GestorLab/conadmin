<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	
	$local_IdLoja			= $_SESSION["IdLoja"];
	$PeriodoInicial			= $_GET['PeriodoInicial'];
	$PeriodoFinal			= $_GET['PeriodoFinal'];	
	$LimiteIni 				= $_GET['LimiteIni'];	
	$LimiteFin 				= $_GET['LimiteFin'];	
	$TorrePainel			= $_GET['TorrePainel'];
	$Quantidade				= $_GET['Quantidade'];
	$IdStatusContrato		= $_GET['IdStatusContrato'];
	

	$QtdRegistros = $LimiteFin - $LimiteIni; 

	$where	= "";	
	$SqlAux = "";
	
	if($PeriodoInicial!=""){		
		$where .= " and substring(Contrato.DataInicio, 1, 10) >= '$PeriodoInicial'";
	}
	
	if($PeriodoFinal!=""){
		$where .= " and substring(Contrato.DataInicio, 1, 10) <= '$PeriodoFinal'";
	}

 	if($TorrePainel != ''){	
		$SqlAux  = ", ContratoParametro";
 		$where	.= " and ContratoParametro.IdLoja = $local_IdLoja 
					 and Contrato.IdContrato = ContratoParametro.IdContrato 
					 and ContratoParametro.IdParametroServico = 6 
		 			 and ContratoParametro.Valor like '%$TorrePainel%'";
 	}	
	if($IdStatusContrato!=''){		
		
		$aux	=	explode("G_",$IdStatusContrato);
		
		if($aux[1]!=""){
			switch($aux[1]){
				case '1':
					$where .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 100)";
					break;
				case '2':
					$where .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case '3':
					$where .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		}else{
			$where .= " and Contrato.IdStatus = '$IdStatusContrato'";
		}
	}	

	header("Content-type: application/vnd.ms-excel");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=ficha_cliente.xls");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	
	$sql  ="select
				distinct
				Contrato.IdContrato,
				Contrato.DataInicio,
				Contrato.DataTermino,
				Contrato.IdStatus,
				Pessoa.Nome,
				Pessoa.CPF_CNPJ,
				PessoaEndereco.Endereco,
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				Estado.NomeEstado,
				Cidade.NomeCidade,
				Servico.DescricaoServico,
				ContratoVigenciaAtiva.Valor					
			from
				Pessoa,
				PessoaEndereco,
				Contrato,
				Servico,
				Cidade,
				Estado,
				ContratoVigenciaAtiva			
				$SqlAux
			where	
				Contrato.IdLoja = $local_IdLoja and
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and	
				Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato and
				Pessoa.IdPessoa = Contrato.IdPessoa and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Contrato.IdServico = Servico.IdServico and
				PessoaEndereco.IdPessoaEndereco = Contrato.IdPessoaEndereco and
				Cidade.IdPais = PessoaEndereco.IdPais and
				Estado.IdPais = Cidade.IdPais and
				Estado.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade
				$where
			limit
				$LimiteIni,$QtdRegistros";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
	
		$sqlParametroContrato = "select
									IdParametroServico,
									Valor
								from
									ContratoParametro
								where
									IdLoja = $local_IdLoja and
									IdContrato = $lin[IdContrato]";
		$resParametroContrato = @mysql_query($sqlParametroContrato,$con);
		while($linParametroContrato = @mysql_fetch_array($resParametroContrato)){
			$ParametroContrato[$linParametroContrato[IdParametroServico]] = $linParametroContrato[Valor];
		}
		
		$Velocidade = $ParametroContrato[4];
		$Estacao 	= $ParametroContrato[6];
		
		$lin[DataInicio] 	= dataConv($lin[DataInicio],'Y-m-d','d/m/Y');
		$lin[DataTermino] 	= dataConv($lin[DataTermino],'Y-m-d','d/m/Y');
				
		$ContratoStatus =  getParametroSistema(69,$lin[IdStatus]);
		
		echo"<table border='1'>
				<tr>
					<td><b>Campo</b></td>
					<td><b>Descrição</b></td>
					<td><b>Tipo/Formato</b></td>
				</tr>
				<tr>
					<td>ASSINANTE</td>
					<td>$lin[Nome]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>LOGRADOURO</td>
					<td>$lin[Endereco]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>BAIRRO</td>
					<td>$lin[Bairro]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>CEP</td>
					<td>$lin[CEP]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>MUNICIPIO</td>
					<td>$lin[NomeCidade]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>ESTADO</td>
					<td>$lin[NomeEstado]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>CPF</td>
					<td>$lin[CPF_CNPJ]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>PLANO</td>
					<td>$lin[DescricaoServico]</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>VALOR</td>
					<td>$lin[Valor]</td>
					<td>Número</td>
				</tr>
				<tr>
					<td>VELOCIDADE</td>
					<td>$Velocidade</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>ATIVO OU INATIVO</td>
					<td>$ContratoStatus</td>
					<td>Texto</td>
				</tr>
				<tr>
					<td>DATA ATIVACAO</td>
					<td style='text-align: left'>$lin[DataInicio]</td>
					<td>dd/mm/aaaa</td>
				</tr>
				<tr>
					<td>DATA DESATIVACAO</td>
					<td style='text-align: left'>$lin[DataTermino]</td>
					<td>dd/mm/aaaa</td>
				</tr>
				<tr>
					<td>ESTACAO</td>
					<td style='text-align: left'>689288247</td>
					<td>Número</td>
				</tr>										
			 </table>
			 <br />	
			 ";	
	}			
?>
