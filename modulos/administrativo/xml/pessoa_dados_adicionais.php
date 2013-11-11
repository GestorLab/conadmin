<?
	$Modulo = 1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function getPessoaAdicionais()
	{
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
		$i 				= 0;
		$cont1			= 0;
		$Ativo			= 0;
		$Bloqueado		= 0;
		$Cancelado		= 0;
		$Migrado		= 0;		
		
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
		
		$sql = "SELECT
					IdPessoa
				FROM
					Pessoa
				WHERE
					IdPessoa = $IdPessoa";
		$res = mysql_query($sql,$con);
		if(mysql_num_fields($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
	
			$sql = "SELECT 
						MIN(DataInicio) DataCriacao
					FROM
						Contrato 
					WHERE 
						Contrato.IdLoja = $IdLoja and
						Contrato.IdPessoa = $IdPessoa";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			//Contratos Ativos,Bloqueados,Cancelados
			$sql1 = "SELECT
						IdStatus,
						COUNT(*) Quant
					FROM
						Contrato
					WHERE
						Contrato.IdLoja = $IdLoja AND 
						Contrato.IdPessoa = $IdPessoa
					GROUP BY
						IdStatus";
			$res1 = mysql_query($sql1,$con);
			while($lin1 = mysql_fetch_array($res1)){
				if(($lin1[IdStatus] > 0 && $lin1[IdStatus] <= 101) || ($lin1[IdStatus] > 103 && $lin1[IdStatus] <= 199)){
					$Cancelado += $lin1[Quant];
				}
				if($lin1[IdStatus] > 199 && $lin1[IdStatus] <= 299)
				{
					$Ativo += $lin1[Quant];
				}
				if($lin1[IdStatus] > 299 && $lin1[IdStatus] <= 399){
					$Bloqueado += $lin1[Quant];
				}
			}
			
			//Valores Maximo e Minino Recebidos
			$sql2 = "SELECT
						MAX(ContaReceberRecebimento.ValorRecebido) ValorMax,
						MIN(ContaReceberRecebimento.ValorRecebido) ValorMin,
						SUM(ContaReceberRecebimento.ValorRecebido) ValorTotal
					FROM
						ContaReceber,
						ContaReceberRecebimento
					WHERE
						ContaReceber.IdLoja = $IdLoja AND
						ContaReceber.IdPessoa = $IdPessoa AND
						ContaReceber.IdStatus = 2 AND
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber";
			$res2 = mysql_query($sql2,$con);
			$lin2 = mysql_fetch_array($res2);
			
			if($lin2[ValorMax] == 0 || $lin2[ValorMax] == ""){
				$lin2[ValorMax] = 0;
			}
			if($lin2[ValorMin] == 0 || $lin2[ValorMin] == ""){
				$lin2[ValorMin] = 0;
			}
			
			$dataHoje 			= date("Y-m-d");
			$ano 				= date("Y");
			
			if($ano - $dataDescrimentada[0] != ""){
				$diferenca = $ano - $dataDescrimentada[0];
				$dataDescrimentada[0] = $dataDescrimentada[0] - $diferenca;
			}
			$dataDescrimentada 	= explode("-",$dataHoje);
			$dataMes	  		= $dataDescrimentada[0]."-".($dataDescrimentada[1]-1)."-".$dataDescrimentada[2];
			$dataTrimestral		= $dataDescrimentada[0]."-0".($dataDescrimentada[1]-3)."-".$dataDescrimentada[2];
			if(($dataDescrimentada[1]-6) < 10){
				$dataSemestral	= $dataDescrimentada[0]."-0".str_replace("-","",($dataDescrimentada[1]-6))."-".$dataDescrimentada[2];
			}
			else{
				$dataSemestral	= $dataDescrimentada[0]."-".($dataDescrimentada[1]-6)."-".$dataDescrimentada[2];
				$dataAnual	= ($dataDescrimentada[0]-1)."-".($dataDescrimentada[1])."-".($dataDescrimentada[2]);
			}
			
			//Media Mensal dos conta Receber quitados
			$sql3 = "SELECT
						AVG(ContaReceberRecebimento.ValorRecebido) MediaContaReceberMensal
					FROM
						ContaReceber,
						ContaReceberRecebimento
					WHERE
						ContaReceber.IdLoja = $IdLoja AND
						ContaReceber.IdPessoa = $IdPessoa AND
						ContaReceber.IdStatus = 2 AND
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber AND
						SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) >= DATE_ADD('$dataHoje',INTERVAL -30 DAY) AND SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) <= '".$dataHoje."'";
			$res3 = mysql_query($sql3,$con);
			$lin3 = mysql_fetch_array($res3);
			
			
			
			//Media Trimestral dos conta receber quitados
			$sql4 = "SELECT
						AVG(ContaReceberRecebimento.ValorRecebido) MediaContaReceberTrimestral
					FROM
						ContaReceber,
						ContaReceberRecebimento
					WHERE
						ContaReceber.IdLoja = $IdLoja AND
						ContaReceber.IdPessoa = $IdPessoa AND
						ContaReceber.IdStatus = 2 AND
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber AND
						SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) >= DATE_ADD('$dataHoje',INTERVAL -3 MONTH) AND SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) <= '".$dataHoje."'";
			$res4 = mysql_query($sql4,$con);
			$lin4 = mysql_fetch_array($res4);
			
			//Media Semestral dos conta receber quitados
			$sql5 = "SELECT
						AVG(ContaReceberRecebimento.ValorRecebido) MediaContaReceberSemestral
					FROM
						ContaReceber,
						ContaReceberRecebimento
					WHERE
						ContaReceber.IdLoja = $IdLoja AND
						ContaReceber.IdPessoa = $IdPessoa AND
						ContaReceber.IdStatus = 2 AND
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber AND
						SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) >= DATE_ADD('$dataHoje',INTERVAL -6 MONTH) AND SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) <= '".$dataHoje."'";
			$res5 = mysql_query($sql5,$con);
			$lin5 = mysql_fetch_array($res5);
			
			//Media Anul dos conta receber aguardando quitados
			$sql6 = "SELECT
						AVG(ContaReceberRecebimento.ValorRecebido) MediaContaReceberAnual
					FROM
						ContaReceber,
						ContaReceberRecebimento
					WHERE
						ContaReceber.IdLoja = $IdLoja AND
						ContaReceber.IdPessoa = $IdPessoa AND
						ContaReceber.IdStatus = 2 AND
						ContaReceberRecebimento.IdLoja = ContaReceber.IdLoja AND
						ContaReceberRecebimento.IdContaReceber = ContaReceber.IdContaReceber AND
						SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) >= DATE_ADD('$dataHoje',INTERVAL -12 MONTH) AND SUBSTRING(ContaReceberRecebimento.DataRecebimento,1,10) <= '".$dataHoje."'";
			$res6 = mysql_query($sql6,$con);
			$lin6 = mysql_fetch_array($res6);
			
			//Valor  total recebido dos conta a receber
			$sql7 = "SELECT
							SUM(ContaReceberDados.ValorFinal) ValorTotalContaReceberAberto
						FROM
							ContaReceberDados
						WHERE
							ContaReceberDados.IdLoja = $IdLoja AND
							ContaReceberDados.IdPessoa = $IdPessoa AND
							ContaReceberDados.IdStatus = 1";
			$res7 = mysql_query($sql7,$con);
			$lin7 = mysql_fetch_array($res7);
			
			//Quantidade de ordem de servico em um mes
			$sql8 = "SELECT
						COUNT(OrdemServico.IdOrdemServico) QuantidadeMesalOrdemServico,
						AVG(OrdemServico.ValorTotal) MediaMesalOrdemServico,
						SUM(OrdemServico.ValorTotal) ValorTotalMesalOrdemServico
					FROM
						OrdemServico
					WHERE
						IdLoja = $IdLoja AND
						IdPessoa = $IdPessoa AND
						SUBSTRING(DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -30 DAY) AND SUBSTRING(DataCriacao,1,10) <= '".$dataHoje."'";
			
			$res8 = mysql_query($sql8,$con);
			$lin8 = mysql_fetch_array($res8);
			echo mysql_error();
			
			//Quantidade de ordem de servico em trimestral
			$sql9 = "SELECT
						COUNT(OrdemServico.IdOrdemServico) QuantidadeTrimestralOrdemServico,
						AVG(OrdemServico.ValorTotal) MediaTrimestralOrdemServico,
						SUM(OrdemServico.ValorTotal) ValorTotalTrimestralOrdemServico
					FROM
						OrdemServico
					WHERE
						IdLoja = $IdLoja AND
						IdPessoa = $IdPessoa AND
						SUBSTRING(DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -3 MONTH) AND SUBSTRING(DataCriacao,1,10) <= '".$dataHoje."'";
						
			$res9 = mysql_query($sql9,$con);
			$lin9 = mysql_fetch_array($res9);
			
			//Quantidade de ordem de serviço semestral
			$sql10 = "SELECT
						COUNT(OrdemServico.IdOrdemServico) QuantidadeSemestralOrdemServico,
						AVG(OrdemServico.ValorTotal) MediaSemestralOrdemServico,
						SUM(OrdemServico.ValorTotal) ValorTotalSemestralOrdemServico
					FROM
						OrdemServico
					WHERE
						IdLoja = $IdLoja AND
						IdPessoa = $IdPessoa AND
						SUBSTRING(DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -6 MONTH) AND SUBSTRING(DataCriacao,1,10) <= '".$dataHoje."'";
						
			$res10 = mysql_query($sql10,$con);
			$lin10 = mysql_fetch_array($res10);
			
			//Quantidade de ordem de servico anual
			$sql11 = "SELECT
						COUNT(OrdemServico.IdOrdemServico) QuantidadeAnualOrdemServico,
						AVG(OrdemServico.ValorTotal) MediaAnualOrdemServico,
						SUM(OrdemServico.ValorTotal) ValorTotalAnualOrdemServico
					FROM
						OrdemServico
					WHERE
						IdLoja = $IdLoja AND
						IdPessoa = $IdPessoa AND
						SUBSTRING(DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -12 MONTH) AND SUBSTRING(DataCriacao,1,10) <= '".$dataHoje."'";
						
			$res11 = mysql_query($sql11,$con);
			$lin11 = mysql_fetch_array($res11);
			
			//Quantidade de conta eventual Mensais
			$sql12 = "SELECT
							COUNT(ContaEventual.IdContaEventual) QuantidadeMensalContaEventual,
							AVG(ContaEventual.ValorTotal) MediaMensalContaEventual,
							SUM(ContaEventual.ValorTotal) ValorTotalMensalContaEventual
						FROM
							ContaEventual
						WHERE
							ContaEventual.IdLoja = $IdLoja AND
							ContaEventual.IdPessoa = $IdPessoa AND
							SUBSTRING(ContaEventual.DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -30 DAY) AND SUBSTRING(ContaEventual.DataCriacao,1,10) <= '".$dataHoje."'";
			$res12 = mysql_query($sql12,$con);
			$lin12 = mysql_fetch_array($res12);
			
			//Quantidade de conta eventual Trimestral
			$sql13	= "SELECT
							COUNT(ContaEventual.IdContaEventual) QuantidadeTrimestralContaEventual,
							AVG(ContaEventual.ValorTotal) MediaTrimestralContaEventual,
							SUM(ContaEventual.ValorTotal) ValorTotalTrimestralContaEventual
						FROM
							ContaEventual
						WHERE
							ContaEventual.IdLoja = $IdLoja AND
							ContaEventual.IdPessoa = $IdPessoa AND
							SUBSTRING(ContaEventual.DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -3 MONTH) AND SUBSTRING(ContaEventual.DataCriacao,1,10) <= '".$dataHoje."'";
			$res13 = mysql_query($sql13,$con);
			$lin13 = mysql_fetch_array($res13);
			
			//Quantidade de conta eventual Semestral
			$sql14	= "SELECT
							COUNT(ContaEventual.IdContaEventual) QuantidadeSemestralContaEventual,
							AVG(ContaEventual.ValorTotal) MediaSemestralContaEventual,
							SUM(ContaEventual.ValorTotal) ValorTotalSemestralContaEventual
						FROM
							ContaEventual
						WHERE
							ContaEventual.IdLoja = $IdLoja AND
							ContaEventual.IdPessoa = $IdPessoa AND
							SUBSTRING(ContaEventual.DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -6 MONTH) AND SUBSTRING(ContaEventual.DataCriacao,1,10) <= '".$dataHoje."'";
			$res14 = mysql_query($sql14,$con);
			$lin14 = mysql_fetch_array($res14);
			
			//Quantidade de conta eventual Anual
			$sql15	= "SELECT
							COUNT(ContaEventual.IdContaEventual) QuantidadeAnualContaEventual,
							AVG(ContaEventual.ValorTotal) MediaAnualContaEventual,
							SUM(ContaEventual.ValorTotal) ValorTotalAnualContaEventual
						FROM
							ContaEventual
						WHERE
							ContaEventual.IdLoja = $IdLoja AND
							ContaEventual.IdPessoa = $IdPessoa AND
							SUBSTRING(ContaEventual.DataCriacao,1,10) >= DATE_ADD('$dataHoje',INTERVAL -12 MONTH) AND SUBSTRING(ContaEventual.DataCriacao,1,10) <= '".$dataHoje."'";
			$res15 = mysql_query($sql15,$con);
			$lin15 = mysql_fetch_array($res15);
			
			//Quantidade contrato migrados
			
			$sql16 = "SELECT
							COUNT(ContratoStatus.IdStatus) ContratoMigrados
						FROM
							ContratoStatus,
							Contrato
						WHERE
							ContratoStatus.IdLoja = $IdLoja AND
							Contrato.IdLoja = ContratoStatus.IdLoja AND
							Contrato.IdPessoa = $IdPessoa AND
							ContratoStatus.IdContrato = Contrato.IdContrato AND
							ContratoStatus.IdStatus = 102";
			$res16 = mysql_query($sql16,$con);
			$lin16 = mysql_fetch_array($res16);
			
			//Valor  total recebido dos conta vencidos
			$sql17 = "SELECT
							SUM(ContaReceberDados.ValorFinal) ValorTotalContaReceberVencidos,
							AVG(ContaReceberDados.ValorFinal) ValorMediaContaReceberVencidos
						FROM
							ContaReceberDados
						WHERE
							ContaReceberDados.IdLoja = $IdLoja AND
							ContaReceberDados.IdPessoa = $IdPessoa AND
							ContaReceberDados.IdStatus != 2 AND
							ContaReceberDados.DataVencimento <= '".$dataHoje."'";
			$res17 = mysql_query($sql17,$con);
			$lin17 = mysql_fetch_array($res17);
			
			//Valor  total recebido dos conta vencidos
			$sql18 = "SELECT
							SUM(ContaReceberDados.ValorFinal) ValorTotalContaReceber
						FROM
							ContaReceberDados
						WHERE
							ContaReceberDados.IdLoja = $IdLoja AND
							ContaReceberDados.IdPessoa = $IdPessoa";
			$res18 = mysql_query($sql18,$con);
			$lin18 = mysql_fetch_array($res18);
			
			//Valor  total recebido dos conta vencidos
			$sql19 = "SELECT
						AVG(ContratoVigenciaAtiva.Valor) ValorMedioContrato,
						SUM(ContratoVigenciaAtiva.Valor) ValorTotalContrato
					FROM
						ContratoVigenciaAtiva,
						Contrato
					WHERE
						Contrato.IdLoja = $IdLoja AND
						ContratoVigenciaAtiva.IdLoja = Contrato.IdLoja AND
						ContratoVigenciaAtiva.IdContrato = Contrato.IdContrato AND
						Contrato.IdPessoa = $IdPessoa";
			$res19 = mysql_query($sql19,$con);
			$lin19 = mysql_fetch_array($res19);
			
			$dados	.=	"\n<PrimeiroContrato><![CDATA[".dataConv($lin[DataCriacao],"Y-m-d","d/m/Y")."]]></PrimeiroContrato>";
			$dados	.=	"\n<Ativo>$Ativo</Ativo>";
			$dados	.=	"\n<Bloqueado>$Bloqueado</Bloqueado>";
			$dados	.=	"\n<Cancelado>$Cancelado</Cancelado>";
			$dados	.=	"\n<Migrado>$lin16[ContratoMigrados]</Migrado>";
			$dados	.=	"\n<ValorMax><![CDATA[".getParametroSistema(5,1)." ".number_format($lin2[ValorMax],2,',','.')."]]></ValorMax>";
			$dados	.=	"\n<ValorMin><![CDATA[".getParametroSistema(5,1)." ".number_format($lin2[ValorMin],2,',','.')."]]></ValorMin>";
			$dados	.=	"\n<MediaContaReceberMensal><![CDATA[".getParametroSistema(5,1)." ".number_format($lin3[MediaContaReceberMensal],2,',','.')."]]></MediaContaReceberMensal>";
			$dados	.=	"\n<MediaContaReceberTrimestral><![CDATA[".getParametroSistema(5,1)." ".number_format($lin4[MediaContaReceberTrimestral],2,',','.')."]]></MediaContaReceberTrimestral>";
			$dados	.=	"\n<MediaContaReceberSemestral><![CDATA[".getParametroSistema(5,1)." ".number_format($lin5[MediaContaReceberSemestral],2,',','.')."]]></MediaContaReceberSemestral>";
			$dados	.=	"\n<MediaContaReceberAnual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin6[MediaContaReceberAnual],2,',','.')."]]></MediaContaReceberAnual>";
			$dados	.=	"\n<ValorTotalQuitado><![CDATA[".getParametroSistema(5,1)." ".number_format($lin2[ValorTotal],2,',','.')."]]></ValorTotalQuitado>";
			$dados	.=	"\n<ValorTotalContaReceberAberto><![CDATA[".getParametroSistema(5,1)." ".number_format($lin7[ValorTotalContaReceberAberto],2,',','.')."]]></ValorTotalContaReceberAberto>";
			$dados	.=	"\n<ValorTotalContaReceberVencidos><![CDATA[".getParametroSistema(5,1)." ".number_format($lin17[ValorTotalContaReceberVencidos],2,',','.')."]]></ValorTotalContaReceberVencidos>";
			$dados	.=	"\n<ValorMediaContaReceberVencidos><![CDATA[".getParametroSistema(5,1)." ".number_format($lin17[ValorMediaContaReceberVencidos],2,',','.')."]]></ValorMediaContaReceberVencidos>";
			$dados	.=	"\n<ValorTotalContaReceber><![CDATA[".getParametroSistema(5,1)." ".number_format($lin18[ValorTotalContaReceber],2,',','.')."]]></ValorTotalContaReceber>";
			$dados	.=	"\n<QuantidadeMesalOrdemServico>$lin8[QuantidadeMesalOrdemServico]</QuantidadeMesalOrdemServico>";
			$dados	.=	"\n<MediaMesalOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin8[MediaMesalOrdemServico],2,',','.')."]]></MediaMesalOrdemServico>";
			$dados	.=	"\n<ValorTotalMesalOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin8[ValorTotalMesalOrdemServico],2,',','.')."]]></ValorTotalMesalOrdemServico>";
			$dados	.=	"\n<QuantidadeTrimestralOrdemServico>$lin9[QuantidadeTrimestralOrdemServico]</QuantidadeTrimestralOrdemServico>";
			$dados	.=	"\n<MediaTrimestralOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin9[MediaTrimestralOrdemServico],2,',','.')."]]></MediaTrimestralOrdemServico>";
			$dados	.=	"\n<ValorTotalTrimestralOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin9[ValorTotalTrimestralOrdemServico],2,',','.')."]]></ValorTotalTrimestralOrdemServico>";
			$dados	.=	"\n<QuantidadeSemestralOrdemServico>$lin10[QuantidadeSemestralOrdemServico]</QuantidadeSemestralOrdemServico>";
			$dados	.=	"\n<MediaSemestralOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin10[MediaSemestralOrdemServico],2,',','.')."]]></MediaSemestralOrdemServico>";
			$dados	.=	"\n<ValorTotalSemestralOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin10[ValorTotalSemestralOrdemServico],2,',','.')."]]></ValorTotalSemestralOrdemServico>";
			$dados	.=	"\n<QuantidadeAnualOrdemServico>$lin11[QuantidadeAnualOrdemServico]</QuantidadeAnualOrdemServico>";
			$dados	.=	"\n<MediaAnualOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin11[MediaAnualOrdemServico],2,',','.')."]]></MediaAnualOrdemServico>";
			$dados	.=	"\n<ValorTotalAnualOrdemServico><![CDATA[".getParametroSistema(5,1)." ".number_format($lin11[ValorTotalAnualOrdemServico],2,',','.')."]]></ValorTotalAnualOrdemServico>";
			$dados	.=	"\n<QuantidadeMensalContaEventual>$lin12[QuantidadeMensalContaEventual]</QuantidadeMensalContaEventual>";
			$dados	.=	"\n<MediaMensalContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin12[MediaMensalContaEventual],2,',','.')."]]></MediaMensalContaEventual>";
			$dados	.=	"\n<ValorTotalMensalContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin12[ValorTotalMensalContaEventual],2,',','.')."]]></ValorTotalMensalContaEventual>";
			$dados	.=	"\n<QuantidadeTrimestralContaEventual>$lin13[QuantidadeTrimestralContaEventual]</QuantidadeTrimestralContaEventual>";
			$dados	.=	"\n<MediaTrimestralContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin13[MediaTrimestralContaEventual],2,',','.')."]]></MediaTrimestralContaEventual>";
			$dados	.=	"\n<ValorTotalTrimestralContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin13[ValorTotalTrimestralContaEventual],2,',','.')."]]></ValorTotalTrimestralContaEventual>";
			$dados	.=	"\n<QuantidadeSemestralContaEventual>$lin14[QuantidadeSemestralContaEventual]</QuantidadeSemestralContaEventual>";
			$dados	.=	"\n<MediaSemestralContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin14[MediaSemestralContaEventual],2,',','.')."]]></MediaSemestralContaEventual>";
			$dados	.=	"\n<ValorTotalSemestralContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin14[ValorTotalSemestralContaEventual],2,',','.')."]]></ValorTotalSemestralContaEventual>";
			$dados	.=	"\n<QuantidadeAnualContaEventual>$lin15[QuantidadeAnualContaEventual]</QuantidadeAnualContaEventual>";
			$dados	.=	"\n<MediaAnualContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin15[MediaAnualContaEventual],2,',','.')."]]></MediaAnualContaEventual>";
			$dados	.=	"\n<ValorTotalAnualContaEventual><![CDATA[".getParametroSistema(5,1)." ".number_format($lin15[ValorTotalAnualContaEventual],2,',','.')."]]></ValorTotalAnualContaEventual>";
			$dados	.=	"\n<ValorMedioContrato><![CDATA[".getParametroSistema(5,1)." ".number_format($lin19[ValorMedioContrato],2,',','.')."]]></ValorMedioContrato>";
			$dados	.=	"\n<ValorTotalContrato><![CDATA[".getParametroSistema(5,1)." ".number_format($lin19[ValorTotalContrato],2,',','.')."]]></ValorTotalContrato>";
			
			if($dados != ""){
				$dados	.=	"\n</reg>";
				return $dados;
			}
		}
	}
	echo getPessoaAdicionais();
?>