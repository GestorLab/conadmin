function busca_nf_2_via_eletronica_remessa(IdNotaFiscalLayout, MesReferencia, Status , Erro, Local){
	
	if(IdNotaFiscalLayout == ''){
		IdNotaFiscalLayout = 0;
	}
	if(MesReferencia == ''){
		MesReferencia = 0;
	}	
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
	}
	var nameNode, nameTextNode, url;
	
	var xmlhttp   = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
    	xmlhttp = new XMLHttpRequest();
        if(xmlhttp.overrideMimeType){
        	xmlhttp.overrideMimeType('text/xml');
		}
	}else if (window.ActiveXObject){ // IE
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }    
    
   	url = "../administrativo/xml/nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout="+IdNotaFiscalLayout+"&MesReferencia="+MesReferencia+"&Status="+Status;

	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){								
				if(Erro != false){
					document.formulario.Erro.value = 0;
					verificaErro();
				}			

				if(xmlhttp.responseText == 'false'){
					
					switch(Local){
						case 'NotaFiscal2ViaEletronicaRemessa':
						//	document.formulario.IdNotaFiscalLayout.value 							= '';
						//	document.formulario.MesReferencia.value 								= '';
						//	document.formulario.IdStatusArquivoMestre.value						 	= '';
							document.formulario.NomeArquivoMestre.value						 		= '';
							document.formulario.NomeArquivoItem.value						 		= '';
							document.formulario.NomeArquivoDestinatario.value				 		= '';
							document.formulario.InscricaoEstadual.value							 	= '';
							document.formulario.CNPJ.value			 								= '';
							document.formulario.RazaoSocial.value								 	= '';
							document.formulario.Endereco.value									 	= '';
							document.formulario.CEP.value			 								= '';
							document.formulario.Bairro.value									 	= '';
							document.formulario.NomeCidade.value								 	= '';
							document.formulario.SiglaEstado.value								 	= '';
							document.formulario.QtdNF.value			 								= '';
							document.formulario.QtdNFCancelado.value							 	= '';
							document.formulario.StatusArquivoMestre.value						 	= '';
							document.formulario.DataPrimeiraNF.value							 	= '';
							document.formulario.DataUltimaNF.value			 						= '';
							document.formulario.NumeroPrimeiraNF.value							 	= '';
							document.formulario.NumeroUltimaNF.value						 		= '';
							document.formulario.ValorTotal.value							 		= '';
							document.formulario.ValorTotalCancelado.value					 		= '';
							document.formulario.ValorTotalBaseCalculo.value					 		= '';
							document.formulario.ValorTotalICMS.value			 					= '';
							document.formulario.ValorTotalIsentoNaoTributado.value			 		= '';
							document.formulario.ValorTotalOutros.value			 					= '';
							document.formulario.CodigoAutenticacaoDigitalArquivoMestre.value 		= '';
							document.formulario.QtdItem.value			 							= '';
							document.formulario.QtdItemCancelado.value						 		= '';
							document.formulario.StatusArquivoItem.value								= '';
							document.formulario.ValorTotalItem.value			 					= '';
							document.formulario.ValorTotalItemDesconto.value				 		= '';
							document.formulario.ValorTotalAcrecimoDespesas.value			 		= '';
							document.formulario.ValorTotalItemBaseCalculo.value					 	= '';
							document.formulario.ValorTotalItemICMS.value				 			= '';
							document.formulario.ValorTotalItemIsentoNaoTributado.value		 		= '';
							document.formulario.ValorTotalItemOutros.value			 				= '';
							document.formulario.CodigoAutenticacaoDigitalArquivoItem.value	 		= '';
							document.formulario.QtdRegistroDestinatario.value			 			= '';
							document.formulario.StatusArquivoDestinatario.value			 			= '';
							document.formulario.CodigoAutenticacaoDigitalArquivoDestinatario.value	= '';
							document.formulario.NomeResponsavel.value			 					= '';
							document.formulario.CargoResponsavel.value			 					= '';
							document.formulario.TelefoneResponsavel.value						 	= '';
							document.formulario.EmailResponsavel.value			 					= '';
							document.formulario.IdStatus.value			 							= '';
							document.formulario.LogProcessamento.value 								= '';
							document.formulario.LoginCriacao.value								 	= '';
							document.formulario.DataCriacao.value								 	= '';
							document.formulario.LoginProcessamento.value						 	= '';
							document.formulario.DataProcessamento.value							 	= '';
							document.formulario.LoginConfirmacao.value							 	= '';
							document.formulario.DataConfirmacao.value							 	= '';
							document.formulario.NotaFiscalTransmitir.value							= '2';
							document.formulario.EnderecoArquivoMestre.value					 		= '';
							document.formulario.EnderecoArquivoItem.value					 		= '';
							document.formulario.EnderecoArquivoDestinatario.value					= '';

							VerificaTransmissao(document.formulario.IdNotaFiscalLayout.value,false,document.formulario.MesReferencia.value,document.formulario.IdStatusArquivoMestre.value);
							
							document.getElementById('cp_Status').style.display						= 'none';
							
							document.formulario.IdNotaFiscalLayout.focus();
							
							document.formulario.Acao.value 		= "inserir";
							verifica_nota_fiscal();
							verificaAcao();
							break;						
					}
				}else{
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscalLayout")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdNotaFiscalLayout = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("MesReferencia")[0]; 
					nameTextNode = nameNode.childNodes[0];
					MesReferencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("StatusMestre")[0]; 
					nameTextNode = nameNode.childNodes[0];
					Status = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeArquivoMestre")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeArquivoMestre = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("EnderecoArquivoMestre")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var EnderecoArquivoMestre = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeArquivoItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeArquivoItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("EnderecoArquivoItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var EnderecoArquivoItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeArquivoDestinatario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeArquivoDestinatario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("EnderecoArquivoDestinatario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var EnderecoArquivoDestinatario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IE")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IE = nameTextNode.nodeValue;					
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CNPJ")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CNPJ = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var RazaoSocial = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Endereco = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CEP = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Bairro = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeCidade = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var SiglaEstado = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdNFCancelado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdNFCancelado = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("StatusArquivoMestre")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var StatusArquivoMestre = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiraNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataPrimeiraNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataUltimaNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataUltimaNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroPrimeiraNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroPrimeiraNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroUltimaNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroUltimaNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroUltimaNF")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroUltimaNF = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotal = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalCancelado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalCancelado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalBaseCalculo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalBaseCalculo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalICMS")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalICMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalIsentoNaoTributado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalIsentoNaoTributado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalOutros")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalOutros = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoAutenticacaoDigitalArquivoMestre")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CodigoAutenticacaoDigitalArquivoMestre = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdItemCancelado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdItemCancelado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("StatusArquivoItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var StatusArquivoItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItemDesconto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItemDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalAcrecimoDespesas")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalAcrecimoDespesas = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItemBaseCalculo")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItemBaseCalculo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItemICMS")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItemICMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItemIsentoNaoTributado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItemIsentoNaoTributado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotalItemOutros")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorTotalItemOutros = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoAutenticacaoDigitalArquivoItem")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CodigoAutenticacaoDigitalArquivoItem = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("QtdRegistroDestinatario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var QtdRegistroDestinatario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("StatusArquivoDestinatario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var StatusArquivoDestinatario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoAutenticacaoDigitalArquivoDestinatario")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CodigoAutenticacaoDigitalArquivoDestinatario = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var NomeResponsavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("CargoResponsavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var CargoResponsavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("TelefoneResponsavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var TelefoneResponsavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("EmailResponsavel")[0]; 					
					nameTextNode = nameNode.childNodes[0];
					var EmailResponsavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataResponsavel")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataResponsavel = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Cor = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LogProcessamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LogProcessamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginProcessamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginProcessamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataProcessamento")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataProcessamento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginConfirmacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DataConfirmacao = nameTextNode.nodeValue;
					
										
					switch (Local){
						case 'NotaFiscal2ViaEletronicaRemessa':
							document.formulario.IdNotaFiscalLayout.value 							= IdNotaFiscalLayout;
							document.formulario.MesReferencia.value 								= MesReferencia;
							document.formulario.NomeArquivoMestre.value						 		= NomeArquivoMestre;
							document.formulario.EnderecoArquivoMestre.value					 		= EnderecoArquivoMestre;
							document.formulario.NomeArquivoItem.value						 		= NomeArquivoItem;
							document.formulario.EnderecoArquivoItem.value					 		= EnderecoArquivoItem;
							document.formulario.NomeArquivoDestinatario.value				 		= NomeArquivoDestinatario;
							document.formulario.EnderecoArquivoDestinatario.value					= EnderecoArquivoDestinatario;
							document.formulario.InscricaoEstadual.value							 	= IE;
							document.formulario.CNPJ.value			 								= CNPJ;
							document.formulario.RazaoSocial.value								 	= RazaoSocial;
							document.formulario.Endereco.value									 	= Endereco;
							document.formulario.CEP.value			 								= CEP;
							document.formulario.Bairro.value									 	= Bairro;
							document.formulario.NomeCidade.value								 	= NomeCidade;
							document.formulario.SiglaEstado.value								 	= SiglaEstado;
							document.formulario.QtdNF.value			 								= QtdNF;
							document.formulario.QtdNFCancelado.value							 	= QtdNFCancelado;
							document.formulario.IdStatusArquivoMestre.value						 	= StatusArquivoMestre;
							document.formulario.DataPrimeiraNF.value							 	= dateFormat(DataPrimeiraNF);
							document.formulario.DataUltimaNF.value			 						= dateFormat(DataUltimaNF);
							document.formulario.NumeroPrimeiraNF.value							 	= NumeroPrimeiraNF;
							document.formulario.NumeroUltimaNF.value						 		= NumeroUltimaNF;
							document.formulario.ValorTotal.value							 		= ValorTotal;
							document.formulario.ValorTotalCancelado.value							= ValorTotalCancelado;
							document.formulario.ValorTotalBaseCalculo.value					 		= ValorTotalBaseCalculo;
							document.formulario.ValorTotalICMS.value			 					= ValorTotalICMS;
							document.formulario.ValorTotalIsentoNaoTributado.value			 		= ValorTotalIsentoNaoTributado;
							document.formulario.ValorTotalOutros.value			 					= ValorTotalOutros;
							document.formulario.CodigoAutenticacaoDigitalArquivoMestre.value 		= CodigoAutenticacaoDigitalArquivoMestre;
							document.formulario.QtdItem.value			 							= QtdItem;
							document.formulario.QtdItemCancelado.value						 		= QtdItemCancelado;
							document.formulario.StatusArquivoItem.value								= StatusArquivoItem;
							document.formulario.ValorTotalItem.value			 					= ValorTotalItem;
							document.formulario.ValorTotalItemDesconto.value				 		= ValorTotalItemDesconto;
							document.formulario.ValorTotalAcrecimoDespesas.value			 		= ValorTotalAcrecimoDespesas;
							document.formulario.ValorTotalItemBaseCalculo.value					 	= ValorTotalItemBaseCalculo;
							document.formulario.ValorTotalItemICMS.value				 			= ValorTotalItemICMS;
							document.formulario.ValorTotalItemIsentoNaoTributado.value		 		= ValorTotalItemIsentoNaoTributado;
							document.formulario.ValorTotalItemOutros.value			 				= ValorTotalItemOutros;
							document.formulario.CodigoAutenticacaoDigitalArquivoItem.value	 		= CodigoAutenticacaoDigitalArquivoItem;
							document.formulario.QtdRegistroDestinatario.value			 			= QtdRegistroDestinatario;
							document.formulario.StatusArquivoDestinatario.value			 			= StatusArquivoDestinatario;
							document.formulario.CodigoAutenticacaoDigitalArquivoDestinatario.value	= CodigoAutenticacaoDigitalArquivoDestinatario;
							document.formulario.NomeResponsavel.value			 					= NomeResponsavel;
							document.formulario.CargoResponsavel.value			 					= CargoResponsavel;
							document.formulario.TelefoneResponsavel.value						 	= TelefoneResponsavel;
							document.formulario.EmailResponsavel.value			 					= EmailResponsavel;

							document.formulario.IdStatus.value			 							= IdStatus;
							document.formulario.LogProcessamento.value 								= LogProcessamento;
							document.formulario.LoginCriacao.value								 	= LoginCriacao;
							document.formulario.DataCriacao.value								 	= dateFormat(DataCriacao);
							document.formulario.LoginProcessamento.value						 	= LoginProcessamento;
							document.formulario.DataProcessamento.value							 	= dateFormat(DataProcessamento);
							document.formulario.LoginConfirmacao.value							 	= LoginConfirmacao;
							document.formulario.DataConfirmacao.value							 	= dateFormat(DataConfirmacao);
														
							//if(IdStatus != 1){
							document.formulario.StatusArquivoMestre.value							= StatusArquivoMestre;
							document.formulario.DataResponsavel.value								= DataResponsavel;
							//}
																					
							switch(IdStatus){
								case '1': // Cadastrado									
									document.formulario.bt_enviar.disabled 	 		  = true;
									document.formulario.bt_inserir.disabled	 		  = true;
									document.formulario.bt_excluir.disabled    	      = false;
									document.formulario.bt_processar.disabled 		  = false;
									document.formulario.bt_confirmar.disabled 		  = true;
									document.formulario.bt_confirmar_entrega.disabled = true;
									document.formulario.bt_imprimir_nota.disabled 	  = false;
									document.formulario.Acao.value 					  = 'processar';
									
									break;
								case '2': // Em Analise
									document.formulario.bt_enviar.disabled 	 		  = true;
									document.formulario.bt_inserir.disabled	 		  = true;
									document.formulario.bt_excluir.disabled    	      = true;
									document.formulario.bt_processar.disabled 		  = true;
									document.formulario.bt_confirmar.disabled 		  = false;
									document.formulario.bt_confirmar_entrega.disabled = true;									
									document.formulario.bt_imprimir_nota.disabled 	  = false;
									document.formulario.Acao.value 					  = 'confirmar';
									
									break;
								case '3': // Confirmado
									document.formulario.bt_enviar.disabled 	 		  	= false;
									document.formulario.bt_inserir.disabled	 		  	= true;									
									document.formulario.bt_excluir.disabled    	      	= false;
									document.formulario.bt_processar.disabled 		  	= true;
									document.formulario.bt_confirmar.disabled 		  	= true;
									document.formulario.bt_confirmar_entrega.disabled 	= false;
									document.formulario.bt_imprimir_nota.disabled 		= false;
									document.formulario.Acao.value 					  	= 'confirmarEntrega';
									
									break;
								case '4': // Entrega Confirmada
									document.formulario.bt_enviar.disabled 	 		  = false;
									document.formulario.bt_inserir.disabled	 		  = true;
									document.formulario.bt_excluir.disabled    	      = true;
									document.formulario.bt_processar.disabled 		  = true;
									document.formulario.bt_confirmar.disabled 		  = true;
									document.formulario.bt_confirmar_entrega.disabled = true;
									document.formulario.bt_imprimir_nota.disabled	  = false;
									document.formulario.Acao.value 					  = 'enviarEmail';
									
									break;
							}
							
							document.getElementById('cp_Status').innerHTML			= Status;
							document.getElementById('cp_Status').style.display		= 'block';
							document.getElementById('cp_Status').style.color		= Cor;													
							
							verificaAcao();						
							break;
					}
				}			
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

function VerificaTransmissao(IdNotaFiscalLayout,Erro,MesReferencia,IdStatusArquivoMestre){
	if(IdNotaFiscalLayout == '' || IdNotaFiscalLayout == undefined){
		IdNotaFiscalLayout = 0;
	}	
	var nameNode, nameTextNode, url;
	
	var xmlhttp   = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		xmlhttp = new XMLHttpRequest();
		if(xmlhttp.overrideMimeType){
			xmlhttp.overrideMimeType('text/xml');
		}
	}else if (window.ActiveXObject){ // IE
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	
	url = "xml/nf_2_via_eletronica_remessa_verifica_transmissao.php?IdNotaFiscalLayout="+IdNotaFiscalLayout+"&MesReferencia="+MesReferencia+"&Status="+IdStatusArquivoMestre;
	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(xmlhttp.responseText == 'false'){		
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("VerificaTransmicao")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var VerificaTransmicao = nameTextNode.nodeValue;
					
					if(VerificaTransmicao == '1'){	
						document.formulario.IdStatusArquivoMestre[1].selected = true;
						busca_nf_2_via_eletronica_remessa(IdNotaFiscalLayout, MesReferencia, 'N' , 'false', document.formulario.Local.value);
					}
					
				}
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}
