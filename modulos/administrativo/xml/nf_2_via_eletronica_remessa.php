<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_NotaFiscal2ViaEletronicaRemessa(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_SESSION["IdLoja"];
		$IdNotaFiscalLayout	 	= $_GET['IdNotaFiscalLayout'];
		$MesReferencia			= $_GET['MesReferencia'];		
		$Status					= $_GET['Status'];		

		$where			= "";
				
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdNotaFiscalLayout != ''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout=$IdNotaFiscalLayout";	
		}
		if($MesReferencia !=''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.MesReferencia like '$MesReferencia'";	
		}
		if($Status !=''){	
			$where .= " and NotaFiscal2ViaEletronicaArquivo.Status like '$Status'";	
		}	
		
		$sql	=	"select					
						NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout,
						NotaFiscal2ViaEletronicaArquivo.MesReferencia,
						NotaFiscal2ViaEletronicaArquivo.Status,
						NotaFiscal2ViaEletronicaArquivo.NomeArquivoMestre,
						NotaFiscal2ViaEletronicaArquivo.NomeArquivoItem,
						NotaFiscal2ViaEletronicaArquivo.NomeArquivoDestinatario,
						NotaFiscal2ViaEletronicaArquivo.IE,
						NotaFiscal2ViaEletronicaArquivo.CNPJ,
						NotaFiscal2ViaEletronicaArquivo.RazaoSocial,
						NotaFiscal2ViaEletronicaArquivo.Endereco,
						NotaFiscal2ViaEletronicaArquivo.CEP,
						NotaFiscal2ViaEletronicaArquivo.Bairro,
						NotaFiscal2ViaEletronicaArquivo.NomeCidade,
						NotaFiscal2ViaEletronicaArquivo.SiglaEstado,
						NotaFiscal2ViaEletronicaArquivo.QtdNF,
						NotaFiscal2ViaEletronicaArquivo.QtdNFCancelado,
						NotaFiscal2ViaEletronicaArquivo.StatusArquivoMestre,
						NotaFiscal2ViaEletronicaArquivo.DataPrimeiraNF,
						NotaFiscal2ViaEletronicaArquivo.DataUltimaNF,
						NotaFiscal2ViaEletronicaArquivo.NumeroPrimeiraNF,
						NotaFiscal2ViaEletronicaArquivo.NumeroUltimaNF,
						NotaFiscal2ViaEletronicaArquivo.ValorTotal,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalCancelado,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalBaseCalculo,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalBaseCalculoCancelado,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalICMS,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalIsentoNaoTributado,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalOutros,
						NotaFiscal2ViaEletronicaArquivo.CodigoAutenticacaoDigitalArquivoMestre,
						NotaFiscal2ViaEletronicaArquivo.QtdItem,
						NotaFiscal2ViaEletronicaArquivo.QtdItemCancelado,
						NotaFiscal2ViaEletronicaArquivo.StatusArquivoItem,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItem,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItemDesconto,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalAcrecimoDespesas,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItemBaseCalculo,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItemICMS,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItemIsentoNaoTributado,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalItemOutros,
						NotaFiscal2ViaEletronicaArquivo.CodigoAutenticacaoDigitalArquivoItem,
						NotaFiscal2ViaEletronicaArquivo.QtdRegistroDestinatario,
						NotaFiscal2ViaEletronicaArquivo.StatusArquivoDestinatario,
						NotaFiscal2ViaEletronicaArquivo.CodigoAutenticacaoDigitalArquivoDestinatario,
						NotaFiscal2ViaEletronicaArquivo.NomeResponsavel,
						NotaFiscal2ViaEletronicaArquivo.CargoResponsavel,
						NotaFiscal2ViaEletronicaArquivo.TelefoneResponsavel,
						NotaFiscal2ViaEletronicaArquivo.EmailResponsavel,
						substr(DataCriacao,1,10) DataResponsavel,
						NotaFiscal2ViaEletronicaArquivo.IdStatus,
						NotaFiscal2ViaEletronicaArquivo.LogProcessamento,
						NotaFiscal2ViaEletronicaArquivo.LoginProcessamento,
						NotaFiscal2ViaEletronicaArquivo.DataProcessamento,
						NotaFiscal2ViaEletronicaArquivo.LoginConfirmacao,
						NotaFiscal2ViaEletronicaArquivo.DataConfirmacao,
						NotaFiscal2ViaEletronicaArquivo.DataCriacao, 
						NotaFiscal2ViaEletronicaArquivo.LoginCriacao
					from 
						NotaFiscal2ViaEletronicaArquivo
					where
						NotaFiscal2ViaEletronicaArquivo.IdLoja = $local_IdLoja 
						$where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
			
		while($lin	=	@mysql_fetch_array($res)){

			# Para ficar como padrão no validador das notas fiscais, o valor total deve ser substraido do cancelado
			$lin[ValorTotal]				-= $lin[ValorTotalCancelado]; 
			$lin[ValorTotalItem]			-= $lin[ValorTotalCancelado];
			$lin[ValorTotalBaseCalculo]		-= $lin[ValorTotalBaseCalculoCancelado];
			$lin[ValorTotalItemBaseCalculo]	-= $lin[ValorTotalBaseCalculoCancelado];

			$Color	 = getCodigoInterno(33,$lin[IdStatus]);
			$Status  = getParametroSistema(137,$lin[IdStatus]);
			
			$EnderecoArquivoMestre			= '';
			$EnderecoArquivoItem			= '';
			$EnderecoArquivoDestinatario	= '';
			
			$MesReferencia = dataConv($lin[MesReferencia],'m/Y','Y-m');
			
			if($lin[IdStatus] > 2 && $lin[IdStatus] < 5){
				if($lin[MesReferencia] != ''){
					if($lin[Status] == 'N'){
						$Endereco = "/modulos/administrativo/remessa/nota_fiscal/$local_IdLoja/$MesReferencia/N/";
					}else{
						$Endereco = "/modulos/administrativo/remessa/nota_fiscal/$local_IdLoja/$MesReferencia/S/";
					}
				} else{
					$Endereco = '';
				}
				
				if($lin[NomeArquivoMestre] != ''){
					$EnderecoArquivoMestre			= $Endereco.$lin[NomeArquivoMestre];
				}
				
				if($lin[NomeArquivoItem] != ''){
					$EnderecoArquivoItem			= $Endereco.$lin[NomeArquivoItem];
				}
				
				if($lin[NomeArquivoDestinatario] != ''){
					$EnderecoArquivoDestinatario	= $Endereco.$lin[NomeArquivoDestinatario];
				}
			}
						
			$DataResponsavel = dataConv($lin[DataResponsavel],'Y-m-d','d/m/Y');
			
			$dados	.=	"\n<IdNotaFiscalLayout>$lin[IdNotaFiscalLayout]</IdNotaFiscalLayout>";			
			$dados	.=	"\n<MesReferencia><![CDATA[$lin[MesReferencia]]]></MesReferencia>";
			$dados	.=	"\n<StatusMestre><![CDATA[$lin[Status]]]></StatusMestre>";
			$dados	.=	"\n<NomeArquivoMestre><![CDATA[$lin[NomeArquivoMestre]]]></NomeArquivoMestre>";
			$dados	.=	"\n<EnderecoArquivoMestre><![CDATA[$EnderecoArquivoMestre]]></EnderecoArquivoMestre>";		
			$dados	.=	"\n<NomeArquivoItem><![CDATA[$lin[NomeArquivoItem]]]></NomeArquivoItem>";
			$dados	.=	"\n<EnderecoArquivoItem><![CDATA[$EnderecoArquivoItem]]></EnderecoArquivoItem>";
			$dados	.=	"\n<NomeArquivoDestinatario><![CDATA[$lin[NomeArquivoDestinatario]]]></NomeArquivoDestinatario>";
			$dados	.=	"\n<EnderecoArquivoDestinatario><![CDATA[$EnderecoArquivoDestinatario]]></EnderecoArquivoDestinatario>";			
			$dados	.=	"\n<IE><![CDATA[$lin[IE]]]></IE>";			
			$dados	.=	"\n<CNPJ><![CDATA[$lin[CNPJ]]]></CNPJ>";			
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";			
			$dados	.=	"\n<Endereco><![CDATA[$lin[Endereco]]]></Endereco>";			
			$dados	.=	"\n<CEP><![CDATA[$lin[CEP]]]></CEP>";			
			$dados	.=	"\n<Bairro><![CDATA[$lin[Bairro]]]></Bairro>";						
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";			
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";			
			$dados	.=	"\n<QtdNF><![CDATA[$lin[QtdNF]]]></QtdNF>";			
			$dados	.=	"\n<QtdNFCancelado><![CDATA[$lin[QtdNFCancelado]]]></QtdNFCancelado>";			
			$dados	.=	"\n<StatusArquivoMestre><![CDATA[$lin[StatusArquivoMestre]]]></StatusArquivoMestre>";			
			$dados	.=	"\n<DataPrimeiraNF><![CDATA[$lin[DataPrimeiraNF]]]></DataPrimeiraNF>";			
			$dados	.=	"\n<DataUltimaNF><![CDATA[$lin[DataUltimaNF]]]></DataUltimaNF>";			
			$dados	.=	"\n<NumeroPrimeiraNF><![CDATA[$lin[NumeroPrimeiraNF]]]></NumeroPrimeiraNF>";			
			$dados	.=	"\n<NumeroUltimaNF><![CDATA[$lin[NumeroUltimaNF]]]></NumeroUltimaNF>";			
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";			
			$dados	.=	"\n<ValorTotalCancelado><![CDATA[$lin[ValorTotalCancelado]]]></ValorTotalCancelado>";			
			$dados	.=	"\n<ValorTotalBaseCalculo><![CDATA[$lin[ValorTotalBaseCalculo]]]></ValorTotalBaseCalculo>";			
			$dados	.=	"\n<ValorTotalICMS><![CDATA[$lin[ValorTotalICMS]]]></ValorTotalICMS>";			
			$dados	.=	"\n<ValorTotalIsentoNaoTributado><![CDATA[$lin[ValorTotalIsentoNaoTributado]]]></ValorTotalIsentoNaoTributado>";			
			$dados	.=	"\n<ValorTotalOutros><![CDATA[$lin[ValorTotalOutros]]]></ValorTotalOutros>";			
			$dados	.=	"\n<CodigoAutenticacaoDigitalArquivoMestre><![CDATA[$lin[CodigoAutenticacaoDigitalArquivoMestre]]]></CodigoAutenticacaoDigitalArquivoMestre>";			
			$dados	.=	"\n<QtdItem><![CDATA[$lin[QtdItem]]]></QtdItem>";			
			$dados	.=	"\n<QtdItemCancelado><![CDATA[$lin[QtdItemCancelado]]]></QtdItemCancelado>";			
			$dados	.=	"\n<StatusArquivoItem><![CDATA[$lin[StatusArquivoItem]]]></StatusArquivoItem>";			
			$dados	.=	"\n<ValorTotalItem><![CDATA[$lin[ValorTotalItem]]]></ValorTotalItem>";			
			$dados	.=	"\n<ValorTotalItemDesconto><![CDATA[$lin[ValorTotalItemDesconto]]]></ValorTotalItemDesconto>";			
			$dados	.=	"\n<ValorTotalAcrecimoDespesas><![CDATA[$lin[ValorTotalAcrecimoDespesas]]]></ValorTotalAcrecimoDespesas>";			
			$dados	.=	"\n<ValorTotalItemBaseCalculo><![CDATA[$lin[ValorTotalItemBaseCalculo]]]></ValorTotalItemBaseCalculo>";			
			$dados	.=	"\n<ValorTotalItemICMS><![CDATA[$lin[ValorTotalItemICMS]]]></ValorTotalItemICMS>";						
			$dados	.=	"\n<ValorTotalItemIsentoNaoTributado><![CDATA[$lin[ValorTotalItemIsentoNaoTributado]]]></ValorTotalItemIsentoNaoTributado>";			
			$dados	.=	"\n<ValorTotalItemOutros><![CDATA[$lin[ValorTotalItemOutros]]]></ValorTotalItemOutros>";			
			$dados	.=	"\n<CodigoAutenticacaoDigitalArquivoItem><![CDATA[$lin[CodigoAutenticacaoDigitalArquivoItem]]]></CodigoAutenticacaoDigitalArquivoItem>";			
			$dados	.=	"\n<QtdRegistroDestinatario><![CDATA[$lin[QtdRegistroDestinatario]]]></QtdRegistroDestinatario>";			
			$dados	.=	"\n<StatusArquivoDestinatario><![CDATA[$lin[StatusArquivoDestinatario]]]></StatusArquivoDestinatario>";			
			$dados	.=	"\n<CodigoAutenticacaoDigitalArquivoDestinatario><![CDATA[$lin[CodigoAutenticacaoDigitalArquivoDestinatario]]]></CodigoAutenticacaoDigitalArquivoDestinatario>";			
			$dados	.=	"\n<NomeResponsavel><![CDATA[$lin[NomeResponsavel]]]></NomeResponsavel>";			
			$dados	.=	"\n<CargoResponsavel><![CDATA[$lin[CargoResponsavel]]]></CargoResponsavel>";			
			$dados	.=	"\n<TelefoneResponsavel><![CDATA[$lin[TelefoneResponsavel]]]></TelefoneResponsavel>";			
			$dados	.=	"\n<EmailResponsavel><![CDATA[$lin[EmailResponsavel]]]></EmailResponsavel>";			
			$dados	.=	"\n<DataResponsavel><![CDATA[$DataResponsavel]]></DataResponsavel>";			
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$Status]]></Status>";
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
			$dados	.=	"\n<LogProcessamento><![CDATA[$lin[LogProcessamento]]]></LogProcessamento>";
			$dados	.=	"\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";			
			$dados	.=	"\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";			
			$dados	.=	"\n<LoginConfirmacao><![CDATA[$lin[LoginConfirmacao]]]></LoginConfirmacao>";			
			$dados	.=	"\n<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";	
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";					
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_NotaFiscal2ViaEletronicaRemessa();
?>