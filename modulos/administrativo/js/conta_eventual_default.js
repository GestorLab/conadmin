	function busca_conta_eventual(IdContaEventual, Erro, Local, NumParcelaEventual){
		if(IdContaEventual == ''){
			IdContaEventual = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		if(NumParcelaEventual == undefined){
			NumParcelaEventual = '';
		}
		
		var url = "xml/conta_eventual.php?IdContaEventual="+IdContaEventual;
		
		if(Local == 'Protocolo'){
			if(document.formulario.IdPessoa != ''){
				IdPessoa = document.formulario.IdPessoa.value;
			} else{
				IdPessoa = document.formulario.IdPessoaF.value;
			}
			
			//url	+= "&IdPessoa="+IdPessoa+"&IdContrato="+document.formulario.IdContrato.value+"&IdContaReceber="+document.formulario.IdContaReceber.value;
		}
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case 'ContaEventual':
						if(Erro != 80 || IdContaEventual == 0){
							document.formulario.IdContaEventual.value 				= '';
							document.formulario.DescricaoContaEventual.value 		= '';
							
							document.getElementById('cp_juridica').style.display	= 'block';
							document.getElementById('cp_fisica').style.display		= 'none';
							document.getElementById('cp_Status').style.display		= "none";
						
							document.formulario.IdPessoa.readOnly				= false;
							document.formulario.IdPessoaF.readOnly				= false;
							document.formulario.IdPessoa.value 					= '';
							document.formulario.Nome.value 						= '';
							document.formulario.RazaoSocial.value 				= '';
							document.formulario.CPF.value 						= '';
							document.formulario.CNPJ.value 						= '';
							document.formulario.IdLocalCobranca.value 			= '';
							document.formulario.DataPrimeiroVencimentoContrato.value	= '';
							document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
							document.formulario.ValorTotal.value				= '';
							document.formulario.ValorTotalContrato.value		= '';
							document.formulario.ValorTotalIndividual.value		= '';
							document.formulario.ValorDespesaLocalCobranca.value	= '';
							document.formulario.ValorCobrancaMinima.value		= '';
							document.formulario.QtdParcela.value				= '';
							document.formulario.QtdParcelaContrato.value		= '';
							document.formulario.QtdParcelaIndividual.value		= '';
							document.formulario.IdStatus.value					= 0;
							document.formulario.ObsContaEventual.value			= '';
							document.formulario.IdContratoAgrupador.value 		= '';
							document.formulario.IdContrato.value 				= '';
							document.formulario.FormaCobranca.value 			= 0;
							document.formulario.IdPessoaEnderecoCobranca.value 	= '';
							document.formulario.IdTipoLocalCobranca.value		= '';
							document.formulario.OcultarReferencia[0].selected	= true;
							document.formulario.IdContaDebitoCartao.disabled			= false;
							
							document.getElementById("descricaoNotaFiscal").innerHTML		=	'';
							document.getElementById("descricaoNotaFiscal").style.display	=	"none";
							
							document.formulario.IdPessoaEnderecoCobranca.value			=	"";
							document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
							document.formulario.CEPCobranca.value						=	"";
							document.formulario.EnderecoCobranca.value					=	"";
							document.formulario.NumeroCobranca.value					=	"";
							document.formulario.ComplementoCobranca.value				=	"";
							document.formulario.BairroCobranca.value					=	"";
							document.formulario.IdPaisCobranca.value					=	"";
							document.formulario.PaisCobranca.value						=	"";
							document.formulario.IdEstadoCobranca.value					=	"";
							document.formulario.EstadoCobranca.value					=	"";
							document.formulario.IdCidadeCobranca.value					=	"";
							document.formulario.CidadeCobranca.value					=	"";
							
							document.formulario.NomeRepresentante.value 				=	"";
							document.formulario.InscricaoEstadual.value 				= 	"";
							document.formulario.DataNascimento.value 					=	"";	
							document.formulario.RG.value 								=	"";
							document.formulario.Telefone1.value 						=	"";
							document.formulario.Telefone2.value 						= 	"";
							document.formulario.Telefone3.value 						= 	"";
							document.formulario.Celular.value 							= 	"";	
							document.formulario.Fax.value 								= 	"";
							document.formulario.ComplementoTelefone.value 				= 	"";
							document.formulario.EmailJuridica.value 					= 	"";
							
							document.formulario.bt_enviar.disabled 						= true;
							
							while(document.getElementById('tabelaVencimento').rows.length > 2){
								document.getElementById('tabelaVencimento').deleteRow(1);
							}
							while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
								document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
							}
							
							document.getElementById('cp_Vencimento').style.display	=	'none';
							
							document.formulario.DataCriacao.value 		  = "";
							document.formulario.LoginCriacao.value 		  = "";
							document.formulario.DataAlteracao.value 	  = "";
							document.formulario.LoginAlteracao.value	  = "";
						    document.formulario.UsuarioConfirmacao.value  = "";
						    document.formulario.DataConfirmacao.value     = "";
							document.formulario.Acao.value 				  = 'inserir';
							
							listaLocalCobranca();
							status_inicial();
							verifica_status();
							
							addParmUrl("marContasReceber","IdContaEventual","");
							addParmUrl("marLancamentoFinanceiro","IdContaEventual","");
							addParmUrl("marContaEventual","IdContaEventual","");
							
							document.getElementById('titFormaCobranca').style.display		=	'block';
							document.getElementById('titContrato').style.display			=	'none';
							document.getElementById('titIndividual').style.display			=	'none';
								
							document.formulario.IdContaEventual.focus();
							verificar_local_cobranca("","","");
						} else{
							document.formulario.ValorTotal.focus();
						}
						break;
					case 'LancamentoFinanceiro':
						document.formulario.IdContaEventual.value 					= '';
						document.formulario.DescricaoContaEventual.value 			= '';
						document.formulario.FormaCobranca[0].selected 				= true;
						document.formulario.IdContratoAgrupador[0].selected 		= true;
						document.formulario.ValorTotalContrato.value				= '';
						document.formulario.QtdParcelaContrato.value				= '';
						document.formulario.DataPrimeiroVencimentoContrato.value	= '';
						document.formulario.ValorTotalIndividual.value				= '';
						document.formulario.ValorDespesaLocalCobranca.value			= '';
						document.formulario.QtdParcelaIndividual.value				= '';
						document.formulario.DataPrimeiroVencimentoIndividual.value	= '';
						
						document.getElementById('cpContaEventual').style.display	=	'none';
						break;
					case 'Protocolo':
						document.formulario.IdContaEventual.value 					= '';
						document.formulario.DescricaoContaEventual.value 			= '';
						document.formulario.FormaCobrancaContaEventual.value		= '';
						document.formulario.QtdParcelaContaEventual.value			= '';
						document.formulario.ValorContaEventual.value				= '';
						
						document.formulario.IdOrdemServico.readOnly = false;
						document.formulario.IdOrdemServico.onfocus = function (){
							Foco(this,'in');
						};
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaEventual")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoContaEventual = nameTextNode.nodeValue;
				
				document.formulario.IdContaEventual.value				= IdContaEventual;
				document.formulario.DescricaoContaEventual.value 		= DescricaoContaEventual;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorTotal = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdParcela = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContratoAgrupador = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiroVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataPrimeiroVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var FormaCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ObsContaEventual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ObsContaEventual = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorCobrancaMinima = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("OcultarReferencia")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var OcultarReferencia = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
		
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;					
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				
				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginConfirmacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataConfirmacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataConfirmacao = nameTextNode.nodeValue;
				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEnderecoCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPessoaEnderecoCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdContaReceberAguardandoPagamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdContaReceberAguardandoPagamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Carne")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Carne = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoLocalCobranca = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaDebito = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCartao = nameTextNode.nodeValue;
				
				switch(Local){
					case 'ContaEventual':
						document.formulario.FormaCobranca.value 			= FormaCobranca;
						document.formulario.ValorTotal.value				= '';
						document.formulario.QtdParcela.value				= '';
						document.formulario.IdStatus.value					= IdStatus;
						document.formulario.ValorCobrancaMinima.value		= ValorCobrancaMinima;
						document.formulario.ObsContaEventual.value			= ObsContaEventual;
						document.formulario.OcultarReferencia.value			= OcultarReferencia;
						document.formulario.IdTipoLocalCobranca.value		= IdTipoLocalCobranca;					
						
						busca_pessoa(IdPessoa,false,Local);	
						
						switch(FormaCobranca){
							case '1': //Em contrato
								document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");									
								document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;									
								
								document.formulario.IdContratoAgrupador.value 				= IdContratoAgrupador;
								document.formulario.QtdParcelaContrato.value				= QtdParcela;
								
								document.getElementById("descricaoNotaFiscal").innerHTML					=	'';
								document.getElementById("descricaoNotaFiscal").style.display				=	"block";
								document.getElementById('titFormaCobranca').style.display					=	'none';
								document.getElementById('titContrato').style.display						=	'block';
								document.getElementById('titIndividual').style.display						=	'none';	
								document.getElementById('titDataPrimeiroVencimentoContrato').style.display	=	'block';
								document.getElementById('cpEnderecoCorrespondencia').style.display			=	'none';
								
								listar_contrato(IdPessoa,IdContratoAgrupador);
								buscar_descricao_layout(IdContratoAgrupador);
							//	atualizaPrimeiraReferencia(IdContratoAgrupador, DataPrimeiroVencimento);
								break;
							case '2': //Individual
								document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
								document.formulario.ValorTotalIndividual.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.ValorDespesaLocalCobranca.value			= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								document.formulario.QtdParcelaIndividual.value				= QtdParcela;
								if(QtdParcela > 1 || QtdParcela == ''){
									document.formulario.IdFormatoCarne.disabled = false;
									document.formulario.IdFormatoCarne.value				= Carne;
								} else{
									document.formulario.IdFormatoCarne.value = 2;
									document.formulario.IdFormatoCarne.disabled = true;
								}
								document.formulario.IdContrato.value 						= IdContratoAgrupador;
								document.formulario.IdPessoaEnderecoCobranca.value			= '0';
								document.formulario.NomeResponsavelEnderecoCobranca.value	= '';
								document.formulario.CEPCobranca.value						= '';
								document.formulario.EnderecoCobranca.value					= '';
								document.formulario.NumeroCobranca.value					= '';
								document.formulario.ComplementoCobranca.value				= '';
								document.formulario.BairroCobranca.value					= '';
								document.formulario.IdPaisCobranca.value					= '';
								document.formulario.PaisCobranca.value						= '';
								document.formulario.IdEstadoCobranca.value					= '';
								document.formulario.EstadoCobranca.value					= '';
								document.formulario.IdCidadeCobranca.value					= '';
								document.formulario.CidadeCobranca.value					= '';
								
								document.getElementById("descricaoNotaFiscal").innerHTML					=	'';
								document.getElementById("descricaoNotaFiscal").style.display				=	"none";
								document.getElementById('titFormaCobranca').style.display					=	'none';
								document.getElementById('titContrato').style.display						=	'none';
								document.getElementById('titIndividual').style.display						=	'block';
								document.getElementById('titDataPrimeiroVencimentoContrato').style.display	=	'none';
								document.getElementById('cpEnderecoCorrespondencia').style.display			=	'block';

								listaLocalCobranca(IdLocalCobranca);
								listar_contrato_individual(IdPessoa,IdContratoAgrupador);
								verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito,IdPessoa);
								
								
								if(IdStatus == 2){
									document.formulario.IdContaDebitoCartao.disabled = true;
								}
								if(IdStatus == ""){
									document.formulario.IdContaDebitoCartao.disabled = false;
								}
								
								break;
						}
					
						addParmUrl("marPessoa","IdPessoa",IdPessoa);
						addParmUrl("marContasReceber","IdContaEventual",IdContaEventual);
						addParmUrl("marLancamentoFinanceiro","IdContaEventual",IdContaEventual);
						addParmUrl("marContrato","IdPessoa",IdPessoa);
						addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
						addParmUrl("marReenvioMensagem","IdContaEventual",IdContaEventual);
						addParmUrl("marContaEventual","IdContaEventual",IdContaEventual);
						addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
						
						if(IdStatus == 2 || IdStatus == 0){
							document.formulario.IdPessoa.readOnly				= true;
							document.formulario.IdPessoaF.readOnly				= true;
						}else{
							document.formulario.IdPessoa.readOnly				= false;
							document.formulario.IdPessoaF.readOnly				= false;
						}
						document.formulario.IdLocalCobranca.value	= IdLocalCobranca;
						document.formulario.DataCriacao.value 		= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 		= LoginCriacao;
						document.formulario.DataAlteracao.value 	= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.Acao.value 				= 'alterar';
						
						
						document.formulario.UsuarioConfirmacao.value 	= LoginConfirmacao;
						document.formulario.DataConfirmacao.value		= dateFormat(DataConfirmacao);
						
						
						
						busca_status(IdStatus);
						busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoCobranca);
						busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEnderecoCobranca);
						verifica_status(IdStatus);

						if(IdStatus == 2){
							if(QtdContaReceberAguardandoPagamento >= 1){
								document.formulario.bt_enviar.disabled = false;
							}else{
								document.formulario.bt_enviar.disabled = true;								
							}
						}
						break;
					case 'LancamentoFinanceiro':
						document.formulario.FormaCobranca.value 			= FormaCobranca;
						
						if(NumParcelaEventual != ''){
							QtdParcela = NumParcelaEventual+'/'+QtdParcela;
						}
						
						switch(FormaCobranca){
							case '1': //Contrato
								document.getElementById('cpContaEventualContrato').style.display	=	'block';
								document.getElementById('cpContaEventualIndividual').style.display	=	'none';
								
								document.formulario.ValorTotalContrato.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.DataPrimeiroVencimentoContrato.value	= DataPrimeiroVencimento;
								document.formulario.IdContratoAgrupador.value 				= IdContratoAgrupador;
								document.formulario.QtdParcelaContrato.value				= QtdParcela;
								
								listar_contrato(IdPessoa,IdContratoAgrupador);
								break;
							case '2': //Individual
								document.getElementById('cpContaEventualContrato').style.display	=	'none';
								document.getElementById('cpContaEventualIndividual').style.display	=	'block';
								
								document.formulario.DataPrimeiroVencimentoIndividual.value	= dateFormat(DataPrimeiroVencimento);
								document.formulario.ValorTotalIndividual.value				= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
								document.formulario.ValorDespesaLocalCobranca.value			= formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",");
								document.formulario.IdLocalCobranca.value 					= IdLocalCobranca;
								document.formulario.QtdParcelaIndividual.value				= QtdParcela;
								
								listar_contrato_individual(IdPessoa,IdContratoAgrupador);
								break;
						}
						
						break;
					case 'Protocolo':
						document.formulario.FormaCobrancaContaEventual.value 	= FormaCobranca;
						document.formulario.QtdParcelaContaEventual.value		= QtdParcela;
						document.formulario.ValorContaEventual.value			= formata_float(Arredonda(ValorTotal,2),2).replace(/\./i,",");
						
						document.formulario.IdOrdemServico.readOnly = true;
						document.formulario.IdOrdemServico.onfocus = undefined;
						
						busca_ordem_servico(0);
						break;
				}
				
				if(document.formulario.Erro.value != ''){
					scrollWindow('bottom');
				} else{
					scrollWindow('top');
				}
			}
			
			if(document.getElementById("quadroBuscaContaEventual") != null){
				if(document.getElementById("quadroBuscaContaEventual").style.display == "block"){
					document.getElementById("quadroBuscaContaEventual").style.display =	"none";
				}
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
			
			verificaAcao();
		});
	}