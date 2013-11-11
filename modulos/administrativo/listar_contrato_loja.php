<?
	$localModulo		= 1;
	$localOperacao		= 170;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$local_Login					= $_SESSION["Login"];
	$filtro_cancelado				= $_SESSION["filtro_contrato_cancelado"];	
	$filtro_soma					= $_SESSION["filtro_contrato_soma"];
	$filtro_ordem_servico_concluido	= $_SESSION["filtro_ordem_servico_concluido"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_loja					= $_POST['filtro_loja'];
	$filtro_descricao_loja			= url_string_xsl($_POST['filtro_descricao_loja'],'url',false);
	$filtro_descricao_servico		= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_campo					= $_POST['filtro_campo'];
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_id_servico				= $_POST['filtro_id_servico'];
	$filtro_local_cobranca			= $_POST['filtro_local_cobranca'];
	$filtro_usuario					= $_POST['filtro_usuario'];
	$filtro_estado					= $_POST['filtro_estado'];
	$filtro_cidade					= $_POST['filtro_cidade'];
	$filtro_bairro					= url_string_xsl($_POST['filtro_bairro'],'url',false);
	$filtro_endereco				= url_string_xsl($_POST['filtro_endereco'],'url',false);
	$filtro_limit					= $_POST['filtro_limit'];
	
	$filtro_url	 		= "";
	$filtro_sql  		= "";
	$filtro_sql_pe  	= "";
	$filtro_sql_os  	= "";
	$filtro_sql_co  	= "";
	$filtro_sql_co_cc	= "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url	.= "&Ordem=$filtro_ordem";
	
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem2 != "")
		$filtro_url	.= "&Ordem2=$filtro_ordem2";
	
	if($filtro_ordem_direcao2 != "")
		$filtro_url .= "&OrdemDirecao2=$filtro_ordem_direcao2";
	
	if($filtro_localTipoDado2 != "")
		$filtro_url .= "&TipoDado2=$filtro_localTipoDado2";
	
	if($filtro_loja != ""){
		$filtro_url .= "&Loja=".$filtro_loja;
		$filtro_sql .= " and Loja.IdLoja = $filtro_loja";
	}
	if($filtro_descricao_loja != ""){
		$filtro_url .= "&DescricaoLoja=".$filtro_descricao_loja;
		$filtro_sql .= " and Loja.DescricaoLoja like '%$filtro_descricao_loja%'";
	}
	
	if($filtro_descricao_servico != ""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql_co .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
		$filtro_sql_os .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_campo != ""){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&DataInicio=$filtro_data_inicio";
		$filtro_url .= "&DataFim=$filtro_data_fim";
		
		switch($filtro_campo){
			case "DataCadastro":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (substring(Contrato.DataCriacao,1,10) >= '$filtro_data_inicio')";
				}
				
				if($filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (substring(Contrato.DataCriacao,1,10) <= '$filtro_data_fim')";
				}
				break;
			case "DataInicioContrato":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataInicio >= '$filtro_data_inicio')";
				}
				
				if($filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataInicio <= '$filtro_data_fim')";
				}
				break;
			case "DataInicioCobranca":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataPrimeiraCobranca >= '$filtro_data_inicio')";
				}
				
				if($filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataPrimeiraCobranca <= '$filtro_data_fim')";
				}
				break;
			case "DataBase":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataBaseCalculo >= '$filtro_data_inicio')";
				}
				
				if($filtro_IdStatus != "" && $filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataBaseCalculo <= '$filtro_data_fim' or Contrato.DataBaseCalculo is null)";
				} else{
					if($filtro_data_fim != ""){
						$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
						
						$filtro_sql_co .= " and (Contrato.DataBaseCalculo <= '$filtro_data_fim')";
					}
				}
				break;
			case "DataTermino":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataTermino >= '$filtro_data_inicio')";
				}
				
				if($filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataTermino <= '$filtro_data_fim')";
				}
				break;
			case "DataUltimaCobranca":
				if($filtro_data_inicio != ""){
					$filtro_data_inicio = dataConv($filtro_data_inicio,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataUltimaCobranca >= '$filtro_data_inicio')";
				}
				
				if($filtro_data_fim != ""){
					$filtro_data_fim = dataConv($filtro_data_fim,"d/m/Y","Y-m-d");
					
					$filtro_sql_co .= " and (Contrato.DataUltimaCobranca <= '$filtro_data_fim')";
				}
				break;
		}		
	} else{
		$filtro_data_inicio = "";
		$filtro_data_fim = "";
	}
	
	if($filtro_status != ""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$aux = explode("G_",$filtro_status);
		
		if($aux[1] != ""){
			switch($aux[1]){
				case "1":
					$filtro_sql_co .= " and (Contrato.IdStatus >= 1 and Contrato.IdStatus < 199)";
					break;
				case "2":
					$filtro_sql_co .= " and (Contrato.IdStatus >= 200 and Contrato.IdStatus < 300)";
					break;
				case "3":
					$filtro_sql_co .= " and (Contrato.IdStatus >= 300 and Contrato.IdStatus < 400)";
					break;
			}
		} else{
			$filtro_sql_co .= " and Contrato.IdStatus = '$filtro_status'";
		}
	}
	
	if($filtro_id_servico != ""){
		$filtro_url .= "&IdServico=".$filtro_id_servico;
		$filtro_sql_co .= " and Servico.IdServico = $filtro_id_servico";
		$filtro_sql_os .= " and Servico.IdServico = $filtro_id_servico";
	}
	
	if($filtro_cancelado == 2 && $filtro_status == ""){
		$filtro_sql_co  .= " and Contrato.IdStatus > 199";
	}
	
	if($filtro_status != ''){//não todos
		if($filtro_status == 'G_1' || $filtro_status <= 199){//cancelados
			if($filtro_soma == 2){//não
				$filtro_sql_co_cc  .= " and Contrato.IdStatus <= 499";
			}else{//sim
				$filtro_sql_co_cc  .= " and Contrato.IdStatus <= 499";	
			}
		}
	}else{
		if($filtro_soma == 2){//não
			$filtro_sql_co_cc  .= " and Contrato.IdStatus > 199";
		}else{//sim
			$filtro_sql_co_cc  .= " and Contrato.IdStatus <= 499";
		}
	}
	
	if($filtro_ordem_servico_concluido == 2){
		$filtro_sql_os  .= " and (OrdemServico.IdStatus < 200 or OrdemServico.IdStatus > 299) ";
	}
	
	if($filtro_local_cobranca != ""){
		$filtro_url .= "&IdLocalCobranca=".$filtro_local_cobranca;
		$filtro_sql_co .= " and Contrato.IdLocalCobranca = $filtro_local_cobranca";
	}
	
	if($filtro_usuario != ""){
		$filtro_url .= "&Usuario=".$filtro_usuario;
		$filtro_sql_co .= " and Contrato.LoginCriacao = '$filtro_usuario'";
		$filtro_sql_os .= " and OrdemServico.LoginCriacao = '$filtro_usuario'";
		$filtro_sql_pe .= " and Pessoa.LoginCriacao = '$filtro_usuario'";
	}
	
	if($filtro_estado != ""){
		$filtro_url .= "&IdEstado=".$filtro_estado;
		$filtro_sql_co .= " and PessoaEndereco.IdEstado = '$filtro_estado'";
		$filtro_sql_os .= " and PessoaEndereco.IdEstado = '$filtro_estado'";
		$filtro_sql_pe .= " and PessoaEndereco.IdEstado = '$filtro_estado'";
	}
	
	if($filtro_cidade != ""){
		$filtro_url .= "&IdCidade=".$filtro_cidade;
		$filtro_sql_co .= " and PessoaEndereco.IdCidade = '$filtro_cidade'";
		$filtro_sql_os .= " and PessoaEndereco.IdCidade = '$filtro_cidade'";
		$filtro_sql_pe .= " and PessoaEndereco.IdCidade = '$filtro_cidade'";
	}
	
	if($filtro_bairro != ""){
		$filtro_url .= "&Bairro=".$filtro_bairro;
		$filtro_sql_co .= " and PessoaEndereco.Bairro = '$filtro_bairro'";
		$filtro_sql_os .= " and PessoaEndereco.Bairro = '$filtro_bairro'";
		$filtro_sql_pe .= " and PessoaEndereco.Bairro = '$filtro_bairro'";
	}
	
	if($filtro_endereco != ""){
		$filtro_url .= "&Endereco=".$filtro_endereco;
		$filtro_sql_co .= " and PessoaEndereco.Endereco = '$filtro_endereco'";
		$filtro_sql_os .= " and PessoaEndereco.Endereco = '$filtro_endereco'";
		$filtro_sql_pe .= " and PessoaEndereco.Endereco = '$filtro_endereco'";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_loja_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select
				Loja.IdLoja,
				Loja.DescricaoLoja,
				case ContratoTemp.QTDContrato is null when 1 then 0 else ContratoTemp.QTDContrato end QTDContrato,
				case ContratoQTDTemp.QTDPessoaContrato is null when 1 then 0 else ContratoQTDTemp.QTDPessoaContrato end QTDPessoaContrato,
				ContratoTemp.ValorContrato,
				case OrdemServicoTemp.QTDOrdemServico is null when 1 then 0 else OrdemServicoTemp.QTDOrdemServico end QTDOrdemServico,
				OrdemServicoTemp.ValorOrdemServico,
				case PessoaTemp.QTDPessoa is null when 1 then 0 else PessoaTemp.QTDPessoa end QTDPessoa
			from
				Loja left join (
					select 
						Loja.IdLoja,
						count(Contrato.IdContrato) QTDContrato,
						sum(ContratoVigenciaAtiva.Valor) ValorContrato
					from
						Loja,
						Contrato left join ContratoVigenciaAtiva on (
							Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and
							Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato
							$filtro_sql_co_cc
						),
						Servico,
						Pessoa,
						PessoaEndereco
					where
						Loja.IdLoja = Contrato.IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdPessoa = Pessoa.IdPessoa and
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco 
						$filtro_sql_co
					group by
						Loja.IdLoja
				) ContratoTemp on (
					Loja.IdLoja = ContratoTemp.IdLoja
				) left join (
					select 
						Temp.IdLoja,
						count(Temp.IdPessoa) QTDPessoaContrato 
					from 
						(
							select 
								Loja.IdLoja,
								Pessoa.IdPessoa
							from
								Loja,
								Contrato,
								Pessoa,
								PessoaEndereco,
								Servico
							where
								Loja.IdLoja = Contrato.IdLoja and
								Contrato.IdPessoa = Pessoa.IdPessoa and
								Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
								Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
								(
									Pessoa.IdLoja is null or
									Pessoa.IdLoja = Loja.IdLoja
								)
								$filtro_sql_co
							group by
								Loja.IdLoja, Pessoa.IdPessoa
						) Temp 
					group by 
						Temp.IdLoja
				) ContratoQTDTemp on (
					Loja.IdLoja = ContratoQTDTemp.IdLoja
				) left join (
					select
						Loja.IdLoja,
						count(OrdemServico.IdOrdemServico) QTDOrdemServico,
						(sum(OrdemServico.Valor) + sum(OrdemServico.ValorOutros)) ValorOrdemServico
					from
						Loja, 
						OrdemServico left join Servico on (
							OrdemServico.IdLoja = Servico.IdLoja and
							OrdemServico.IdServico = Servico.IdServico
						) left join (
							Pessoa, 
							PessoaEndereco
						) on (
							OrdemServico.IdPessoa = Pessoa.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco
						)
					where
						Loja.IdLoja = OrdemServico.IdLoja and
						OrdemServico.IdStatus > 99
						$filtro_sql_os
					group by
						Loja.IdLoja
				) OrdemServicoTemp on (
					Loja.IdLoja = OrdemServicoTemp.IdLoja
				) left join (
					select 
						Loja.IdLoja,
						count(Pessoa.IdPessoa) QTDPessoa
					from
						Loja,
						Pessoa,
						PessoaEndereco
					where
						(
							Loja.IdLoja = Pessoa.IdLoja or 
							Pessoa.IdLoja is null
						) and 
						Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
						Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco 
						$filtro_sql_pe
					group by
						Loja.IdLoja
				) PessoaTemp on (
					Loja.IdLoja = PessoaTemp.IdLoja
				)
			where 
				1
				$filtro_sql
			order by
				Loja.IdLoja
			$Limit";
	$res = mysql_query($sql,$con);
	
	while($lin = mysql_fetch_array($res)){
		$lin[ValorContratoTemp] = number_format($lin[ValorContrato],2,",","");
		$lin[ValorOrdemServicoTemp] = number_format($lin[ValorOrdemServico],2,",","");
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<DescricaoLoja><![CDATA[$lin[DescricaoLoja]]]></DescricaoLoja>";
		echo 	"<QTDPessoa>$lin[QTDPessoa]</QTDPessoa>";	
		echo 	"<QTDContrato>$lin[QTDContrato]</QTDContrato>";	
		echo 	"<QTDPessoaContrato>$lin[QTDPessoaContrato]</QTDPessoaContrato>";	
		echo 	"<ValorContrato>$lin[ValorContrato]</ValorContrato>";	
		echo 	"<ValorContratoTemp>$lin[ValorContratoTemp]</ValorContratoTemp>";	
		echo 	"<QTDOrdemServico>$lin[QTDOrdemServico]</QTDOrdemServico>";	
		echo 	"<ValorOrdemServico>$lin[ValorOrdemServico]</ValorOrdemServico>";	
		echo 	"<ValorOrdemServicoTemp>$lin[ValorOrdemServicoTemp]</ValorOrdemServicoTemp>";	
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>